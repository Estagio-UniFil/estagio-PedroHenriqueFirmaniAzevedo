<?php

namespace App\Exports;

use App\Models\Presenca;
use App\Models\PresencaAluno;
use App\Models\PresencaMonitor;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PresencaExport implements FromView
{
    protected $presenca;

    public function __construct($presenca)
    {
        $this->presenca = $presenca;
    }

    public function view(): View
    {
        $turma = $this->presenca->turma;
        $alunos = $turma->alunos;
        $monitores = $turma->monitores;

        foreach ($alunos as $aluno) {
            $presencaAluno = PresencaAluno::where('presenca_id', $this->presenca->id)
                ->where('aluno_id', $aluno->id)
                ->first();

            $aluno->presente = $presencaAluno ? $presencaAluno->presente : '';
            $aluno->observacao = $presencaAluno ? $presencaAluno->observacao : '';
        }

        foreach ($monitores as $monitor) {
            $presencaMonitor = PresencaMonitor::where('presenca_id', $this->presenca->id)
                ->where('monitor_id', $monitor->id)
                ->first();

            $monitor->presente = $presencaMonitor ? $presencaMonitor->presente : '';
            $monitor->observacao = $presencaMonitor ? $presencaMonitor->observacao : '';
        }

        return view('exports.presenca', compact('turma', 'alunos', 'monitores'));
    }

}
