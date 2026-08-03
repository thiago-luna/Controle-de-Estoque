<?php
// app/Controllers/HomeController.php

require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../../core/Logger.php';
require_once __DIR__ . '/../Models/ProdutoModel.php';

class HomeController extends Controller
{
    /**
     * GET /
     * Página inicial: se o usuário estiver logado, mostra um pequeno
     * painel com o total de produtos e alertas de estoque mínimo;
     * caso contrário, mostra a tela de apresentação do sistema.
     */
    public function index(): void
    {
        $dados = [
            'titulo'          => 'Sistema de Controle de Estoque',
            'totalProdutos'   => null,
            'produtosAlerta'  => [],
        ];

        if ($this->usuarioLogado()) {
            try {
                $produtos = (new ProdutoModel())->listarTodos();
                $dados['totalProdutos']  = count($produtos);
                $dados['produtosAlerta'] = array_filter(
                    $produtos,
                    fn($p) => $p['quantidade'] <= $p['estoque_minimo']
                );
            } catch (\Throwable $e) {
                // Banco ainda não disponível: painel simplesmente não é exibido.
                Logger::warning('Painel inicial não pôde carregar dados de estoque', [
                    'erro' => $e->getMessage(),
                ]);
            }
        }

        $this->view('home', $dados);
    }
}
