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

namespace RznViewComponent\Container;

class Result implements \ArrayAccess
{
    protected $_html = '';

    protected $_array = array();

    public function offsetExists($offset)
    {
        return isset($this->_array[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->_array[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->_array[$offset]) ? $this->_array[$offset] : null;
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset))
        {
            $this->_array[] = $value;
        }
        else
        {
            $this->_array[$offset] = $value;
        }
    }


    public function setHtml($html)
    {
        $this->_html = $html;
        return $this;
    }

    public function getHtml()
    {
        return $this->_html;
    }


    public function __toString()
    {
        return $this->getHtml();
    }
} 