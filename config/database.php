<?php
// config/database.php
// Conexão PDO com o banco de dados (Aula 02 - Banco de Dados)
// Ajuste os dados abaixo para o seu ambiente (MySQL ou PostgreSQL).

require_once __DIR__ . '/../core/Logger.php';

class Database
{
    private static ?PDO $instance = null;

    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            $host   = 'localhost';
            $dbname = 'controle_estoque';
            $user   = 'root';
            $pass   = '';

            // Para MySQL:
            $dsn = "mysql:host={$host};dbname={$dbname};charset=utf8mb4";

            // Para PostgreSQL, use em vez da linha acima:
            // $dsn = "pgsql:host={$host};dbname={$dbname}";

            try {
                self::$instance = new PDO($dsn, $user, $pass, [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);
            } catch (PDOException $e) {
                Logger::error('Falha ao conectar ao banco de dados', ['erro' => $e->getMessage()]);
                die("Erro ao conectar ao banco de dados: " . $e->getMessage());
            }
        }

        return self::$instance;
    }
}
