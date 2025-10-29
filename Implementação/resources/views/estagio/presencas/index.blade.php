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
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                     @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    <div class="row mb-3">
                        <div class="col-12 col-sm-4 col-md-2 mb-2 mb-sm-0">
                            <a href="{{ route('presencas.create') }}" class="btn btn-success">Criar Presença</a>
                        </div>
                        <div class="col-12 col-sm-8 col-md-10">
                            <form action="{{ route('presencas.index') }}" method="GET">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" placeholder="Pesquisar por data (AAAA-MM-DD) ou nome da turma" value="{{ request('search') }}">
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
                                    <th>Total Alunos Ativos</th>
                                    <th>Presentes</th>
                                    <th>Faltantes</th>
                                    <th data-bs-toggle="tooltip" title="Alunos com observações que não são abonos/justificativas">
                                        Obs. (sem abonar) <i class='bx bx-info-circle'></i>
                                    </th>
                                    <th data-bs-toggle="tooltip" title="Faltas abonadas ou justificadas na observação. Soma com 'Presentes' para o total de presença considerada.">
                                        Abonadas <i class='bx bx-info-circle'></i>
                                    </th>
                                    <th class="text-center">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($presencas as $presenca)
                                <tr class="align-middle">
                                    <td>{{ \Carbon\Carbon::parse($presenca->data)->format('d/m/Y') }}</td>
                                    <td>{{ $presenca->turma->nome_turma ?? 'Turma não encontrada' }}</td>
                                    <td>{{ $presenca->total_ativos }}</td>
                                    <td>{{ $presenca->presentes_dia }}</td>
                                    <td>{{ $presenca->faltantes_dia }}</td>
                                    <td>{{ $presenca->com_observacao }}</td>
                                    <td>{{ $presenca->abonadas }}</td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group" aria-label="Ações Presença">
                                            <a href="{{ route('presencas.register', $presenca->id) }}" class="btn btn-warning btn-sm" title="Registrar/Editar Presença">
                                                <i class='bx bxs-pencil' style="color:#ffffff"></i>
                                            </a>
                                            <form action="{{ route('presencas.destroy', $presenca->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Tem certeza que deseja excluir este registro de presença? Esta ação não pode ser desfeita.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" title="Excluir Presença">
                                                    <i class='bx bxs-trash' style='color:#ffffff'></i>
                                                </button>
                                            </form>
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

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>

@else
<div class="container">
     <div class="alert alert-danger mt-5" role="alert">
         Acesso não autorizado.
     </div>
</div>
@endif
@endsection