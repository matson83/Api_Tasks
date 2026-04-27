# API Tasks

API REST para cadastro de usuários, autenticação com token e gerenciamento de tasks por usuário autenticado.

## Tecnologias utilizadas

- Docker
- Laravel Sail
- PHP 8.3+
- Laravel 13
- Laravel Sanctum
- MySQL 8
- Composer
- Bruno para testar os endpoints

## Pré-requisitos

Antes de iniciar, tenha instalado:

- Docker
- Docker Compose
- Git

Como o projeto usa Laravel Sail, não é necessário ter PHP, Composer, Node.js ou MySQL instalados diretamente na máquina. Eles rodam dentro dos containers.

## Instalação

Clone o projeto:

```bash
git clone git@github.com:matson83/Api_Tasks.git
cd Api_Tasks
```

Crie o arquivo de ambiente:

```bash
cp .env.example .env
```

Configure o banco no `.env` para usar o serviço `mysql` do Docker e se conecte ao banco:

```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=api_tasks
DB_USERNAME=sail
DB_PASSWORD=password
```

Suba os containers do Sail:

```bash
./vendor/bin/sail up -d --build
```

Gere a chave da aplicação:

```bash
./vendor/bin/sail artisan key:generate
```

## Migrations

Rode as migrations:

```bash
./vendor/bin/sail artisan migrate
```

## Seeders

Rode os seeders:

```bash
./vendor/bin/sail artisan db:seed
```


Usuários criados pelo seeder:

| Nome | Email | Senha |
| --- | --- | --- |
| Admin Matson | admin@example.com | password |
| User Cubo | cubo@example.com | password |
| User 1 | user1@example.com | password |

O `TaskSeeder` cria pelo menos 3 tasks para cada usuário.

## Rodando a API

 A URL será:

```txt
http://localhost:80
```

Para parar os containers:

```bash
./vendor/bin/sail down
```

## Autenticação

Faça login:

```http
POST /api/login
```

Body:

```json
{
  "email": "admin@example.com",
  "password": "password"
}
```

A resposta retorna um `access_token`. Use esse token nas rotas protegidas:

```http
Authorization: Bearer SEU_ACCESS_TOKEN
```

## Rotas principais

Rotas públicas:

```http
POST /api/register
POST /api/login
```

Rotas protegidas:

```http
GET    /api/prd/profile/user
POST   /api/prd/profile/logout
GET    /api/prd/tasks
POST   /api/prd/tasks
GET    /api/prd/tasks/{id}
PATCH  /api/prd/tasks/{id}
DELETE /api/prd/tasks/{id}
```

Filtros da listagem de tasks:

```http
GET /api/prd/tasks?status=pending
GET /api/prd/tasks?created_at=2026-04-27
GET /api/prd/tasks?status=completed&created_at=2026-04-27
```

Valores aceitos para `status`:

```txt
pending
completed
in_progress
```

## Collection do Bruno

A collection do Bruno pode ser baixada pelo Google Drive:

https://drive.google.com/file/d/1tSrE80U0c3v1HaFBxCBjY1-U4aZbvIlW/view?usp=sharing

Para importar no Bruno:

1. Abra o Bruno.
2. Baixe e extraia a collection do link acima.
3. Clique em `Open Collection`.
4. Selecione a pasta extraída da collection.
5. Selecione o environment `Local`.
6. Execute o request `Auth / Login`.

O request de login salva automaticamente o token na variável de environment:

```txt
access_token
```

Depois disso, os endpoints protegidos já usam:

```http
Authorization: Bearer {{access_token}}
```

Se estiver usando `APP_PORT=8000`, ajuste a variável `base_url` do environment `Local` para:

```txt
http://localhost:8000
```

## Exemplo de criação de task

```http
POST /api/prd/tasks
```

Body:

```json
{
  "title": "Criacao de Task Bruno",
  "description": "Ola",
  "status": "pending"
}
```

O `user_id` não deve ser enviado no body. Ele é definido automaticamente pelo usuário autenticado.

