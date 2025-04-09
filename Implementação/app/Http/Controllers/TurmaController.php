<?php

namespace App\Http\Controllers;

use App\Models\Turma;
use Illuminate\Http\Request;

class TurmaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $turmas = Turma::withTrashed()->orderBy('nome_turma', 'asc')->when($search, function ($query, $search) {
            return $query->where('nome_turma', 'like', "%{$search}%")
                ->orWhere('instrutor_responsavel', 'like', "%{$search}%")
                ->orWhere('nivel', 'like', "%{$search}%")
                ->orWhere('dia_semana', 'like', "%{$search}%")
                ->orWhere('laboratorio', 'like', "%{$search}%");
        })->paginate(100);

        return view('estagio.turmas.index', compact('turmas'));
    }

    public function create()
    {
        return view('estagio.turmas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome_turma' => 'required|string|max:255',
            'instrutor_responsavel' => 'required|string|max:255',
            'nivel' => 'required',
            'horario' => 'required',
            'dia_semana' => 'required',
            'quantidade_alunos' => 'required|integer|min:1|max:255',
            'laboratorio' => 'required',
        ]);

        $horario = \Carbon\Carbon::parse($request->horario);
        Turma::create($request->all());

        return redirect()->route('turmas.index')->with('success', 'Turma criada com sucesso.');
    }

    public function edit($id)
    {
        $turma = Turma::findOrFail($id);
        return view('estagio.turmas.edit', compact('turma'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nome_turma' => 'required|string|max:255',
            'instrutor_responsavel' => 'required|string|max:255',
            'nivel' => 'required',
            'horario' => 'required',
            'dia_semana' => 'required',
            'quantidade_alunos' => 'required|integer|min:1|max:255',
            'laboratorio' => 'required',
        ]);

        $turma = Turma::findOrFail($id);
        $turma->update($request->all());

        return redirect()->route('turmas.index')->with('success', 'Turma editada com sucesso.');
    }

    public function destroy($id)
    {
        $turma = Turma::findOrFail($id);
        $turma->delete();

        return redirect()->route('turmas.index')->with('success', 'Turma deletada com sucesso.');
    }

    public function restore($id)
    {
        $turma = Turma::onlyTrashed()->findOrFail($id);
        $turma->restore();
        return redirect()->route('turmas.index')->with('success', 'Turma reativada com sucesso!');
    }
}
