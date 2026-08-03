<?php
require __DIR__ . '/../partials/header.php';
$souAdmin = ($usuarioLogado['perfil'] ?? null) === 'administrador';
?>

<header class="topbar">
    <h1>Estoque &gt; Fornecedores</h1>
    <?php if ($souAdmin): ?>
        <a class="botao" href="/fornecedores/novo">+ Novo Fornecedor</a>
    <?php endif; ?>
</header>

<table class="tabela">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>CNPJ</th>
            <th>Telefone</th>
            <th>E-mail</th>
            <?php if ($souAdmin): ?><th>Ações</th><?php endif; ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($fornecedores as $f): ?>
            <tr>
                <td><?= htmlspecialchars($f['id']) ?></td>
                <td><?= htmlspecialchars($f['nome']) ?></td>
                <td><?= htmlspecialchars($f['cnpj'] ?? '-') ?></td>
                <td><?= htmlspecialchars($f['telefone'] ?? '-') ?></td>
                <td><?= htmlspecialchars($f['email'] ?? '-') ?></td>
                <?php if ($souAdmin): ?>
                    <td class="col-acoes">
                        <a class="link-acao" href="/fornecedores/<?= $f['id'] ?>/editar">Editar</a>
                        <form action="/fornecedores/<?= $f['id'] ?>" method="POST" class="form-inline" onsubmit="return confirm('Excluir este fornecedor?');">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="link-acao link-excluir">Excluir</button>
                        </form>
                    </td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($fornecedores)): ?>
            <tr><td colspan="6">Nenhum fornecedor cadastrado ainda.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<?php require __DIR__ . '/../partials/footer.php'; ?>
