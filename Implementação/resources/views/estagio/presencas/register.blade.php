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
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 70%;">Nome do Monitor</th>
                                    <th style="width: 10%;">Presença</th>
                                    <th style="width: 20%">Observação</th>
                                </tr>
                            </thead>
                            <tbody class="align-middle">
                                @foreach($monitores as $monitor)
                                <tr>
                                    <td>{{ $monitor->nome_monitor }}</td>
                                    <td>
                                        <input type="text" name="monitores[{{ $monitor->id }}][presente]"
                                            value="{{ old("monitores.{$monitor->id}.presente", $monitor->presente) }}"
                                            class="form-control">
                                    </td>
                                    <td>
                                        <input type="text" name="monitores[{{ $monitor->id }}][observacao]"
                                            value="{{ old("monitores.{$monitor->id}.observacao", $monitor->observacao ?? '') }}"
                                            class="form-control">
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <h3>Alunos</h3>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 70%;">Nome do Aluno</th>
                                    <th style="width: 10%;">Presença</th>
                                    <th style="width: 20%">Observação</th>
                                </tr>
                            </thead>
                            <tbody class="align-middle">
                                @foreach($alunos as $aluno)
                                <tr>
                                    <td>{{ $aluno->nome_aluno }}</td>
                                    <td>
                                        <input type="text" name="alunos[{{ $aluno->id }}][presente]"
                                            value="{{ old("alunos.{$aluno->id}.presente", $aluno->presente) }}"
                                            class="form-control">
                                    </td>
                                    <td>
                                        <input type="text" name="alunos[{{ $aluno->id }}][observacao]"
                                            value="{{ old("alunos.{$aluno->id}.observacao", $aluno->observacao ?? '') }}"
                                            class="form-control">
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Salvar Presenças</button>
                            <a href="{{ route('presencas.index') }}" class="btn btn-danger">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@else
<h1 class="text-center" style="color: red;">Sai daqui manézinho</h1>
@endif
@endsection
