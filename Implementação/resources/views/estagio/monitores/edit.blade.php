@extends('estagio.layouts.app')

@section('title', 'Editar Monitor')

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
                <div class="card-header">Editar Monitor</div>
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

                    <form action="{{ route('monitores.update', $monitor->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="nome_monitor" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="nome_monitor" name="nome_monitor" value="{{ $monitor->nome_monitor }}">
                        </div>
                        <div class="mb-3">
                            <label for="email_monitor" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email_monitor" name="email_monitor" value="{{ $monitor->email_monitor }}">
                        </div>
                        <div class="mb-3">
                            <label for="matricula" class="form-label">Matrícula</label>
                            <input type="text" class="form-control" id="matricula" name="matricula" value="{{ $monitor->matricula }}">
                        </div>
                        <div class="mb-3">
                            <label for="turmas">Turmas</label>
                            <select name="turmas[]" class="form-control" id="turmas" multiple>
                                @foreach ($turmas as $turma)
                                <option value="{{ $turma->id }}" {{ in_array($turma->id, $monitor->turmas->pluck('id')->toArray()) ? 'selected' : '' }}>{{ $turma->nome_turma }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                        <a href="{{ route('monitores.index') }}" class="btn btn-danger">Cancelar</a>
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