<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CepTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Testa a busca de um CEP válido e garante que a resposta tenha os dados esperados.
     */
    public function test_busca_cep_com_sucesso()
    {
        Http::fake([
            "https://viacep.com.br/ws/14784478/json/" => Http::response([
                'cep' => '14784478',
                'logradouro' => 'Avenida Ranulfo Prata',
                'bairro' => 'Jardim Universitário',
                'localidade' => 'Barretos',
                'uf' => 'SP'
            ], 200)
        ]);

        $response = $this->getJson('/api/cep/14784478');

        $response->assertStatus(200)
            ->assertJson([
                'cep' => '14784478',
                'logradouro' => 'Avenida Ranulfo Prata',
                'bairro' => 'Jardim Universitário',
                'localidade' => 'Barretos',
                'uf' => 'SP'
            ]);
    }

    /**
     * Testa a resposta para um CEP inválido (menos de 8 dígitos).
     */
    public function test_busca_cep_invalido()
    {
        $response = $this->getJson('/api/cep/123');

        $response->assertStatus(400)
            ->assertJson(['error' => 'O CEP deve conter 8 dígitos numéricos.']);
    }

    /**
     * Testa a resposta para um CEP inexistente (API retorna erro).
     */
    public function test_cep_nao_existente()
    {
        Http::fake([
            "https://viacep.com.br/ws/99999999/json/" => Http::response(['erro' => true], 404)
        ]);

        $response = $this->getJson('/api/cep/99999999');

        $response->assertStatus(404)
            ->assertJson(['error' => 'CEP não encontrado.']);
    }

    /**
     * Testa se um CEP já consultado está sendo armazenado em cache corretamente.
     */
    public function test_busca_cep_cache_funciona()
    {
        Cache::flush(); // Limpa o cache antes do teste

        $cacheKey = 'cep:14784478';

        // Simula a primeira requisição salvando no cache
        Cache::put($cacheKey, [
            'cep' => '14784478',
            'logradouro' => 'Avenida Paulista',
            'bairro' => 'Jardim Universitário',
            'localidade' => 'Barretos',
            'uf' => 'SP'
        ], now()->addMinutes(5));

        $response = $this->getJson('/api/cep/14784478');

        $response->assertStatus(200)
            ->assertJson([
                'cep' => '14784478',
                'logradouro' => 'Avenida Paulista',
                'bairro' => 'Jardim Universitário',
                'localidade' => 'Barretos',
                'uf' => 'SP'
            ]);
    }

    /**
     * Testa se um erro de conexão com a API externa é tratado corretamente.
     */
    public function test_erro_de_conexao_com_api_externa()
    {
        // Simula uma falha de conexão com a API ViaCEP
        Http::fake([
            "https://viacep.com.br/ws/14784478/json/" => fn() => throw new \Exception("Falha na conexão")
        ]);

        $response = $this->getJson('/api/cep/14784478');

        $response->assertStatus(500)
            ->assertJson(['error' => 'Erro interno ao buscar CEP.']);
    }
}
