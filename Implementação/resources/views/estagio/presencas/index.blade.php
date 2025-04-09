@extends('estagio.layouts.app')

@section('title', 'Presenças')

@section('content')

@if (auth()->check() && auth()->user()->role !== 'aluno')
<div class="container">
    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h1>Lista de Presenças</h1>
                </div>
                <div class="card-body">
                    @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif
                    <div class="row mb-3">
                        <div class="col-12 col-sm-4 col-md-2 mb-2 mb-sm-0">
                            <a href="{{ route('presencas.create') }}" class="btn btn-success">Criar Presença</a>
                        </div>
                        <div class="col-10">
                            <form action="{{ route('presencas.index') }}" method="GET">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" placeholder="Pesquisar presenças" value="{{ request('search') }}">
                                    <button type="submit" class="btn btn-primary">Pesquisar</button>
                                </div>
                            </form>

                        </div>
                    </div>
                    @if ($presencas->count())
                    <div class="table-responsive">
                        <table class="table table-striped align-middle">
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Turma</th>
                                    <th class="text-center">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($presencas as $presenca)
                                <tr class="align-middle">
                                    <td>{{ \Carbon\Carbon::parse($presenca->data)->format('d/m/Y') }}</td>
                                    <td>{{ $presenca->turma->nome_turma }}</td>
                                    <td class="text-center">
                                        <div>
                                            <a href="{{ route('presencas.register', $presenca->id) }}" class="btn btn-warning btn-sm">
                                                <i class='bx bxs-pencil' style="color:#ffffff"></i>
                                            </a>

                                            <a href="{{ route('presencas.visualizar', $presenca->id) }}" class="btn btn-info btn-sm">
                                                <i class='bx bxs-receipt' style='color:#ffffff'></i>
                                            </a>

                                            <a href="{{ route('presencas.exportar', $presenca->id) }}" class="btn btn-secondary btn-sm">
                                                <i class='bx bxs-download' style='color:#ffffff'></i>
                                            </a>

                                            <a href="" class="btn btn-danger btn-sm">
                                                <i class='bx bxs-trash' style='color:#ffffff'></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <h3 class="text-center">Nenhuma presença encontrada. 😞</h3>
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
