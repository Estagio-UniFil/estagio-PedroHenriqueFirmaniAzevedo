<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TurmaController;
use App\Http\Controllers\AlunoController;
use App\Http\Controllers\MonitorController;
use App\Http\Controllers\EscolaController;
use App\Http\Controllers\PresencaController;
use App\Http\Controllers\AtividadeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GraficoController;

Route::get('/', function () {
    return redirect('login');
});

Route::get('/dashboard', function () {
    return redirect('turmas');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/aluno/notas', [AlunoController::class, 'notas_aluno'])->name('aluno.notas');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rotas para Turmas
Route::resource('turmas', TurmaController::class);
Route::post('/turmas/{id}/restore', [TurmaController::class, 'restore'])->name('turmas.restore');

// Rotas para Alunos
Route::resource('alunos', AlunoController::class);
Route::post('/alunos/{id}/restore', [AlunoController::class, 'restore'])->name('alunos.restore');

// Rotas para Monitores
Route::resource('monitores', MonitorController::class);
Route::post('/monitores/{id}/restore', [MonitorController::class, 'restore'])->name('monitores.restore');

// Rotas para Escolas
Route::resource('escolas', EscolaController::class);
Route::post('/escolas/{id}/restore', [EscolaController::class, 'restore'])->name('escolas.restore');

// Rotas para Presencas
Route::resource('presencas', PresencaController::class);
Route::post('/presencas/{id}/restore', [PresencaController::class, 'restore'])->name('presencas.restore');
Route::get('/presencas', [PresencaController::class, 'index'])->name('presencas.index');
Route::get('/presencas/{id}/registrar', [PresencaController::class, 'registrarPresenca'])->name('presencas.register');
Route::post('/presencas/{id}/salvar', [PresencaController::class, 'salvarPresenca'])->name('presencas.salvar');
Route::get('/presencas/{id}/visualizar', [PresencaController::class, 'visualizarPresenca'])->name('presencas.visualizar');
Route::get('presencas/{id}/exportar', [PresencaController::class, 'exportar'])->name('presencas.exportar');

// Rotas para Atividades
Route::resource('atividades', AtividadeController::class);
Route::post('/atividades/{id}/restore', [AtividadeController::class, 'restore'])->name('atividades.restore');
Route::get('atividades/{id}/notas', [AtividadeController::class, 'atribuirNotas'])->name('atividades.notas');
Route::post('atividades/{id}/notas', [AtividadeController::class, 'salvarNotas'])->name('atividades.notas.salvar');

Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
Route::post('/users', [UserController::class, 'store'])->name('users.store');
Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
Route::get('/users', [UserController::class, 'index'])->name('users.index');

// Importação e Exportação
Route::middleware('auth')->get('/import-export', function () {
    return view('estagio.importarExportar.import_export');
})->name('import.export');

// Rotas para Gráficos
Route::middleware(['auth'])->group(function () {
    Route::get('/graficos', [App\Http\Controllers\GraficoController::class, 'index'])->name('graficos.index');
});

require __DIR__ . '/auth.php';
