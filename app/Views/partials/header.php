<?php
// app/Views/partials/header.php
// Cabeçalho + menu de navegação, incluído no topo de todas as páginas
// internas. Espera que $titulo e $usuarioLogado estejam disponíveis
// (o Controller base já injeta $usuarioLogado automaticamente).
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($titulo ?? 'Controle de Estoque') ?></title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <nav class="navbar">
        <a class="marca" href="/">📦 Controle de Estoque</a>
        <?php if ($usuarioLogado): ?>
            <div class="nav-links">
                <a href="/produtos">Produtos</a>
                <a href="/categorias">Categorias</a>
                <a href="/fornecedores">Fornecedores</a>
                <a href="/movimentacoes">Movimentações</a>
            </div>
            <div class="nav-usuario">
                <span>
                    <?= htmlspecialchars($usuarioLogado['nome']) ?>
                    <small>(<?= $usuarioLogado['perfil'] === 'administrador' ? 'Administrador' : 'Usuário Comum' ?>)</small>
                </span>
                <form action="/logout" method="POST" class="form-inline">
                    <button type="submit" class="botao botao-sair">Sair</button>
                </form>
            </div>
        <?php else: ?>
            <div class="nav-links">
                <a href="/login">Entrar</a>
                <a href="/registrar">Criar conta</a>
            </div>
        <?php endif; ?>
    </nav>

    <?php if (!empty($flash)): ?>
        <div class="flash flash-<?= htmlspecialchars($flash['tipo']) ?>">
            <?= htmlspecialchars($flash['mensagem']) ?>
        </div>
    <?php endif; ?>

    <main class="conteudo">
