<table>
    <thead>
        <tr>
            <th>Nome</th>
            <th>Tipo</th>
            <th>Presente</th>
            <th>Observação</th>
        </tr>
    </thead>
    <tbody>
        @foreach($monitores as $monitor)
            <tr>
                <td>{{ $monitor->nome_monitor }}</td>
                <td>Monitor</td>
                <td>{{ $monitor->presente ? 'Sim' : 'Não' }}</td>
                <td>{{ $monitor->observacao ?? '-' }}</td>
            </tr>
        @endforeach

        @foreach($alunos as $aluno)
            <tr>
                <td>{{ $aluno->nome_aluno }}</td>
                <td>Aluno</td>
                <td>{{ $aluno->presente ? 'Sim' : 'Não' }}</td>
                <td>{{ $aluno->observacao ?? '-' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
