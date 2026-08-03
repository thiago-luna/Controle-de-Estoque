<?php
// app/Controllers/CategoriaController.php
// CRUD de Categorias. Gestão de categorias é restrita ao Administrador,
// conforme perfis definidos na modelagem (Entrega Parcial 1).

require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../../core/Router.php';
require_once __DIR__ . '/../../core/Logger.php';
require_once __DIR__ . '/../Models/CategoriaModel.php';

class CategoriaController extends Controller
{
    public function index(): void
    {
        $this->exigirLogin();

        $categorias = (new CategoriaModel())->listarTodos();
        $this->view('categorias/listar', [
            'titulo'     => 'Categorias',
            'categorias' => $categorias,
        ]);
    }

    public function novo(): void
    {
        $this->exigirAdministrador();

        $this->view('categorias/form', [
            'titulo'    => 'Nova Categoria',
            'categoria' => null,
            'erros'     => [],
        ]);
    }

    public function editar(): void
    {
        $this->exigirAdministrador();

        $id = $this->idDaUri();
        $categoria = (new CategoriaModel())->buscarPorId($id);

        if (!$categoria) {
            $this->comFlash('erro', 'Categoria não encontrada.');
            $this->redirecionar('/categorias');
        }

        $this->view('categorias/form', [
            'titulo'    => 'Editar Categoria',
            'categoria' => $categoria,
            'erros'     => [],
        ]);
    }

    public function salvar(): void
    {
        $this->exigirAdministrador();

        $dados = $this->dadosDoPost();
        $erros = $this->validar($dados);

        if (!empty($erros)) {
            $this->view('categorias/form', [
                'titulo'    => 'Nova Categoria',
                'categoria' => $dados,
                'erros'     => $erros,
            ]);
            return;
        }

        (new CategoriaModel())->criar($dados);
        $this->comFlash('sucesso', 'Categoria cadastrada com sucesso!');
        $this->redirecionar('/categorias');
    }

    public function atualizar(): void
    {
        $this->exigirAdministrador();

        $id = $this->idDaUri();
        $dados = $this->dadosDoPost();
        $erros = $this->validar($dados);

        if (!empty($erros)) {
            $dados['id'] = $id;
            $this->view('categorias/form', [
                'titulo'    => 'Editar Categoria',
                'categoria' => $dados,
                'erros'     => $erros,
            ]);
            return;
        }

        (new CategoriaModel())->atualizar($id, $dados);
        $this->comFlash('sucesso', 'Categoria atualizada com sucesso!');
        $this->redirecionar('/categorias');
    }

    public function excluir(): void
    {
        $this->exigirAdministrador();

        $id = $this->idDaUri();

        try {
            $ok = (new CategoriaModel())->excluir($id);
        } catch (\Throwable $e) {
            Logger::error('Falha inesperada ao excluir categoria', ['id' => $id, 'erro' => $e->getMessage()]);
            $ok = false;
        }

        if ($ok) {
            $this->comFlash('sucesso', 'Categoria excluída com sucesso.');
        } else {
            $this->comFlash('erro', 'Não é possível excluir: existem produtos cadastrados nessa categoria.');
        }

        $this->redirecionar('/categorias');
    }

    private function validar(array $dados): array
    {
        $erros = [];
        if (empty($dados['nome'])) {
            $erros['nome'] = 'O nome da categoria é obrigatório.';
        } elseif (strlen($dados['nome']) > 100) {
            $erros['nome'] = 'O nome pode ter no máximo 100 caracteres.';
        }
        return $erros;
    }

    private function idDaUri(): int
    {
        return (int) Router::parametro('id', 0);
    }
}
