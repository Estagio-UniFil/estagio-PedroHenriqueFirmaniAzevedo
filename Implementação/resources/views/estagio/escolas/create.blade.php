@extends('estagio.layouts.app')

@section('title', 'Adicionar Escola')

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
                <div class="card-header">Adicionar Escola</div>
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

                    <form action="{{ route('escolas.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="nome_escola" class="form-label">Nome da Escola*</label>
                            <input type="text" name="nome_escola" class="form-control" id="nome_escola" value="{{ old('nome_escola') }}">
                        </div>
                        <div class="mb-3">
                            <label for="responsavel_escola" class="form-label">Responsável*</label>
                            <input type="text" name="responsavel_escola" class="form-control" id="responsavel_escola" value="{{ old('responsavel_escola') }}">
                        </div>
                        <div class="mb-3">
                            <label for="contato" class="form-label">Contato (Telefone, e-mail...)*</label>
                            <input type="text" name="contato" class="form-control" id="contato" value="{{ old('contato') }}">
                        </div>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                        <a href="{{ route('escolas.index') }}" class="btn btn-danger">Cancelar</a>
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