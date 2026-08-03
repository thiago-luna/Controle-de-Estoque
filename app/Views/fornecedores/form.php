<?php
require __DIR__ . '/../partials/header.php';
$editando = !empty($fornecedor['id']);
$acaoUrl = $editando ? '/fornecedores/' . $fornecedor['id'] : '/fornecedores';
?>

<header class="topbar">
    <h1>Estoque &gt; Fornecedores &gt; <?= $editando ? 'Editar Fornecedor' : 'Novo Fornecedor' ?></h1>
</header>

<form class="formulario" action="<?= $acaoUrl ?>" method="POST">
    <?php if ($editando): ?><input type="hidden" name="_method" value="PUT"><?php endif; ?>

    <label>Nome</label>
    <input type="text" name="nome" value="<?= htmlspecialchars($fornecedor['nome'] ?? '') ?>" required>
    <?php if (!empty($erros['nome'])): ?><span class="erro-campo"><?= htmlspecialchars($erros['nome']) ?></span><?php endif; ?>

    <label>CNPJ</label>
    <input type="text" name="cnpj" value="<?= htmlspecialchars($fornecedor['cnpj'] ?? '') ?>">

    <label>Telefone</label>
    <input type="text" name="telefone" value="<?= htmlspecialchars($fornecedor['telefone'] ?? '') ?>">

    <label>E-mail</label>
    <input type="email" name="email" value="<?= htmlspecialchars($fornecedor['email'] ?? '') ?>">
    <?php if (!empty($erros['email'])): ?><span class="erro-campo"><?= htmlspecialchars($erros['email']) ?></span><?php endif; ?>

    <button type="submit" class="botao"><?= $editando ? 'Salvar alterações' : 'Salvar' ?></button>
    <a class="botao botao-cancelar" href="/fornecedores">Cancelar</a>
</form>

<?php require __DIR__ . '/../partials/footer.php'; ?>
