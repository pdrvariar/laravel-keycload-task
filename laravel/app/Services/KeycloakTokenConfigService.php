<?php

/**
 * Script para aumentar o tempo de vida do token no Keycloak
 * Execute este script via CLI: php artisan tinker < este_arquivo
 * ou como um comando artisan customizado
 */

namespace App\Services;

use Illuminate\Support\Facades\Http;

class KeycloakTokenConfigService
{
    protected $baseUrl;
    protected $realm;
    protected $adminUser;
    protected $adminPassword;
    protected $tokenLifetime; // em segundos

    public function __construct()
    {
        $this->baseUrl = config('keycloak.base_url_internal') ?? env('KEYCLOAK_BASE_URL_INTERNAL', 'http://keycloak:8080');
        $this->realm = config('keycloak.realm') ?? env('KEYCLOAK_REALM', 'master');
        $this->adminUser = env('KEYCLOAK_ADMIN', 'admin');
        $this->adminPassword = env('KEYCLOAK_ADMIN_PASSWORD', 'admin');
        $this->tokenLifetime = 7200; // 2 horas
    }

    /**
     * Obter token de acesso do administrador
     */
    public function getAdminToken()
    {
        try {
            $response = Http::asForm()->post(
                "{$this->baseUrl}/realms/master/protocol/openid-connect/token",
                [
                    'username' => $this->adminUser,
                    'password' => $this->adminPassword,
                    'client_id' => 'admin-cli',
                    'grant_type' => 'password',
                ]
            );

            if ($response->failed()) {
                throw new \Exception('Erro ao obter token de admin: ' . $response->body());
            }

            return $response->json('access_token');
        } catch (\Exception $e) {
            \Log::error('KeycloakTokenConfig: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Atualizar tempo de vida do token na realm
     */
    public function updateRealmTokenLifetime()
    {
        $adminToken = $this->getAdminToken();

        if (!$adminToken) {
            throw new \Exception('Erro ao obter token de administrador');
        }

        try {
            // Obter configuração atual da realm
            $realmConfig = Http::withToken($adminToken)->get(
                "{$this->baseUrl}/admin/realms/{$this->realm}"
            );

            if ($realmConfig->failed()) {
                throw new \Exception('Erro ao obter configuração da realm: ' . $realmConfig->body());
            }

            $config = $realmConfig->json();

            // Atualizar tempo de vida do token
            $config['accessTokenLifespan'] = $this->tokenLifetime;
            $config['refreshTokenLifespan'] = 2592000; // 30 dias
            $config['offlineSessionIdleTimeout'] = 2592000; // 30 dias

            // Enviar configuração atualizada
            $updateResponse = Http::withToken($adminToken)->put(
                "{$this->baseUrl}/admin/realms/{$this->realm}",
                $config
            );

            if ($updateResponse->failed()) {
                throw new \Exception('Erro ao atualizar realm: ' . $updateResponse->body());
            }

            \Log::info('KeycloakTokenConfig: Token lifetime atualizado para ' . $this->tokenLifetime . ' segundos');

            return true;
        } catch (\Exception $e) {
            \Log::error('KeycloakTokenConfig: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Atualizar tempo de vida do token para um client específico
     */
    public function updateClientTokenLifetime($clientId = 'task-app')
    {
        $adminToken = $this->getAdminToken();

        if (!$adminToken) {
            throw new \Exception('Erro ao obter token de administrador');
        }

        try {
            // Obter lista de clients
            $clients = Http::withToken($adminToken)->get(
                "{$this->baseUrl}/admin/realms/{$this->realm}/clients"
            );

            if ($clients->failed()) {
                throw new \Exception('Erro ao obter clientes: ' . $clients->body());
            }

            // Procurar pelo client
            $clientData = null;
            foreach ($clients->json() as $client) {
                if ($client['clientId'] === $clientId) {
                    $clientData = $client;
                    break;
                }
            }

            if (!$clientData) {
                throw new \Exception("Cliente '{$clientId}' não encontrado");
            }

            $id = $clientData['id'];

            // Obter configuração do client
            $clientConfig = Http::withToken($adminToken)->get(
                "{$this->baseUrl}/admin/realms/{$this->realm}/clients/{$id}"
            );

            if ($clientConfig->failed()) {
                throw new \Exception('Erro ao obter configuração do cliente: ' . $clientConfig->body());
            }

            $config = $clientConfig->json();

            // Atualizar tempo de vida do token
            $config['accessTokenLifespan'] = $this->tokenLifetime;

            // Enviar configuração atualizada
            $updateResponse = Http::withToken($adminToken)->put(
                "{$this->baseUrl}/admin/realms/{$this->realm}/clients/{$id}",
                $config
            );

            if ($updateResponse->failed()) {
                throw new \Exception('Erro ao atualizar cliente: ' . $updateResponse->body());
            }

            \Log::info("KeycloakTokenConfig: Token lifetime do client '{$clientId}' atualizado para " . $this->tokenLifetime . ' segundos');

            return true;
        } catch (\Exception $e) {
            \Log::error('KeycloakTokenConfig: ' . $e->getMessage());
            return false;
        }
    }
}

