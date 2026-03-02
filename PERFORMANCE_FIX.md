# Otimização de Performance Docker + Laravel

Você relatou lentidão no ambiente de desenvolvimento. Isso é comum ao usar Docker Desktop no Windows devido à forma como o sistema de arquivos é compartilhado entre o Windows e o Linux (WSL2/Hyper-V).

## O que foi feito

1.  **Volumes Delegados (`:delegated`)**:
    *   No `docker-compose.yml`, alteramos a montagem dos volumes do Laravel (`./laravel:/var/www`) para usar a flag `:delegated`.
    *   Isso diz ao Docker que o container tem autoridade sobre os arquivos e pode atrasar a sincronização de volta para o Windows, acelerando drasticamente a leitura/escrita dentro do container.

2.  **Otimização do PHP (`php/local.ini`)**:
    *   **Realpath Cache**: Aumentado para `4096K` (padrão é muito baixo). O Laravel acessa muitos arquivos; isso evita que o PHP pergunte ao disco "onde está esse arquivo?" repetidamente.
    *   **OPcache**: Ajustado para manter mais scripts pré-compilados em memória (`opcache.memory_consumption=256`), mas ainda validando alterações (`opcache.revalidate_freq=0`) para que você veja suas mudanças de código instantaneamente.

3.  **Vendor Fora do Volume (Avançado)**:
    *   Movemos a pasta `vendor` para um **volume nomeado do Docker** (`vendor_data`).
    *   Isso significa que os milhares de arquivos de dependências do Laravel agora vivem **exclusivamente dentro do sistema de arquivos do Linux (Docker)** e não são sincronizados com o Windows.
    *   **Benefício**: Aceleração massiva no carregamento da aplicação, pois o Windows não precisa indexar/sincronizar esses arquivos.
    *   **Efeito Colateral**: A pasta `vendor` no seu Windows (dentro de `laravel/vendor`) ficará vazia ou desatualizada. **Isso é normal.** A IDE pode reclamar de classes não encontradas.
    *   **Solução para IDE**: Se sua IDE (PHPStorm/VSCode) precisar indexar as classes, você pode rodar `composer install` no Windows apenas para gerar os arquivos locais para a IDE, mas o container usará a versão interna dele.

## Como aplicar as mudanças

Para que essas alterações surtam efeito, você precisa recriar os containers e volumes:

```bash
# Derruba os containers e remove os volumes antigos (para recriar o vendor_data limpo)
docker-compose down -v

# Reconstrói a imagem (importante pois mudamos o Dockerfile) e sobe os containers
docker-compose up -d --build
```

## Dicas Adicionais

1.  **WSL 2**: Certifique-se de que o Docker Desktop está usando o backend WSL 2 e não o Hyper-V legado.
2.  **Mover Projeto para o WSL**: A solução definitiva para performance no Windows é mover a pasta do projeto (`C:/MyDev/...`) para dentro do sistema de arquivos do Linux (ex: `\\wsl$\Ubuntu\home\seu-usuario\projetos`). O Docker no Windows acessa arquivos no sistema de arquivos do Linux com velocidade nativa, enquanto acessar arquivos no `C:` é muito mais lento.
