<?php
// app/Controllers/ProdutoController.php
// Entrega Parcial 4: CRUD completo de produtos, validações e mensagens
// de sucesso/erro.

require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../../core/Router.php';
require_once __DIR__ . '/../../core/Logger.php';
require_once __DIR__ . '/../Models/ProdutoModel.php';
require_once __DIR__ . '/../Models/CategoriaModel.php';
require_once __DIR__ . '/../Models/FornecedorModel.php';

class ProdutoController extends Controller
{
    /**
     * GET /produtos
     * Lista os produtos cadastrados.
     */
    public function index(): void
    {
        $this->exigirLogin();

        try {
            $model = new ProdutoModel();
            $produtos = $model->listarTodos();
        } catch (\Throwable $e) {
            // Banco ainda não configurado/criado neste ambiente: usa dados
            // de exemplo para que a tela continue navegável.
            Logger::warning('Falha ao listar produtos no banco; usando dados de exemplo', [
                'erro' => $e->getMessage(),
            ]);
            $produtos = [
                ['id' => 1, 'nome' => 'Parafuso M6', 'categoria' => 'Ferragens', 'quantidade' => 1200, 'preco_unitario' => 0.15, 'estoque_minimo' => 100],
                ['id' => 2, 'nome' => 'Cabo Flexível 2,5mm', 'categoria' => 'Elétrica', 'quantidade' => 300, 'preco_unitario' => 3.20, 'estoque_minimo' => 50],
                ['id' => 3, 'nome' => 'Luva de Proteção', 'categoria' => 'EPI', 'quantidade' => 45, 'preco_unitario' => 12.90, 'estoque_minimo' => 20],
            ];
        }

        $this->view('produtos/listar', [
            'titulo'   => 'Produtos em Estoque',
            'produtos' => $produtos,
        ]);
    }

    /**
     * GET /produtos/novo
     * Exibe o formulário de cadastro (Create, parte 1).
     */
    public function novo(): void
    {
        $this->exigirLogin();

        $this->view('produtos/form', [
            'titulo'      => 'Novo Produto',
            'produto'     => null,
            'erros'       => [],
            'categorias'  => (new CategoriaModel())->listarTodos(),
            'fornecedores' => (new FornecedorModel())->listarTodos(),
        ]);
    }

    /**
     * GET /produtos/{id}/editar
     * Exibe o formulário já preenchido para edição.
     */
    public function editar(): void
    {
        $this->exigirLogin();

        $id = $this->idDaUri();
        $model = new ProdutoModel();
        $produto = $model->buscarPorId($id);

        if (!$produto) {
            $this->comFlash('erro', 'Produto não encontrado.');
            $this->redirecionar('/produtos');
        }

        $this->view('produtos/form', [
            'titulo'      => 'Editar Produto',
            'produto'     => $produto,
            'erros'       => [],
            'categorias'  => (new CategoriaModel())->listarTodos(),
            'fornecedores' => (new FornecedorModel())->listarTodos(),
        ]);
    }

    /**
     * POST /produtos
     * Salva um novo produto (Create, parte 2), com validação de campos.
     */
    public function salvar(): void
    {
        $this->exigirLogin();

        $dados = $this->dadosDoPost();
        $erros = $this->validar($dados);

        if (!empty($erros)) {
            $this->view('produtos/form', [
                'titulo'       => 'Novo Produto',
                'produto'      => $dados,
                'erros'        => $erros,
                'categorias'   => (new CategoriaModel())->listarTodos(),
                'fornecedores' => (new FornecedorModel())->listarTodos(),
            ]);
            return;
        }

        (new ProdutoModel())->criar($dados);
        $this->comFlash('sucesso', 'Produto "' . $dados['nome'] . '" cadastrado com sucesso!');
        $this->redirecionar('/produtos');
    }

    /**
     * PUT /produtos/{id}  (via method override)
     * Atualiza um produto existente (Update).
     */
    public function atualizar(): void
    {
        $this->exigirLogin();

        $id = $this->idDaUri();
        $dados = $this->dadosDoPost();
        $erros = $this->validar($dados);

        if (!empty($erros)) {
            $dados['id'] = $id;
            $this->view('produtos/form', [
                'titulo'       => 'Editar Produto',
                'produto'      => $dados,
                'erros'        => $erros,
                'categorias'   => (new CategoriaModel())->listarTodos(),
                'fornecedores' => (new FornecedorModel())->listarTodos(),
            ]);
            return;
        }

        (new ProdutoModel())->atualizar($id, $dados);
        $this->comFlash('sucesso', 'Produto atualizado com sucesso!');
        $this->redirecionar('/produtos');
    }

    /**
     * DELETE /produtos/{id}  (via method override)
     * Remove um produto (Delete). Restrito a administradores, pois é uma
     * ação destrutiva e irreversível.
     */
    public function excluir(): void
    {
        $this->exigirAdministrador();

        $id = $this->idDaUri();

        try {
            (new ProdutoModel())->excluir($id);
            $this->comFlash('sucesso', 'Produto excluído com sucesso.');
        } catch (\Throwable $e) {
            Logger::error('Falha ao excluir produto', ['id' => $id, 'erro' => $e->getMessage()]);
            $this->comFlash('erro', 'Não foi possível excluir: existem movimentações de estoque associadas a este produto.');
        }

        $this->redirecionar('/produtos');
    }

    /**
     * Validação dos dados do formulário de produto (Entrega Parcial 4).
     * Mantida no Controller por ser validação de entrada/apresentação;
     * regras de negócio mais profundas (ex.: consistência de estoque)
     * ficam no Model.
     */
    private function validar(array $dados): array
    {
        $erros = [];

        if (empty($dados['nome'])) {
            $erros['nome'] = 'O nome do produto é obrigatório.';
        } elseif (strlen($dados['nome']) > 150) {
            $erros['nome'] = 'O nome pode ter no máximo 150 caracteres.';
        }

        if (isset($dados['quantidade']) && !is_numeric($dados['quantidade'])) {
            $erros['quantidade'] = 'Quantidade deve ser um número.';
        } elseif (isset($dados['quantidade']) && (int) $dados['quantidade'] < 0) {
            $erros['quantidade'] = 'Quantidade não pode ser negativa.';
        }

        if (isset($dados['preco_unitario']) && !is_numeric($dados['preco_unitario'])) {
            $erros['preco_unitario'] = 'Preço unitário deve ser um número.';
        } elseif (isset($dados['preco_unitario']) && (float) $dados['preco_unitario'] < 0) {
            $erros['preco_unitario'] = 'Preço unitário não pode ser negativo.';
        }

        if (isset($dados['estoque_minimo']) && !is_numeric($dados['estoque_minimo'])) {
            $erros['estoque_minimo'] = 'Estoque mínimo deve ser um número.';
        } elseif (isset($dados['estoque_minimo']) && (int) $dados['estoque_minimo'] < 0) {
            $erros['estoque_minimo'] = 'Estoque mínimo não pode ser negativo.';
        }

        return $erros;
    }

    /**
     * Extrai o {id} numérico da URI atual, ex.: /produtos/7/editar -> 7.
     * Usado pelas rotas que operam sobre um produto específico.
     */
    private function idDaUri(): int
    {
        return (int) Router::parametro('id', 0);
    }
}
