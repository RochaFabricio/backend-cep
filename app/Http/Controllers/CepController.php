<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CepService;
use App\Exceptions\CepNotFoundException;
use Illuminate\Support\Facades\Log;

class CepController extends Controller
{
    protected CepService $cepService;

    /**
     * Injeta a dependência do serviço de CEP no Controller.
     */
    public function __construct(CepService $cepService)
    {
        $this->cepService = $cepService;
    }

    /**
     * Retorna os dados de um CEP consultando a API ViaCEP.
     *
     * @param  Request  $request  Objeto da requisição HTTP.
     * @param  string  $cep  CEP a ser consultado (8 dígitos numéricos).
     * @return \Illuminate\Http\JsonResponse Resposta com os dados do CEP ou erro.
     */
    public function buscarCep(Request $request, string $cep)
    {
        try {
            // Valida manualmente o CEP para evitar sobrecarga do Laravel ValidationException.
            if (!preg_match('/^\d{8}$/', $cep)) {
                return response()->json(['error' => 'O CEP deve conter 8 dígitos numéricos.'], 400);
            }

            // Consulta os dados do CEP via serviço, que aplica cache e chamada externa.
            $data = $this->cepService->buscarCep($cep);

            return response()->json($data->toArray(), 200);
        } catch (CepNotFoundException $e) {
            // Loga tentativa de busca com CEP inválido, útil para auditoria e monitoramento.
            Log::warning("CEP não encontrado: {$cep}");
            return response()->json(['error' => $e->getMessage()], 404);
        } catch (\RuntimeException $e) {
            // Captura erros inesperados de conexão ou problemas na API externa.
            Log::error("Erro ao consultar o CEP: " . $e->getMessage());
            return response()->json(['error' => 'Erro interno ao buscar CEP.'], 500);
        }
    }
}
