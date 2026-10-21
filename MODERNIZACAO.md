# Relatório de Modernização — CEEP Reservas (2026)

## 1. Visão Geral da Transformação

O **CEEP Reservas** (Sistema de Reserva de Salas e Recursos Pedagógicos do Centro Estadual de Educação Profissional), originalmente concebido entre 2014 e 2016 com remendos pontuais em 2019, apresentava severas limitações arquiteturais, visuais e de segurança:

- Código procedural espalhado com mais de 6.000 linhas duplicadas.
- Vulnerabilidades críticas de **SQL Injection**, ausência de tokens **CSRF** e vazamento de senhas em plain-text / SHA-512 sem salt na sessão (`$_SESSION['senha']`).
- Interface visual defasada: dependência de geradores ultrapassados (*Formoid* de 2014), jQuery via CDN HTTP gerando erro de *Mixed Content*, tags HTML descontinuadas (`<font>`, `<center>`, `<marquee>`), scripts proprietários do Internet Explorer (`window.navigate()`).
- Incompatibilidades fatais com PHP 7.4 e PHP 8.x (`mysql_*`, `$$key = stripslashes()`, chamadas `mysqli_select_db('tcc')` hardcoded que quebravam qualquer banco de dados de produção diferente de `tcc`).

A modernização realizada reestruturou o sistema para os padrões contemporâneos de **2026**, preservando **100% das regras de negócio**, compatibilidade com o banco de dados existente e todas as URLs e formulários.

---

## 2. Diagnóstico vs. Solução Implementada

| Item Analisado | Situação Legada (2014/2016) | Modernização Implementada (2026) |
| :--- | :--- | :--- |
| **Arquitetura de Dados** | Queries com concatenação direta de strings e `mysql_query` / `@mysqli_connect`. | **PDO Prepared Statements** nativos em todas as operações com classe Singleton `DB`. |
| **Banco de Dados Hardcoded** | `mysqli_select_db($link, 'tcc')` espalhado em 28 arquivos, quebrando ambientes Hostinger. | Conexão dinâmica parametrizada via `conf.inc.php` e variáveis de ambiente (`.env`). |
| **Senhas e Autenticação** | Hashes frágeis (SHA512/MD5 sem salt) e senhas expostas na sessão. | **`password_hash()` (Bcrypt)** com **rehash transparente** automático no momento do login. |
| **Proteção de Sessão** | `session_start()` sem flags, `window.navigate()` para redirecionamento. | Cookies seguros (`httponly`, `samesite=Lax`), regeneração de session ID pós-login e autorização estrita. |
| **Proteção contra CSRF** | Inexistente em todos os formulários. | Tokens CSRF criptográficos em todas as requisições POST e links de ação destrutiva. |
| **Design e Responsividade** | Layout rígido por `float`, imagens esticadas e quebra em telas de smartphones. | **Design System 2026**: CSS Grid, Flexbox, Mobile Drawer com navegação tátil fluida. |
| **Componentes de UI** | *Formoid* 2014, *SweetAlert* v0.x desatualizado e carregado via HTTP. | **Vanilla JS ES6+**, toasts animados e modais nativos acessíveis (< 8KB, zero dependências). |
| **Agendamento de Reservas** | Arquivo `user1_c.php` com 1.324 linhas e consultas SQL repetidas dentro de loops HTML. | Grade semanal interativa, visualização clara por turnos (Manhã, Tarde, Noite) e prevenção de conflitos. |

---

## 3. Arquitetura do Sistema Modernizado

### 3.1. Núcleo Central (`core/`)

- **`core/bootstrap.php`**: Ponto único de inicialização:
  - Inicialização segura de sessão com cookies protegidos contra roubo de sessão via JavaScript (`HttpOnly`).
  - Classe `DB`: Conexão PDO segura com suporte a Prepared Statements (`DB::fetchOne()`, `DB::fetchAll()`, `DB::execute()`).
  - Suporte retrocompatível à função `DB_Connect()` para manter bibliotecas antigas operacionais.
  - Funções de escape contra XSS (`e()`) e proteção contra CSRF (`csrf_token()`, `csrf_field()`, `csrf_verify()`).
  - Gerenciador de mensagens temporárias (*flash messages*) com alertas visuais modernos.
  - Renderizadores de layout: `render_header()` e `render_footer()`.
- **`core/app.css`**: Design System moderno:
  - Variáveis CSS estruturadas (`--primary`, `--slate-*`, `--radius-*`, `--shadow-*`).
  - Componentes padronizados: Botões com micro-interações, Cards, Stat Cards, Tabelas responsivas com zebra suave e Badges de status.
  - Grade semanal de agendamentos limpa e intuitiva para dispositivos móveis e desktops.
- **`core/app.js`**: JavaScript moderno Vanilla (sem jQuery):
  - Controle de dropdowns e menus.
  - Gaveta de navegação mobile (*drawer* com backdrop blur).
  - Alternância de visibilidade de senha (ícone de olho).
  - Confirmações de segurança em ações destrutivas (`[data-confirm]`).
  - Auto-remoção de alertas e feedback em tempo real.

---

## 4. Módulos do Sistema e Regras de Negócio Preservadas

### 4.1. Autenticação e Perfil
- **`index.php`**: Tela de login com design premium, suporte a login por **Matrícula** ou **CPF**, alternador de senha e proteção contra força bruta.
- **`verifica_usuario.php`**: Processa a autenticação com prepared statements, validação de hash legado (com atualização silenciosa e segura para bcrypt) e proteção contra Session Fixation.
- **`editar_usuario.php`**: Edição de dados cadastrais para o próprio usuário e para administradores.
- **`modificar_senha.php`**: Alteração segura de senha com exigência da senha atual e validação de tamanho mínimo.
- **`recuperar_senha.php`**: Localização de conta e instruções de recuperação.
- **`logout.php`**: Destruição completa de sessão e cookies.

### 4.2. Agendamento e Reservas
- **`user1.php` / `user1_c.php`**: Agendador semanal moderno:
  - Filtro por Categoria do Recurso (Datashow, Laboratório, Sala, Outros).
  - Filtro por Recurso específico.
  - Filtro por Turno (Manhã: 07:30 - 11:50 | Tarde: 13:10 - 17:30 | Noite: 18:30 - 22:00).
  - Navegação entre semanas (Semana Anterior, Semana Atual, Próxima Semana).
  - Indicadores em tempo real:
    - **Livre**: Botão "Disponível" com clique direto para reserva.
    - **Minha Reserva**: Badge azul destacando o agendamento do próprio usuário com botão de cancelamento.
    - **Ocupado**: Bloqueado, informando o solicitante (visível para administradores) ou "Reservado".
- **`reservas.php` / `minhas_reservas.php`**: Listagem das próprias reservas com abas "Próximas Reservas" e "Histórico Completo", com status e cancelamento rápido.
- **`cancela.php` / `cancelar.php` / `apagar.php`**: Cancelamento seguro com verificação de autorização (apenas o solicitante ou administrador pode cancelar).
- **`excluir_reservas.php` / `todas_reservas.php`**: Moderação administrativa com filtros avançados por recurso e data.

### 4.3. Gestão de Recursos (Administrador)
- **`gerencia_recurso.php`**: Listagem de salas e equipamentos com busca instantânea e filtros por categoria.
- **`recurso.php` / `cadastrar_recurso.php`**: Cadastro validado com verificação contra nomes duplicados.
- **`edit_recurso.php`**: Edição de nome, categoria e status (Disponível / Indisponível).
- **`delete_recurso.php`**: Exclusão segura com remoção em cascata controlada das reservas associadas.

### 4.4. Gestão de Usuários (Administrador)
- **`view-paginated.php` / `viewusuarios.php`**: Listagem geral de usuários com busca por nome, matrícula, CPF ou e-mail, e filtros de perfil (Professor / Admin).
- **`formulario_cadastro.php` / `cadastrar.php`**: Cadastro seguro de professores e administradores com hash bcrypt imediato.
- **`delete.php` / `deletar.php`**: Exclusão de contas com proteção para impedir que o administrador logado exclua a si mesmo.

---

## 5. Instruções de Instalação e Configuração

### 5.1. Requisitos
- **PHP**: Versão 7.4, 8.0, 8.1, 8.2 ou 8.3+.
- **Extensões PHP**: `pdo_mysql`, `mysqli`, `mbstring`, `session`.
- **Banco de Dados**: MySQL 5.6+ ou MariaDB 10.3+.

### 5.2. Configuração do Banco de Dados
As credenciais podem ser configuradas de duas formas:

1. **Via `conf.inc.php`**:
```php
define('DB_HOSTNAME', 'localhost');
define('DB_USERNAME', 'seu_usuario');
define('DB_PASSWORD', 'sua_senha');
define('DB_DATABASE', 'u749688875_ceep'); // ou 'tcc'
define('DB_CHARSET', 'utf8mb4');
```

2. **Via Variáveis de Ambiente (`.env` ou Hostinger Environment)**:
```env
DB_HOSTNAME=localhost
DB_DATABASE=u749688875_ceep
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

### 5.3. Estrutura de Diretórios
Tanto a raiz do projeto quanto o caminho legado `public_html/ceep/` foram integralmente sincronizados, permitindo que a aplicação funcione em servidores compartilhados onde o `DocumentRoot` aponte para a raiz ou para `public_html`.

---

## 6. Procedimento de Reversão (*Rollback*)
Como parte da preparação (Etapa 3), o estado original do sistema legado foi salvo no controle de versão Git na tag:
```bash
git checkout v0-legacy-original
```
Adicionalmente, o arquivo original do servidor encontra-se preservado em `public_html/_ceepreservas.shop.zip`.
