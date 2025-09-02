@extends('estagio.layouts.app')

@section('title', 'Atividades')

@section('content')

@if (auth()->check() && auth()->user()->role !== 'aluno')
<div class="container">
    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h1>Lista de Atividades</h1>
                </div>
                <div class="card-body">
                    @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif
                    <div class="row mb-3">
                        <div class="col-12 col-sm-4 col-md-2 mb-2 mb-sm-0">
                            <a href="{{ route('atividades.create') }}" class="btn btn-success">Criar Atividade</a>
                        </div>
                        <div class="col-10">
                            <form action="{{ route('atividades.index') }}" method="GET">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" placeholder="Pesquisar Atividades" value="{{ request('search') }}">
                                    <button type="submit" class="btn btn-primary">Pesquisar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    @if ($atividades->count())
                    <div class="table-responsive">
                        <table class="table table-striped align-middle">
                            <thead>
                                <tr>
                                    <th>Título</th>
                                    <th>Turma</th>
                                    <!-- Adicione a coluna de Peso aqui para ver o peso de cada atividade na lista-->
                                    <th>Peso</th>
                                    <th>Notas</th>
                                    <th class="text-center"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($atividades as $atividade)
                                <tr>
                                    <td>{{ $atividade->titulo }}</td>
                                    <td>{{ $atividade->turma->nome_turma }}</td>
                                    <td>{{ $atividade->peso ?? 'N/A' }}</td> <!-- NOVO -->
                                    <td>
                                        <a href="{{ route('atividades.notas', $atividade->id) }}" class="btn btn-primary btn-sm">
                                            Atribuir Notas
                                        </a>
                                    </td>
                                    <td>
                                        @if($atividade->trashed())
                                        <form action="{{ route('atividades.restore', $atividade->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-warning btn-sm">Reativar</button>
                                        </form>
                                        @else
                                        <a href="{{ route('atividades.edit', $atividade->id) }}" class="btn btn-warning btn-sm">
                                            <i class='bx bxs-edit' style="color:#ffffff"></i>
                                        </a>
                                        <form action="{{ route('atividades.destroy', $atividade->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja inativar esta atividade?');">
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
                    {{ $atividades->links() }}
                    @else
                    <h3 class="text-center">Nenhuma atividade encontrada. 😞</h3>
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
