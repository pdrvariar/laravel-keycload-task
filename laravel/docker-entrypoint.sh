#!/bin/bash

echo "================================================"
echo "Iniciando Laravel Task Controller"
echo "================================================"
echo ""

# Aguardar o MySQL ficar pronto
echo "Aguardando MySQL ficar disponível..."
until mysql -h"${DB_HOST:-mysql}" -u"${DB_USERNAME:-laravel}" -p"${DB_PASSWORD:-secret}" -e "SELECT 1" &> /dev/null; do
  printf '.'
  sleep 1
done
echo ""
echo "✓ MySQL conectado com sucesso!"
echo ""

# Criar arquivo .env se não existir
if [ ! -f .env ]; then
    echo "Criando arquivo .env..."
    cp .env.example .env
    php artisan key:generate
fi

# Instalar dependências
echo "Instalando dependências PHP..."
composer install --no-interaction --prefer-dist --no-dev --optimize-autoloader 2>&1 | tail -5

echo ""
echo "Executando migrations..."
php artisan migrate --force

echo ""
echo "Limpando cache..."
php artisan cache:clear
php artisan config:cache
php artisan view:cache

echo ""
echo "================================================"
echo "✓ Laravel iniciado com sucesso!"
echo "================================================"
echo ""

# Iniciar PHP-FPM ou o servidor especificado
exec "$@"

