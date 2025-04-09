<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Turma;
use App\Models\Monitor;
use App\Models\Escola;
use App\Models\Aluno;
use App\Models\Atividade;
use App\Models\Nota;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'InstrutorAdmin',
            'email' => 'instrutor@admin',
            'password' => bcrypt('npinstrutor'),
            'role' => 'instrutor',
        ]);

        Turma::create([
            'nome_turma' => 'Turma A',
            'instrutor_responsavel' => 'Eron Ponce Pereira',
            'nivel' => 'Iniciante',
            'horario' => '14:00',
            'dia_semana' => 'Quarta',
            'quantidade_alunos' => 70,
            'laboratorio' => 'Lab 3',
        ]);

        Turma::create([
            'nome_turma' => 'Turma B',
            'instrutor_responsavel' => 'Matheus Vinícius',
            'nivel' => 'Intermediário',
            'horario' => '16:00',
            'dia_semana' => 'Terça',
            'quantidade_alunos' => 65,
            'laboratorio' => 'Lab 6',
        ]);

        Turma::create([
            'nome_turma' => 'Turma C',
            'instrutor_responsavel' => 'Kawan Shigueo Watanabe',
            'nivel' => 'Iniciante',
            'horario' => '14:00',
            'dia_semana' => 'Quinta',
            'quantidade_alunos' => 70,
            'laboratorio' => 'Outro',
        ]);

        /*
        Monitor::create([
            'nome_monitor' => 'Willson Tamais Neto',
            'email_monitor' => 'will.tamais@edu.unifil.br',
            'matricula' => '223058451',
            'turmas' => [1],
        ]);*/

        Escola::create([
            'nome_escola' => 'UniFil',
            'responsavel_escola' => 'Sérgio Akio Tanaka',
            'contato' => 'sergio.tanaka@unifil.br',
        ]);

        Escola::create([
            'nome_escola' => 'Colégio Londrinense',
            'responsavel_escola' => 'Dr. Eleazar Ferreira',
            'contato' => '4399999999',
        ]);

        Escola::create([
            'nome_escola' => 'Colégio Maxi',
            'responsavel_escola' => 'Professora Jaqueline',
            'contato' => '4399999999',
        ]);

        Escola::create([
            'nome_escola' => 'Positivo',
            'responsavel_escola' => 'Professor Carlos',
            'contato' => 'carlos@positivo.com',
        ]);
/*
        Aluno::create([
            'nome_aluno' => 'Simone Sawasaki Tanaka',
            'escola_id' => 2,
            'turma_id' => 1,
            'email' => 'simone.tanaka@unifil.br',
            'password' => bcrypt('simone123'),
            'role' => 'aluno',
        ]);

        Aluno::create([
            'nome_aluno' => 'Tânia Camila Kochmansky',
            'escola_id' => 1,
            'turma_id' => 1,
            'email' => 'tania.camila@unifil.br',
            'password' => bcrypt('tania123'),
            'role' => 'aluno',
        ]);

        Atividade::create([
            'titulo' => 'Prototipação de site em HTML e CSS', 
            'descricao' => 'Fazer prototipagem no Replit.com',
            'turma_id' => 1,
        ]);

        Nota::create([
            'atividade_id' => 1,
            'aluno_id' => 1,
            'nota' => 100,
        ]);

        Nota::create([
            'atividade_id' => 1,
            'aluno_id' => 2,
            'nota' => 100,
        ]);
*/        
    }
}
