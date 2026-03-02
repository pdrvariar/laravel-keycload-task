# Correção da Identificação da Role de Administrador

## Problema Identificado
O usuário `administrador@example.com` não estava sendo identificado corretamente como administrador. O sistema exibia o badge "Usuário" em vez de "Administrador" e não mostrava o menu de administração.

## Causa Raiz
O sistema estava procurando as roles do usuário apenas dentro de `resource_access` (roles específicas do cliente Keycloak). No entanto, a role `admin` estava configurada como uma role de realm (`realm_access`) ou uma role global, e não estava sendo capturada pela lógica anterior.

## Solução Aplicada
Modificamos a lógica de extração de roles em quatro pontos críticos do sistema para considerar tanto as roles do cliente (`resource_access`) quanto as roles do realm (`realm_access`).

### Arquivos Modificados:

1.  **`laravel/app/Http/Controllers/AuthController.php`**
    *   No método `login`, agora combinamos as roles de `resource_access` e `realm_access` antes de verificar se o usuário é admin.
    *   Isso garante que o redirecionamento pós-login leve o administrador para o dashboard correto.

2.  **`laravel/resources/views/partials/header.blade.php`**
    *   Atualizamos a lógica de exibição do badge para verificar roles em ambas as fontes.
    *   Agora, se a role `admin` estiver presente em qualquer lugar, o badge "Administrador" será exibido.

3.  **`laravel/resources/views/partials/sidebar.blade.php`**
    *   Atualizamos a lógica de exibição do menu lateral.
    *   O menu "ADMINISTRAÇÃO" agora aparecerá corretamente para usuários com a role `admin`, independentemente de onde ela esteja definida no token.

4.  **`laravel/app/Http/Controllers/Api/TaskController.php`**
    *   Atualizamos o método `getUserAndRoles` para verificar roles em `realm_access` também.
    *   Isso garante que as chamadas de API (como listagem de tarefas) reconheçam corretamente o administrador e permitam ver todas as tarefas.

## Como Verificar
1.  Faça logout do sistema (se estiver logado).
2.  Faça login com o usuário `administrador@example.com`.
3.  Verifique:
    *   Se você é redirecionado para `/admin/dashboard` (ou se tem acesso a essa rota).
    *   Se o badge no canto superior direito exibe "Administrador" com ícone de escudo.
    *   Se o menu lateral exibe a seção "ADMINISTRAÇÃO".
    *   Se a listagem de tarefas mostra todas as tarefas (incluindo as de outros usuários).

## Script de Verificação
Foi criado um script `verificar-role-admin.ps1` na raiz do projeto que simula a lógica de verificação com um token de exemplo, confirmando que a nova abordagem detecta corretamente a role `admin`.
