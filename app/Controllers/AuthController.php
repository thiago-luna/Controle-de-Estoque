<?php
// app/Controllers/AuthController.php
// Entrega Parcial 5: Login, logout, sessões e perfis de acesso.

require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../Models/UsuarioModel.php';

class AuthController extends Controller
{
    /** GET /login */
    public function telaLogin(): void
    {
        if ($this->usuarioLogado()) {
            $this->redirecionar('/');
        }

        $this->view('auth/login', [
            'titulo' => 'Entrar no Sistema',
            'erro'   => null,
        ]);
    }

    /** POST /login */
    public function login(): void
    {
        $dados = $this->dadosDoPost();
        $email = $dados['email'] ?? '';
        $senha = $dados['senha'] ?? '';

        $usuario = (new UsuarioModel())->autenticar($email, $senha);

        if (!$usuario) {
            $this->view('auth/login', [
                'titulo' => 'Entrar no Sistema',
                'erro'   => 'E-mail ou senha inválidos.',
            ]);
            return;
        }

        // Regenera o ID de sessão ao autenticar: boa prática de segurança
        // que evita ataques de fixação de sessão (session fixation).
        session_regenerate_id(true);
        $_SESSION['usuario'] = $usuario;

        $destino = $_SESSION['redirect_apos_login'] ?? '/';
        unset($_SESSION['redirect_apos_login']);

        $this->comFlash('sucesso', 'Bem-vindo(a), ' . $usuario['nome'] . '!');
        $this->redirecionar($destino);
    }

    /** GET/POST /logout */
    public function logout(): void
    {
        unset($_SESSION['usuario']);
        session_regenerate_id(true);
        $this->comFlash('sucesso', 'Você saiu do sistema.');
        $this->redirecionar('/login');
    }

    /** GET /registrar */
    public function telaRegistro(): void
    {
        if ($this->usuarioLogado()) {
            $this->redirecionar('/');
        }

        $this->view('auth/registro', [
            'titulo' => 'Criar Conta',
            'erros'  => [],
            'dados'  => [],
        ]);
    }

    /** POST /registrar */
    public function registrar(): void
    {
        $dados = $this->dadosDoPost();
        $erros = $this->validar($dados);

        if (!empty($erros)) {
            $this->view('auth/registro', [
                'titulo' => 'Criar Conta',
                'erros'  => $erros,
                'dados'  => $dados,
            ]);
            return;
        }

        // Auto-cadastro sempre cria um Usuário Comum. A promoção a
        // Administrador é uma ação administrativa feita diretamente no
        // banco de dados, e não uma opção exposta no formulário público
        // (evita que qualquer visitante se autopromova a administrador).
        $model = new UsuarioModel();
        $model->criar([
            'nome'   => $dados['nome'],
            'email'  => $dados['email'],
            'senha'  => $dados['senha'],
            'perfil' => 'usuario_comum',
        ]);

        $this->comFlash('sucesso', 'Conta criada com sucesso! Faça login para continuar.');
        $this->redirecionar('/login');
    }

    private function validar(array $dados): array
    {
        $erros = [];
        $model = new UsuarioModel();

        if (empty($dados['nome'])) {
            $erros['nome'] = 'Informe seu nome.';
        }

        if (empty($dados['email']) || !filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
            $erros['email'] = 'Informe um e-mail válido.';
        } elseif ($model->emailJaCadastrado($dados['email'])) {
            $erros['email'] = 'Já existe uma conta com esse e-mail.';
        }

        if (empty($dados['senha']) || strlen($dados['senha']) < 6) {
            $erros['senha'] = 'A senha deve ter pelo menos 6 caracteres.';
        } elseif (($dados['senha'] ?? '') !== ($dados['senha_confirmacao'] ?? '')) {
            $erros['senha_confirmacao'] = 'As senhas não coincidem.';
        }

        return $erros;
    }
}
