<?php
// app/Models/UsuarioModel.php
// Camada Model: dados e autenticação da entidade Usuario (Aula 03 - MVC)
// Regras de negócio de segurança (hash de senha, verificação de senha)
// ficam concentradas aqui, e não no Controller (Aula 04 - Boas Práticas).

require_once __DIR__ . '/../../config/database.php';

class UsuarioModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function buscarPorEmail(string $email): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $usuario = $stmt->fetch();
        return $usuario ?: null;
    }

    public function buscarPorId(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT id, nome, email, perfil FROM usuarios WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $usuario = $stmt->fetch();
        return $usuario ?: null;
    }

    /**
     * Cria um novo usuário. A senha nunca é armazenada em texto puro:
     * password_hash() usa bcrypt para gerar um hash seguro (requisito de
     * Segurança do enunciado da disciplina).
     */
    public function criar(array $dados): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO usuarios (nome, email, senha_hash, perfil)
             VALUES (:nome, :email, :senha_hash, :perfil)"
        );

        return $stmt->execute([
            'nome'       => $dados['nome'],
            'email'      => $dados['email'],
            'senha_hash' => password_hash($dados['senha'], PASSWORD_BCRYPT),
            'perfil'     => $dados['perfil'] ?? 'usuario_comum',
        ]);
    }

    /**
     * Confere e-mail/senha contra o banco. Retorna os dados do usuário
     * (sem o hash da senha) em caso de sucesso, ou null se as credenciais
     * forem inválidas.
     */
    public function autenticar(string $email, string $senha): ?array
    {
        $usuario = $this->buscarPorEmail($email);

        if (!$usuario || !password_verify($senha, $usuario['senha_hash'])) {
            return null;
        }

        unset($usuario['senha_hash']);
        return $usuario;
    }

    public function emailJaCadastrado(string $email): bool
    {
        return $this->buscarPorEmail($email) !== null;
    }
}
