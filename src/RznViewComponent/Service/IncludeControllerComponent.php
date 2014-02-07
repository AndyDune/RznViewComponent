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