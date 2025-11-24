<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Turma;
use App\Models\Escola;
use App\Models\Aluno;
use App\Models\Atividade;
use App\Models\Nota;
use App\Models\Presenca;
use App\Models\PresencaAluno;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Limpeza Geral
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        PresencaAluno::truncate();
        Nota::truncate();
        Atividade::truncate();
        Presenca::truncate();
        Aluno::truncate();
        Turma::truncate();
        Escola::truncate();
        User::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info('Banco de dados limpo! Iniciando população...');

        // 2. Criar Usuário Admin
        $adminName = 'Instrutor Admin';
        User::factory()->create([
            'name' => $adminName,
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
            'role' => 'instrutor',
        ]);

        // 3. Criar Escola
        $escola = Escola::create([
            'nome_escola' => 'Colégio Estadual Unifil',
            'responsavel_escola' => 'Diretor Geral',
            'contato' => '(43) 3333-0000'
        ]);
        
        // 4. Criar Turmas (Com todos os campos obrigatórios preenchidos)
        $turmaA = Turma::create([
            'nome_turma' => '1º Ano A - Pensamento Computacional',
            'minimo_presenca' => 75,
            'minimo_nota' => 60,
            'instrutor_responsavel' => $adminName,
            'nivel' => 'Ensino Fundamental I',
            'horario' => '08:00:00',
            'dia_semana' => 'Segunda e Quarta',
            'quantidade_alunos' => 30,
            'laboratorio' => 'Lab 01'
        ]);

        $turmaB = Turma::create([
            'nome_turma' => '2º Ano B - Robótica (Problemática)',
            'minimo_presenca' => 75,
            'minimo_nota' => 60,
            'instrutor_responsavel' => $adminName,
            'nivel' => 'Ensino Fundamental II',
            'horario' => '14:00:00',
            'dia_semana' => 'Terça e Quinta',
            'quantidade_alunos' => 25,
            'laboratorio' => 'Lab 02'
        ]);

        // 5. Criar Atividades (CORREÇÃO: Usando 'titulo' e removendo 'peso')
        $atividadesA = [];
        $atividadesB = [];

        // Turma A
        $atividadesA[] = Atividade::create(['titulo' => 'Prova Lógica', 'turma_id' => $turmaA->id]);
        $atividadesA[] = Atividade::create(['titulo' => 'Trabalho Scratch', 'turma_id' => $turmaA->id]);
        $atividadesA[] = Atividade::create(['titulo' => 'Projeto Final', 'turma_id' => $turmaA->id]);

        // Turma B
        $atividadesB[] = Atividade::create(['titulo' => 'Prova Teórica', 'turma_id' => $turmaB->id]);
        $atividadesB[] = Atividade::create(['titulo' => 'Montagem Robô', 'turma_id' => $turmaB->id]);

        // 6. Gerar Presenças
        $datasAulas = [];
        $dataAtual = Carbon::now()->subDays(45);
        for ($i = 0; $i < 20; $i++) {
            if ($dataAtual->isWeekend()) {
                $dataAtual->addDay();
                continue;
            }
            
            $pA = Presenca::create(['data' => $dataAtual->format('Y-m-d'), 'turma_id' => $turmaA->id]);
            $pB = Presenca::create(['data' => $dataAtual->format('Y-m-d'), 'turma_id' => $turmaB->id]);
            
            $datasAulas[] = ['A' => $pA, 'B' => $pB];
            $dataAtual->addDay();
        }

        // --- FUNÇÃO PARA CRIAR ALUNO ---
        $criarAluno = function($nome, $turma, $escola, $arquetipo) use ($datasAulas, $atividadesA, $atividadesB) {
            
            $user = User::factory()->create([
                'name' => $nome,
                'email' => strtolower(str_replace([' ', '(', ')'], ['.', '', ''], $nome)) . rand(1,99) . '@aluno.com',
                'role' => 'aluno'
            ]);

            $aluno = Aluno::create([
                'nome_aluno' => $nome,
                'escola_id' => $escola->id,
                'turma_id' => $turma->id,
                'user_id' => $user->id
            ]);

            $atividades = ($turma->nome_turma == '1º Ano A - Pensamento Computacional') ? $atividadesA : $atividadesB;

            // Notas
            foreach ($atividades as $ativ) {
                $notaValor = 0;
                switch ($arquetipo) {
                    case 'exemplar': $notaValor = rand(90, 100); break;
                    case 'medio':    $notaValor = rand(60, 75); break;
                    case 'reprovado_nota': $notaValor = rand(20, 50); break;
                    case 'reprovado_falta': $notaValor = rand(80, 100); break;
                    case 'inativo': $notaValor = rand(50, 70); break;
                    case 'faltoso_3_plus': $notaValor = rand(60, 80); break;
                }
                Nota::create(['aluno_id' => $aluno->id, 'atividade_id' => $ativ->id, 'nota' => $notaValor]);
            }

            // Presenças
            foreach ($datasAulas as $index => $p) {
                $presencaDia = ($turma->nome_turma == '1º Ano A - Pensamento Computacional') ? $p['A'] : $p['B'];
                
                $presente = 1;
                
                if ($arquetipo == 'exemplar') {
                    $presente = (rand(0, 100) > 5) ? 1 : 0; 
                } elseif ($arquetipo == 'medio') {
                    $presente = (rand(0, 100) > 20) ? 1 : 0; 
                } elseif ($arquetipo == 'reprovado_nota') {
                    $presente = 1; 
                } elseif ($arquetipo == 'reprovado_falta') {
                    $presente = (rand(0, 100) > 60) ? 1 : 0; 
                } elseif ($arquetipo == 'inativo') {
                    $presente = ($index < 5) ? 1 : 0;
                } elseif ($arquetipo == 'faltoso_3_plus') {
                     if ($index >= count($datasAulas) - 4) {
                         $presente = 0; 
                     } else {
                         $presente = 1;
                     }
                }

                PresencaAluno::create([
                    'presenca_id' => $presencaDia->id,
                    'aluno_id' => $aluno->id,
                    'presente' => $presente,
                    'observacao' => ($presente == 0 && rand(0,10) > 8) ? 'Atestado Médico' : null
                ]);
            }

            if ($arquetipo == 'inativo') {
                $aluno->delete();
            }
        };

        // 7. Popular Alunos
        $this->command->info('Criando alunos...');
        $criarAluno('Ana Silva (Aprovada)', $turmaA, $escola, 'exemplar');
        $criarAluno('Beatriz Costa (Aprovada)', $turmaA, $escola, 'exemplar');
        $criarAluno('Carlos Souza (Na Média)', $turmaA, $escola, 'medio');
        $criarAluno('Daniel Oliveira (Faltou Recente)', $turmaA, $escola, 'faltoso_3_plus'); 
        $criarAluno('Eduardo Pereira (Reprovado Nota)', $turmaA, $escola, 'reprovado_nota');
        $criarAluno('Fernanda Lima (Inativa)', $turmaA, $escola, 'inativo');

        $criarAluno('Gabriel Santos (Reprovado Falta)', $turmaB, $escola, 'reprovado_falta');
        $criarAluno('Hugo Alves (Reprovado Nota)', $turmaB, $escola, 'reprovado_nota');
        $criarAluno('Igor Martins (Inativo)', $turmaB, $escola, 'inativo');
        $criarAluno('João Mendes (Faltou Recente)', $turmaB, $escola, 'faltoso_3_plus');
        $criarAluno('Karla Nunes (Na Média)', $turmaB, $escola, 'medio');

        $this->command->info('Tudo pronto! Logue com admin@admin.com / password');
    }
}