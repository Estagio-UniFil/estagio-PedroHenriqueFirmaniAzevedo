<?php

namespace App\Http\Controllers;

use App\Models\Atividade;
use App\Models\Turma;
use App\Models\Aluno;
use App\Models\Nota;
use Illuminate\Http\Request;

class AtividadeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $atividades = Atividade::withTrashed()->orderBy('titulo', 'asc')->when($search, function ($query, $search) {
            return $query->where('titulo', 'like', "%{$search}%")
                ->orWhere('turma_id', 'like', "%{$search}%");
        })->paginate(255);

        return view('estagio.atividades.index', compact('atividades'));
    }

    public function create()
    {
        $turmas = Turma::all();
        return view('estagio.atividades.create', compact('turmas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'turma_id' => 'required|exists:turmas,id',
            'titulo' => 'required|string|max:255',
            'descricao' => 'string|max:255|nullable',
            'peso' => 'required|integer|min:0|max:100',
        ]);

        $data = $request->all();
        $data['peso'] = intval($request->input('peso')); // garante inteiro

        $atividade = Atividade::create($data);

        $alunos = Aluno::where('turma_id', $request->turma_id)->get();
        foreach ($alunos as $aluno) {
            Nota::create([
                'atividade_id' => $atividade->id,
                'aluno_id' => $aluno->id,
                'nota' => 0
            ]);
        }

        return redirect()->route('atividades.index')->with('success', 'Atividade criada e notas atribuídas.');
    }

    public function edit($id)
    {
        $atividade = Atividade::findOrFail($id);
        $turmas = Turma::all();

        return view('estagio.atividades.edit', compact('atividade', 'turmas'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'turma_id' => 'required|exists:turmas,id',
            'titulo' => 'required|string|max:255',
            'descricao' => 'string|max:255|nullable',
            'peso' => 'required|integer|min:0|max:100',
        ]);

        $atividade = Atividade::findOrFail($id);
        $data = $request->all();
        $data['peso'] = intval($request->input('peso')); // força inteiro
        $atividade->update($data);

        return redirect()->route('atividades.index')->with('success', 'Atividade atualizada com sucesso!');
    }

    public function destroy($id)
    {
        $atividade = Atividade::findOrFail($id);
        $atividade->delete();

        return redirect()->route('atividades.index')->with('success', 'Atividade removida com sucesso!');
    }

    public function restore($id)
    {
        $atividade = Atividade::onlyTrashed()->findOrFail($id);
        $atividade->restore();

        return redirect()->route('atividades.index')->with('success', 'Atividade reativada com sucesso!');
    }

    public function atribuirNotas($id)
    {
        $atividade = Atividade::findOrFail($id);
        $alunos = $atividade->turma->alunos;

        $notas = Nota::where('atividade_id', $atividade->id)->get()->keyBy('aluno_id');

        return view('estagio.atividades.atribuir-notas', compact('atividade', 'alunos', 'notas'));
    }

    public function salvarNotas(Request $request, $id)
    {
        $request->validate([
            'peso_atividade' => 'nullable|integer|min:0|max:100',
            'alunos' => 'required|array',
            'alunos.*' => 'required|numeric|min:0|max:100',
        ]);


        $atividade = Atividade::findOrFail($id);

        // Atualiza o peso da atividade (inteiro)
        $atividade->peso = intval($request->input('peso_atividade'));
        $atividade->save();

        // Atualiza as notas dos alunos
        foreach ($request->input('alunos') as $aluno_id => $nota) {
            Nota::updateOrCreate(
                [
                    'atividade_id' => $atividade->id,
                    'aluno_id' => $aluno_id,
                ],
                [
                    'nota' => $nota,
                ]
            );
        }

        return redirect()->route('atividades.index')->with('success', 'Notas e peso da atividade atualizados com sucesso!');
    }
}
