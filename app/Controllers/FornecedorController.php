<?php
// app/Controllers/FornecedorController.php
// CRUD de Fornecedores. Assim como Categorias, a gestão é restrita ao
// Administrador.

require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../../core/Router.php';
require_once __DIR__ . '/../../core/Logger.php';
require_once __DIR__ . '/../Models/FornecedorModel.php';

class FornecedorController extends Controller
{
    public function index(): void
    {
        $this->exigirLogin();

        $fornecedores = (new FornecedorModel())->listarTodos();
        $this->view('fornecedores/listar', [
            'titulo'       => 'Fornecedores',
            'fornecedores' => $fornecedores,
        ]);
    }

    public function novo(): void
    {
        $this->exigirAdministrador();

        $this->view('fornecedores/form', [
            'titulo'     => 'Novo Fornecedor',
            'fornecedor' => null,
            'erros'      => [],
        ]);
    }

    public function editar(): void
    {
        $this->exigirAdministrador();

        $id = $this->idDaUri();
        $fornecedor = (new FornecedorModel())->buscarPorId($id);

        if (!$fornecedor) {
            $this->comFlash('erro', 'Fornecedor não encontrado.');
            $this->redirecionar('/fornecedores');
        }

        $this->view('fornecedores/form', [
            'titulo'     => 'Editar Fornecedor',
            'fornecedor' => $fornecedor,
            'erros'      => [],
        ]);
    }

    public function salvar(): void
    {
        $this->exigirAdministrador();

        $dados = $this->dadosDoPost();
        $erros = $this->validar($dados);

        if (!empty($erros)) {
            $this->view('fornecedores/form', [
                'titulo'     => 'Novo Fornecedor',
                'fornecedor' => $dados,
                'erros'      => $erros,
            ]);
            return;
        }

        (new FornecedorModel())->criar($dados);
        $this->comFlash('sucesso', 'Fornecedor cadastrado com sucesso!');
        $this->redirecionar('/fornecedores');
    }

    public function atualizar(): void
    {
        $this->exigirAdministrador();

        $id = $this->idDaUri();
        $dados = $this->dadosDoPost();
        $erros = $this->validar($dados);

        if (!empty($erros)) {
            $dados['id'] = $id;
            $this->view('fornecedores/form', [
                'titulo'     => 'Editar Fornecedor',
                'fornecedor' => $dados,
                'erros'      => $erros,
            ]);
            return;
        }

        (new FornecedorModel())->atualizar($id, $dados);
        $this->comFlash('sucesso', 'Fornecedor atualizado com sucesso!');
        $this->redirecionar('/fornecedores');
    }

    public function excluir(): void
    {
        $this->exigirAdministrador();

        $id = $this->idDaUri();

        try {
            $ok = (new FornecedorModel())->excluir($id);
        } catch (\Throwable $e) {
            Logger::error('Falha inesperada ao excluir fornecedor', ['id' => $id, 'erro' => $e->getMessage()]);
            $ok = false;
        }

        if ($ok) {
            $this->comFlash('sucesso', 'Fornecedor excluído com sucesso.');
        } else {
            $this->comFlash('erro', 'Não é possível excluir: existem produtos vinculados a esse fornecedor.');
        }

        $this->redirecionar('/fornecedores');
    }

    private function validar(array $dados): array
    {
        $erros = [];

        if (empty($dados['nome'])) {
            $erros['nome'] = 'O nome do fornecedor é obrigatório.';
        }

        if (!empty($dados['email']) && !filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
            $erros['email'] = 'Informe um e-mail válido.';
        }

        return $erros;
    }

    private function idDaUri(): int
    {
        return (int) Router::parametro('id', 0);
    }
}
