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
            $parts = explode('.', $idToken);
            $payload = json_decode(base64_decode($parts[1]), true);

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

            // Log the user in (Laravel session)
            Auth::login($user, true);

            // Redirect based on role
            $roles = $payload['resource_access']['task-controller']['roles'] ?? [];

            if (in_array('admin', $roles)) {
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
