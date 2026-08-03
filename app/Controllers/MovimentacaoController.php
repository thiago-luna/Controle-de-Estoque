<?php
// app/Controllers/MovimentacaoController.php
// Registro de entradas e saídas de estoque. Qualquer usuário autenticado
// pode registrar movimentações (Administrador ou Usuário Comum), conforme
// definido no público-alvo da Entrega Parcial 1.

require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../../core/Logger.php';
require_once __DIR__ . '/../Models/MovimentacaoEstoqueModel.php';
require_once __DIR__ . '/../Models/ProdutoModel.php';

class MovimentacaoController extends Controller
{
    /**
     * GET /movimentacoes
     * Histórico geral de movimentações.
     */
    public function index(): void
    {
        $this->exigirLogin();

        $movimentacoes = (new MovimentacaoEstoqueModel())->listarTodos();
        $this->view('movimentacoes/listar', [
            'titulo'        => 'Movimentações de Estoque',
            'movimentacoes' => $movimentacoes,
        ]);
    }

    /**
     * GET /movimentacoes/nova
     * Formulário para registrar entrada ou saída.
     */
    public function nova(): void
    {
        $this->exigirLogin();

        $this->view('movimentacoes/form', [
            'titulo'   => 'Nova Movimentação',
            'produtos' => (new ProdutoModel())->listarTodos(),
            'erro'     => null,
        ]);
    }

    /**
     * POST /movimentacoes
     * Processa o registro, atualizando o saldo do produto.
     */
    public function salvar(): void
    {
        $this->exigirLogin();

        $dados = $this->dadosDoPost();
        $usuario = $this->usuarioLogado();

        try {
            $produtoId  = (int) ($dados['produto_id'] ?? 0);
            $tipo       = $dados['tipo'] ?? '';
            $quantidade = (int) ($dados['quantidade'] ?? 0);
            $observacao = $dados['observacao'] ?? null;

            if ($produtoId <= 0) {
                throw new InvalidArgumentException('Selecione um produto.');
            }

            (new MovimentacaoEstoqueModel())->registrar(
                $produtoId,
                (int) $usuario['id'],
                $tipo,
                $quantidade,
                $observacao !== '' ? $observacao : null
            );

            $this->comFlash('sucesso', 'Movimentação registrada e estoque atualizado com sucesso!');
            $this->redirecionar('/produtos');
        } catch (\InvalidArgumentException $e) {
            // Rejeição de regra de negócio (ex.: saída maior que o estoque)
            // não é um "erro" do sistema, mas vale registrar para auditoria.
            Logger::warning('Movimentação de estoque rejeitada pela regra de negócio', [
                'usuario_id' => $usuario['id'] ?? null,
                'motivo'     => $e->getMessage(),
            ]);
            $this->view('movimentacoes/form', [
                'titulo'   => 'Nova Movimentação',
                'produtos' => (new ProdutoModel())->listarTodos(),
                'erro'     => $e->getMessage(),
            ]);
        } catch (\Throwable $e) {
            // Falha inesperada (ex.: banco fora do ar) — essa sim é um erro
            // real de sistema, registrada com mais detalhe para o
            // desenvolvedor investigar depois.
            Logger::error('Falha inesperada ao registrar movimentação de estoque', [
                'usuario_id' => $usuario['id'] ?? null,
                'erro'       => $e->getMessage(),
            ]);
            $this->view('movimentacoes/form', [
                'titulo'   => 'Nova Movimentação',
                'produtos' => (new ProdutoModel())->listarTodos(),
                'erro'     => 'Não foi possível registrar a movimentação. Tente novamente.',
            ]);
        }
    }
}
