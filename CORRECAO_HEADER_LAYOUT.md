# Correção do Layout do Header

## Objetivo
Garantir que o Título do Sistema fique sempre à esquerda e as informações de login/logout fiquem sempre à direita, ocupando toda a largura disponível.

## Alterações Realizadas

### 1. Arquivo `laravel/resources/views/layouts/app.blade.php`
- Removida a restrição de `max-width: 1400px` da classe `.header-content`.
- Definida a largura como `width: 100%`.
- Mantido `justify-content: space-between` para separar os elementos.

### 2. Arquivo `laravel/resources/views/partials/header.blade.php`
- Adicionado estilo inline `width: 100%` na div `.header-content` para garantir o comportamento.
- Adicionado `margin-left: auto` na div `.header-user` para forçar o alinhamento à direita.
- Estruturado o conteúdo em dois blocos principais:
  1. Bloco da Esquerda: Botão de Menu (Mobile) + Logo/Título.
  2. Bloco da Direita: Informações do Usuário + Botão Sair.

## Resultado
O header agora ocupa toda a largura da tela, com o título fixado na extremidade esquerda e as informações do usuário na extremidade direita, independentemente do tamanho da tela.
