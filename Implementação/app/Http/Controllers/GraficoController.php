<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aluno;
use App\Models\Nota;
use App\Models\Turma;
use Illuminate\Support\Facades\DB;

class GraficoController extends Controller
{
    public function index(Request $request)
    {
        // Média geral - sempre buscada
        $mediaGeral = Nota::avg('nota');

        // Média por turma - sempre busca todas as turmas para comparação
        $mediasTurmas = Turma::select('turmas.nome_turma', DB::raw('AVG(notas.nota) as media'))
            ->join('alunos', 'turmas.id', '=', 'alunos.turma_id')
            ->join('notas', 'alunos.id', '=', 'notas.aluno_id')
            ->groupBy('turmas.nome_turma')
            ->get();

        // Todas as turmas (para select)
        $turmas = Turma::all();

        // Filtro de turma para o gráfico de alunos
        $turmaSelecionada = $request->get('turma_id');

        // Média dos alunos (filtrada por turma, se houver)
        $mediasAlunos = Aluno::select('alunos.nome_aluno', DB::raw('AVG(notas.nota) as media'))
            ->join('notas', 'alunos.id', '=', 'notas.aluno_id')
            ->when($turmaSelecionada, function ($query, $turmaSelecionada) {
                return $query->where('alunos.turma_id', $turmaSelecionada);
            })
            ->groupBy('alunos.nome_aluno')
            ->get();

        return view('estagio.graficos.index', compact(
            'mediaGeral',
            'mediasTurmas',
            'mediasAlunos',
            'turmas',
            'turmaSelecionada'
        ));
    }
}