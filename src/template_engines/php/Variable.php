<?php
namespace ntentan\honam\template_engines\php;

class Variable implements \ArrayAccess, \Iterator
{
    private $keys;
    private $position;
    private $data;
    
    public static function initialize($data)
    {
        $type = gettype($data);
        switch ($type)
        {
            case 'string':
                return new Variable($data);
                
            case 'array':
                return new Variable($data, array_keys($data));
                
            case 'boolean':
            case 'integer':
            case 'double':
            case 'NULL':
            case 'object':
                return $data;
                
            default:
                throw new \ntentan\honam\ViewException("Cannot handle the $type type in templates");
        }
    }


    public function __construct($data, $keys = array())
    {
        $this->data = $data;
        $this->keys = $keys;
    }
    
    public function __toString()
    {
        return Janitor::cleanHtml($this->data);
    }
    
    public function u()
    {
        return $this->unescape();
    }
    
    public function unescape()
    {
        return $this->data;
    }
    
    public function rewind() 
    {
        return $this->position = 0;
    }

    public function valid() 
    {
        return @isset($this->data[$this->keys[$this->position]]);
    }    

    public function current() 
    {
        return Variable::initialize($this->data[$this->keys[$this->position]]);
    }

    public function key() 
    {
        return $this->keys[$this->position];
    }

    public function next() 
    {
        $this->position++;
    }

    public function offsetExists($offset) 
    {
        return isset($this->data[$offset]);
    }

    public function offsetGet($offset) 
    {
        return Variable::initialize($this->data[$offset]);
    }

    public function offsetSet($offset, $value) 
    {
        if(is_null($offset))
        {
            $this->data[] = $value;
        }
        else
        {
            $this->data[$offset] = $value;
        }
    }

    public function offsetUnset($offset) 
    {
        unset($this->data[$offset]);
    }
}
