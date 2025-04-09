<?php

namespace App\Http\Controllers;

use App\Exports\PresencaExport;
use App\Models\Presenca;
use App\Models\Turma;
use App\Models\PresencaAluno;
use App\Models\PresencaMonitor;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PresencaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $presencas = Presenca::with('turma')
            ->when($search, function ($query, $search) {
                return $query->whereHas('turma', function ($q) use ($search) {
                    $q->where('nome_turma', 'like', "%{$search}%");
                })->orWhere('data', 'like', "%{$search}%");
            })
            ->orderBy('data', 'desc')
            ->get();

        return view('estagio.presencas.index', compact('presencas'));
    }

    public function create()
    {
        $turmas = Turma::all();
        return view('estagio.presencas.create', compact('turmas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'data' => 'required|date',
            'turma_id' => 'required|exists:turmas,id',
        ]);

        $presenca = Presenca::create([
            'data' => $request->data,
            'turma_id' => $request->turma_id,
        ]);

        return redirect()->route('presencas.index')->with('success', 'Presença registrada com sucesso!');
    }

    public function registrarPresenca($id)
    {
        $presenca = Presenca::findOrFail($id);
        $turma = $presenca->turma;
        $alunos = $turma->alunos;
        $monitores = $turma->monitores;

        foreach ($alunos as $aluno) {
            $presencaAluno = PresencaAluno::where('presenca_id', $presenca->id)
                ->where('aluno_id', $aluno->id)
                ->first();

            $aluno->presente = $presencaAluno ? $presencaAluno->presente : '';
            $aluno->observacao = $presencaAluno ? $presencaAluno->observacao : '';
        }

        foreach ($monitores as $monitor) {
            $presencaMonitor = PresencaMonitor::where('presenca_id', $presenca->id)
                ->where('monitor_id', $monitor->id)
                ->first();

            $monitor->presente = $presencaMonitor ? $presencaMonitor->presente : '';
            $monitor->observacao = $presencaMonitor ? $presencaMonitor->observacao : '';
        }

        return view('estagio.presencas.register', compact('presenca', 'alunos', 'monitores'));
    }

    public function salvarPresenca(Request $request, $id)
    {
        $presenca = Presenca::findOrFail($id);

        $request->validate([
            'alunos.*.presente' => 'required|in:1,0',
            'alunos.*.observacao' => 'nullable|string|max:255',
            'monitores.*.presente' => 'required|in:1,0',
            'monitores.*.observacao' => 'nullable|string|max:255'

        ]);

        foreach ($request->alunos as $aluno_id => $dados) {
            if (!is_array($dados)) {
                continue;
            }

            PresencaAluno::updateOrCreate(
                ['presenca_id' => $presenca->id, 'aluno_id' => $aluno_id],
                ['presente' => $dados['presente'], 'observacao' => $dados['observacao'] ?? null],
            );
        }

        foreach ($request->monitores as $monitor_id => $dados) {
            if (!is_array($dados)) {
                continue;
            }

            PresencaMonitor::updateOrCreate(
                ['presenca_id' => $presenca->id, 'monitor_id' => $monitor_id],
                ['presente' => $dados['presente'], 'observacao' => $dados['observacao'] ?? null,]
            );
        }

        return redirect()->route('presencas.index')->with('success', 'Presença registrada com sucesso.');
    }

    public function visualizarPresenca($id)
    {
        $presenca = Presenca::findOrFail($id);
        $turma = $presenca->turma;
        $alunos = $turma->alunos;
        $monitores = $turma->monitores;

        foreach ($alunos as $aluno) {
            $presencaAluno = PresencaAluno::where('presenca_id', $presenca->id)
                ->where('aluno_id', $aluno->id)
                ->first();

            $aluno->presente = $presencaAluno ? $presencaAluno->presente : '';
            $aluno->observacao = $presencaAluno ? $presencaAluno->observacao : '';
        }

        foreach ($monitores as $monitor) {
            $presencaMonitor = PresencaMonitor::where('presenca_id', $presenca->id)
                ->where('monitor_id', $monitor->id)
                ->first();

            $monitor->presente = $presencaMonitor ? $presencaMonitor->presente : '';
            $monitor->observacao = $presencaMonitor ? $presencaMonitor->observacao : '';
        }

        return view('estagio.presencas.visualizar', compact('presenca', 'alunos', 'monitores'));
    }

    public function exportar($id)
    {
        $presenca = Presenca::findOrFail($id);
        return Excel::download(new PresencaExport($presenca), "Presenca_{$presenca->turma->nome_turma}_{$presenca->data}.CSV", \Maatwebsite\Excel\Excel::CSV);
    }
}
