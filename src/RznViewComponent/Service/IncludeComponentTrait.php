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
use Zend\ServiceManager\ServiceLocatorInterface;
use RznViewComponent\Container\Result;

trait IncludeComponentTrait
{

    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * Configuration array.
     *
     * @var array
     */
    protected $config = array();


    /**
     * @param $service aim service name
     * @param $template template for render after service data return
     * @param array $inputData data wich will be set to the service
     * @param array $params parameters
     * @return Result
     */
    public function __invoke($service, $template, $inputData = array(), $params = array())
    {
        $applicationService = $this->serviceLocator->getServiceLocator();
        if ($this->config['cache_allow'] and $this->config['cache_service'])
            $cache = $this->config['cache_service'];
        else
            $cache = false;

        if ($cache) {
            $cacheKey = $this->_buildCacheKey($service, $template, $inputData);
            /** @var \Zend\Cache\Storage\StorageInterface $cacheService */
            $cacheService = $applicationService->get($this->config['cache_service']);
            if ($this->config['cache_remove_item_key']
                and isset($_GET[$this->config['cache_remove_item_key']])
                and $_GET[$this->config['cache_remove_item_key']]) {
                $cacheService->removeItem($cacheKey);
                $cache = false;
            }
            else {
                $result = $cacheService->getItem($cacheKey);
                if (!empty($result)) {
                    return $result;
                    $result = @unserialize($result);
                    if ($result) {
                        return $result;
                    }
                }

            }
        }

        $result = $this-> _getResult($service, $template, $inputData, $params);
        if ($cache) {
            $applicationService->get($this->config['cache_service'])->addItem($cacheKey, $result);
        }
        return $result;
    }

    /**
     * Set the service locator.
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return CustomHelper
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;

        $config = $serviceLocator->getServiceLocator()->get('config');

        if (isset($config['rznviewcomponent'])) {
            $this->config = $config['rznviewcomponent'];
        }

        return $this;
    }

    /**
     * Get the service locator.
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }
} 