<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CepController;
/**
 * Definição das rotas da API.
 *
 * Todas as rotas aqui são prefixadas por "/api" automaticamente pelo Laravel.
 * Exemplo: A rota abaixo estará disponível em "/api/cep/{cep}".
 */

Route::middleware(['throttle:10,1'])->get('/cep/{cep}', [CepController::class, 'buscarCep']);

/**
 * 🔹 Explicação dos Middleware aplicados:
 *
 * - throttle:10,1 → Limita o número de requisições para evitar abusos (10 requisições por minuto por IP).
 * - name('cep.buscar') → Nomeia a rota para somente para facilitar referências no código e nos testes.
 */
