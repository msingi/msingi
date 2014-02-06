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
            } else if ($type == 'Action') {

            } else if ($type == 'Segment') {

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


//            $route = array(
//                'type' => 'Literal',
//                'options' => array(
//                    'defaults' => array(
//                        'controller' => EasyRoutes::formatControllerName($parent . $routeName),
//                        'action' => 'index',
//                    ),
//                ),
//            );
//
//            if (is_string($routeSpec)) {
//                switch ($routeSpec) {
//                    case '*':
//                        $route['options']['route'] = '/' . $routeName;
////                        $route['options']['may_terminate'] = true;
//                        break;
//                    case 'action':
//                        $route['type'] = 'Segment';
//                        $route['options']['route'] = '[:action]';
//                        $route['options']['constraints'] = array(
//                            'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
//                        );
//                        break;
//                    default:
//                        $route['options']['route'] = $routeSpec;
////                        $route['options']['may_terminate'] = true;
//                        break;
//                }
//            } else if (is_array($routeSpec)) {
//                $route['options']['route'] = '/' . $routeName;
//                $route['may_terminate'] = true;
//                $route['child_routes'] = EasyRoutes::r($routeSpec, $parent . $routeName . '/');
//            }
//
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