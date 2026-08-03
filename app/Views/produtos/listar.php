<?php require __DIR__ . '/../partials/header.php'; ?>

<header class="topbar">
    <h1>Estoque &gt; Produtos</h1>
    <a class="botao" href="/produtos/novo">+ Novo Produto</a>
</header>

<table class="tabela">
    <thead>
        <tr>
            <th>ID</th>
            <th>Produto</th>
            <th>Categoria</th>
            <th>Qtd.</th>
            <th>Preço</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($produtos as $p): ?>
            <tr class="<?= $p['quantidade'] <= $p['estoque_minimo'] ? 'linha-alerta' : '' ?>">
                <td><?= htmlspecialchars($p['id']) ?></td>
                <td><?= htmlspecialchars($p['nome']) ?></td>
                <td><?= htmlspecialchars($p['categoria'] ?? '-') ?></td>
                <td><?= htmlspecialchars($p['quantidade']) ?></td>
                <td>R$ <?= number_format((float) $p['preco_unitario'], 2, ',', '.') ?></td>
                <td class="col-acoes">
                    <a class="link-acao" href="/produtos/<?= $p['id'] ?>/editar">Editar</a>
                    <?php if (($usuarioLogado['perfil'] ?? null) === 'administrador'): ?>
                        <form action="/produtos/<?= $p['id'] ?>" method="POST" class="form-inline" onsubmit="return confirm('Excluir este produto?');">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="link-acao link-excluir">Excluir</button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($produtos)): ?>
            <tr><td colspan="6">Nenhum produto cadastrado ainda.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<p class="legenda">Linhas destacadas indicam produtos com quantidade igual ou abaixo do estoque mínimo.</p>

<?php require __DIR__ . '/../partials/footer.php'; ?>
