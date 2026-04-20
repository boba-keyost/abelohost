<?php declare(strict_types=1);

namespace Router;

use App\Logger;
use Config\Config;
use Exception;
use Extensions\LoggerExtension;
use ReflectionClass;
use Renderers\RendererFabric;
use Router\Routes\DefaultRoute;

class Router extends LoggerExtension{
    protected array $paths = [];

    /**
     * @throws Exception
     */
    public function __construct() {
        $this->registerRoute(new DefaultRoute());
    }
    public function registerRoutes(Route ...$routes): void {
        foreach ($routes as $route) {
            if ($route instanceof Route) {
                $this->registerRoute($route);
            }
        }
    }

    /**
     * @throws Exception
     */
    public function registerRoute(Route $route): void {
        $reflection = new ReflectionClass($route);
        $attributes = $reflection->getAttributes(RouteAttribute::class);
        if (empty($attributes)) {
            throw new Exception("Route attributes not defined");
        }
        foreach ($attributes as $attribute) {
            /* @var RouteAttribute $attr */
            $attr = $attribute->newInstance();
            $path = "/" . trim($attr->path, " \n\r\t\v\0\/");
            $method = strtoupper($attr->method);
            $parameters = $attr->parameters;
            if (!empty($parameters)) {
                $route->setParameters($parameters);
                if (!empty($parameters["renderer"])) {
                    $route->setRenderer(RendererFabric::get($parameters["renderer"]));
                }
            }
            $route->setLogger($this->getLogger());

            if (empty($this->paths[$path])) {
                $this->paths[$path] = [];
            }

            $this->paths[$path][$method] = $route;
        }
    }

    public static function resolvePath(string $routePath, string $path, array &$parameters = []): bool {
        $isReg = false;
        if (str_contains($routePath, "*")) {
            $routePath = str_replace("*", "{-}", $routePath);
        }
        $parameters = [];
        $paramOrder = [];
        if (str_contains($path, "?")) {
            [$path, $query] = explode("?", $path);
        } else {
            $query = "";
        }
        $path = "/" . trim($path, " \n\r\t\v\0\/");
        if (str_contains($routePath, "{") && str_contains($routePath, "}")) {
            $isReg = true;
            $routePath = preg_replace_callback(
                "/\\\\{([\w_\\\\-]+)\\\\}/",
                function ($match) use (&$paramOrder) {
                    $param = str_replace("\\", "", $match[1]);
                    $paramOrder[] = $param;
                    return $param === "-" ? "(.*)" : "(.+)";
                },
                preg_quote($routePath, "/")
            );
        }
        $matches = [];
        if (
            $isReg && preg_match("/^" . $routePath . "$/Ui", $path, $matches)
            || $path === $routePath
        ) {
            foreach ($paramOrder as $k => $param) {
                $paramVal = $matches[$k + 1] ?? null;
                if (!array_key_exists($param, $parameters)) {
                    $parameters[$param] = $paramVal;
                } else {
                    if (!is_array($parameters[$param])) {
                        $parameters[$param] = [$parameters[$param]];
                    }
                    $parameters[$param][] = $paramVal;
                }
            }
            if (!empty($query)) {
                $queryParameters = [];
                parse_str($query, $queryParameters);
                foreach ($queryParameters as $q => $p) {
                    if (array_key_exists($q, $parameters)) {
                        $q = 'query-' . $q;
                    }
                    $parameters[$q] = $p;
                }
            }

            return true;
        }
        return false;
    }

    public function resolve(string $method = "", string $path = "", array &$parameters = []): ?Route {
        $pathList = array_reverse(array_keys($this->paths));
        foreach ($pathList as $p) {
            $resolved = static::resolvePath($p, $path, $parameters);
            if ($resolved) {
                $methods = $this->paths[$p];
                $method = strtoupper($method);
                $route = $methods[$method] ?? $methods[""] ?? null;
                if (!is_null($route)) {
                    return $route;
                }
            }
        }

        return null;
    }

    public function run(?string $method = null, ?string $path = null, mixed $body = null): void
    {
        if (is_null($method)) {
            $method = $_SERVER['REQUEST_METHOD'] ?? "GET";
        }
        if (is_null($path)) {
            $path = $_SERVER['REQUEST_URI'] ?? "/";
        }
        if (is_null($body)) {
            $body = file_get_contents('php://input');
        }
        $parameters = [];
        $route = $this->resolve($method, $path, $parameters);
        if (!is_null($route)) {
            $route->run($parameters, $body);
        }
    }
}