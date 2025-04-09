@extends('estagio.layouts.app')

@section('title', 'Alunos')

@section('content')

@if (auth()->check() && auth()->user()->role !== 'aluno')
<div class="container">
    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h1>Lista de Alunos</h1>
                </div>
                <div class="card-body">
                    @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif
                    <div class="row mb-3">
                        <div class="col-12 col-sm-4 col-md-2 mb-2 mb-sm-0">
                            <a href="{{ route('alunos.create') }}" class="btn btn-success">Adicionar Aluno</a>
                        </div>
                        <div class="col-10">
                            <form action="{{ route('alunos.index') }}" method="GET">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" placeholder="Pesquisar Alunos" value="{{ request('search') }}">
                                    <button type="submit" class="btn btn-primary">Pesquisar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    @if ($alunos->count())
                    <div class="table-responsive">
                        <table class="table table-striped align-middle">
                            <thead>
                                <tr>
                                    <th>Nome do Aluno</th>
                                    <th>Escola</th>
                                    <th>Turma</th>
                                    <th class="text-center"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($alunos as $aluno)
                                <tr>
                                    <td>{{ $aluno->nome_aluno }}</td>
                                    <td>{{ $aluno->escola->nome_escola ?? 'N/A' }}</td>
                                    <td>{{ $aluno->turma->nome_turma }}</td>
                                    <td>
                                        @if($aluno->trashed())
                                        <form action="{{ route('alunos.restore', $aluno->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-warning btn-sm">Reativar</button>
                                        </form>
                                        @else
                                        <a href="{{ route('alunos.edit', $aluno->id) }}" class="btn btn-warning btn-sm">
                                            <i class='bx bxs-edit' style="color:#ffffff"></i>
                                        </a>
                                        <form action="{{ route('alunos.destroy', $aluno->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja inativar este aluno?');">
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
                    {{ $alunos->links() }}
                    @else
                    <h3 class="text-center">Não há alunos cadastrados. 😞</h3>
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