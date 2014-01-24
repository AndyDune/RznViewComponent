<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 23.01.14
 * Time: 12:09
 */

namespace RznViewComponent\View\Helper;
use Zend\View\Helper\AbstractHelper;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use RznViewComponent\Container\Result;
use RznViewComponent\Service\ComponentInterface;

class IncludeComponent extends AbstractHelper implements ServiceLocatorAwareInterface
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
            $result = $applicationService->get($this->config['cache_service'])->getItem($cacheKey);
            if (!empty($result)) {
                return $result;
                $result = @unserialize($result);
                if ($result)
                {
                    return $result;
                }
            }
        }

        $result = $this-> _getResult($service, $template, $inputData, $params);
        if ($cache) {
            $applicationService->get($this->config['cache_service'])->addItem($cacheKey, $result);
        }
        return $result;
    }


    protected function _buildCacheKey($service, $template, $inputData)
    {
        return md5($service . '__' . $template . '__' . serialize($inputData));
    }

    protected function _getResult($service, $template, $inputData, $params)
    {
        $resultData = array();
        if ($this->serviceLocator->has($service))
            $service = $this->serviceLocator->get($service);
        else
            $service = $this->serviceLocator->getServiceLocator()->get($service);

        if ($service instanceof ComponentInterface)
        {
            $service->setInitialData($inputData);
            $resultData = $service->getResultData();
        }
        else if (isset($params['initial_function']))
        {
            call_user_func(array($service, $params['initial_method']), $inputData);
        }
        else if (isset($params['initial_functions_map']))
        {
            foreach($params['initial_functions_map'] as $key => $value)
            {
                if (isset($inputData[$key]))
                {
                    if (is_array($inputData[$key]))
                        call_user_func_array(array($service, $value), $inputData[$key]);
                    else
                        call_user_func(array($service, $value), $inputData[$key]);
                }
            }
        }

        if (isset($params['result_function']))
        {
            $resultData = call_user_func(array($service, $params['result_function']));
        }
        else if (isset($params['result_functions_map']))
        {
            $resultData = array();
            foreach($params['result_functions_map'] as $key => $value)
            {
                $resultData[$key] = call_user_func(array($service, $value));
            }
        }

        $result = new Result();

        if (isset($params['result_key_return']))
        {
            foreach($params['result_key_return'] as $value)
            {
                if (isset($resultData[$value]))
                    $result[$value] = $resultData[$value];
            }
        }


        $result->setHtml($this->getView()->partial($template, $resultData));

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

        if (isset($config['rznviewcomponent']))
        {
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