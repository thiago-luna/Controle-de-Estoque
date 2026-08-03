<?php require __DIR__ . '/../partials/header.php'; ?>

<header class="topbar">
    <h1>Estoque &gt; Movimentações &gt; Nova Movimentação</h1>
</header>

<?php if ($erro): ?>
    <div class="flash flash-erro"><?= htmlspecialchars($erro) ?></div>
<?php endif; ?>

<form class="formulario" action="/movimentacoes" method="POST">
    <label>Produto</label>
    <select name="produto_id" required>
        <option value="">-- Selecione --</option>
        <?php foreach ($produtos as $p): ?>
            <option value="<?= $p['id'] ?>">
                <?= htmlspecialchars($p['nome']) ?> (estoque atual: <?= (int) $p['quantidade'] ?>)
            </option>
        <?php endforeach; ?>
    </select>

    <label>Tipo de movimentação</label>
    <select name="tipo" required>
        <option value="entrada">Entrada</option>
        <option value="saida">Saída</option>
    </select>

    <label>Quantidade</label>
    <input type="number" name="quantidade" min="1" required>

    <label>Observação</label>
    <input type="text" name="observacao" placeholder="Opcional">

    <button type="submit" class="botao">Registrar</button>
    <a class="botao botao-cancelar" href="/movimentacoes">Cancelar</a>
</form>

<?php require __DIR__ . '/../partials/footer.php'; ?>
