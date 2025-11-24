@extends('estagio.layouts.app')

@section('title', 'Registrar Presença')

@section('content')

@if (auth()->check() && auth()->user()->role !== 'aluno')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h1>Registrar Presença para {{ $presenca->turma->nome_turma }} em {{ \Carbon\Carbon::parse($presenca->data)->format('d/m/Y') }}</h1>
                </div>
                <div class="card-body">
                    <form action="{{ route('presencas.salvar', $presenca->id) }}" method="POST">
                        @csrf

                        <h3>Monitores</h3>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th style="width: 60%;">Nome do Monitor</th>
                                        <th style="width: 10%; text-align: center;">Presença</th>
                                        <th style="width: 30%">Observação</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($monitores as $monitor)
                                    <tr>
                                        <td>{{ $monitor->nome_monitor }}</td>
                                        <td class="text-center">
                                            <div class="form-check form-switch d-flex justify-content-center">
                                                <input type="hidden" name="monitores[{{ $monitor->id }}][presente]" value="0">
                                                
                                                <input class="form-check-input" type="checkbox" style="width: 50px; height: 25px;"
                                                    name="monitores[{{ $monitor->id }}][presente]" 
                                                    value="1" 
                                                    id="monitor_{{ $monitor->id }}"
                                                    {{ (old("monitores.{$monitor->id}.presente", $monitor->presente) == 1) ? 'checked' : '' }}>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="text" name="monitores[{{ $monitor->id }}][observacao]"
                                                value="{{ old("monitores.{$monitor->id}.observacao", $monitor->observacao ?? '') }}"
                                                class="form-control form-control-sm" placeholder="Obs (Opcional)">
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <h3 class="mt-4">Alunos</h3>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th style="width: 60%;">Nome do Aluno</th>
                                        <th style="width: 10%; text-align: center;">Presença</th>
                                        <th style="width: 30%">Observação</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($alunos as $aluno)
                                    <tr>
                                        <td>{{ $aluno->nome_aluno }}</td>
                                        <td class="text-center">
                                            <div class="form-check form-switch d-flex justify-content-center">
                                                <input type="hidden" name="alunos[{{ $aluno->id }}][presente]" value="0">
                                                
                                                <input class="form-check-input" type="checkbox" style="width: 50px; height: 25px;"
                                                    name="alunos[{{ $aluno->id }}][presente]" 
                                                    value="1" 
                                                    id="aluno_{{ $aluno->id }}"
                                                    {{ (old("alunos.{$aluno->id}.presente", $aluno->presente) == 1) ? 'checked' : '' }}>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="text" name="alunos[{{ $aluno->id }}][observacao]"
                                                value="{{ old("alunos.{$aluno->id}.observacao", $aluno->observacao ?? '') }}"
                                                class="form-control form-control-sm" placeholder="Obs (Opcional)">
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="form-group mt-4 d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('presencas.index') }}" class="btn btn-danger me-md-2">Cancelar</a>
                            <button type="submit" class="btn btn-success btn-lg">Salvar Presenças</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@else
<h1 class="text-center" style="color: red;">Acesso Negado</h1>
@endif
@endsection