<?php
// core/Router.php
// Sistema simples de gerenciamento de rotas (Aula 03 - Rotas no MVC)
// Também atua como Front Controller de apoio: recebe toda requisição,
// identifica a rota e direciona para o Controller correto (Aula 04).

class Router
{
    private array $routes = [];

    /**
     * Parâmetros dinâmicos extraídos da URL da rota atual
     * (ex.: ['id' => '7'] para a rota "/produtos/{id}/editar").
     * Os Controllers podem lê-los através de Router::parametro('id').
     */
    private static array $parametros = [];

    public static function parametro(string $nome, $padrao = null)
    {
        return self::$parametros[$nome] ?? $padrao;
    }

    /** Registra uma rota GET. */
    public function get(string $uri, array $action): void
    {
        $this->routes['GET'][$uri] = $action;
    }

    /** Registra uma rota POST. */
    public function post(string $uri, array $action): void
    {
        $this->routes['POST'][$uri] = $action;
    }

    /** Registra uma rota PUT (usada via "method override" em formulários HTML). */
    public function put(string $uri, array $action): void
    {
        $this->routes['PUT'][$uri] = $action;
    }

    /** Registra uma rota DELETE (idem, via "method override"). */
    public function delete(string $uri, array $action): void
    {
        $this->routes['DELETE'][$uri] = $action;
    }

    /**
     * Resolve a URL e o método HTTP atuais, chamando o Controller
     * correspondente.
     *
     * Formulários HTML só enviam GET/POST nativamente. Para permitir rotas
     * PUT/DELETE (mais corretas semanticamente para "atualizar" e
     * "excluir"), aceitamos um campo oculto "_method" no POST que
     * sobrescreve o verbo HTTP real — técnica conhecida como
     * "method override", comum em frameworks MVC.
     */
    public function resolve(): void
    {
        $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
        $uri = rtrim($uri, '/');
        if ($uri === '') {
            $uri = '/';
        }

        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        if ($method === 'POST' && !empty($_POST['_method'])) {
            $override = strtoupper($_POST['_method']);
            if (in_array($override, ['PUT', 'DELETE'], true)) {
                $method = $override;
            }
        }

        // 1) Tenta uma correspondência exata primeiro (rotas estáticas).
        $action = $this->routes[$method][$uri] ?? null;

        // 2) Se não houver, procura entre as rotas com parâmetros
        //    dinâmicos, ex.: "/produtos/{id}/editar" casando com
        //    "/produtos/7/editar" e extraindo id = "7".
        if (!$action) {
            foreach ($this->routes[$method] ?? [] as $padrao => $rotaAction) {
                if (!str_contains($padrao, '{')) {
                    continue;
                }

                $nomesParametros = [];
                $regex = preg_replace_callback('#\{([a-zA-Z_][a-zA-Z0-9_]*)\}#', function ($m) use (&$nomesParametros) {
                    $nomesParametros[] = $m[1];
                    return '([^/]+)';
                }, $padrao);

                if (preg_match('#^' . $regex . '$#', $uri, $valores)) {
                    array_shift($valores); // remove o match completo
                    self::$parametros = array_combine($nomesParametros, $valores);
                    $action = $rotaAction;
                    break;
                }
            }
        }

        if (!$action) {
            http_response_code(404);
            require __DIR__ . '/../app/Views/erros/404.php';
            return;
        }

        [$controllerClass, $methodName] = $action;
        $controller = new $controllerClass();
        $controller->$methodName();
    }
}
