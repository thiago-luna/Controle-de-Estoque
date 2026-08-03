<?php
// core/Logger.php
// Registro de erros em arquivo (Aula 08 - Boas Práticas para Update e
// Delete: "Registrar erros — facilita manutenção do sistema").
//
// Usada nos pontos onde os Controllers/Models capturam exceções ou
// falhas inesperadas, para que o desenvolvedor consiga investigar
// problemas depois, sem depender de o usuário descrever o que viu na
// tela. Mensagens voltadas ao USUÁRIO continuam sendo as flash messages
// já existentes (comFlash); o Logger é para o desenvolvedor.

class Logger
{
    private static string $arquivo = __DIR__ . '/../storage/logs/app.log';

    public static function error(string $mensagem, array $contexto = []): void
    {
        self::escrever('ERROR', $mensagem, $contexto);
    }

    public static function warning(string $mensagem, array $contexto = []): void
    {
        self::escrever('WARNING', $mensagem, $contexto);
    }

    public static function info(string $mensagem, array $contexto = []): void
    {
        self::escrever('INFO', $mensagem, $contexto);
    }

    private static function escrever(string $nivel, string $mensagem, array $contexto): void
    {
        $diretorio = dirname(self::$arquivo);
        if (!is_dir($diretorio)) {
            mkdir($diretorio, 0775, true);
        }

        $usuario = $_SESSION['usuario']['email'] ?? 'visitante';
        $linha = sprintf(
            "[%s] [%s] usuario=%s %s%s" . PHP_EOL,
            date('Y-m-d H:i:s'),
            $nivel,
            $usuario,
            $mensagem,
            $contexto ? ' | ' . json_encode($contexto, JSON_UNESCAPED_UNICODE) : ''
        );

        // FILE_APPEND + LOCK_EX: evita que duas requisições simultâneas
        // corrompam o arquivo ao escreverem ao mesmo tempo.
        file_put_contents(self::$arquivo, $linha, FILE_APPEND | LOCK_EX);
    }
}
