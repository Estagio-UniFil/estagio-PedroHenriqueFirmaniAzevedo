@extends('estagio.layouts.app')

@section('title', 'Criar Presença')

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
                <div class="card-header">Criar Presença</div>
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

                    <form action="{{ route('presencas.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="data" class="form-label">Data*</label>
                            <input type="date" name="data" class="form-control" id="data" value="{{ old('data') }}" required
                                   min="{{ date('Y') }}-01-01" max="{{ date('Y') }}-12-31">
                        </div>
                        <div class="mb-3">
                            <label for="turma_id" class="form-label">Turma*</label>
                            <select name="turma_id" class="form-select" id="turma_id" required>
                                <option value="" disabled selected>Selecione uma turma</option>
                                @foreach ($turmas as $turma)
                                <option value="{{ $turma->id }}">{{ $turma->nome_turma }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                        <a href="{{ route('presencas.index') }}" class="btn btn-danger">Cancelar</a>
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