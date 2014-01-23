<?php
/**
 * Component result container.
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