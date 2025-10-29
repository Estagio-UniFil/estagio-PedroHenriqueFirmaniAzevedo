<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Turma;
use App\Models\Aluno;
use App\Models\Presenca;
use App\Models\PresencaAluno;
use App\Models\Nota;
use App\Models\Atividade;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $totalAlunosGeral = Aluno::withTrashed()->count();
        $alunosAtivosGeral = Aluno::count();
        $alunosInativosGeral = Aluno::onlyTrashed()->count();
        $mediaNotasGeral = Nota::avg('nota');

        $statsPorTurma = [];
        $turmasTodas = Turma::withTrashed()
            ->withCount(['alunos' => fn($q) => $q->withTrashed()])
            ->withCount('alunos as alunos_ativos_count')
            ->withCount(['alunos as alunos_inativos_count' => fn($q) => $q->onlyTrashed()])
            ->orderBy('nome_turma')
            ->get();

        foreach ($turmasTodas as $turma) {
            $totalTurma = $turma->alunos_count;
            $ativosTurma = $turma->alunos_ativos_count;
            $inativosTurma = $turma->alunos_inativos_count;
            $porcentagemDesistentes = ($totalTurma > 0) ? ($inativosTurma / $totalTurma) * 100 : 0;

            $mediaNotasTurma = Nota::join('alunos', 'notas.aluno_id', '=', 'alunos.id')
                            ->where('alunos.turma_id', $turma->id)
                            ->avg('notas.nota');

            $aulasTurma = $this->calcularAulasDaTurma($turma->id);

            $statsPorTurma[$turma->nome_turma] = [
                'total' => $totalTurma,
                'ativos' => $ativosTurma,
                'inativos' => $inativosTurma,
                'porcentagem_desistentes' => round($porcentagemDesistentes, 2),
                'media_notas' => round($mediaNotasTurma ?? 0, 2),
                'total_aulas' => $aulasTurma,
            ];
        }

        $turmaSelecionadaId = $request->input('turma_id');
        $dataInicio = $request->input('data_inicio');
        $dataFim = $request->input('data_fim');
        $filtroSituacao = $request->input('situacao');

        $queryAlunos = Aluno::query()->withTrashed()->with(['turma', 'user']);

        if ($turmaSelecionadaId && $turmaSelecionadaId !== 'todas') {
            $queryAlunos->where('turma_id', $turmaSelecionadaId);
        }

        if ($filtroSituacao === 'ativos') {
            $queryAlunos->whereNull('deleted_at');
        } elseif ($filtroSituacao === 'inativos') {
            $queryAlunos->onlyTrashed();
        }

        $alunosFaltososIds = [];
        if ($filtroSituacao === 'faltosos') {
            $queryAlunos->whereNull('deleted_at');
            $alunosFaltososIds = $this->getAlunosFaltososIds($turmaSelecionadaId);
            $queryAlunos->whereIn('id', $alunosFaltososIds);
        }

        $queryAlunos->join('turmas', 'alunos.turma_id', '=', 'turmas.id')
                    ->orderBy('turmas.nome_turma', 'asc')
                    ->orderBy('alunos.nome_aluno', 'asc')
                    ->select('alunos.*');

        $alunosPaginados = $queryAlunos->paginate(50)->withQueryString();

        foreach ($alunosPaginados as $aluno) {
            list($presencas, $porcentagemPresenca, $totalAulas) = $this->calcularPresencaAluno($aluno->id, $aluno->turma_id, $dataInicio, $dataFim);
            $aluno->quantidade_presencas = $presencas;
            $aluno->porcentagem_presenca = $porcentagemPresenca;

            $aluno->nota_ponderada = $this->calcularNotaPonderadaAluno($aluno, $dataInicio, $dataFim);

            $minPresenca = $aluno->turma->minimo_presenca ?? 75;
            $minNota = $aluno->turma->minimo_nota ?? 60;
            
            $aluno->situacao_final = ($aluno->porcentagem_presenca >= $minPresenca && $aluno->nota_ponderada >= $minNota) ? 'Aprovado' : 'Reprovado';
            
            if($totalAulas == 0) {
                 $aluno->situacao_final = 'N/A';
            }

            $aluno->faltou_ultimas_3 = in_array($aluno->id, $alunosFaltososIds);
        }

        $turmasParaFiltro = Turma::orderBy('nome_turma')->get();

        return view('estagio.dashboard.dashboard_inicial', compact(
            'totalAlunosGeral',
            'alunosAtivosGeral',
            'alunosInativosGeral',
            'statsPorTurma',
            'mediaNotasGeral',
            'turmasParaFiltro',
            'alunosPaginados',
            'turmaSelecionadaId',
            'dataInicio',
            'dataFim',
            'filtroSituacao'
        ));
    }

    private function calcularAulasDaTurma($turmaId, $dataInicio = null, $dataFim = null)
    {
        $query = Presenca::where('turma_id', $turmaId);
        if ($dataInicio) {
            $query->where('data', '>=', $dataInicio);
        }
        if ($dataFim) {
            $query->where('data', '<=', $dataFim);
        }
        return $query->distinct('data')->count('data');
    }

    private function calcularPresencaAluno($alunoId, $turmaId, $dataInicio = null, $dataFim = null)
    {
        $totalAulasTurma = $this->calcularAulasDaTurma($turmaId, $dataInicio, $dataFim);

        if ($totalAulasTurma == 0) {
            return [0, 0, 0];
        }

        $queryPresencas = PresencaAluno::join('presencas', 'presenca_alunos.presenca_id', '=', 'presencas.id')
                                ->where('presenca_alunos.aluno_id', $alunoId)
                                ->where('presencas.turma_id', $turmaId)
                                ->where(function($q) {
                                    $q->where('presenca_alunos.presente', '1')
                                      ->orWhere('presenca_alunos.observacao', 'like', '%abonada%');
                                });

        if ($dataInicio) {
            $queryPresencas->where('presencas.data', '>=', $dataInicio);
        }
        if ($dataFim) {
            $queryPresencas->where('presencas.data', '<=', $dataFim);
        }

        $totalPresencasAluno = $queryPresencas->count();
        $porcentagem = round(($totalPresencasAluno / $totalAulasTurma) * 100, 2);

        return [$totalPresencasAluno, $porcentagem, $totalAulasTurma];
    }

    private function calcularNotaPonderadaAluno($aluno, $dataInicio = null, $dataFim = null)
    {
        $notasQuery = Nota::join('atividades', 'notas.atividade_id', '=', 'atividades.id')
                    ->where('notas.aluno_id', $aluno->id)
                    ->where('atividades.turma_id', $aluno->turma_id)
                    ->select('notas.nota', 'atividades.peso');

        if ($dataInicio && $dataFim) {
             $notasQuery->whereHas('atividade.presenca', function ($q) use ($dataInicio, $dataFim) {
                 $q->whereBetween('data', [$dataInicio, $dataFim]);
             });
        } elseif ($dataInicio) {
             $notasQuery->whereHas('atividade.presenca', function ($q) use ($dataInicio) {
                 $q->where('data', '>=', $dataInicio);
             });
        } elseif ($dataFim) {
             $notasQuery->whereHas('atividade.presenca', function ($q) use ($dataFim) {
                 $q->where('data', '<=', $dataFim);
             });
        }

        $notas = $notasQuery->get();

        if ($notas->isEmpty()) {
            return 0;
        }

        $somaNotasPonderadas = 0;
        $somaPesos = 0;

        foreach ($notas as $nota) {
            $peso = $nota->peso ?? 1;
            $somaNotasPonderadas += $nota->nota * $peso;
            $somaPesos += $peso;
        }

        if ($somaPesos == 0) {
             return $notas->avg('nota') ? round($notas->avg('nota'), 2) : 0;
        }

        return round($somaNotasPonderadas / $somaPesos, 2);
    }
    
    private function getAlunosFaltososIds($turmaId = null, $numAulas = 3)
    {
        $alunosFaltosos = [];
        
        $turmasQuery = Turma::query();
        if ($turmaId && $turmaId !== 'todas') {
            $turmasQuery->where('id', $turmaId);
        }
        
        $turmas = $turmasQuery->with('alunos')->get();

        foreach ($turmas as $turma) {
            $ultimasAulas = Presenca::where('turma_id', $turma->id)
                                    ->orderBy('data', 'desc')
                                    ->limit($numAulas)
                                    ->pluck('id');

            if ($ultimasAulas->count() < $numAulas) {
                continue;
            }

            foreach ($turma->alunos as $aluno) {
                if ($aluno->deleted_at !== null) {
                    continue;
                }

                $faltas = PresencaAluno::where('aluno_id', $aluno->id)
                                    ->whereIn('presenca_id', $ultimasAulas)
                                    ->where('presente', '0')
                                    ->count();

                if ($faltas >= $numAulas) {
                    $alunosFaltosos[] = $aluno->id;
                }
            }
        }
        
        return $alunosFaltosos;
    }
}
