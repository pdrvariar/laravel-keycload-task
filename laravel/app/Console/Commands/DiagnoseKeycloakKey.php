<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DiagnoseKeycloakKey extends Command
{
    protected $signature = 'keycloak:diagnose-key';
    protected $description = 'Diagnose Keycloak public key configuration';

    public function handle()
    {
        $this->info('==============================================');
        $this->info('Diagnóstico da Chave Pública do Keycloak');
        $this->info('==============================================');
        $this->newLine();

        // 1. Verificar variável de ambiente
        $envKey = env('KEYCLOAK_REALM_PUBLIC_KEY');
        $this->info('1. Chave do .env:');
        if ($envKey) {
            $this->line("   ✓ Encontrada (" . strlen($envKey) . " caracteres)");
            $this->line("   Primeiros 50 chars: " . substr($envKey, 0, 50) . "...");
        } else {
            $this->error("   ✗ NÃO encontrada");
            return 1;
        }

        $this->newLine();

        // 2. Verificar configuração Laravel
        $this->info('2. Chave da configuração Laravel:');
        $configKey = config('keycloak.realm_public_key');
        if ($configKey) {
            $this->line("   ✓ Encontrada (" . strlen($configKey) . " caracteres)");
            $this->line("   Primeiros 100 chars:");
            $this->line("   " . substr($configKey, 0, 100));
        } else {
            $this->error("   ✗ NÃO encontrada");
            return 1;
        }

        $this->newLine();

        // 3. Verificar formato da chave
        $this->info('3. Análise do formato:');
        $hasBegin = strpos($configKey, '-----BEGIN PUBLIC KEY-----') !== false;
        $hasEnd = strpos($configKey, '-----END PUBLIC KEY-----') !== false;
        $hasNewlines = strpos($configKey, "\n") !== false;

        $this->line("   Tem BEGIN: " . ($hasBegin ? "✓ Sim" : "✗ Não"));
        $this->line("   Tem END: " . ($hasEnd ? "✓ Sim" : "✗ Não"));
        $this->line("   Tem quebras de linha: " . ($hasNewlines ? "✓ Sim" : "✗ Não"));

        if (!$hasBegin || !$hasEnd) {
            $this->warn("   ⚠️ PROBLEMA: Faltam delimitadores!");
        }

        $this->newLine();

        // 4. Testar se OpenSSL consegue ler a chave
        $this->info('4. Teste OpenSSL:');
        $resource = @openssl_pkey_get_public($configKey);
        if ($resource) {
            $this->line("   ✓ OpenSSL conseguiu ler a chave!");
            openssl_free_key($resource);
            $this->newLine();
            $this->info('✅ A chave está configurada corretamente!');
            return 0;
        } else {
            $this->error("   ✗ OpenSSL NÃO conseguiu ler a chave");
            $this->error("   Erro: " . openssl_error_string());

            // Tentar formatar corretamente
            $this->newLine();
            $this->info('5. Tentando reformatar a chave:');

            // Remove delimitadores e espaços
            $cleanKey = str_replace(['-----BEGIN PUBLIC KEY-----', '-----END PUBLIC KEY-----', "\n", "\r", ' '], '', $configKey);

            // Remove aspas se houver
            $cleanKey = trim($cleanKey, '"\'');

            $this->line("   Chave limpa (" . strlen($cleanKey) . " chars)");

            // Formata corretamente
            $formattedKey = "-----BEGIN PUBLIC KEY-----\n";
            $formattedKey .= chunk_split($cleanKey, 64, "\n");
            $formattedKey .= "-----END PUBLIC KEY-----";

            $this->newLine();
            $this->info('   Chave reformatada (preview):');
            $lines = explode("\n", $formattedKey);
            foreach (array_slice($lines, 0, 3) as $line) {
                $this->line("   " . $line);
            }
            $this->line("   ...");
            foreach (array_slice($lines, -2) as $line) {
                $this->line("   " . $line);
            }

            // Testa novamente
            $resource = @openssl_pkey_get_public($formattedKey);
            if ($resource) {
                $this->newLine();
                $this->info('   ✓ Chave reformatada funciona!');
                openssl_free_key($resource);

                $this->newLine();
                $this->info('==============================================');
                $this->info('SOLUÇÃO: Substitua no .env por:');
                $this->info('==============================================');
                $this->line('KEYCLOAK_REALM_PUBLIC_KEY="' . $cleanKey . '"');
                $this->info('==============================================');
                $this->newLine();
                $this->warn('Depois execute:');
                $this->line('docker compose restart app');
                $this->line('docker compose exec app php artisan config:clear');

                return 1;
            } else {
                $this->newLine();
                $this->error('   ✗ Ainda não funciona: ' . openssl_error_string());
                $this->newLine();
                $this->warn('A chave pode estar corrompida. Obtenha uma nova em:');
                $this->line('http://localhost:8000/setup/keycloak-key');
                return 1;
            }
        }
    }
}

