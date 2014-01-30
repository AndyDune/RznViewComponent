<?php
/**
 * Andrey Ryzhov (http://rznw.ru/)
 *
 * @link      http://github.com/AndyDune/RznViewComponent for the canonical source repository
 * @license   The MIT License (MIT)
 */

namespace RznViewComponent;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module
{

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }


    public function getViewHelperConfig()
    {
        return array(
            'invokables' => array(
                'includeComponent' => 'RznViewComponent\View\Helper\IncludeComponent',
            ),
        );
    }

    public function getServiceConfig()
    {
        return array (
            'factories' => array(
                'cache_view_component' => function($sm) {
                        $config = $sm->get('config');
                        if (isset($config['rznviewcomponent']['cache_adapter']))
                        {
                            $adapter = $config['rznviewcomponent']['cache_adapter'];
                        }
                        else
                            $adapter = array(
                                'name' => 'filesystem',
                                'options' => array(
                                    'ttl' => 3600,
                                    'dirLevel' => 2,
                                    'file_locking' => false,
                                    'cacheDir' => 'data/cache',
                                    'dirPermission' => 0755,
                                    'filePermission' => 0666,
                                ),
                            );

                        return \Zend\Cache\StorageFactory::factory(
                            array(
                                'adapter' => $adapter,
                                'plugins' => array('serializer'),
                            )
                        );
                    }
            )
        );
    }


    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

}
