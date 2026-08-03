<?php require __DIR__ . '/partials/header.php'; ?>

<h1><?= htmlspecialchars($titulo) ?></h1>

<?php if ($usuarioLogado): ?>
    <p>Olá, <strong><?= htmlspecialchars($usuarioLogado['nome']) ?></strong>! Aqui está um resumo do seu estoque.</p>

    <div class="cartoes">
        <div class="cartao">
            <span class="cartao-numero"><?= (int) ($totalProdutos ?? 0) ?></span>
            <span class="cartao-legenda">produtos cadastrados</span>
        </div>
        <div class="cartao cartao-alerta">
            <span class="cartao-numero"><?= count($produtosAlerta ?? []) ?></span>
            <span class="cartao-legenda">produto(s) abaixo do estoque mínimo</span>
        </div>
    </div>

    <?php if (!empty($produtosAlerta)): ?>
        <h2>Alertas de estoque mínimo</h2>
        <ul class="lista-alerta">
            <?php foreach ($produtosAlerta as $p): ?>
                <li><?= htmlspecialchars($p['nome']) ?> — restam <?= (int) $p['quantidade'] ?> (mínimo: <?= (int) $p['estoque_minimo'] ?>)</li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <div class="atalhos">
        <a class="botao" href="/produtos/novo">+ Novo Produto</a>
        <a class="botao" href="/movimentacoes/nova">+ Registrar Movimentação</a>
    </div>
<?php else: ?>
    <p>
        Aplicação web em PHP, seguindo a arquitetura MVC, para gerenciar produtos,
        categorias, fornecedores e movimentações de entrada/saída de um estoque.
    </p>
    <div class="atalhos">
        <a class="botao" href="/login">Entrar</a>
        <a class="botao botao-cancelar" href="/registrar">Criar conta</a>
    </div>
<?php endif; ?>

<?php require __DIR__ . '/partials/footer.php'; ?>
