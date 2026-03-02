<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class AuthController extends Controller
{
    // Show login page
    public function showLogin()
    {
        return view('auth.login');
    }

    // Handle direct login with email and password (transparent Keycloak auth)
    public function login(Request $request)
    {
        // Validate input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        try {
            // Get access token from Keycloak using Resource Owner Password Credentials
            $tokenData = $this->getAccessTokenFromKeycloak(
                $request->input('email'),
                $request->input('password')
            );

            // Decode ID token to get user info
            $idToken = $tokenData['id_token'];
            $idTokenParts = explode('.', $idToken);
            $idPayload = json_decode(base64_decode($idTokenParts[1]), true);

            // Decode ACCESS token to get roles (roles are in access_token, not id_token)
            $accessToken = $tokenData['access_token'];
            $accessTokenParts = explode('.', $accessToken);
            $accessPayload = json_decode(base64_decode($accessTokenParts[1]), true);

            // Log detalhado do access_token
            \Log::info('=== DEBUG ACCESS TOKEN ===');
            \Log::info('Access Token Payload Completo:', $accessPayload);
            \Log::info('Resource Access:', ['resource_access' => $accessPayload['resource_access'] ?? 'N/A']);
            \Log::info('Realm Access:', ['realm_access' => $accessPayload['realm_access'] ?? 'N/A']);

            // Merge payloads - user info from id_token, roles from access_token
            $payload = array_merge($idPayload, [
                'resource_access' => $accessPayload['resource_access'] ?? [],
                'realm_access' => $accessPayload['realm_access'] ?? []
            ]);

            \Log::info('=== PAYLOAD FINAL MERGED ===');
            \Log::info('Payload após merge:', $payload);

            // Find or create local user
            $user = \App\Models\User::updateOrCreate(
                ['email' => $payload['email']],
                [
                    'name' => $payload['name'] ?? $payload['preferred_username'],
                    'keycloak_id' => $payload['sub'],
                ]
            );

            // Store tokens in session
            session([
                'keycloak_access_token' => $tokenData['access_token'],
                'keycloak_refresh_token' => $tokenData['refresh_token'],
                'keycloak_user' => $payload,
            ]);

            // Log para debug
            \Log::info('Login - Roles encontradas', [
                'email' => $payload['email'],
                'resource_access' => $payload['resource_access'] ?? 'N/A',
                'realm_access' => $payload['realm_access'] ?? 'N/A'
            ]);

            // Log the user in (Laravel session)
            Auth::login($user, true);

            // Redirect based on role
            $clientId = config('keycloak.client_id', 'task-app');
            $clientRoles = $payload['resource_access'][$clientId]['roles'] ?? [];
            $realmRoles = $payload['realm_access']['roles'] ?? [];
            $allRoles = array_merge($clientRoles, $realmRoles);

            if (in_array('admin', $allRoles)) {
                return redirect('/admin/dashboard')->with('success', 'Bem-vindo de volta, Admin!');
            }

            return redirect('/dashboard')->with('success', 'Bem-vindo de volta!');

        } catch (\Exception $e) {
            \Log::error('Login failed: ' . $e->getMessage());
            return back()->withErrors(['email' => 'Email ou senha inválidos'])->withInput();
        }
    }

    // Get access token from Keycloak using Resource Owner Password Credentials
    private function getAccessTokenFromKeycloak($email, $password)
    {
        $client = new Client([
            'timeout' => 5,
            'connect_timeout' => 2,
        ]);

        try {
            $keycloakUrl = config('keycloak.base_url_internal', config('keycloak.base_url'));
            $realm = config('keycloak.realm');
            $clientId = config('keycloak.client_id');
            $clientSecret = config('keycloak.client_secret');

            $response = $client->post(
                "{$keycloakUrl}/realms/{$realm}/protocol/openid-connect/token",
                [
                    'form_params' => [
                        'grant_type' => 'password',
                        'client_id' => $clientId,
                        'client_secret' => $clientSecret,
                        'username' => $email,
                        'password' => $password,
                        'scope' => 'openid profile email',
                    ],
                ]
            );

            $tokenData = json_decode($response->getBody(), true);

            if (!isset($tokenData['access_token'])) {
                throw new \Exception('No access token received from Keycloak');
            }

            return $tokenData;

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            \Log::error('Keycloak auth error: ' . $e->getMessage());
            throw new \Exception('Email ou senha inválidos');
        } catch (\Exception $e) {
            \Log::error('Token exchange failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function logout()
    {
        Auth::logout();
        session()->flush();
        return redirect('/login')->with('info', 'Você foi desconectado com sucesso');
    }
}
