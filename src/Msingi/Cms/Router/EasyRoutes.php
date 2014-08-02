<?php

namespace Msingi\Cms\Router;

/**
 * Class EasyRoutes
 *
 * Generate routes in easy way
 *
 * @package Msingi\Cms\Router
 */
class EasyRoutes
{
    /**
     * @param array $config
     * @param string $section "frontend" or "backend"
     */
    public static function updateConfig($config, $section)
    {
        if (!isset($config['easy_routes'])) {
            return;
        }

        $easyRoutes = static::generateEasyRoutes($config['easy_routes'], $section);
        if (isset($config['router']['routes'][$section]['child_routes'])) {
            $config['router']['routes'][$section]['child_routes'] = array_merge_recursive($config['router']['routes'][$section]['child_routes'], $easyRoutes);
        } else {
            $config['router']['routes'][$section]['child_routes'] = $easyRoutes;
        }

        return $config;
    }

    /**
     * @param array $routesSpec
     * @param string section
     * @return array
     */
    protected static function generateEasyRoutes($routesSpec, $section)
    {
        $easyRoutes = static::genereateRecursive($routesSpec, $section);

        return $easyRoutes;
    }

    /**
     * @param array $routesSpec
     * @param string $parent
     * @return array
     */
    protected static function genereateRecursive($routesSpec, $parent)
    {
        $routes = array();
        foreach ($routesSpec as $routePath => $routeSpec) {

            $type = isset($routeSpec['type']) ? $routeSpec['type'] : 'Literal';
            $controller = isset($routeSpec['controller']) ? $routeSpec['controller'] : static::formatControllerName($parent . '/' . $routePath);

            $route = array(
                'type' => $type,
                'may_terminate' => false,
                'options' => array(
                    'route' => '',
                    'defaults' => array(
                        'controller' => $controller,
                        'action' => 'index',
                    ),
                ),
                'child_routes' => array(),
            );

            if ($type == 'Root') {
                $route['type'] = 'Literal';
                $route['may_terminate'] = true;
                $route['options']['route'] = '/';
            } else if ($type == 'StaticPage') {
                $route['type'] = 'Msingi\Cms\Router\StaticPage';
                $route['may_terminate'] = true;
                $route['options']['defaults']['action'] = 'page';
            } else if ($type == 'Literal') {
                $route['may_terminate'] = true;
                $route['options']['route'] = '/' . $routePath;
                if (isset($routeSpec['defaults'])) {
                    $route['options']['defaults'] = array_merge($route['options']['defaults'], $routeSpec['defaults']);
                }
            } else if ($type == 'Action') {

            } else if ($type == 'Segment') {
                $route['type'] = 'Segment';
                $route['may_terminate'] = true;
                $route['options']['route'] = $routeSpec['route'];
                $route['options']['defaults'] = array_merge($route['options']['defaults'], $routeSpec['defaults']);
                $route['options']['constraints'] = $routeSpec['constraints'];
            }

            if (isset($routeSpec['actions'])) {
                foreach ($routeSpec['actions'] as $action) {
                    if ($action == 'index') {
                        continue;
                    }

                    $actionRoute = array(
                        'type' => 'Literal',
                        'may_terminate' => true,
                        'options' => array(
                            'route' => '/' . $action,
                            'defaults' => array(
                                'controller' => $controller,
                                'action' => $action,
                            ),
                        ),
                    );

                    $route['child_routes'][$action] = $actionRoute;
                }
            }

            if (isset($routeSpec['child_routes'])) {
                $route['child_routes'] = array_merge($route['child_routes'], static::genereateRecursive($routeSpec['child_routes'], $parent . '/' . $routePath));
            }

            $routes[$routePath] = $route;
        }

        return $routes;
    }

    /**
     * Generate controller name from route
     *
     * @param string $routeName
     * @return string
     */
    protected static function formatControllerName($routeName)
    {
        $controllerName = strtolower(str_replace('/', '-', $routeName));

        return $controllerName;
    }
}
