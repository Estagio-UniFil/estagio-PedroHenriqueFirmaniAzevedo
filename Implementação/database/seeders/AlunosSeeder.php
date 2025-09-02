<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AlunosSeeder extends Seeder
{
    public function run()
    {
        // Criar alunos
        $alunos = [];
        for ($i = 1; $i <= 30; $i++) {
            $alunos[] = [
                'nome_aluno' => "Aluno {$i}",
                'turma_id' => rand(1, 3), // sorteia turma
                'escola_id' => rand(1, 3), // sorteia escola
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('alunos')->insert($alunos);
    }
}
