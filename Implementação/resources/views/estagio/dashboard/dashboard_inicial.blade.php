@extends('estagio.layouts.app')

@section('title', 'Dashboard Inicial')

@section('content')

@if (auth()->check() && auth()->user()->role !== 'aluno')
<div class="container-fluid">

    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h1>Dashboard</h1>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card shadow-sm mb-3">
                                <div class="card-header">
                                    <strong>Estatísticas Gerais</strong>
                                </div>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Total de Alunos (Ativos e Inativos):
                                        <span class="badge bg-primary rounded-pill">{{ $totalAlunosGeral }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Alunos Ativos:
                                        <span class="badge bg-success rounded-pill">{{ $alunosAtivosGeral }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Alunos Inativos:
                                        <span class="badge bg-danger rounded-pill">{{ $alunosInativosGeral }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Média Geral de Notas:
                                        <span class="badge bg-info rounded-pill">{{ round($mediaNotasGeral, 2) }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="card shadow-sm mb-3">
                                <div class="card-header">
                                    <strong>Estatísticas por Turma</strong>
                                </div>
                                <div class="card-body" style="max-height: 250px; overflow-y: auto;">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Turma</th>
                                                    <th>Total Alunos</th>
                                                    <th>Ativos</th>
                                                    <th>Inativos</th>
                                                    <th>% Desist.</th>
                                                    <th>Média Notas</th>
                                                    <th>Total Aulas</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($statsPorTurma as $nomeTurma => $stats)
                                                <tr>
                                                    <td>{{ $nomeTurma }}</td>
                                                    <td>{{ $stats['total'] }}</td>
                                                    <td>{{ $stats['ativos'] }}</td>
                                                    <td>{{ $stats['inativos'] }}</td>
                                                    <td>{{ $stats['porcentagem_desistentes'] }}%</td>
                                                    <td>{{ $stats['media_notas'] }}</td>
                                                    <td>{{ $stats['total_aulas'] }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3>Filtros</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('dashboard.inicial') }}" method="GET" class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label for="turma_id" class="form-label">Turma</label>
                            <select name="turma_id" id="turma_id" class="form-select">
                                <option value="todas" {{ ($turmaSelecionadaId ?? 'todas') == 'todas' ? 'selected' : '' }}>Todas as Turmas</option>
                                @foreach($turmasParaFiltro as $turma)
                                    <option value="{{ $turma->id }}" {{ ($turmaSelecionadaId ?? '') == $turma->id ? 'selected' : '' }}>
                                        {{ $turma->nome_turma }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="data_inicio" class="form-label">Data Início</label>
                            <input type="date" name="data_inicio" id="data_inicio" class="form-control" value="{{ $dataInicio ?? '' }}">
                        </div>
                        <div class="col-md-2">
                            <label for="data_fim" class="form-label">Data Fim</label>
                            <input type="date" name="data_fim" id="data_fim" class="form-control" value="{{ $dataFim ?? '' }}">
                        </div>
                        <div class="col-md-3">
                            <label for="situacao" class="form-label">Situação do Aluno</label>
                            <select name="situacao" id="situacao" class="form-select">
                                <option value="" {{ ($filtroSituacao ?? '') == '' ? 'selected' : '' }}>Todos</option>
                                <option value="ativos" {{ ($filtroSituacao ?? '') == 'ativos' ? 'selected' : '' }}>Somente Ativos</option>
                                <option value="inativos" {{ ($filtroSituacao ?? '') == 'inativos' ? 'selected' : '' }}>Somente Inativos</option>
                                <option value="faltosos" {{ ($filtroSituacao ?? '') == 'faltosos' ? 'selected' : '' }}>Ativos (Faltou 3+)</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3>Lista de Alunos</h3>
                </div>
                <div class="card-body">
                    @if ($alunosPaginados->count())
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nome</th>
                                    <th>Email</th>
                                    <th>Situação</th>
                                    <th>Turma</th>
                                    <th>Presenças</th>
                                    <th>% Presença</th>
                                    <th>Nota Ponderada</th>
                                    <th>Situação Final</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($alunosPaginados as $aluno)
                                <tr class="{{ $aluno->faltou_ultimas_3 ? 'table-danger' : '' }}">
                                    <td>{{ $aluno->id }}</td>
                                    <td>{{ strtoupper($aluno->nome_aluno) }}</td>
                                    <td>{{ $aluno->user->email ?? 'N/A' }}</td>
                                    <td>
                                        @if($aluno->deleted_at)
                                            <span class="badge bg-danger">Inativo</span>
                                        @else
                                            <span class="badge bg-success">Ativo</span>
                                        @endif
                                    </td>
                                    <td>{{ $aluno->turma->nome_turma ?? 'N/A' }}</td>
                                    <td>{{ $aluno->quantidade_presencas }}</td>
                                    <td>{{ $aluno->porcentagem_presenca }}%</td>
                                    <td>{{ $aluno->nota_ponderada }}</td>
                                    <td>
                                        @if($aluno->situacao_final == 'Aprovado')
                                            <span class="badge bg-success">Aprovado</span>
                                        @elseif($aluno->situacao_final == 'Reprovado')
                                            <span class="badge bg-danger">Reprovado</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $aluno->situacao_final }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $alunosPaginados->links() }}
                    @else
                    <h3 class="text-center">Nenhum aluno encontrado com os filtros aplicados. 😞</h3>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>
@else
<h1 class="text-center" style="color: red;">Acesso negado.</h1>
@endif
@endsection
