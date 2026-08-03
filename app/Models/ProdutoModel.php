<?php
// app/Models/ProdutoModel.php
// Camada Model: dados e regras de negócio da entidade Produto (Aula 03 - MVC)

require_once __DIR__ . '/../../config/database.php';

class ProdutoModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    /**
     * Lista todos os produtos, já com nome da categoria (JOIN).
     * Implementação completa da consulta será usada a partir da
     * Entrega Parcial 3 (CRUD Inicial), quando o banco estiver criado.
     */
    public function listarTodos(): array
    {
        $sql = "SELECT p.id, p.nome, p.quantidade, p.preco_unitario, p.estoque_minimo,
                       c.nome AS categoria
                FROM produtos p
                LEFT JOIN categorias c ON p.categoria_id = c.id
                ORDER BY p.nome ASC";

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function buscarPorId(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM produtos WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $produto = $stmt->fetch();
        return $produto ?: null;
    }

    /**
     * Cria um novo produto (prepared statement - previne SQL Injection,
     * conforme boas práticas vistas na Aula 02/03).
     */
    public function criar(array $dados): bool
    {
        $sql = "INSERT INTO produtos (nome, descricao, categoria_id, fornecedor_id,
                    quantidade, preco_unitario, estoque_minimo)
                VALUES (:nome, :descricao, :categoria_id, :fornecedor_id,
                    :quantidade, :preco_unitario, :estoque_minimo)";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'nome'            => $dados['nome'],
            'descricao'       => $dados['descricao'] ?? null,
            'categoria_id'    => $this->nuloSeVazio($dados['categoria_id'] ?? null),
            'fornecedor_id'   => $this->nuloSeVazio($dados['fornecedor_id'] ?? null),
            'quantidade'      => $dados['quantidade'] ?? 0,
            'preco_unitario'  => $dados['preco_unitario'] ?? 0,
            'estoque_minimo'  => $dados['estoque_minimo'] ?? 0,
        ]);
    }

    /**
     * Formulários HTML enviam campo de seleção não preenchido como string
     * vazia (""), o que violaria a FK caso fosse gravado como está — o
     * banco espera NULL para "sem categoria/fornecedor".
     */
    private function nuloSeVazio($valor)
    {
        return ($valor === '' || $valor === null) ? null : $valor;
    }

    public function atualizar(int $id, array $dados): bool
    {
        $sql = "UPDATE produtos SET nome = :nome, descricao = :descricao,
                    categoria_id = :categoria_id, fornecedor_id = :fornecedor_id,
                    quantidade = :quantidade, preco_unitario = :preco_unitario,
                    estoque_minimo = :estoque_minimo
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id'              => $id,
            'nome'            => $dados['nome'],
            'descricao'       => $dados['descricao'] ?? null,
            'categoria_id'    => $this->nuloSeVazio($dados['categoria_id'] ?? null),
            'fornecedor_id'   => $this->nuloSeVazio($dados['fornecedor_id'] ?? null),
            'quantidade'      => $dados['quantidade'] ?? 0,
            'preco_unitario'  => $dados['preco_unitario'] ?? 0,
            'estoque_minimo'  => $dados['estoque_minimo'] ?? 0,
        ]);
    }

    public function excluir(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM produtos WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
