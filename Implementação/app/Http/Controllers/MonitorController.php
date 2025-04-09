<?php

namespace App\Http\Controllers;

use App\Models\Monitor;
use App\Models\Turma;
use Illuminate\Http\Request;

class MonitorController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $monitores = Monitor::withTrashed()->orderBy('nome_monitor', 'asc')->when($search, function ($query, $search) {
            return $query->where('nome_monitor', 'like', "%{$search}%")
                ->orWhere('email_monitor', 'like', "%{$search}%")
                ->orWhere('matricula', 'like', "%{$search}%")
                ->orWhere('turmas', 'like', "%{$search}%");
        })->paginate(100);

        return view('estagio.monitores.index', compact('monitores'));
    }

    public function create()
    {
        $turmas = Turma::all();

        return view('estagio.monitores.create', compact('turmas'));
    }

    public function store(Request $request)
    {
        $validation = $request->validate([
            'nome_monitor' => 'required|string|max:255',
            'email_monitor' => 'required|string|email|max:255|unique:monitores,email_monitor',
            'matricula' => 'required|string|max:20',
            'turmas' => 'required|array',
        ]);

        $monitor = Monitor::create([
            'nome_monitor' => $validation['nome_monitor'],
            'email_monitor' => $validation['email_monitor'],
            'matricula' => $validation['matricula'],
        ]);


        $monitor->turmas()->sync($validation['turmas'] ?? []);
        return redirect()->route('monitores.index')->with('success', 'Monitor criado com sucesso!');
    }

    public function edit($id)
    {
        $monitor = Monitor::findOrFail($id);
        $turmas = Turma::all();
        return view('estagio.monitores.edit', compact('monitor', 'turmas'));
    }

    public function update(Request $request, Monitor $monitor)
    {
        $validated = $request->validate([
            'nome_monitor' => 'required|string|max:255',
            'email_monitor' => 'required|string|email|max:255|unique:monitores,email_monitor,' . $monitor->id,
            'matricula' => 'required|string|max:20',
            'turmas' => 'array',
        ]);

        $monitor->update([
            'nome_monitor' => $validated['nome_monitor'],
            'email_monitor' => $validated['email_monitor'],
            'matricula' => $validated['matricula'],
        ]);

        $monitor->turmas()->sync($validated['turmas'] ?? []);

        return redirect()->route('monitores.index')->with('success', 'Monitor atualizado com sucesso!');
    }


    public function destroy($id)
    {
        $monitor = Monitor::findOrFail($id);
        $monitor->delete();

        return redirect()->route('monitores.index')->with('success', 'Monitor inativado com sucesso!');
    }

    public function restore($id)
    {
        $monitor = Monitor::onlyTrashed()->findOrFail($id);
        $monitor->restore();
        return redirect()->route('monitores.index')->with('success', 'Monitor reativado com sucesso!');
    }
}
