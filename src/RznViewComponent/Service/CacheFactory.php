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


namespace RznViewComponent\Service;
use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CacheFactory implements AbstractFactoryInterface
{
    /**
     * Determine if we can create a service with name
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param $name
     * @param $requestedName
     * @return bool
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        return true;
    }

    /**
     * Create service with name
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param $name
     * @param $requestedName
     * @return mixed
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $config = $serviceLocator->get('config');
        if (isset($config['rznviewcomponent']['cache_adapter']))
        {
            $config = $config['rznviewcomponent']['cache_adapter'];
        }
        else
            $config = array(
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
                'adapter' => $config,
                'plugins' => array('serializer'),
            )
        );
    }
} 