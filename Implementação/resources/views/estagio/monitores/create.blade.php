@extends('estagio.layouts.app')

@section('title', 'Adicionar Monitor')

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
                <div class="card-header">Adicionar Monitor</div>
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

                    <form action="{{ route('monitores.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="nome_monitor" class="form-label">Nome do Monitor*</label>
                            <input type="text" name="nome_monitor" class="form-control" id="nome_monitor" value="{{ old('nome_monitor') }}">
                        </div>
                        <div class="mb-3">
                            <label for="email_monitor" class="form-label">Email do Monitor*</label>
                            <input type="email" name="email_monitor" class="form-control" id="email_monitor" value="{{ old('email_monitor') }}">
                        </div>
                        <div class="mb-3">
                            <label for="matricula" class="form-label">Matrícula*</label>
                            <input type="text" name="matricula" class="form-control" id="matricula" value="{{ old('matricula') }}">
                        </div>
                        <div class="mb-3 form-group">
                            <label for="turmas">Turmas*</label>
                            <select name="turmas[]" multiple class="form-control">
                                @foreach($turmas as $turma)
                                <option value="{{ $turma->id }}">{{ $turma->nome_turma }}</option>
                                @endforeach
                            </select>

                        </div>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                        <a href="{{ route('monitores.index') }}" class="btn btn-danger">Cancelar</a>
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