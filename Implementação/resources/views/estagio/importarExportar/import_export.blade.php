@extends('estagio.layouts.app')

@section('title', 'Importar/Exportar Dados')

@section('content')

@if (auth()->check() && auth()->user()->role !== 'aluno')
<div class="container">
    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h1>Importar e Exportar Dados</h1>
                </div>
                <div class="card-body">
                    @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif

                    <div class="row mb-3">
                        <div class="col-md-6 text-center mb-2">
                            <a href="#" class="btn btn-primary btn-lg w-100">Importar Dados</a>
                        </div>
                        <div class="col-md-6 text-center mb-2">
                            <a href="#" class="btn btn-success btn-lg w-100">Exportar Dados</a>
                        </div>
                    </div>

                    <p class="text-muted text-center">Funcionalidades de importação e exportação serão adicionadas futuramente.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@else
<h1 class="text-center" style="color: red;">Sai daqui manézinho</h1>
@endif

@endsection
