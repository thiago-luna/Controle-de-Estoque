<?php
// core/Controller.php
// Controller base: outros Controllers herdam dele (Aula 01 - Herança)
// Concentra funções utilitárias comuns (view, redirect, sessão, flash messages)
// para que os Controllers filhos fiquem pequenos e só coordenem ações
// (Aula 04 - Boas Práticas).

class Controller
{
    /**
     * Carrega uma View, passando dados para ela.
     * Injeta automaticamente o usuário logado (se houver) e as mensagens
     * "flash" (avisos de sucesso/erro de uma ação anterior).
     */
    protected function view(string $viewName, array $dados = []): void
    {
        $dados['usuarioLogado'] = $this->usuarioLogado();
        $dados['flash'] = $this->getFlash();

        extract($dados);
        $viewPath = __DIR__ . '/../app/Views/' . $viewName . '.php';

        if (!file_exists($viewPath)) {
            echo "View '{$viewName}' não encontrada em app/Views.";
            return;
        }

        require $viewPath;
    }

    /**
     * Redireciona o usuário para outra rota e encerra a execução.
     */
    protected function redirecionar(string $uri): void
    {
        header('Location: ' . $uri);
        exit;
    }

    /**
     * Guarda uma mensagem para ser exibida uma única vez na próxima página
     * (padrão "flash message"), útil para avisos de sucesso/erro após um
     * redirecionamento (ex.: "Produto salvo com sucesso!").
     */
    protected function comFlash(string $tipo, string $mensagem): static
    {
        $_SESSION['flash'] = ['tipo' => $tipo, 'mensagem' => $mensagem];
        return $this;
    }

    private function getFlash(): ?array
    {
        if (!empty($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            unset($_SESSION['flash']);
            return $flash;
        }
        return null;
    }

    /**
     * Retorna os dados do usuário logado (array) ou null se não houver
     * ninguém autenticado na sessão atual.
     */
    protected function usuarioLogado(): ?array
    {
        return $_SESSION['usuario'] ?? null;
    }

    /**
     * Proteção de rota (Entrega Parcial 5): garante que exista um usuário
     * autenticado na sessão. Caso contrário, redireciona para o login.
     */
    protected function exigirLogin(): void
    {
        if (!$this->usuarioLogado()) {
            $_SESSION['redirect_apos_login'] = $_SERVER['REQUEST_URI'] ?? '/';
            $this->comFlash('erro', 'Você precisa entrar com sua conta para acessar essa página.');
            $this->redirecionar('/login');
        }
    }

    /**
     * Proteção de rota por perfil: algumas ações (gerenciar categorias,
     * fornecedores, excluir produtos) são restritas ao Administrador.
     */
    protected function exigirAdministrador(): void
    {
        $this->exigirLogin();

        if (($this->usuarioLogado()['perfil'] ?? null) !== 'administrador') {
            http_response_code(403);
            $this->comFlash('erro', 'Apenas administradores podem acessar essa área.');
            $this->redirecionar('/');
        }
    }

    /**
     * Lê o corpo de dados de um POST, aplicando trim em todos os valores
     * de texto (higienização simples de entrada do usuário).
     */
    protected function dadosDoPost(): array
    {
        return array_map(
            fn($valor) => is_string($valor) ? trim($valor) : $valor,
            $_POST
        );
    }
}
