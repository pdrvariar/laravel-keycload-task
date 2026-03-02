<?php

namespace App\Console\Commands;

use App\Services\KeycloakTokenConfigService;
use Illuminate\Console\Command;

class ConfigureKeycloakTokenLifetime extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'keycloak:configure-token-lifetime {--lifetime=7200 : Tempo de vida do token em segundos (padrão: 7200 = 2 horas)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Configura o tempo de vida do token no Keycloak para 2 horas';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔧 Configurando tempo de vida do token no Keycloak...');
        $this->info('');

        $service = new KeycloakTokenConfigService();

        // Atualizar realm
        $this->info('📝 Atualizando configuração da realm...');
        if ($service->updateRealmTokenLifetime()) {
            $this->info('✓ Realm configurada com sucesso!');
        } else {
            $this->error('✗ Erro ao configurar realm');
            return 1;
        }

        $this->info('');

        // Atualizar client
        $this->info('📝 Atualizando configuração do client task-app...');
        if ($service->updateClientTokenLifetime('task-app')) {
            $this->info('✓ Client configurado com sucesso!');
        } else {
            $this->error('✗ Erro ao configurar client');
            return 1;
        }

        $this->info('');
        $this->info('✓ Configuração do Keycloak concluída!');
        $this->info('✓ Tokens terão validade de 2 horas');

        return 0;
    }
}

