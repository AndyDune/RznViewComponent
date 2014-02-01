<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 01.02.14
 * Time: 12:49
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