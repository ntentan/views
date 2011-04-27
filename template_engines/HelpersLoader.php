<?php
namespace ntentan\views\template_engines;

use ntentan\Ntentan;

class HelpersLoader
{
    private $loadedHelpers = array();

    private function getHelper($helper)
    {
        $helperPlural = Ntentan::plural($helper);
        $helper = $helperPlural == null ? $helper : $helperPlural;
        if($helper === null)
        {
            throw new \Exception("Unknown helper <b>$helper</b>");
        }
        if(!isset($this->loadedHelpers[$helper]))
        {
            Ntentan::addIncludePath(Ntentan::getFilePath("lib/views/helpers/$helper"));
            $helperClass = "\\ntentan\\views\\helpers\\$helper\\" . Ntentan::camelize($helper) . "Helper";
            $this->loadedHelpers[$helper] = new $helperClass();
        }
        return $this->loadedHelpers[$helper];
    }

    public function __get($helper)
    {
        return $this->getHelper($helper);
    }

    public function __call($helper, $arguments)
    {
        $helper = $this->getHelper($helper);
        $method = new \ReflectionMethod($helper, 'help');
        return $method->invokeArgs($helper, $arguments);
    }
}
