<?php

namespace App\Http\Controllers;

use App\Models\Presenca;
use App\Models\Turma;
use App\Models\PresencaAluno;
use App\Models\PresencaMonitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PresencaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $presencas = Presenca::with('turma')
            ->when($search, function ($query, $search) {
                if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $search)) {
                     return $query->where('data', $search);
                }
                 return $query->whereHas('turma', function ($q) use ($search) {
                    $q->where('nome_turma', 'like', "%{$search}%");
                });
            })
            ->orderBy('data', 'desc')
            ->get();

        foreach ($presencas as $presenca) {
            $presenca->total_ativos = $presenca->turma->alunos()->count();
            
            // CORREÇÃO: Garantir comparação com inteiro 1 para contagem precisa
            $presenca->presentes_dia = PresencaAluno::where('presenca_id', $presenca->id)
                                                    ->where('presente', 1)
                                                    ->count();
                                                    
            $presenca->abonadas = PresencaAluno::where('presenca_id', $presenca->id)
                                ->where(function($q) {
                                    $q->where('observacao', 'like', '%abonad%')
                                      ->orWhere('observacao', 'like', '%justif%');
                                })->count();
            $presenca->faltantes_dia = max(0, $presenca->total_ativos - $presenca->presentes_dia - $presenca->abonadas);
            $presenca->com_observacao = PresencaAluno::where('presenca_id', $presenca->id)
                                        ->whereNotNull('observacao')
                                        ->where('observacao', '!=', '')
                                        ->where(function($q) {
                                            $q->where('observacao', 'not like', '%abonad%')
                                              ->where('observacao', 'not like', '%justif%');
                                        })->count();
        }

        return view('estagio.presencas.index', compact('presencas'));
    }

    public function create()
    {
        $turmas = Turma::orderBy('nome_turma')->get();
        return view('estagio.presencas.create', compact('turmas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'data' => 'required|date',
            'turma_id' => 'required|exists:turmas,id',
        ], [
            'data.required' => 'O campo data é obrigatório.',
            'data.date' => 'O campo data deve ser uma data válida.',
            'turma_id.required' => 'O campo turma é obrigatório.',
            'turma_id.exists' => 'A turma selecionada é inválida.',
        ]);

        $existe = Presenca::where('turma_id', $request->turma_id)
                          ->where('data', $request->data)
                          ->first();

        if ($existe) {
            return redirect()->route('presencas.create')
                             ->withErrors(['data' => 'Já existe um registro de presença para esta turma nesta data.'])
                             ->withInput();
        }

        $presenca = Presenca::create([
            'data' => $request->data,
            'turma_id' => $request->turma_id,
        ]);

        return redirect()->route('presencas.register', $presenca->id)->with('success', 'Registro de presença criado! Agora marque os presentes/ausentes.');
    }

    public function destroy($id)
    {
        $presenca = Presenca::findOrFail($id);

        try {
            DB::beginTransaction();

            PresencaAluno::where('presenca_id', $presenca->id)->delete();
            PresencaMonitor::where('presenca_id', $presenca->id)->delete();
            $presenca->forceDelete();

            DB::commit();

            return redirect()->route('presencas.index')->with('success', 'Registro de presença excluído com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('presencas.index')->with('error', 'Erro ao excluir o registro de presença.');
        }
    }

    public function registrarPresenca($id)
    {
        $presenca = Presenca::findOrFail($id);
        $turma = $presenca->turma;
        $alunos = $turma->alunos()->orderBy('nome_aluno')->get();
        $monitores = $turma->monitores()->orderBy('nome_monitor')->get();

        $presencasAlunosExistentes = PresencaAluno::where('presenca_id', $presenca->id)
                                                ->pluck('presente', 'aluno_id');
        $observacoesAlunosExistentes = PresencaAluno::where('presenca_id', $presenca->id)
                                                ->pluck('observacao', 'aluno_id');

        $presencasMonitoresExistentes = PresencaMonitor::where('presenca_id', $presenca->id)
                                                    ->pluck('presente', 'monitor_id');
        $observacoesMonitoresExistentes = PresencaMonitor::where('presenca_id', $presenca->id)
                                                    ->pluck('observacao', 'monitor_id');

        foreach ($alunos as $aluno) {
            // Se não existir registro, assume '1' (Presente) por padrão, ou '0' se preferir que comece vazio.
            // Mantive '1' pois é mais comum a maioria estar presente.
            $aluno->presente = $presencasAlunosExistentes[$aluno->id] ?? 1; 
            $aluno->observacao = $observacoesAlunosExistentes[$aluno->id] ?? '';
        }

        foreach ($monitores as $monitor) {
            $monitor->presente = $presencasMonitoresExistentes[$monitor->id] ?? 1;
            $monitor->observacao = $observacoesMonitoresExistentes[$monitor->id] ?? '';
        }

        return view('estagio.presencas.register', compact('presenca', 'alunos', 'monitores'));
    }

    public function salvarPresenca(Request $request, $id)
    {
        $presenca = Presenca::findOrFail($id);

        // Validação ajustada para aceitar 0 ou 1 (booleanos do checkbox)
        $request->validate([
            'alunos.*.presente' => 'sometimes|required|in:1,0,on', 
            'alunos.*.observacao' => 'nullable|string|max:255',
            'monitores.*.presente' => 'sometimes|required|in:1,0,on',
            'monitores.*.observacao' => 'nullable|string|max:255'
        ]);

        if($request->has('alunos')) {
            foreach ($request->alunos as $aluno_id => $dados) {
                if (!is_array($dados) ) {
                     continue;
                }

                $isPresente = isset($dados['presente']) && ($dados['presente'] == '1' || $dados['presente'] == 'on') ? 1 : 0;

                PresencaAluno::updateOrCreate(
                    ['presenca_id' => $presenca->id, 'aluno_id' => $aluno_id],
                    [
                        'presente' => $isPresente,
                        'observacao' => $dados['observacao'] ?? null
                    ]
                );
            }
        } else {

        }

        if($request->has('monitores')) {
            foreach ($request->monitores as $monitor_id => $dados) {
                 if (!is_array($dados)) {
                    continue;
                 }

                $isPresente = isset($dados['presente']) && ($dados['presente'] == '1' || $dados['presente'] == 'on') ? 1 : 0;

                PresencaMonitor::updateOrCreate(
                    ['presenca_id' => $presenca->id, 'monitor_id' => $monitor_id],
                    [
                         'presente' => $isPresente,
                         'observacao' => $dados['observacao'] ?? null
                    ]
                );
            }
        } 

        return redirect()->route('presencas.index')->with('success', 'Presença salva com sucesso.');
    }
}