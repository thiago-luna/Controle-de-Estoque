<?php
// public/index.php
// Ponto de entrada único da aplicação (Front Controller).
// Responsabilidade: inicializar sessão, carregar as classes necessárias,
// montar o Router e delegar o mapeamento de URLs ao arquivo de rotas
// (app/routes.php), conforme a Aula 05.

session_start();

require_once __DIR__ . '/../core/Router.php';
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Logger.php';
require_once __DIR__ . '/../app/Controllers/HomeController.php';
require_once __DIR__ . '/../app/Controllers/AuthController.php';
require_once __DIR__ . '/../app/Controllers/ProdutoController.php';
require_once __DIR__ . '/../app/Controllers/CategoriaController.php';
require_once __DIR__ . '/../app/Controllers/FornecedorController.php';
require_once __DIR__ . '/../app/Controllers/MovimentacaoController.php';

// Registra erros/exceções que escaparem de qualquer try/catch (Aula 08 -
// Boas Práticas: "Registrar erros"). Sem isso, um erro inesperado
// mostraria ao usuário uma tela em branco ou um stack trace do PHP, o
// que é ruim tanto para experiência quanto para segurança (vazamento de
// caminhos internos do servidor). Aqui, ele é gravado em
// storage/logs/app.log e o usuário vê uma página de erro genérica.
set_exception_handler(function (\Throwable $e) {
    Logger::error('Exceção não tratada', [
        'mensagem' => $e->getMessage(),
        'arquivo'  => $e->getFile() . ':' . $e->getLine(),
    ]);
    http_response_code(500);
    require __DIR__ . '/../app/Views/erros/500.php';
});

$router = new Router();

// O arquivo de rotas usa a variável $router acima para registrar cada
// Endpoint (ver app/routes.php).
require __DIR__ . '/../app/routes.php';

$router->resolve();
