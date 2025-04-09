<?php

namespace App\Http\Controllers;

use App\Models\Escola;
use Illuminate\Http\Request;

class EscolaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $escolas = Escola::withTrashed()->orderBy('nome_escola', 'asc')->when($search, function ($query, $search) {
            return $query->where('nome_escola', 'like', "%{$search}%")
                ->orWhere('responsavel_escola', 'like', "%{$search}%")
                ->orWhere('contato', 'like', "%{$search}%");
        })->paginate(255);

        return view('estagio.escolas.index', compact('escolas'));
    }

    public function create()
    {
        return view('estagio.escolas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome_escola' => 'required|string|max:255',
            'responsavel_escola' => 'required|string|max:255',
            'contato' => 'required|string|max:255',
        ]);

        Escola::create($request->all());

        return redirect()->route('escolas.index')->with('success', 'Escola criada com sucesso!');
    }

    public function edit($id)
    {
        $escola = Escola::findOrFail($id);
        return view('estagio.escolas.edit', compact('escola'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nome_escola' => 'required|string|max:255',
            'responsavel_escola' => 'required|string|max:255',
            'contato' => 'required|string|max:255',
        ]);

        $escola = Escola::findOrFail($id);
        $escola->update($request->all());

        return redirect()->route('escolas.index')->with('success', 'Escola atualizada com sucesso!');
    }

    public function destroy($id)
    {
        $escola = Escola::findOrFail($id);
        $escola->delete();

        return redirect()->route('escolas.index')->with('success', 'Escola inativada com sucesso!');
    }

    public function restore($id)
    {
        $escola = Escola::onlyTrashed()->findOrFail($id);
        $escola->restore();

        return redirect()->route('escolas.index')->with('success', 'Escola reativada com sucesso!');
    }
}
