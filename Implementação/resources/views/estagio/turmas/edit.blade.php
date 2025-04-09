@extends('estagio.layouts.app')

@section('title', 'Editar Turma')

@section('content')

@if (auth()->check() && auth()->user()->role !== 'aluno')
<div class="container">
    <div class="row mt-3">
        <div class="col-md-2">
            <div class="list-group">
            </div>
        </div>
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Editar Turma</div>
                <div class="card-body">
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('turmas.update', $turma->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="nome_turma" class="form-label">Nome da Turma*</label>
                            <input type="text" name="nome_turma" class="form-control" id="nome_turma" value="{{ old('nome_turma', $turma->nome_turma) }}">
                        </div>
                        <div class="mb-3">
                            <label for="instrutor_responsavel" class="form-label">Instrutor Responsável*</label>
                            <input type="text" name="instrutor_responsavel" class="form-control" id="instrutor_responsavel" value="{{ old('instrutor_responsavel', $turma->instrutor_responsavel) }}">
                        </div>
                        <div class="mb-3">
                            <label for="nivel" class="form-label">Nível da Turma*</label>
                            <select name="nivel" class="form-control" id="nivel">
                                <option value="Iniciante" {{ (old('nivel') ?? $turma->nivel) == 'Iniciante' ? 'selected' : '' }}>Iniciante</option>
                                <option value="Intermediário" {{ (old('nivel') ?? $turma->nivel) == 'Intermediário' ? 'selected' : '' }}>Intermediário</option>
                                <option value="Avançado" {{ (old('nivel') ?? $turma->nivel) == 'Avançado' ? 'selected' : '' }}>Avançado</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="horario" class="form-label">Horário*</label>
                            <input type="time" name="horario" class="form-control" id="horario" value="{{ old('horario', $turma->horario) }}">
                        </div>
                        <div class="mb-3">
                            <label for="dia_semana" class="form-label">Dia da Semana*</label>
                            <select name="dia_semana" class="form-control" id="dia_semana">
                                <option value="Segunda" {{ (old('dia_semana') ?? $turma->dia_semana) == 'Segunda' ? 'selected' : '' }}>Segunda</option>
                                <option value="Terça" {{ (old('dia_semana') ?? $turma->dia_semana) == 'Terça' ? 'selected' : '' }}>Terça</option>
                                <option value="Quarta" {{ (old('dia_semana') ?? $turma->dia_semana) == 'Quarta' ? 'selected' : '' }}>Quarta</option>
                                <option value="Quinta" {{ (old('dia_semana') ?? $turma->dia_semana) == 'Quinta' ? 'selected' : '' }}>Quinta</option>
                                <option value="Sexta" {{ (old('dia_semana') ?? $turma->dia_semana) == 'Sexta' ? 'selected' : '' }}>Sexta</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="quantidade_alunos" class="form-label">Quantidade Máxima de Alunos*</label>
                            <input type="number" name="quantidade_alunos" class="form-control" id="quantidade_alunos" value="{{ old('quantidade_alunos', $turma->quantidade_alunos) }}">
                        </div>
                        <div class="mb-3">
                            <label for="laboratorio" class="form-label">Laboratório</label>
                            <select name="laboratorio" class="form-control" id="laboratorio">
                                <option value="Lab 3" {{ (old('laboratorio') ?? $turma->laboratorio) == 'Lab 3' ? 'selected' : '' }}>Lab 3</option>
                                <option value="Lab 6" {{ (old('laboratorio') ?? $turma->laboratorio) == 'Lab 6' ? 'selected' : '' }}>Lab 6</option>
                                <option value="Lab 7" {{ (old('laboratorio') ?? $turma->laboratorio) == 'Lab 7' ? 'selected' : '' }}>Lab 7</option>
                                <option value="Lab 8" {{ (old('laboratorio') ?? $turma->laboratorio) == 'Lab 8' ? 'selected' : '' }}>Lab 8</option>
                                <option value="Outro" {{ (old('laboratorio') ?? $turma->laboratorio) == 'Outro' ? 'selected' : '' }}>Outro</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                        <a href="{{ route('turmas.index') }}" class="btn btn-danger">Cancelar</a>
                    </form>
                </div>
            </div>
            <br>
        </div>
    </div>
</div>
@else
<h1 class="text-center" style="color: red;">Sai daqui manézinho</h1>
@endif
@endsection