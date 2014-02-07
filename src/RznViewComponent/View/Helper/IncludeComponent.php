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