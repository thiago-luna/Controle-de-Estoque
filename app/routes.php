<?php
// app/routes.php
// Arquivo exclusivo para o mapeamento de rotas (Aula 05 - Rotas e
// Gerenciamento de URLs: "Uma boa prática é criar um arquivo exclusivo
// para as rotas").
//
// Centraliza em um único lugar a relação entre cada Endpoint (URL) e o
// par [Controller, método] responsável por atendê-lo, evitando que essa
// informação fique espalhada pelo projeto.
//
// Espera receber a variável $router (instância de Router) já criada por
// quem incluir este arquivo (public/index.php).

// ------------------------------------------------------------------
// Página inicial
// ------------------------------------------------------------------
$router->get('/', [HomeController::class, 'index']);

// ------------------------------------------------------------------
// Autenticação (Entrega Parcial 5)
// ------------------------------------------------------------------
$router->get('/login', [AuthController::class, 'telaLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->post('/logout', [AuthController::class, 'logout']);
$router->get('/registrar', [AuthController::class, 'telaRegistro']);
$router->post('/registrar', [AuthController::class, 'registrar']);

// ------------------------------------------------------------------
// Produtos (CRUD completo - Entrega Parcial 4)
// ------------------------------------------------------------------
$router->get('/produtos', [ProdutoController::class, 'index']);
$router->get('/produtos/novo', [ProdutoController::class, 'novo']);
$router->post('/produtos', [ProdutoController::class, 'salvar']);
$router->get('/produtos/{id}/editar', [ProdutoController::class, 'editar']);
$router->put('/produtos/{id}', [ProdutoController::class, 'atualizar']);
$router->delete('/produtos/{id}', [ProdutoController::class, 'excluir']);

// ------------------------------------------------------------------
// Categorias (CRUD)
// ------------------------------------------------------------------
$router->get('/categorias', [CategoriaController::class, 'index']);
$router->get('/categorias/novo', [CategoriaController::class, 'novo']);
$router->post('/categorias', [CategoriaController::class, 'salvar']);
$router->get('/categorias/{id}/editar', [CategoriaController::class, 'editar']);
$router->put('/categorias/{id}', [CategoriaController::class, 'atualizar']);
$router->delete('/categorias/{id}', [CategoriaController::class, 'excluir']);

// ------------------------------------------------------------------
// Fornecedores (CRUD)
// ------------------------------------------------------------------
$router->get('/fornecedores', [FornecedorController::class, 'index']);
$router->get('/fornecedores/novo', [FornecedorController::class, 'novo']);
$router->post('/fornecedores', [FornecedorController::class, 'salvar']);
$router->get('/fornecedores/{id}/editar', [FornecedorController::class, 'editar']);
$router->put('/fornecedores/{id}', [FornecedorController::class, 'atualizar']);
$router->delete('/fornecedores/{id}', [FornecedorController::class, 'excluir']);

// ------------------------------------------------------------------
// Movimentações de estoque (entrada/saída)
// ------------------------------------------------------------------
$router->get('/movimentacoes', [MovimentacaoController::class, 'index']);
$router->get('/movimentacoes/nova', [MovimentacaoController::class, 'nova']);
$router->post('/movimentacoes', [MovimentacaoController::class, 'salvar']);
