@extends('estagio.layouts.app')

@section('title', 'Minhas Notas')

@section('content')
<div class="container">
    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h1>Minhas Notas</h1>
                    <div class="h5">
                        <strong>Média:</strong>
                        @php
                            $somaNotasPonderadas = 0;
                            $somaPesos = 0;
                        @endphp

                        @foreach ($atividades as $atividade)
                            @php
                                $nota = optional($atividade->notas->first())->nota;
                                $peso = $atividade->peso; // agora o peso vem direto da atividade

                                if (is_numeric($nota) && is_numeric($peso) && $peso > 0) {
                                    $somaNotasPonderadas += $nota * $peso;
                                    $somaPesos += $peso;
                                } elseif (is_numeric($nota) && $peso == 0) {
                                    $somaNotasPonderadas += $nota;
                                }
                            @endphp
                        @endforeach

                        @php
                            $mediaPonderada = $somaPesos > 0 
                                ? $somaNotasPonderadas / $somaPesos 
                                : (count($atividades) > 0 ? $somaNotasPonderadas / count($atividades) : null);
                        @endphp

                        @if (!is_null($mediaPonderada))
                            {{ number_format($mediaPonderada, 2) }}
                        @else
                            Não disponível
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Atividade</th>
                                <th>Turma</th>
                                <th>Nota</th>
                                <th>Peso</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($atividades as $atividade)
                            <tr>
                                <td class="align-middle">{{ $atividade->titulo }}</td>
                                <td class="align-middle">{{ $atividade->turma->nome_turma }}</td>
                                <td>{{ optional($atividade->notas->first())->nota ?? 'Não disponível' }}</td>
                                <td>{{ $atividade->peso ?? 'Não disponível' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
