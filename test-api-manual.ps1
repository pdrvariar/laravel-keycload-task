#!/usr/bin/env pwsh

# Script para testar API de tarefas

$baseUrl = "http://localhost/api"
$taskId = 1

# Aqui você precisa ter um token válido do Keycloak
# Para este teste, vamos assumir que você pode executar após fazer login

Write-Host "Testando API de Tarefas..." -ForegroundColor Green

# 1. Buscar tarefa
Write-Host "`n1. Buscando tarefa $taskId..." -ForegroundColor Yellow
$getResponse = Invoke-WebRequest -Uri "$baseUrl/tasks/$taskId" `
    -Method GET `
    -Headers @{
        'Authorization' = 'Bearer TOKEN_AQUI'
        'Accept' = 'application/json'
    } `
    -ErrorAction Stop

Write-Host "Resposta GET:" -ForegroundColor Cyan
$getResponse.Content | ConvertFrom-Json | ConvertTo-Json

# 2. Atualizar tarefa
Write-Host "`n2. Atualizando tarefa $taskId..." -ForegroundColor Yellow
$taskData = $getResponse.Content | ConvertFrom-Json
$task = $taskData.data

$newStatus = if ($task.status -eq 'Concluído') { 'Em Andamento' } else { 'Concluído' }

$payload = @{
    title = $task.title
    description = $task.description
    status = $newStatus
} | ConvertTo-Json

Write-Host "Payload:" -ForegroundColor Cyan
$payload

$updateResponse = Invoke-WebRequest -Uri "$baseUrl/tasks/$taskId" `
    -Method PUT `
    -Headers @{
        'Authorization' = 'Bearer TOKEN_AQUI'
        'Accept' = 'application/json'
        'Content-Type' = 'application/json'
    } `
    -Body $payload `
    -ErrorAction Stop

Write-Host "Resposta PUT:" -ForegroundColor Green
$updateResponse.Content | ConvertFrom-Json | ConvertTo-Json

Write-Host "`n✓ Teste concluído!" -ForegroundColor Green

