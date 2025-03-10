# 📌 API de Consulta de CEP com Laravel 12 🚀

[![Laravel](https://img.shields.io/badge/Laravel-12-red)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2-blue)](https://www.php.net/)
[![PHPUnit](https://img.shields.io/badge/Tests-PHPUnit-blue)](https://phpunit.de/)

## 📖 Sobre o Projeto

Este projeto é uma **API RESTful de consulta de CEP**, desenvolvida em **Laravel 12**, que busca informações de endereços utilizando a API externa **ViaCEP**.  
Possui um **cache eficiente**, tratamento de exceções robusto, e **testes automatizados** garantindo sua confiabilidade.

---

## ⚙️ Tecnologias Utilizadas

✅ **PHP 8.2+** → Linguagem principal  
✅ **Laravel 12** → Framework backend  
✅ **Redis (Cache)** → Para otimizar as buscas  
✅ **PHPUnit** → Para testes automatizados  
✅ **Http Client do Laravel** → Para consumir APIs externas  
✅ **Docker (Opcional)** → Para ambiente de desenvolvimento  
✅ **SQLite (Banco de dados para testes, pré-configurado no Laravel)**

---

## 📥 Instalação e Configuração

### 🔹 **1. Clonar o Repositório**
Faça o clone do projeto para sua máquina.

### 🔹 **2. Instalar Dependências**
```sh
composer install
```

### 🔹 **3. Criar o Arquivo `.env` e Configurar SQLite**
```sh
cp .env.example .env
php artisan key:generate
```

### 🔹 **4. Iniciar o Servidor**
```sh
php artisan serve
```

A API estará disponível em:  
📌 **`http://127.0.0.1:8000/api/cep/{cep}`**

### 🔹 **5. Rodar os Testes**
```sh
php artisan test
```
Para rodar apenas os testes de CEP:
```sh
php artisan test --filter=CepTest
```

## ✅ Explicação dos Testes

✅ **Testa CEP válido (`test_busca_cep_com_sucesso`)** → Garante que a API retorna os dados corretos.  
✅ **Testa CEP inválido (`test_busca_cep_invalido`)** → Verifica se a API retorna erro para entrada incorreta.  
✅ **Testa CEP inexistente (`test_cep_nao_existente`)** → Simula uma resposta 404 da API ViaCEP.  
✅ **Testa cache (`test_busca_cep_cache_funciona`)** → Verifica se a resposta do cache é usada corretamente.  
✅ **Testa falha na API (`test_erro_de_conexao_com_api_externa`)** → Simula um erro 500 na API externa e verifica o tratamento de erro.

---


## 📂 Estrutura do Projeto

```sh
📦 projeto
 ┣ 📂 app
 ┃ ┣ 📂 DTOs
 ┃ ┃ ┗ 📜 CepDTO.php  # DTO para garantir consistência dos dados
 ┃ ┣ 📂 Exceptions
 ┃ ┃ ┗ 📜 CepNotFoundException.php  # Exceção personalizada
 ┃ ┣ 📂 Http
 ┃ ┃ ┣ 📂 Controllers
 ┃ ┃ ┃ ┗ 📜 CepController.php  # Controller principal
 ┃ ┣ 📂 Services
 ┃ ┃ ┗ 📜 CepService.php  # Serviço responsável pela lógica de negócio
 ┣ 📂 tests
 ┃ ┣ 📂 Feature
 ┃ ┃ ┗ 📜 CepTest.php  # Testes automatizados
 ┣ 📂 routes
 ┃ ┗ 📜 api.php  # Definição das rotas da API
 ┗ 📜 README.md
```

---

## 📌 Boas Práticas e Considerações

📌 **Cache** → Utiliza a Facade **Cache** do Laravel para armazenar respostas temporárias e reduzir chamadas externas desnecessárias.  
📌 **Rate Limiting** → Máximo de **10 requisições por minuto por IP**.  
📌 **Tratamento de Erros** → Usa exceções personalizadas para garantir que apenas respostas bem formatadas sejam enviadas.  
📌 **Arquitetura SOLID** → Código modular com separação clara de responsabilidades (Controllers, Services, DTOs, Exceptions).   
📌 **Segurança** → O Laravel protege automaticamente contra SQL Injection e CSRF.

---

## 👨‍💻 Autor

🔹 **Fabricio Rocha**  
🔹 💼 [LinkedIn](https://www.linkedin.com/in/fabricio-v-rocha/)
