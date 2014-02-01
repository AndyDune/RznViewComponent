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
use RznViewComponent\Container\Result;
use RznViewComponent\Service\ComponentInterface;
use RznViewComponent\Service\IncludeComponentTrait;

class IncludeComponent extends AbstractHelper implements ServiceLocatorAwareInterface
{
    use IncludeComponentTrait;


    protected function _getResult($service, $template, $inputData, $params)
    {
        $resultData = array();
        if ($this->serviceLocator->has($service))
            $service = $this->serviceLocator->get($service);
        else
            $service = $this->serviceLocator->getServiceLocator()->get($service);

        if ($service instanceof ComponentInterface) {
            $service->setInitialData($inputData);
            $resultData = $service->getResultData();
        }
        else if (isset($params['initial_function'])) {
            call_user_func(array($service, $params['initial_method']), $inputData);
        }
        else if (isset($params['initial_functions_map'])) {
            foreach($params['initial_functions_map'] as $key => $value) {
                if (isset($inputData[$key])) {
                    if (is_array($inputData[$key]))
                        call_user_func_array(array($service, $value), $inputData[$key]);
                    else
                        call_user_func(array($service, $value), $inputData[$key]);
                }
            }
        }

        if (isset($params['result_function'])) {
            $resultData = call_user_func(array($service, $params['result_function']));
        }
        else if (isset($params['result_functions_map'])) {
            $resultData = array();
            foreach($params['result_functions_map'] as $key => $value) {
                $resultData[$key] = call_user_func(array($service, $value));
            }
        }


        $html = $this->getView()->partial($template, $resultData);
        if ($this->config['use_result_object']) {
            $result = new Result();

            if (isset($params['result_key_return'])) {
                foreach($params['result_key_return'] as $value) {
                    if (isset($resultData[$value]))
                        $result[$value] = $resultData[$value];
                }
            }
            $result->setHtml($html);
            return $result;
        }
        return $html;
    }

    protected function _buildCacheKey($service, $template, $inputData)
    {
        return md5('IncludeComponent' . $service . '__' . $template . '__' . serialize($inputData));
    }

} 