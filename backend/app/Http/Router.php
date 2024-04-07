<?php

namespace App\Http;

use \Closure;
use \Exception;
use \ReflectionFunction;

class Router {

    /**
     * URL completa do projeto
     * @var string
     */
    private $url = '';

    /**
     * Prefixo de todas as rotas
     * @var string
     */
    private $prefix = '';

    /**
     * Índice de rotas
     * @var array
     */
    private $routes = [];

    /**
     * Instancia de Request
     * @var Request
     */
    private $request;

    /**
     * Método responsável por iniciar a classe
     * @param string $url
     */
    public function __construct($url) {
        $this->request = new Request();
        $this->url = $url;
        $this->setPrefix();
    }

    /**
     * Método responsável por definir o prefixo das rotas
     */
    private function setPrefix() {
        // Informações da URL
        $parseUrl = parse_url($this->url);

        $this->prefix = $parseUrl['path'] ?? '';
    }

    /**
     * Método responsável por adicionar uma rota na classe
     * @param string $method
     * @param string $route
     * @param array $params
     */
    private function addRoute($method, $route, $params = []) {
        // Validação dos parametros
        foreach ($params as $key => $value) {
            if ($value instanceof Closure) {
                $params['controller'] = $value;
                unset($params[$key]);
                continue;
            }
        }

        // Variáveis da rota
        $params['variables'] = [];

        $patternVariables = '/{(.*?)}/';
        if (preg_match_all($patternVariables, $route, $matches)) {
            $route = preg_replace($patternVariables, '(.*?)', $route);
            $params['variables'] = $matches[1];
        }

        // Padrão de validação da URL
        $patternRoute = '/^' . str_replace('/', '\/', $route) . '(?:\?(?!.*\/).*)?$/';

        // Adiciona a rota dentro da classe
        $this->routes[$patternRoute][$method] = $params;
    }

    /**
     * Método responsável por definir uma rota GET
     * @param string $route
     * @param array $params
     */
    public function get($route, $params = []) {
        return $this->addRoute('GET', $route, $params);
    }

    /**
     * Método responsável por definir uma rota POST
     * @param string $route
     * @param array $params
     */
    public function post($route, $params = []) {
        return $this->addRoute('POST', $route, $params);
    }

    /**
     * Método responsável por definir uma rota PUT
     * @param string $route
     * @param array $params
     */
    public function put($route, $params = []) {
        return $this->addRoute('PUT', $route, $params);
    }

    /**
     * Método responsável por definir uma rota DELETE
     * @param string $route
     * @param array $params
     */
    public function delete($route, $params = []) {
        return $this->addRoute('DELETE', $route, $params);
    }

    /**
     * Responsável por retornar a URI desconsiderando o prefixo
     * @return string
     */
    private function getUri() {
        // Uri da request
        $uri = $this->request->getUri();

        // Fatia a uri com prefixo
        $xUri = strlen($this->prefix) ? explode($this->prefix, $uri) : [$uri];

        // Retorna a uri sem prefixo
        return end($xUri);
    }

    /**
     * Método responsável por retornar os dados da rota atual
     * @return array
     */
    private function getRoute() {
        // URI
        $uri = $this->getUri();

        // Método
        $httpMethod = $this->request->getHttpMethod();

        // Valida as rotas
        foreach($this->routes as $patternRoute => $methods) {
            // Verifica se a URI bate com o padrão

            if (preg_match($patternRoute, $uri, $matches)) {
                
                if ($methods[$httpMethod]) {
                    // Remove a primeira posição
                    unset($matches[0]);

                    // Variáveis processadas
                    $keys = $methods[$httpMethod]['variables'];
                    $methods[$httpMethod]['variables'] = array_combine($keys, $matches);
                    $methods[$httpMethod]['variables']['request'] = $this->request;

                    // Retorno dos parametros da rota
                    return $methods[$httpMethod];
                }

                throw new Exception('Método não permitido', 405);
            }

            throw new Exception('URL não encontrada', 404);
        }
    }

    /**
     * Método responsável por executar a rota atual
     * @return Response
     */
    public function run() {
        try {
            // Obtém a rota atual
            $route = $this->getRoute();

            // Verifica o controlador
            if (!isset($route['controller'])) {
                throw new Exception('A URL não pode ser processada', 500);
            }

            // Argumentos da função
            $args = [];

            // Reflection
            $reflection = new ReflectionFunction($route['controller']);

            foreach($reflection->getParameters() as $parameter) {
                $name = $parameter->getName();
                $args[$name] = $route['variables'][$name] ?? '';
            }
            
            return call_user_func_array($route['controller'], $args);
        } catch (Exception $e) {
            return new Response($e->getCode(), $e->getMessage());
        }
    }
}