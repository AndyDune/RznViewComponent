<?php
/**
 * Copyright (c) 2014 Andrey Ryzhov.
 * All rights reserved.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package     RznViewComponent
 * @author      Andrey Ryzhov <info@rznw.ru>
 * @copyright   2014 Andrey Ryzhov.
 * @license     http://www.opensource.org/licenses/mit-license.php  MIT License
 * @link https://github.com/AndyDune/RznViewComponent for the canonical source repository
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

    public function getControllerPluginConfig()
    {
        return array (
            'invokables' => array(
                'includeComponent' => 'RznViewComponent\Service\IncludeControllerComponent',
            )
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
