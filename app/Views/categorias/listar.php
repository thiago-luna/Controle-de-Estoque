<?php
require __DIR__ . '/../partials/header.php';
$souAdmin = ($usuarioLogado['perfil'] ?? null) === 'administrador';
?>

<header class="topbar">
    <h1>Estoque &gt; Categorias</h1>
    <?php if ($souAdmin): ?>
        <a class="botao" href="/categorias/novo">+ Nova Categoria</a>
    <?php endif; ?>
</header>

<table class="tabela">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Descrição</th>
            <?php if ($souAdmin): ?><th>Ações</th><?php endif; ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($categorias as $c): ?>
            <tr>
                <td><?= htmlspecialchars($c['id']) ?></td>
                <td><?= htmlspecialchars($c['nome']) ?></td>
                <td><?= htmlspecialchars($c['descricao'] ?? '-') ?></td>
                <?php if ($souAdmin): ?>
                    <td class="col-acoes">
                        <a class="link-acao" href="/categorias/<?= $c['id'] ?>/editar">Editar</a>
                        <form action="/categorias/<?= $c['id'] ?>" method="POST" class="form-inline" onsubmit="return confirm('Excluir esta categoria?');">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="link-acao link-excluir">Excluir</button>
                        </form>
                    </td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($categorias)): ?>
            <tr><td colspan="4">Nenhuma categoria cadastrada ainda.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<?php require __DIR__ . '/../partials/footer.php'; ?>
