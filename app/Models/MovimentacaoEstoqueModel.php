<?php
// app/Models/MovimentacaoEstoqueModel.php
// Camada Model: registra entradas/saídas e mantém a quantidade do
// Produto sempre consistente com o histórico (Aula 03/04 - MVC).

require_once __DIR__ . '/../../config/database.php';

class MovimentacaoEstoqueModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function listarTodos(int $limite = 100): array
    {
        $sql = "SELECT m.id, m.tipo, m.quantidade, m.data, m.observacao,
                       p.nome AS produto_nome, u.nome AS usuario_nome
                FROM movimentacoes_estoque m
                JOIN produtos  p ON m.produto_id = p.id
                JOIN usuarios  u ON m.usuario_id = u.id
                ORDER BY m.data DESC, m.id DESC
                LIMIT :limite";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function listarPorProduto(int $produtoId): array
    {
        $stmt = $this->db->prepare(
            "SELECT m.*, u.nome AS usuario_nome
               FROM movimentacoes_estoque m
               JOIN usuarios u ON m.usuario_id = u.id
              WHERE m.produto_id = :produto_id
              ORDER BY m.data DESC, m.id DESC"
        );
        $stmt->execute(['produto_id' => $produtoId]);
        return $stmt->fetchAll();
    }

    /**
     * Registra uma movimentação (entrada ou saída) e atualiza a
     * quantidade do produto na mesma transação, garantindo que o
     * histórico e o saldo do estoque nunca fiquem inconsistentes entre si.
     *
     * Regra de negócio: uma saída nunca pode deixar a quantidade do
     * produto negativa.
     *
     * @throws InvalidArgumentException quando a saída deixaria o estoque negativo.
     */
    public function registrar(int $produtoId, int $usuarioId, string $tipo, int $quantidade, ?string $observacao): bool
    {
        if (!in_array($tipo, ['entrada', 'saida'], true)) {
            throw new InvalidArgumentException('Tipo de movimentação inválido.');
        }

        if ($quantidade <= 0) {
            throw new InvalidArgumentException('A quantidade movimentada deve ser maior que zero.');
        }

        $this->db->beginTransaction();

        try {
            $stmt = $this->db->prepare("SELECT quantidade FROM produtos WHERE id = :id");
            $stmt->execute(['id' => $produtoId]);

            $produto = $stmt->fetch();
            if (!$produto) {
                throw new InvalidArgumentException('Produto não encontrado.');
            }

            $quantidadeAtual = (int) $produto['quantidade'];
            $novaQuantidade = $tipo === 'entrada'
                ? $quantidadeAtual + $quantidade
                : $quantidadeAtual - $quantidade;

            if ($novaQuantidade < 0) {
                throw new InvalidArgumentException(
                    "Saída inválida: o estoque atual ({$quantidadeAtual}) é menor que a quantidade informada."
                );
            }

            $stmt = $this->db->prepare(
                "INSERT INTO movimentacoes_estoque (produto_id, usuario_id, tipo, quantidade, observacao)
                 VALUES (:produto_id, :usuario_id, :tipo, :quantidade, :observacao)"
            );
            $stmt->execute([
                'produto_id'  => $produtoId,
                'usuario_id'  => $usuarioId,
                'tipo'        => $tipo,
                'quantidade'  => $quantidade,
                'observacao'  => $observacao,
            ]);

            $stmt = $this->db->prepare("UPDATE produtos SET quantidade = :quantidade WHERE id = :id");
            $stmt->execute(['quantidade' => $novaQuantidade, 'id' => $produtoId]);

            $this->db->commit();
            return true;
        } catch (\Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
}
