@extends('estagio.layouts.app')

@section('title', 'Escolas')

@section('content')

@if (auth()->check() && auth()->user()->role !== 'aluno')
<div class="container">
    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h1>Lista de Escolas</h1>
                </div>
                <div class="card-body">
                    @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif
                    <div class="row mb-3">
                        <div class="col-12 col-sm-4 col-md-2 mb-2 mb-sm-0">
                            <a href="{{ route('escolas.create') }}" class="btn btn-success">Adicionar Escola</a>
                        </div>
                        <div class="col-10">
                            <form action="{{ route('escolas.index') }}" method="GET">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" placeholder="Pesquisar Escolas" value="{{ request('search') }}">
                                    <button type="submit" class="btn btn-primary">Pesquisar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    @if ($escolas->count())
                    <div class="table-responsive">
                        <table class="table table-striped align-middle">
                            <thead>
                                <tr>
                                    <th>Nome da Escola</th>
                                    <th>Responsável</th>
                                    <th>Contato (Telefone, e-mail...)</th>
                                    <th class="text-center"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($escolas as $escola)
                                <tr>
                                    <td>{{ $escola->nome_escola }}</td>
                                    <td>{{ $escola->responsavel_escola }}</td>
                                    <td>{{ $escola->contato }}</td>
                                    <td>
                                        @if($escola->trashed())
                                        <form action="{{ route('escolas.restore', $escola->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-warning btn-sm">Reativar</button>
                                        </form>
                                        @else
                                        <a href="{{ route('escolas.edit', $escola->id) }}" class="btn btn-warning btn-sm">
                                            <i class='bx bxs-edit' style="color:#ffffff"></i>
                                        </a>
                                        <form action="{{ route('escolas.destroy', $escola->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja inativar esta escola?');">
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
                    {{ $escolas->links() }}
                    @else
                    <h3 class="text-center">Não há escolas cadastradas. 😞</h3>
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