<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use App\Exceptions\CepNotFoundException;
use App\DTOs\CepDTO;

class CepService
{
    /**
     * URL base da API ViaCEP.
     *
     * @var string
     */
    protected string $apiUrl = "https://viacep.com.br/ws/";

    /**
     * Busca os dados de um CEP, utilizando cache para otimização.
     *
     * @param  string  $cep  CEP a ser consultado (8 dígitos numéricos).
     * @return CepDTO Objeto contendo os dados do CEP.
     *
     * @throws \RuntimeException Se houver erro de conexão com a API externa.
     * @throws CepNotFoundException Se o CEP não for encontrado na API.
     */
    public function buscarCep(string $cep): CepDTO
    {
        // Gera a chave única de cache para o CEP.
        $cacheKey = "cep:{$cep}";

        // Retorna os dados diretamente do cache, se disponíveis, evitando requisições desnecessárias.
        if (Cache::has($cacheKey)) {
            return new CepDTO(Cache::get($cacheKey));
        }

        // Realiza a requisição para a API externa.
        try {
            $response = Http::timeout(5)->get("{$this->apiUrl}{$cep}/json/");
        } catch (\Exception $e) {
            throw new \RuntimeException("Erro ao se conectar à API do ViaCEP: " . $e->getMessage());
        }

        // Verifica se a API retornou erro ou se o CEP não existe.
        if ($response->failed() || isset($response->json()['erro'])) {
            throw new CepNotFoundException("CEP não encontrado.");
        }

        // Converte os dados recebidos para um DTO (garantindo padronização).
        $data = new CepDTO($response->json());

        // Armazena a resposta no cache por 5 minutos para otimizar futuras consultas.
        Cache::put($cacheKey, $data->toArray(), now()->addMinutes(5));

        return $data;
    }
}
