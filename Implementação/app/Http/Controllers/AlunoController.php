<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Aluno;
use App\Models\Turma;
use App\Models\Escola;
use App\Models\Atividade;
use App\Models\User;

class AlunoController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $alunos = Aluno::withTrashed()->orderBy('nome_aluno', 'asc')->when($search, function ($query) use ($search) {
            return $query->where('nome_aluno', 'like', "%{$search}%")
                ->orWhere('escola_id', 'like', "%{$search}%")
                ->orWhere('turma_id', 'like', "%{$search}%");
        })->paginate(255);

        return view('estagio.alunos.index', compact('alunos'));
    }

    public function create()
    {
        $turmas = Turma::all();
        $escolas = Escola::all();
        return view('estagio.alunos.create', compact('turmas', 'escolas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome_aluno' => 'required|string|max:255',
            'escola_id' => 'nullable|string|max:255',
            'turma_id' => 'required|exists:turmas,id',
            'email' => 'required|email',
            'password' => 'required|min:8',
            'role' => 'aluno',
        ]);

        $user = User::create([
            'name' => $request->nome_aluno,
            'email' => $request->email,
            'password' => bcrypt($request->password), // Hash a senha
            'role' => 'aluno',
        ]);

        Aluno::create([
            'user_id' => $user->id,
            'nome_aluno' => $request->nome_aluno,
            'escola_id' => $request->escola_id,
            'turma_id' => $request->turma_id,
            'email' => $request->email,
            'password' => $request->password,
            'role' => 'aluno',
        ]);

        return redirect()->route('alunos.index')->with('success', 'Aluno criado com sucesso!');
    }

    public function edit($id)
    {
        $aluno = Aluno::findOrFail($id);
        $turmas = Turma::all();
        $escolas = Escola::all();
        return view('estagio.alunos.edit', compact('aluno', 'turmas', 'escolas'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nome_aluno' => 'required|string|max:255',
            'escola_id' => 'required|string|max:255',
            'turma_id' => 'required|exists:turmas,id',
        ]);

        $aluno = Aluno::findOrFail($id);
        $aluno->update($request->all());

        return redirect()->route('alunos.index')->with('success', 'Aluno atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $aluno = Aluno::findOrFail($id);
        $aluno->delete();

        return redirect()->route('alunos.index')->with('success', 'Aluno inativado com sucesso!');
    }

    public function restore($id)
    {
        $aluno = Aluno::onlyTrashed()->findOrFail($id);
        $aluno->restore();

        return redirect()->route('alunos.index')->with('success', 'Aluno reativado com sucesso!');
    }

    public function notas_aluno()
    {
        $alunoId = auth()->id() - 1;

        $atividades = Atividade::whereHas('turma.alunos', function ($query) use ($alunoId) {
            $query->where('alunos.id', $alunoId);
        })
            ->with(['notas' => function ($query) use ($alunoId) {
                $query->where('aluno_id', $alunoId);
            }])
            ->get();

        return view('estagio.aluno.notas', compact('atividades'));
    }
}
