@extends('estagio.layouts.app')

@section('title', 'Criar Atividade')

@section('content')
<div class="container mt-4">
    <h1>Criar Nova Atividade</h1>
    <form action="{{ route('atividades.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="titulo">Título</label>
            <input type="text" name="titulo" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="descricao">Descrição</label>
            <textarea name="descricao" class="form-control"></textarea>
        </div>

        <div class="form-group">
            <label for="turma_id">Turma</label>
            <select name="turma_id" class="form-control" required>
                @foreach($turmas as $turma)
                    <option value="{{ $turma->id }}">{{ $turma->nome_turma }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <!--Adicione o campo de peso da atividade aqui para adicionar quando criar uma atividade-->
            <label for="peso">Peso da Atividade</label>
            <input type="number" name="peso" class="form-control" step="1" min="0" max="100" value="1" required>
        </div>

        <button type="submit" class="btn btn-primary mt-2">Criar</button>
    </form>
</div>
@endsection
