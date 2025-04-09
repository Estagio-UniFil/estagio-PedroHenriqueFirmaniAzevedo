@extends('estagio.layouts.app')

@section('title', 'Editar Atividade')

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
                <div class="card-header">Editar Atividade</div>
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

                    <form action="{{ route('atividades.update', $atividade->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="turma_id" class="form-label">Turma*</label>
                            <select name="turma_id" class="form-control" id="turma_id">
                                @foreach($turmas as $turma)
                                    <option value="{{ $turma->id }}"
                                            {{ $atividade->turma_id == $turma->id ? 'selected' : '' }}>
                                        {{ $turma->nome_turma }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="titulo" class="form-label">Titulo*</label>
                            <input type="text" name="titulo" class="form-control" id="titulo" value="{{ old('titulo', $atividade->titulo) }}">
                        </div>
                        <div class="mb-3">
                            <label for="descricao">Descrição</label>
                            <textarea name="descricao" class="form-control" id="descricao">{{ old('descricao', $atividade->descricao) }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                        <a href="{{ route('atividades.index') }}" class="btn btn-danger">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@else
<h1 class="text-center" style="color: red;">Sai daqui manézinho</h1>
@endif
@endsection