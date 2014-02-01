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

    }


    protected function _buildCacheKey($service, $template, $inputData)
    {
        return md5('IncludeControllerComponent' . $service . '__' . $template . '__' . serialize($inputData));
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