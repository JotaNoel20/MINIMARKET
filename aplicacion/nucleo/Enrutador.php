<?php
namespace App\Nucleo;

/**
 * Enrutador de URLs
 * Maneja todas las rutas de la aplicación
 */
class Enrutador
{
    private $rutas = [];
    private $rutasConMiddleware = [];

    /**
     * Registra una ruta GET
     */
    public function get($ruta, $controlador, $middlewares = [])
    {
        $this->agregarRuta('GET', $ruta, $controlador, $middlewares);
    }

    /**
     * Registra una ruta POST
     */
    public function post($ruta, $controlador, $middlewares = [])
    {
        $this->agregarRuta('POST', $ruta, $controlador, $middlewares);
    }

    /**
     * Agrega una ruta al enrutador
     */
    private function agregarRuta($metodo, $ruta, $controlador, $middlewares)
    {
        $this->rutas[$metodo][$ruta] = [
            'controlador' => $controlador,
            'middlewares' => $middlewares
        ];
    }

    /**
     * Despacha la ruta solicitada
     */
    public function despachar($url)
    {
        $metodo = $_SERVER['REQUEST_METHOD'];
        
        // Limpiar URL
        $url = '/' . ltrim($url, '/');
        
        // Buscar coincidencia exacta
        if (isset($this->rutas[$metodo][$url])) {
            $this->ejecutarRuta($this->rutas[$metodo][$url]);
            return;
        }

        // Buscar ruta con parámetros {id}
        foreach ($this->rutas[$metodo] as $patron => $datos) {
            $patronRegex = preg_replace('/\{[a-zA-Z0-9_]+\}/', '([0-9]+)', $patron);
            $patronRegex = '#^' . $patronRegex . '$#';
            
            if (preg_match($patronRegex, $url, $matches)) {
                array_shift($matches);
                $this->ejecutarRuta($datos, $matches);
                return;
            }
        }

        // Si no encuentra la ruta, mostrar 404
        http_response_code(404);
        include RUTA_PUBLICO . '/404.php';
    }

    /**
     * Ejecuta una ruta con sus middlewares
     */
    private function ejecutarRuta($datos, $parametros = [])
    {
        // Ejecutar middlewares
        if (!empty($datos['middlewares'])) {
            foreach ($datos['middlewares'] as $middleware) {
                $clase = 'App\\Middleware\\' . $middleware;
                if (class_exists($clase) && method_exists($clase, 'ejecutar')) {
                    $clase::ejecutar();
                }
            }
        }

        // Ejecutar controlador
        $controlador = $datos['controlador'];
        
        if (is_callable($controlador)) {
            // Si es una función anónima
            echo call_user_func_array($controlador, $parametros);
        } else {
            // Si es [Controlador@metodo]
            list($clase, $metodo) = explode('@', $controlador);
            $claseCompleta = 'App\\Controladores\\' . $clase;
            
            if (class_exists($claseCompleta)) {
                $instancia = new $claseCompleta();
                if (method_exists($instancia, $metodo)) {
                    echo call_user_func_array([$instancia, $metodo], $parametros);
                } else {
                    throw new \Exception("Método $metodo no encontrado en $clase");
                }
            } else {
                throw new \Exception("Controlador $clase no encontrado");
            }
        }
    }
}