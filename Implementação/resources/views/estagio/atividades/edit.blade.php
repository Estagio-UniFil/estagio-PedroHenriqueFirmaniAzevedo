@extends('estagio.layouts.app')

@section('title', 'Editar Atividade')

@section('content')
<div class="container mt-4">
    <h1>Editar Atividade</h1>
    <form action="{{ route('atividades.update', $atividade->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="titulo">Título</label>
            <input type="text" name="titulo" class="form-control" value="{{ $atividade->titulo }}" required>
        </div>

        <div class="form-group">
            <label for="descricao">Descrição</label>
            <textarea name="descricao" class="form-control">{{ $atividade->descricao }}</textarea>
        </div>

        <div class="form-group">
            <label for="turma_id">Turma</label>
            <select name="turma_id" class="form-control" required>
                @foreach($turmas as $turma)
                    <option value="{{ $turma->id }}" {{ $atividade->turma_id == $turma->id ? 'selected' : '' }}>
                        {{ $turma->nome_turma }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="peso">Peso da Atividade</label>
            <input type="number" name="peso" class="form-control" step="1" min="0" max="100" value="{{ $atividade->peso }}" required>
        </div>

        <button type="submit" class="btn btn-primary mt-2">Atualizar</button>
        <a href="{{ route('atividades.index') }}" class="btn btn-secondary mt-2">Cancelar</a>
    </form>
</div>
@endsection
