<?php
class Router {
    private $routes = [];
    
    public function add($method, $path, $controller, $action) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'controller' => $controller,
            'action' => $action
        ];
    }
    
    public function dispatch($method, $uri) {
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $route['path'] === $uri) {
                $controllerName = $route['controller'] . 'Controller';
                require_once "../app/controllers/$controllerName.php";
                $controller = new $controllerName();
                return $controller->{$route['action']}();
            }
        }
        // 404 handling
        http_response_code(404);
        echo "Page not found";
    }
}
?>