# Banco de Provas SIS/PSC

Plataforma digital para consulta, organização e resolução colaborativa de provas e questões dos processos seletivos **SIS/UEA** e **PSC/UFAM**.

---

## Visão geral

A plataforma permite que estudantes:

- Consultem provas do SIS (UEA) e PSC (UFAM) de **2016 a 2026**
- Acessem questões individualizadas (priorizando 2020–2026)
- Visualizem gabaritos oficiais com referência à fonte
- Acompanhem resoluções oficiais quando disponíveis
- Publiquem e consultem explicações da comunidade
- Diferenciem claramente conteúdo oficial de contribuições da comunidade

---

## Tecnologias

| Camada      | Tecnologia                                  |
|-------------|---------------------------------------------|
| Backend     | PHP 8.2, Laravel 12                         |
| Frontend    | Blade, Bootstrap 5, JavaScript              |
| Banco       | MySQL / MariaDB (produção), SQLite (testes) |
| Build       | Vite 7, Node.js, NPM                        |
| Autenticação| Laravel UI + AuthenticatesUsers             |
| Testes      | PHPUnit 11                                  |

---

## Requisitos

- PHP >= 8.2 com extensões: `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`
- Composer >= 2.x
- Node.js >= 18.x e NPM >= 9.x
- MariaDB >= 10.4 ou MySQL >= 8.0
- XAMPP (Windows) ou equivalente

---

## Instalação

### 1. Clonar o repositório

```bash
git clone https://github.com/SEU_USUARIO/sis-psc-plataforma.git
cd sis-psc-plataforma
```

### 2. Instalar dependências PHP

```bash
composer install
```

### 3. Instalar dependências JavaScript

```bash
npm install
```

### 4. Configurar o ambiente

```bash
cp .env.example .env
php artisan key:generate
```

Edite o arquivo `.env` e configure as credenciais do banco:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sis_psc
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Criar o banco de dados

No MariaDB/MySQL:

```sql
CREATE DATABASE sis_psc CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 6. Executar as migrations

```bash
php artisan migrate
```

### 7. Executar os seeders (dados iniciais)

```bash
php artisan db:seed
```

Isso criará:
- Conta de administrador
- Instituições UEA e UFAM
- 10 disciplinas com assuntos
- Estrutura de provas 2016–2026 (SIS e PSC)

### 8. Criar link de storage

```bash
php artisan storage:link
```

---

## Executando o projeto

### Servidor Laravel (terminal 1)

```bash
php artisan serve
```

Acesse: **http://127.0.0.1:8000**

### Compilação de assets — desenvolvimento (terminal 2)

```bash
npm run dev
```

### Compilação para produção

```bash
npm run build
```

---

## Primeiro acesso como administrador

Após executar os seeders, acesse:

- **URL:** http://127.0.0.1:8000/login
- **E-mail:** `admin@sispsc.local`
- **Senha:** `Admin@2024!`

> **Importante:** altere a senha imediatamente após o primeiro acesso em produção.

O painel administrativo está disponível em: **http://127.0.0.1:8000/admin**

---

## Perfis de acesso

| Perfil        | Permissões                                                                 |
|---------------|----------------------------------------------------------------------------|
| **Visitante** | Consultar provas, questões, gabaritos e respostas da comunidade            |
| **Usuário**   | Tudo do visitante + publicar, editar e excluir suas próprias respostas     |
| **Admin**     | Acesso completo: CRUD de todo o conteúdo, moderação e gestão de usuários   |

---

## Estrutura principal

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/          — painel administrativo
│   │   ├── Api/V1/         — API REST versionada
│   │   ├── Auth/           — autenticação (Laravel UI)
│   │   ├── ProvaController.php
│   │   ├── QuestaoController.php
│   │   ├── RespostaComunidadeController.php
│   │   ├── DenunciaController.php
│   │   └── PerfilController.php
│   ├── Middleware/
│   │   ├── AdminMiddleware.php
│   │   └── CheckUserAtivo.php
│   └── Requests/           — Form Requests com validação
├── Models/                 — Eloquent Models
└── Policies/               — autorização granular

database/
├── migrations/             — 16 migrations do domínio
├── seeders/                — Admin, Instituições, Disciplinas, Provas
└── factories/              — para testes automatizados

resources/views/
├── layouts/
│   ├── app.blade.php       — layout público
│   └── admin.blade.php     — layout do painel admin
├── admin/                  — todas as views do painel
├── provas/                 — listagem e detalhes de provas
├── questoes/               — listagem, questão individual, edição de resposta
└── perfil/                 — perfil do usuário

routes/
├── web.php                 — rotas web
└── api.php                 — API REST /api/v1
```

---

## API REST

Base URL: `http://127.0.0.1:8000/api/v1`

### Endpoints públicos

| Método | Endpoint                              | Descrição                           |
|--------|---------------------------------------|-------------------------------------|
| GET    | `/instituicoes`                       | Lista instituições                  |
| GET    | `/instituicoes/{id}`                  | Detalhes de uma instituição         |
| GET    | `/provas`                             | Lista provas (filtros: instituicao_id, ano, etapa) |
| GET    | `/provas/{id}`                        | Detalhes de uma prova               |
| GET    | `/questoes`                           | Lista questões (filtros: prova_id, disciplina_id, ano, instituicao_id) |
| GET    | `/questoes/{id}`                      | Detalhes de uma questão             |
| GET    | `/questoes/{id}/alternativas`         | Alternativas de uma questão         |
| GET    | `/questoes/{id}/gabarito`             | Gabarito oficial (se disponível)    |
| GET    | `/questoes/{id}/resolucao`            | Resolução oficial (se disponível)   |
| GET    | `/questoes/{id}/respostas`            | Respostas da comunidade             |

### Endpoints autenticados (requer sessão ativa)

| Método | Endpoint                              | Descrição                           |
|--------|---------------------------------------|-------------------------------------|
| POST   | `/questoes/{id}/respostas`            | Publicar resposta                   |
| PUT    | `/respostas/{id}`                     | Editar própria resposta             |
| DELETE | `/respostas/{id}`                     | Excluir própria resposta            |
| POST   | `/respostas/{id}/votar`               | Marcar/desmarcar como útil          |

---

## Testes

```bash
php artisan test
```

Ou com saída detalhada:

```bash
php artisan test --testdox
```

Os testes cobrem:
- Autenticação (login, logout, registro, usuário inativo)
- Permissões (admin, usuário, visitante)
- CRUD de provas no painel admin
- API REST (públicos e autenticados)
- Regras de negócio (edição/exclusão apenas de respostas próprias)

---

## Banco de dados — entidades principais

```
users                   — usuários com role (admin/usuario)
instituicoes            — UEA e UFAM
provas                  — provas 2016–2026
documentos              — PDFs vinculados às provas
fontes_documentos       — rastreabilidade das fontes
disciplinas             — ex: Matemática, Física, Biologia
assuntos                — subdivisões por disciplina
questoes                — questões individualizadas
alternativas            — A, B, C, D, E
gabaritos_oficiais      — resposta + fonte oficial
resolucoes_oficiais     — explicação oficial + fonte
respostas_comunidade    — respostas dos usuários
interacoes_respostas    — votos "útil"
denuncias               — denúncias de conteúdo inadequado
```

---

## Documentos das provas

Os PDFs originais das provas estão organizados no Google Drive:

- **PSC/UFAM:** https://drive.google.com/drive/folders/1BWvhgtRPzvSJ-6kkGuZohAzrEzfNVdQg
- **SIS/UEA:** https://drive.google.com/drive/folders/1zj3y07rGTJli6nVw_EEpRntV6DSXewlW

Para cadastrar um documento na plataforma, acesse o painel admin → Provas → selecione a prova → adicione o documento informando o tipo e a URL externa do Drive.

---

## Segurança

- Proteção CSRF em todos os formulários
- Hash bcrypt nas senhas (rounds configurável)
- Autorização por Policy (users só editam seus próprios recursos)
- Middleware de admin em todas as rotas `/admin`
- Verificação de usuário ativo no login e em cada request
- Variáveis sensíveis exclusivamente no `.env` (nunca no código)
- Validação de todos os inputs no servidor (Form Requests)
- Soft delete para preservar histórico de dados

---

## GitHub

```bash
# Configurar repositório remoto (substitua pela sua URL)
git remote add origin https://github.com/SEU_USUARIO/sis-psc-plataforma.git
git push -u origin main
```

> O arquivo `.env` **não é versionado**. Nunca commite credenciais reais.

---

## Licença

Plataforma educacional sem fins lucrativos.
Os documentos das provas são de propriedade das respectivas instituições (UEA e UFAM).
