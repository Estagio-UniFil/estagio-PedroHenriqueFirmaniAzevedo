@extends('estagio.layouts.app')

@section('title', '')

@section('content')
<div class="container">
    <h2>Visualizar Presença - Turma {{ $presenca->turma->nome }}</h2>

    <h3>Monitores</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Presente</th>
                <th>Observação</th>
            </tr>
        </thead>
        <tbody>
            @foreach($monitores as $monitor)
                <tr>
                    <td>{{ $monitor->nome_monitor }}</td>
                    <td>{{ $monitor->presente ? 'Sim' : 'Não' }}</td>
                    <td>{{ $monitor->observacao ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h3>Alunos</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Presente</th>
                <th>Observação</th>
            </tr>
        </thead>
        <tbody>
            @foreach($alunos as $aluno)
                <tr>
                    <td>{{ $aluno->nome_aluno }}</td>
                    <td>{{ $aluno->presente ? 'Sim' : 'Não' }}</td>
                    <td>{{ $aluno->observacao ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <a href="{{ route('presencas.index') }}" class="btn btn-primary">Voltar</a>
</div>
@endsection
