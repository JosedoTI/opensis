# DESENVOLVIMENTO DE UMA PLATAFORMA WEB — BANCO DE PROVAS SIS/PSC

Atue como um desenvolvedor Full Stack sênior, com experiência em Laravel, PHP, MySQL/MariaDB, Bootstrap, JavaScript, modelagem de banco de dados, APIs REST e arquitetura de aplicações web.

Sua responsabilidade é desenvolver uma plataforma web completa para consulta, organização e resolução colaborativa de provas e questões dos processos seletivos **SIS/UEA e PSC/UFAM**.

O desenvolvimento deverá utilizar o projeto Laravel já existente no computador. Não comece um projeto do zero e não reinstale tecnologias que já estão configuradas.

---

# 1. OBJETIVO DO PROJETO

Desenvolver uma plataforma digital voltada exclusivamente aos processos seletivos:

- **SIS — Sistema de Ingresso Seriado da Universidade do Estado do Amazonas (UEA).**
- **PSC — Processo Seletivo Contínuo da Universidade Federal do Amazonas (UFAM).**

A plataforma permitirá que estudantes consultem provas anteriores, acessem questões individualizadas, visualizem gabaritos oficiais, acompanhem resoluções e compartilhem explicações com outros estudantes.

A experiência deverá combinar referências funcionais de plataformas como Brainly e Estuda+, sem copiar identidade visual, código ou estrutura proprietária dessas plataformas.

O sistema deverá oferecer uma experiência organizada, responsiva, intuitiva e adequada ao ambiente educacional.

# 2. PERÍODO DE COBERTURA

A plataforma deverá abranger provas dos anos **2016 até 2026**, inclusive.

A organização do conteúdo deverá respeitar esta prioridade:

- **2020–2026:** prioridade para cadastro individualizado das questões, alternativas, gabaritos e demais informações disponíveis.
- **2016–2019:** as provas deverão estar disponíveis para consulta em seus documentos originais, mesmo que suas questões ainda não estejam individualmente cadastradas.

Não invente questões, alternativas, respostas, gabaritos, anos, etapas ou informações que não estejam presentes nas fontes.

A ausência de uma questão individualizada não deverá impedir a consulta ao PDF original da prova.

# 3. PERFIS DE ACESSO

O sistema deverá trabalhar com três categorias de acesso.

## 3.1. Visitante

O visitante poderá acessar as funcionalidades públicas sem precisar criar uma conta:

- Consultar provas do SIS e PSC.
- Filtrar provas por instituição, ano e etapa.
- Consultar questões cadastradas.
- Visualizar alternativas.
- Consultar gabaritos oficiais disponíveis.
- Consultar resoluções oficiais disponíveis.
- Visualizar respostas e explicações publicadas pela comunidade.
- Acessar os documentos PDF originais.

O visitante não poderá publicar respostas, editar conteúdos ou realizar ações administrativas.

## 3.2. Usuário cadastrado

O usuário autenticado terá todas as permissões de visitante e poderá participar da comunidade:

- Publicar resoluções e explicações das questões.
- Editar suas próprias respostas.
- Excluir suas próprias respostas.
- Marcar respostas como úteis.
- Denunciar respostas inadequadas ou incorretas.
- Consultar seu histórico de publicações.
- Visualizar as interações realizadas em suas respostas.

As permissões deverão ser aplicadas no backend, e não somente escondidas na interface.

Um usuário não poderá editar ou excluir respostas publicadas por outra pessoa.

## 3.3. Administrador

O administrador deverá possuir acesso completo ao painel administrativo.

Permissões:

- Cadastrar, editar, consultar e excluir instituições.
- Cadastrar, editar, consultar e excluir provas.
- Cadastrar, editar, consultar e excluir questões.
- Gerenciar alternativas.
- Cadastrar e atualizar gabaritos oficiais.
- Cadastrar resoluções oficiais.
- Gerenciar documentos e fontes.
- Consultar e administrar usuários.
- Moderar respostas da comunidade.
- Consultar denúncias.
- Remover ou ocultar conteúdos inadequados.
- Acompanhar indicadores do sistema.

As operações administrativas deverão ser protegidas por autenticação, autorização e validações no servidor.

# 4. DIFERENCIAÇÃO DOS TIPOS DE RESPOSTA

Este requisito é obrigatório e deve ser respeitado em toda a aplicação.

A plataforma deverá separar claramente três categorias:

### A. Gabarito oficial

Resposta publicada ou confirmada pela instituição responsável pelo processo seletivo, com referência à sua fonte oficial.

### B. Resolução oficial

Explicação ou resolução disponibilizada oficialmente pela instituição, quando existir.

### C. Respostas da comunidade

Explicações, comentários e resoluções elaboradas pelos usuários cadastrados.

As respostas da comunidade nunca deverão aparecer como se fossem gabaritos oficiais.

Cada categoria deverá possuir identificação visual e estrutura própria no banco de dados e na interface.

Quando não houver gabarito oficial ou resolução oficial disponível, o sistema deverá informar essa indisponibilidade, sem inventar conteúdo.

# 5. FUNCIONALIDADES PRINCIPAIS

## 5.1. Página inicial

Criar uma página inicial organizada e responsiva, com:

- Apresentação da plataforma.
- Acesso rápido ao SIS/UEA.
- Acesso rápido ao PSC/UFAM.
- Campo de pesquisa.
- Acesso às provas recentes.
- Acesso às questões cadastradas.
- Indicadores gerais de conteúdo disponível, quando houver dados reais.
- Área de acesso e cadastro.

## 5.2. Área de provas

Permitir a consulta de provas com filtros por:

- Instituição.
- Ano.
- Etapa ou ciclo, conforme a nomenclatura oficial.
- Modalidade ou tipo de prova, quando aplicável.

Cada prova deverá apresentar informações como:

- Instituição responsável.
- Ano.
- Etapa ou ciclo.
- Nome da prova.
- Documento original.
- Quantidade de questões cadastradas, quando conhecida.
- Disponibilidade de gabarito oficial, quando verificada.

Os documentos originais deverão poder ser visualizados ou acessados pela plataforma.

## 5.3. Área de questões

Criar uma área para consulta individualizada das questões.

Filtros desejados:

- Instituição.
- Ano.
- Etapa ou ciclo.
- Disciplina.
- Assunto.
- Número da questão.

Cada questão poderá apresentar:

- Enunciado.
- Imagem ou conteúdo complementar, quando houver.
- Alternativas.
- Identificação da prova de origem.
- Número da questão.
- Página do documento original, quando identificável.
- Gabarito oficial, quando disponível.
- Resolução oficial, quando disponível.
- Respostas da comunidade.

Não cadastrar questões incompletas como se estivessem completas. Preservar a referência ao documento e à página de origem.

## 5.4. Área de resolução das questões

A página de cada questão deverá organizar o conteúdo nesta sequência:

1. Enunciado da questão.
2. Alternativas.
3. Gabarito oficial, quando disponível.
4. Resolução oficial, quando disponível.
5. Respostas e explicações da comunidade.
6. Campo para publicação de uma nova resposta, exclusivo para usuários autenticados.

Cada resposta da comunidade deverá identificar seu autor e data de publicação.

Disponibilizar ações de utilidade e denúncia.

## 5.5. Perfil do usuário

Criar uma área para que o usuário consulte:

- Dados básicos da conta.
- Respostas publicadas.
- Histórico de atividades relevantes.
- Respostas recebidas ou marcadas como úteis, quando aplicável.

O usuário deverá poder atualizar seus dados permitidos e gerenciar suas próprias publicações.

## 5.6. Painel administrativo

Criar uma área administrativa separada da experiência pública.

O painel deverá conter:

- Dashboard com indicadores reais.
- Gerenciamento de instituições.
- Gerenciamento de provas.
- Gerenciamento de questões.
- Gerenciamento de disciplinas e assuntos.
- Gerenciamento de alternativas.
- Gerenciamento de gabaritos.
- Gerenciamento de resoluções oficiais.
- Gerenciamento de documentos e fontes.
- Gerenciamento de usuários.
- Moderação de respostas.
- Gerenciamento de denúncias.

Implementar operações CRUD completas, com validações, mensagens de sucesso e tratamento de erros.

# 6. FONTES DOS DOCUMENTOS

Os documentos das provas estão organizados nas seguintes pastas do Google Drive:

**PSC/UFAM:**
https://drive.google.com/drive/folders/1BWvhgtRPzvSJ-6kkGuZohAzrEzfNVdQg

**SIS/UEA:**
https://drive.google.com/drive/folders/1zj3y07rGTJli6nVw_EEpRntV6DSXewlW

Essas pastas contêm documentos PDF das provas. Não considerar que elas também possuem os gabaritos oficiais.

## 6.1. Tratamento dos documentos

A primeira atividade envolvendo esses arquivos deverá ser realizar um inventário dos documentos disponíveis.

Para cada arquivo, identificar, quando possível:

- Instituição.
- Ano.
- Etapa ou ciclo.
- Nome do documento.
- Tipo de documento.
- Quantidade de páginas.
- Disponibilidade de texto extraível.
- Possíveis duplicidades.

Preservar os documentos originais.

Não alterar os PDFs originais nem eliminar arquivos durante o processo de organização.

## 6.2. Extração de questões

Priorizar a extração e o cadastro individualizado das questões de 2020 até 2026.

Durante a extração:

- Preservar o enunciado.
- Preservar as alternativas.
- Preservar imagens, tabelas, fórmulas e outros elementos.
- Registrar o número da questão.
- Registrar a prova de origem.
- Registrar a página do documento, quando identificável.
- Não completar informações ausentes por suposição.

Se o PDF não permitir extração adequada, informar o problema e solicitar uma forma apropriada de revisão.

Não afirmar que documentos foram analisados quando não houve acesso real a eles.

## 6.3. Gabaritos oficiais

Os gabaritos deverão ser pesquisados separadamente em fontes oficiais da UEA e da UFAM.

Para cada gabarito localizado, registrar sua fonte e sua relação com a prova correspondente.

Não utilizar respostas da comunidade como substitutas de gabaritos oficiais.

Se não encontrar uma fonte confiável, manter o gabarito como indisponível.

# 7. BANCO DE DADOS

Utilizar MySQL/MariaDB com estrutura relacional normalizada.

Antes de desenvolver as migrations definitivas, apresentar a modelagem do banco de dados e o respectivo MER/DER.

A estrutura deverá contemplar, no mínimo, as seguintes entidades:

- users
- instituicoes
- provas
- documentos
- fontes_documentos
- disciplinas
- assuntos
- questoes
- alternativas
- gabaritos_oficiais
- resolucoes_oficiais
- respostas_comunidade
- interacoes_respostas
- denuncias

A modelagem deverá considerar:

- Chaves primárias.
- Chaves estrangeiras.
- Relacionamentos e cardinalidades.
- Restrições de integridade.
- Índices para consultas frequentes.
- Campos de criação e atualização.
- Exclusão lógica quando necessária.
- Histórico e rastreabilidade de conteúdos relevantes.

A estrutura deverá evitar duplicidade desnecessária e permitir a expansão futura da plataforma.

O gabarito oficial, a resolução oficial e as respostas da comunidade deverão possuir armazenamento e relacionamentos próprios.

Não executar migrations definitivas antes de apresentar e revisar o modelo de dados.

# 8. TECNOLOGIAS OBRIGATÓRIAS

Utilizar exclusivamente a seguinte stack:

**Frontend:**
- HTML5.
- CSS3.
- JavaScript.
- Bootstrap.
- Blade.

**Backend:**
- PHP.
- Laravel.

**Banco de dados:**
- MySQL/MariaDB.

**Ferramentas de desenvolvimento:**
- Composer.
- Node.js.
- NPM.
- Vite.
- Git e GitHub.

Não utilizar React, Vue, Angular ou outros frameworks frontend que não estejam previstos neste projeto.

A interface deverá utilizar Bootstrap, Blade e JavaScript, mantendo a estrutura simples de manter e evoluir.

# 9. BACKEND E ORGANIZAÇÃO LARAVEL

Utilizar os recursos adequados do Laravel:

- Models.
- Migrations.
- Seeders.
- Factories.
- Controllers.
- Form Requests.
- Middleware.
- Policies e Gates.
- Services, quando houver necessidade real.
- Rotas web.
- Rotas de API.
- Validação de dados.
- Tratamento de exceções.
- Autenticação e autorização.

Manter a aplicação organizada, seguindo boas práticas de responsabilidade e separação de camadas.

Evitar colocar toda a lógica de negócio diretamente nos Controllers.

# 10. API REST

Desenvolver uma API REST versionada utilizando o prefixo:

`/api/v1`

A API deverá disponibilizar, conforme as permissões:

- Consulta de instituições.
- Consulta de provas.
- Consulta de questões.
- Consulta de alternativas.
- Consulta de disciplinas e assuntos.
- Consulta de gabaritos oficiais.
- Consulta de resoluções oficiais.
- Consulta de respostas da comunidade.
- Publicação e gerenciamento de respostas autorizadas.
- Consulta de documentos públicos.

As rotas administrativas deverão exigir autenticação e autorização adequadas.

A API pública deverá retornar somente conteúdos que possam ser visualizados publicamente.

Utilizar respostas HTTP apropriadas, validação, paginação e mensagens de erro padronizadas.

Documentar os endpoints, parâmetros, respostas e requisitos de autenticação.

# 11. SEGURANÇA E QUALIDADE

Implementar:

- Proteção contra CSRF.
- Proteção contra XSS.
- Validação de entradas no servidor.
- Controle de acesso por perfil.
- Proteção de rotas.
- Hash seguro de senhas.
- Tratamento de uploads.
- Limitação de requisições quando necessário.
- Proteção contra acesso indevido a registros.
- Mensagens de erro sem exposição de informações sensíveis.

Não armazenar senhas, tokens ou credenciais diretamente no código.

Utilizar variáveis de ambiente.

# 12. INTERFACE E EXPERIÊNCIA DO USUÁRIO

Criar uma interface visual própria, moderna, organizada e responsiva.

A identidade visual deverá ser apropriada para uma plataforma educacional.

Priorizar:

- Navegação simples.
- Hierarquia visual clara.
- Boa legibilidade.
- Organização das informações.
- Responsividade para computadores, tablets e celulares.
- Identificação visual das instituições.
- Identificação clara de gabaritos oficiais e respostas da comunidade.
- Formulários simples e intuitivos.
- Feedback visual para operações realizadas.
- Estados vazios e mensagens quando não houver conteúdo disponível.

Não copiar a identidade visual do Brainly ou do Estuda+.

# 13. TESTES E DOCUMENTAÇÃO

Criar testes para os principais recursos:

- Autenticação.
- Permissões de usuários.
- Operações administrativas.
- Cadastro e consulta de provas.
- Cadastro e consulta de questões.
- Gabaritos oficiais.
- Respostas da comunidade.
- Edição e exclusão de respostas próprias.
- Proteção das rotas da API.

Criar um README contendo:

- Descrição do projeto.
- Requisitos.
- Tecnologias utilizadas.
- Instruções de instalação.
- Configuração do banco de dados.
- Execução do Laravel.
- Execução do Vite.
- Comandos de migrations e seeders.
- Estrutura principal do projeto.
- Rotas da API.
- Instruções para criação do primeiro administrador.

# 14. GITHUB

O projeto deverá ser versionado em um repositório privado.

Nome sugerido:

`sis-psc-plataforma`

Configurações:

- Repositório privado.
- Branch principal `main`.
- Remote `origin`.
- Commits descritivos.
- README atualizado.
- `.gitignore` adequado.

Não enviar para o GitHub:

- Arquivo `.env`.
- Senhas.
- Tokens.
- Credenciais.
- Chaves privadas.
- Dados pessoais de usuários.

Criar ou manter o `.env.example` sem credenciais reais.

Antes de criar o repositório, verificar se já existe um repositório remoto configurado. Não sobrescrever repositórios existentes.

Utilizar a autenticação já disponível no GitHub, quando possível. Não inventar sucesso em operações que não foram realizadas.

# 15. AMBIENTE DE DESENVOLVIMENTO EXISTENTE

O projeto já está iniciado no seguinte diretório:

`C:\OpenSIS`

Ambiente atualmente identificado:

- Laravel Framework 12.71.0.
- Laravel UI 4.6.3.
- Autenticação e estrutura Bootstrap geradas.
- NPM instalado e dependências instaladas.
- Node.js e Composer disponíveis.
- Chave de aplicação gerada.
- Migrations iniciais já executadas anteriormente utilizando SQLite.
- Vite 7.3.7 instalado.

Os comandos abaixo já funcionaram anteriormente:

`npm run dev`

`php artisan serve`

Endereços locais:

- Laravel: http://127.0.0.1:8000
- Vite: http://localhost:5173

Os dois processos precisam permanecer ativos durante o desenvolvimento local.

## 15.1. Banco de dados atual

O XAMPP está instalado em:

`C:\xampp`

O MariaDB utilizado é a versão 10.4.32 e está ativo na porta 3306.

O serviço Windows `MySQL80` está parado. Não tentar iniciá-lo, pois o MariaDB do XAMPP já está utilizando a porta 3306.

O banco abaixo já foi criado:

` sis_psc `

Configuração atual do `.env`:

`DB_CONNECTION=mysql`

`DB_HOST=127.0.0.1`

`DB_PORT=3306`

`DB_DATABASE=sis_psc`

`DB_USERNAME=root`

`DB_PASSWORD=`

O acesso root local foi testado sem senha e o banco `sis_psc` foi criado com sucesso, utilizando `utf8mb4` e `utf8mb4_unicode_ci`.

**Atenção:** a configuração foi salva no `.env`, mas a conexão do Laravel ainda precisa ser testada após essa alteração.

# 16. REGRAS IMPORTANTES PARA O DESENVOLVIMENTO

Estas regras deverão ser seguidas durante todo o projeto:

1. Antes de alterar qualquer arquivo, verificar seu conteúdo e sua função.
2. Não reinstalar Laravel.
3. Não executar novamente `composer create-project`.
4. Não executar novamente os comandos de instalação do Laravel UI ou de geração da autenticação.
5. Não sobrescrever arquivos existentes sem necessidade.
6. Não utilizar `migrate:fresh`, `db:wipe` ou comandos que possam apagar dados.
7. Não executar migrations definitivas antes de revisar o MER/DER.
8. Não inventar informações de provas, questões, gabaritos ou documentos.
9. Não adicionar tecnologias diferentes da stack definida.
10. Evitar dependências desnecessárias.
11. Manter o projeto organizado e funcional após cada alteração.
12. Não criar funcionalidades que não estejam relacionadas aos objetivos da plataforma.
13. Não declarar uma tarefa concluída sem verificar seu funcionamento.
14. Informar erros, limitações e dependências que precisem de intervenção do usuário.
15. Priorizar soluções simples, seguras e compatíveis com o ambiente existente.

# 17. ORDEM DE EXECUÇÃO DO PROJETO

Executar o desenvolvimento por etapas, nesta ordem:

**ETAPA 1 — Auditoria do projeto**

Inspecionar os arquivos existentes, estrutura Laravel, dependências, configurações, rotas, migrations e estado do Git.

Verificar a conexão com o MariaDB sem executar migrations que alterem o banco.

**ETAPA 2 — Modelagem do banco**

Planejar as entidades, atributos, relacionamentos, chaves e restrições.

Apresentar o MER/DER para revisão antes de criar as migrations definitivas.

**ETAPA 3 — Estrutura do banco**

Após a revisão do modelo, desenvolver migrations, Models, relacionamentos, Seeders e Factories.

**ETAPA 4 — Autenticação e permissões**

Revisar a autenticação existente e implementar os perfis de acesso, autorização e políticas.

**ETAPA 5 — Backend**

Desenvolver as funcionalidades de instituições, provas, documentos, disciplinas, assuntos, questões, alternativas, gabaritos e respostas da comunidade.

**ETAPA 6 — API REST**

Implementar e testar os endpoints versionados.

**ETAPA 7 — Interface**

Desenvolver as páginas públicas, área de questões, área de provas, perfil do usuário e painel administrativo.

**ETAPA 8 — Organização dos documentos**

Inventariar os PDFs dos Drives, organizar as provas e iniciar a extração das questões conforme as prioridades estabelecidas.

**ETAPA 9 — Testes e ajustes**

Executar os testes, corrigir problemas, verificar permissões, responsividade e consistência dos dados.

**ETAPA 10 — GitHub e documentação**

Atualizar o README, revisar o `.gitignore`, versionar o projeto e preparar o repositório privado.

---

# 18. PRIMEIRA AÇÃO

Não comece criando arquivos ou alterando a estrutura do projeto imediatamente.

Primeiro, faça uma auditoria do projeto existente em `C:\OpenSIS`.

Verifique:

- Estrutura atual de diretórios.
- Arquivos principais do Laravel.
- Configuração de banco de dados.
- Dependências instaladas.
- Rotas existentes.
- Migrations existentes.
- Estado atual do Git.
- Conexão efetiva com o MariaDB `sis_psc`.

Não execute comandos destrutivos.

Depois da auditoria, apresente um relatório objetivo contendo:

1. O que já está pronto.
2. O que ainda precisa ser feito.
3. Problemas encontrados.
4. Possíveis conflitos com a estrutura existente.
5. Próxima etapa recomendada.

Trabalhe de maneira incremental. Explique os comandos e alterações em português, com orientações adequadas para quem está aprendendo desenvolvimento web.

**Prioridade absoluta: preservar o que já existe, desenvolver uma plataforma funcional e garantir que os dados oficiais sejam tratados com precisão e separados das contribuições da comunidade.**