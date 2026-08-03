<?php
// app/Models/FornecedorModel.php
// Camada Model: dados da entidade Fornecedor (Aula 03 - MVC)

require_once __DIR__ . '/../../config/database.php';

class FornecedorModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function listarTodos(): array
    {
        $stmt = $this->db->query("SELECT * FROM fornecedores ORDER BY nome ASC");
        return $stmt->fetchAll();
    }

    public function buscarPorId(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM fornecedores WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $fornecedor = $stmt->fetch();
        return $fornecedor ?: null;
    }

    public function criar(array $dados): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO fornecedores (nome, cnpj, telefone, email)
             VALUES (:nome, :cnpj, :telefone, :email)"
        );
        return $stmt->execute([
            'nome'     => $dados['nome'],
            'cnpj'     => $dados['cnpj'] ?? null,
            'telefone' => $dados['telefone'] ?? null,
            'email'    => $dados['email'] ?? null,
        ]);
    }

    public function atualizar(int $id, array $dados): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE fornecedores
                SET nome = :nome, cnpj = :cnpj, telefone = :telefone, email = :email
              WHERE id = :id"
        );
        return $stmt->execute([
            'id'       => $id,
            'nome'     => $dados['nome'],
            'cnpj'     => $dados['cnpj'] ?? null,
            'telefone' => $dados['telefone'] ?? null,
            'email'    => $dados['email'] ?? null,
        ]);
    }

    /**
     * Exclui um fornecedor. Regra de negócio: não permitir excluir
     * fornecedor que ainda tenha produtos vinculados.
     */
    public function excluir(int $id): bool
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM produtos WHERE fornecedor_id = :id");
        $stmt->execute(['id' => $id]);
        if ((int) $stmt->fetchColumn() > 0) {
            return false;
        }

        $stmt = $this->db->prepare("DELETE FROM fornecedores WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
