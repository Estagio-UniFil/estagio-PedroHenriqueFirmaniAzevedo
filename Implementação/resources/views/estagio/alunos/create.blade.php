@extends('estagio.layouts.app')

@section('title', 'Adicionar Aluno')

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
                <div class="card-header">Adicionar Aluno</div>
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

                    <form action="{{ route('alunos.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="nome_aluno" class="form-label">Nome do Aluno*</label>
                            <input type="text" name="nome_aluno" class="form-control" id="nome_aluno" value="{{ old('nome_aluno') }}">
                        </div>
                        <div class="mb-3">
                            <label for="escola_id" class="form-label">Escola</label>
                            <select name="escola_id" class="form-control" id="escola_id">
                                <option value="">Selecione a Escola</option>
                                @foreach($escolas as $escola)
                                <option value="{{ $escola->id }}" {{ old('escola_id') == $escola->id ? 'selected' : '' }}>{{ $escola->nome_escola }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="turma_id" class="form-label">Turma*</label>
                            <select name="turma_id" class="form-control" id="turma_id">
                                @foreach($turmas as $turma)
                                <option value="{{ $turma->id }}" {{ old('turma_id') == $turma->id ? 'selected' : '' }}>{{ $turma->nome_turma }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="email">Email (Login)*</label>
                            <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="password">Senha*</label>
                            <input type="password" name="password" id="password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                        <a href="{{ route('alunos.index') }}" class="btn btn-danger">Cancelar</a>
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