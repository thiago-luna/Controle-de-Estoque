<?php require __DIR__ . '/../partials/header.php'; ?>

<div class="caixa-central">
    <h1>Criar Conta</h1>
    <p class="texto-auxiliar">Novas contas são criadas com o perfil "Usuário Comum".</p>

    <form class="formulario" action="/registrar" method="POST">
        <label>Nome</label>
        <input type="text" name="nome" value="<?= htmlspecialchars($dados['nome'] ?? '') ?>" required>
        <?php if (!empty($erros['nome'])): ?><span class="erro-campo"><?= htmlspecialchars($erros['nome']) ?></span><?php endif; ?>

        <label>E-mail</label>
        <input type="email" name="email" value="<?= htmlspecialchars($dados['email'] ?? '') ?>" required>
        <?php if (!empty($erros['email'])): ?><span class="erro-campo"><?= htmlspecialchars($erros['email']) ?></span><?php endif; ?>

        <label>Senha</label>
        <input type="password" name="senha" required minlength="6">
        <?php if (!empty($erros['senha'])): ?><span class="erro-campo"><?= htmlspecialchars($erros['senha']) ?></span><?php endif; ?>

        <label>Confirmar senha</label>
        <input type="password" name="senha_confirmacao" required minlength="6">
        <?php if (!empty($erros['senha_confirmacao'])): ?><span class="erro-campo"><?= htmlspecialchars($erros['senha_confirmacao']) ?></span><?php endif; ?>

        <button type="submit" class="botao">Criar conta</button>
    </form>

    <p class="texto-auxiliar">Já tem conta? <a href="/login">Entrar</a></p>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
