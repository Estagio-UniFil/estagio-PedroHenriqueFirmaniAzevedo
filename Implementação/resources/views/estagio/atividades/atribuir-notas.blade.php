@extends('estagio.layouts.app')

@section('title', 'Atribuir Notas - ' . $atividade->titulo)

@section('content')

@if (auth()->check() && auth()->user()->role !== 'aluno')
<div class="container">
    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h1>Notas para Turma {{ $atividade->turma->nome_turma }}: {{ $atividade->titulo }}</h1>
                    <div>
                        <label for="pesoAtividade" class="me-2 fw-bold">Peso da Atividade:</label>
                        <input type="number" name="peso_atividade" id="pesoAtividade" min="0" max="100" value="{{ $atividade->peso ?? 0 }}" class="form-control d-inline-block" style="width: 80px;" form="formNotas" step="1" required>
                    </div>
                </div>
                <div class="card-body">
                    <form id="formNotas" action="{{ route('atividades.notas.salvar', $atividade->id) }}" method="POST">
                        @csrf
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nome do Aluno</th>
                                    <th>Turma</th>
                                    <th>Nota</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($alunos as $aluno)
                                <tr>
                                    <td class="align-middle">{{ $aluno->nome_aluno }}</td>
                                    <td class="align-middle">{{ $aluno->turma->nome_turma }}</td>
                                    <td>
                                        <input type="number" name="alunos[{{ $aluno->id }}]" min="0" max="100" class="form-control" value="{{ $notas[$aluno->id]->nota ?? '0' }}" step="0.1" required>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <button type="submit" class="btn btn-primary">Salvar Notas</button>
                        <a href="{{ route('atividades.index') }}" class="btn btn-danger">Cancelar</a>
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
