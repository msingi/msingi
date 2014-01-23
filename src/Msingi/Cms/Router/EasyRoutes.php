<?php

namespace Msingi\Cms\Router;

/**
 * Class EasyRoutes
 * @package Msingi\Cms\Router
 */
class EasyRoutes
{
    /**
     * @param $config
     */
    public static function generateEasyRoutes($routesSpec)
    {
        $easyRoutes = EasyRoutes::r($routesSpec, 'frontend/');

        return $easyRoutes;
    }

    /**
     * @param $r
     */
    protected static function r($r, $p)
    {
        $routes = array();
        foreach ($r as $routeName => $routeSpec) {

            $route = array(
                'type' => 'Literal',
                'options' => array(
                    'defaults' => array(
                        'controller' => EasyRoutes::formatControllerName($p . $routeName),
                        'action' => 'index',
                    ),
                ),
            );

            if (is_string($routeSpec)) {
                switch ($routeSpec) {
                    case '*':
                        $route['options']['route'] = '/' . $routeName;
                        $route['options']['may_terminate'] = true;
                        break;
                    case 'action':
                        $route['type'] = 'Segment';
                        $route['options']['route'] = '[:action]';
                        $route['options']['constraints'] = array(
                            'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        );
                        break;
                    default:
                        $route['options']['route'] = $routeSpec;
                        $route['options']['may_terminate'] = true;
                        break;
                }
            } else if (is_array($routeSpec)) {
                $route['options']['route'] = '/' . $routeName;
                $route['child_routes'] = EasyRoutes::r($routeSpec, $p . $routeName . '/');
            }

            $routes[$routeName] = $route;
        }

        return $routes;
    }

    /**
     * @param $routeName
     */
    protected static function formatControllerName($routeName)
    {
        $controllerName = preg_replace('/[^a-z0-9-]/i', '-', $routeName);
        return $controllerName;
    }


}