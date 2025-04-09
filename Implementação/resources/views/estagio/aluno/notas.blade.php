@extends('estagio.layouts.app')

@section('title', 'Minhas Notas')

@section('content')
<div class="container">
    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h1>Minhas Notas</h1>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Atividade</th>
                                <th>Turma</th>
                                <th>Nota</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($atividades as $atividade)
                            <tr>
                                <td class="align-middle">{{ $atividade->titulo }}</td>
                                <td class="align-middle">{{ $atividade->turma->nome_turma }}</td>
                                <td>
                                    {{ optional($atividade->notas->first())->nota ?? 'Não disponível' }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection