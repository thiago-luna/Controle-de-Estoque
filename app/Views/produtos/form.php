<?php
require __DIR__ . '/../partials/header.php';
$editando = !empty($produto['id']);
$acaoUrl = $editando ? '/produtos/' . $produto['id'] : '/produtos';
?>

<header class="topbar">
    <h1>Estoque &gt; Produtos &gt; <?= $editando ? 'Editar Produto' : 'Novo Produto' ?></h1>
</header>

<form class="formulario" action="<?= $acaoUrl ?>" method="POST">
    <?php if ($editando): ?>
        <input type="hidden" name="_method" value="PUT">
    <?php endif; ?>

    <label>Nome do produto</label>
    <input type="text" name="nome" value="<?= htmlspecialchars($produto['nome'] ?? '') ?>" required>
    <?php if (!empty($erros['nome'])): ?><span class="erro-campo"><?= htmlspecialchars($erros['nome']) ?></span><?php endif; ?>

    <label>Descrição</label>
    <input type="text" name="descricao" value="<?= htmlspecialchars($produto['descricao'] ?? '') ?>">

    <label>Categoria</label>
    <select name="categoria_id">
        <option value="">-- Selecione --</option>
        <?php foreach ($categorias as $c): ?>
            <option value="<?= $c['id'] ?>" <?= (isset($produto['categoria_id']) && (int) $produto['categoria_id'] === (int) $c['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($c['nome']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label>Fornecedor</label>
    <select name="fornecedor_id">
        <option value="">-- Selecione --</option>
        <?php foreach ($fornecedores as $f): ?>
            <option value="<?= $f['id'] ?>" <?= (isset($produto['fornecedor_id']) && (int) $produto['fornecedor_id'] === (int) $f['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($f['nome']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <div class="linha">
        <div>
            <label>Quantidade</label>
            <input type="number" name="quantidade" value="<?= htmlspecialchars($produto['quantidade'] ?? '0') ?>">
            <?php if (!empty($erros['quantidade'])): ?><span class="erro-campo"><?= htmlspecialchars($erros['quantidade']) ?></span><?php endif; ?>
        </div>
        <div>
            <label>Preço unitário</label>
            <input type="number" step="0.01" name="preco_unitario" value="<?= htmlspecialchars($produto['preco_unitario'] ?? '0') ?>">
            <?php if (!empty($erros['preco_unitario'])): ?><span class="erro-campo"><?= htmlspecialchars($erros['preco_unitario']) ?></span><?php endif; ?>
        </div>
        <div>
            <label>Estoque mínimo</label>
            <input type="number" name="estoque_minimo" value="<?= htmlspecialchars($produto['estoque_minimo'] ?? '0') ?>">
            <?php if (!empty($erros['estoque_minimo'])): ?><span class="erro-campo"><?= htmlspecialchars($erros['estoque_minimo']) ?></span><?php endif; ?>
        </div>
    </div>

    <button type="submit" class="botao"><?= $editando ? 'Salvar alterações' : 'Salvar' ?></button>
    <a class="botao botao-cancelar" href="/produtos">Cancelar</a>
</form>

<?php require __DIR__ . '/../partials/footer.php'; ?>
