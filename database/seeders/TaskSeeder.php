<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tasksByUser = [
            'admin@example.com' => [
                [
                    'title' => 'Revisar dashboard',
                    'description' => 'Conferir dados principais da API de tasks.',
                    'status' => 'pending',
                ],
                [
                    'title' => 'Publicar primeira versao',
                    'description' => 'Preparar commit inicial e enviar para o GitHub.',
                    'status' => 'in_progress',
                ],
                [
                    'title' => 'Validar autenticacao',
                    'description' => 'Testar login, token e rotas protegidas.',
                    'status' => 'completed',
                ],
            ],
            'cubo@example.com' => [
                [
                    'title' => 'Criar tarefa de onboarding',
                    'description' => 'Organizar passos iniciais para novos usuarios.',
                    'status' => 'pending',
                ],
                [
                    'title' => 'Atualizar status das tarefas',
                    'description' => 'Testar atualizacao parcial usando PATCH.',
                    'status' => 'completed',
                ],
                [
                    'title' => 'Revisar filtros da listagem',
                    'description' => 'Filtrar tasks por status e data de criacao.',
                    'status' => 'in_progress',
                ],
            ],
            'user1@example.com' => [
                [
                    'title' => 'Planejar tarefas da semana',
                    'description' => 'Criar lista inicial de prioridades.',
                    'status' => 'pending',
                ],
                [
                    'title' => 'Concluir primeira task',
                    'description' => 'Marcar tarefa como concluida pelo endpoint de update.',
                    'status' => 'completed',
                ],
                [
                    'title' => 'Testar exclusao segura',
                    'description' => 'Garantir que usuarios nao removam tasks de outros usuarios.',
                    'status' => 'pending',
                ],
            ],
        ];

        foreach ($tasksByUser as $email => $tasks) {
            $user = User::where('email', $email)->first();

            if (!$user) {
                continue;
            }

            foreach ($tasks as $task) {
                Task::updateOrCreate(
                    [
                        'title' => $task['title'],
                        'user_id' => $user->id,
                    ],
                    [
                        'description' => $task['description'],
                        'status' => $task['status'],
                    ],
                );
            }
        }
    }
}
