<?php require __DIR__ . '/../partials/header.php'; ?>

<header class="topbar">
    <h1>Estoque &gt; Movimentações</h1>
    <a class="botao" href="/movimentacoes/nova">+ Nova Movimentação</a>
</header>

<table class="tabela">
    <thead>
        <tr>
            <th>Data</th>
            <th>Produto</th>
            <th>Tipo</th>
            <th>Quantidade</th>
            <th>Usuário</th>
            <th>Observação</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($movimentacoes as $m): ?>
            <tr>
                <td><?= htmlspecialchars($m['data']) ?></td>
                <td><?= htmlspecialchars($m['produto_nome']) ?></td>
                <td>
                    <span class="badge badge-<?= $m['tipo'] === 'entrada' ? 'entrada' : 'saida' ?>">
                        <?= $m['tipo'] === 'entrada' ? 'Entrada' : 'Saída' ?>
                    </span>
                </td>
                <td><?= (int) $m['quantidade'] ?></td>
                <td><?= htmlspecialchars($m['usuario_nome']) ?></td>
                <td><?= htmlspecialchars($m['observacao'] ?? '-') ?></td>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($movimentacoes)): ?>
            <tr><td colspan="6">Nenhuma movimentação registrada ainda.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<?php require __DIR__ . '/../partials/footer.php'; ?>
