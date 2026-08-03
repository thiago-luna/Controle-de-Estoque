<?php
// app/Models/CategoriaModel.php
// Camada Model: dados da entidade Categoria (Aula 03 - MVC)

require_once __DIR__ . '/../../config/database.php';

class CategoriaModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function listarTodos(): array
    {
        $stmt = $this->db->query("SELECT * FROM categorias ORDER BY nome ASC");
        return $stmt->fetchAll();
    }

    public function buscarPorId(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM categorias WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $categoria = $stmt->fetch();
        return $categoria ?: null;
    }

    public function criar(array $dados): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO categorias (nome, descricao) VALUES (:nome, :descricao)"
        );
        return $stmt->execute([
            'nome'      => $dados['nome'],
            'descricao' => $dados['descricao'] ?? null,
        ]);
    }

    public function atualizar(int $id, array $dados): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE categorias SET nome = :nome, descricao = :descricao WHERE id = :id"
        );
        return $stmt->execute([
            'id'        => $id,
            'nome'      => $dados['nome'],
            'descricao' => $dados['descricao'] ?? null,
        ]);
    }

    /**
     * Exclui uma categoria. Retorna false se houver produtos vinculados
     * (regra de negócio: não permitir excluir categoria em uso), em vez de
     * deixar o banco lançar um erro de violação de chave estrangeira.
     */
    public function excluir(int $id): bool
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM produtos WHERE categoria_id = :id");
        $stmt->execute(['id' => $id]);
        if ((int) $stmt->fetchColumn() > 0) {
            return false;
        }

        $stmt = $this->db->prepare("DELETE FROM categorias WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
