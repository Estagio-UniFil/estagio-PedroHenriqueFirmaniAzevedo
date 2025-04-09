@extends('estagio.layouts.app')

@section('title', 'Turmas')

@section('content')

@if (auth()->check() && auth()->user()->role !== 'aluno')
<div class="container">
    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h1>Lista de Turmas</h1>
                </div>
                <div class="card-body">
                    @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif
                    <div class="row mb-3">
                        <div class="col-12 col-sm-4 col-md-2 mb-2 mb-sm-0">
                            <a href="{{ route('turmas.create') }}" class="btn btn-success">Adicionar Turma</a>
                        </div>
                        <div class="col-10">
                            <form action="{{ route('turmas.index') }}" method="GET">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" placeholder="Pesquisar Turmas" value="{{ request('search') }}">
                                    <button type="submit" class="btn btn-primary">Pesquisar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    @if ($turmas->count())
                    <div class="table-responsive">
                        <table class="table table-striped align-middle">
                            <thead>
                                <tr>
                                    <th>Nome da Turma</th>
                                    <th>Instrutor Responsável</th>
                                    <th>Nível</th>
                                    <th>Horário</th>
                                    <th>Dia da Semana</th>
                                    <th>Qtd. Máx. Alunos</th>
                                    <th>Laboratório</th>
                                    <th class="text-center"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($turmas as $turma)
                                <tr>
                                    <td>{{ $turma->nome_turma }}</td>
                                    <td>{{ $turma->instrutor_responsavel }}</td>
                                    <td>{{ $turma->nivel }}</td>
                                    <td>{{ \Carbon\Carbon::parse($turma->horario)->format('H:i') }}</td>
                                    <td>{{ $turma->dia_semana }}</td>
                                    <td>{{ $turma->quantidade_alunos }}</td>
                                    <td>{{ $turma->laboratorio }}</td>
                                    <td>
                                        @if($turma->trashed())
                                        <form action="{{ route('turmas.restore', $turma->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-warning btn-sm">Reativar</button>
                                        </form>
                                        @else
                                        <!-- Outros botões de ação, como editar ou inativar -->

                                        <a href="{{ route('turmas.edit', $turma->id) }}" class="btn btn-warning btn-sm">
                                            <i class='bx bxs-edit' style='color:#ffffff'></i>
                                        </a>
                                        <form action="{{ route('turmas.destroy', $turma->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja inativar esta turma?');">
                                                <i class='bx bx-trash'></i>
                                            </button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $turmas->links() }}
                    @else
                    <h3 class="text-center">Não há turmas cadastradas. 😞</h3>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@else
<h1 class="text-center" style="color: red;">Sai daqui manézinho</h1>
@endif
@endsection