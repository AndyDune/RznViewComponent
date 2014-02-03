<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 01.02.14
 * Time: 12:43
 */

namespace RznViewComponent\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use RznViewComponent\Container\Result;
use RznViewComponent\Service\IncludeComponentTrait;
use Zend\Mvc\Controller\Plugin\PluginInterface;
use Zend\Stdlib\DispatchableInterface as Dispatchable;


class IncludeControllerComponent implements ServiceLocatorAwareInterface, PluginInterface
{
    use IncludeComponentTrait;

    protected $controller;

    protected function _getResult($method, $template, $inputData, $params)
    {
        $resultData = call_user_func_array(array($this->getController(), $method), $inputData);
        $html = call_user_func_array($this->_getViewPartial(), array($template, $resultData));

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


    protected function _buildCacheKey($method, $template, $inputData)
    {
        return md5('IncludeControllerComponent' . $method . '__' . $template . '__' . serialize($inputData));
    }


    protected function _getViewPartial()
    {
        return $this->getServiceLocator()->getServiceLocator()->get('ViewHelperManager')->get('partial');
    }


    /**
     * Set the current controller instance
     *
     * @param  Dispatchable $controller
     * @return void
     */
    public function setController(Dispatchable $controller)
    {
        $this->controller = $controller;
    }

    /**
     * Get the current controller instance
     *
     * @return null|Dispatchable
     */
    public function getController()
    {
        return $this->controller;
    }

}