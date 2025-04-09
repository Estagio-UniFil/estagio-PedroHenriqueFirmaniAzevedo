@extends('estagio.layouts.app')

@section('title', 'Monitores')

@section('content')

@if (auth()->check() && auth()->user()->role !== 'aluno')
<div class="container">
    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h1>Lista de Monitores</h1>
                </div>
                <div class="card-body">
                    @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif
                    <div class="row mb-3">
                        <div class="col-12 col-sm-4 col-md-2 mb-2 mb-sm-0">
                            <a href="{{ route('monitores.create') }}" class="btn btn-success">Adicionar Monitor</a>
                        </div>
                        <div class="col-10">
                            <form action="{{ route('monitores.index') }}" method="GET">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" placeholder="Pesquisar Monitores" value="{{ request('search') }}">
                                    <button type="submit" class="btn btn-primary">Pesquisar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    @if ($monitores->count())
                    <div class="table-responsive">
                        <table class="table table-striped align-middle">
                            <thead>
                                <tr>
                                    <th>Nome do Monitor</th>
                                    <th>Email do Monitor</th>
                                    <th>Matrícula</th>
                                    <th>Turmas</th>
                                    <th class="text-center"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($monitores as $monitor)
                                <tr>
                                    <td>{{ $monitor->nome_monitor }}</td>
                                    <td>{{ $monitor->email_monitor }}</td>
                                    <td>{{ $monitor->matricula }}</td>
                                    <td>{{ $monitor->turmas->implode('nome_turma', ', ') }}</td>
                                    <td>
                                        @if ($monitor->trashed())
                                        <form action="{{ route('monitores.restore', $monitor->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-warning btn-sm">Reativar</button>
                                        </form>
                                        @else
                                        <a href="{{ route('monitores.edit', $monitor->id) }}" class="btn btn-warning btn-sm">
                                            <i class="bx bxs-edit" style="color:#ffffff;"></i>
                                        </a>
                                        <form action="{{ route('monitores.destroy', $monitor->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja inativar este monitor?');">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $monitores->links() }}
                    @else
                    <h3 class="text-center">Não há monitores cadastrados. 😞</h3>
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