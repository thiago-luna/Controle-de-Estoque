<?php require __DIR__ . '/../partials/header.php'; ?>

<div class="caixa-central">
    <h1>Entrar no Sistema</h1>

    <?php if ($erro): ?>
        <div class="flash flash-erro"><?= htmlspecialchars($erro) ?></div>
    <?php endif; ?>

    <form class="formulario" action="/login" method="POST">
        <label>E-mail</label>
        <input type="email" name="email" required autofocus>

        <label>Senha</label>
        <input type="password" name="senha" required>

        <button type="submit" class="botao">Entrar</button>
    </form>

    <p class="texto-auxiliar">Ainda não tem conta? <a href="/registrar">Criar conta</a></p>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
