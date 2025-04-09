@extends('estagio.layouts.app')

@section('title', 'Criar Atividade')

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
                <div class="card-header">Criar Atividade</div>
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

                    <form action="{{ route('atividades.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="turma_id">Turma*</label>
                            <select name="turma_id" id="turma_id" class="form-control" value="{{ old('turma_id') }}">
                                <option value="">Selecione a Turma</option>
                                @foreach($turmas as $turma)
                                <option value="{{ $turma->id }}">{{ $turma->nome_turma }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="titulo" class="form-label">Título*</label>
                            <input type="text" name="titulo" id="titulo" class="form-control" value="{{ old('titulo') }}">
                        </div>
                        <div class="mb-3">
                            <label for="descricao">Descrição</label>
                            <textarea name="descricao" id="descricao" class="form-control" value="{{ old('descricao') }}"></textarea>
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