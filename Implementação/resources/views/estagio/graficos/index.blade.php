@extends('estagio.layouts.app')

@section('title', 'Gráficos')

@section('content')
<div class="container mt-4">
    <h1>📊 Gráficos de Desempenho</h1>

    <div class="mb-4">
        <label for="graficoSelect">Escolha o gráfico:</label>
        <select id="graficoSelect" class="form-select w-50">
            <option value="geral" {{ request('tipo_grafico', 'geral') == 'geral' ? 'selected' : '' }}>Média Geral</option>
            <option value="turma" {{ request('tipo_grafico') == 'turma' ? 'selected' : '' }}>Média por Turma</option>
            <option value="aluno" {{ request('tipo_grafico') == 'aluno' ? 'selected' : '' }}>Média por Aluno</option>
        </select>
    </div>

    <div id="filtroTurma" class="mb-4 {{ request('tipo_grafico') != 'aluno' ? 'd-none' : '' }}">
        <form id="filtroForm" method="GET" action="{{ route('graficos.index') }}">
            <input type="hidden" name="tipo_grafico" value="aluno">
            <label for="turma_id">Filtrar por Turma:</label>
            <select name="turma_id" id="turma_id" class="form-select w-50" onchange="this.form.submit()">
                <option value="">Todas</option>
                @foreach($turmas as $turma)
                    <option value="{{ $turma->id }}" {{ $turmaSelecionada == $turma->id ? 'selected' : '' }}>
                        {{ $turma->nome_turma }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>

    <div class="mb-4">
        <canvas id="meuGrafico"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const dados = {
        geral: {
            labels: ['Média Geral'],
            data: [{{ $mediaGeral ?? 0 }}]
        },
        turma: {
            labels: @json($mediasTurmas->pluck('nome_turma')),
            data: @json($mediasTurmas->pluck('media'))
        },
        aluno: {
            labels: @json($mediasAlunos->pluck('nome_aluno')),
            data: @json($mediasAlunos->pluck('media'))
        }
    };

    let meuGrafico;
    const ctx = document.getElementById('meuGrafico').getContext('2d');

    function renderizarGrafico(tipoDados) {
        if (meuGrafico) {
            meuGrafico.destroy();
        }

        let chartData = {
            labels: dados[tipoDados].labels,
            datasets: [{
                label: 'Média de Notas',
                data: dados[tipoDados].data,
                backgroundColor: '#36a2eb'
            }]
        };

        let chartOptions = {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100 
                }
            }
        };

        meuGrafico = new Chart(ctx, {
            type: 'bar', 
            data: chartData,
            options: chartOptions
        });
    }

    const graficoSelect = document.getElementById('graficoSelect');
    const filtroTurma = document.getElementById('filtroTurma');

    graficoSelect.addEventListener('change', function() {
        const selectedValue = this.value;
        
        if (selectedValue === 'aluno') {
            filtroTurma.classList.remove('d-none');
        } else {
            filtroTurma.classList.add('d-none');
        }
        
        renderizarGrafico(selectedValue);
    });

    window.addEventListener('load', function() {
        const initialTipoDados = '{{ request("tipo_grafico", "geral") }}';
        renderizarGrafico(initialTipoDados);
    });
</script>
@endsection