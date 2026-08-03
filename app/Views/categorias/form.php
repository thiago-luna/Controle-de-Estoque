<?php
require __DIR__ . '/../partials/header.php';
$editando = !empty($categoria['id']);
$acaoUrl = $editando ? '/categorias/' . $categoria['id'] : '/categorias';
?>

<header class="topbar">
    <h1>Estoque &gt; Categorias &gt; <?= $editando ? 'Editar Categoria' : 'Nova Categoria' ?></h1>
</header>

<form class="formulario" action="<?= $acaoUrl ?>" method="POST">
    <?php if ($editando): ?><input type="hidden" name="_method" value="PUT"><?php endif; ?>

    <label>Nome</label>
    <input type="text" name="nome" value="<?= htmlspecialchars($categoria['nome'] ?? '') ?>" required>
    <?php if (!empty($erros['nome'])): ?><span class="erro-campo"><?= htmlspecialchars($erros['nome']) ?></span><?php endif; ?>

    <label>Descrição</label>
    <input type="text" name="descricao" value="<?= htmlspecialchars($categoria['descricao'] ?? '') ?>">

    <button type="submit" class="botao"><?= $editando ? 'Salvar alterações' : 'Salvar' ?></button>
    <a class="botao botao-cancelar" href="/categorias">Cancelar</a>
</form>

<?php require __DIR__ . '/../partials/footer.php'; ?>
