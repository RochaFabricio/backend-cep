<?php

namespace App\DTOs;

class CepDTO
{
    /**
     * @var string CEP formatado (ex: "01001000").
     */
    public string $cep;

    /**
     * @var string Nome da rua ou logradouro associado ao CEP.
     */
    public string $logradouro;

    /**
     * @var string Nome do bairro associado ao CEP.
     */
    public string $bairro;

    /**
     * @var string Nome da cidade associada ao CEP.
     */
    public string $localidade;

    /**
     * @var string Sigla do estado (UF) associado ao CEP.
     */
    public string $uf;

    /**
     * Construtor do DTO que inicializa os dados do CEP.
     *
     * @param array $data Array de dados da API ViaCEP.
     */
    public function __construct(array $data)
    {
        $this->cep = $data['cep'] ?? '';
        $this->logradouro = $data['logradouro'] ?? '';
        $this->bairro = $data['bairro'] ?? '';
        $this->localidade = $data['localidade'] ?? '';
        $this->uf = $data['uf'] ?? '';
    }

    /**
     * Converte o objeto DTO para um array associativo.
     *
     * @return array Representação do DTO em formato de array.
     */
    public function toArray(): array
    {
        return [
            'cep' => $this->cep,
            'logradouro' => $this->logradouro,
            'bairro' => $this->bairro,
            'localidade' => $this->localidade,
            'uf' => $this->uf,
        ];
    }
}
