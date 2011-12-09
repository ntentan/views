<?php
/* 
 * Ntentan PHP Framework
 * Copyright 2010 James Ekow Abaka Ainooson
 * 
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace ntentan\views\helpers\forms\api\renderers;

use \ntentan\views\helpers\forms\api\Element;

class Inline extends Renderer
{

    /**
     * The default renderer head function
     *
     */
    public function head()
    {
    
    }
    
    /**
     * The default renderer body function
     *
     * @param $element The element to be rendererd.
     */
    public function element($element, $showfields=true)
    {
    	$ret = "";
    	// Ignore Hidden Fields
    	if($element->getType()=="HiddenField")
    	{
            return $element->render();
    	}
    
        $ret .= "<div class='form-element-div' ". ($element->id()==""?"":"id='".$element->id()."_wrapper'") . " " . $element->getAttributes(Element::SCOPE_WRAPPER).">";

        if($element->getType() == "checkbox")
        {
            $element->renderLabel = false;
        }
        
        if(!$element->isContainer && $element->renderLabel)
        {
            $label = $element->getLabel();
            $ret .= $this->renderLabel($element);
        }
    
        if($element->hasError())
        {
            $ret .= "<div class='form-errors'>";
            $ret .= "<ul>";
            foreach($element->getErrors() as $error)
            {
                $ret .= "<li>$error</li>";
            }
            $ret .= "</ul>";
            $ret .= "</div>";
        }
        
        if($element->getType()=="ntentan\views\helpers\forms\api\Field")
        {
            $ret .= "<div>" . $element->render() . "</div>";
        }
        else if($element->getType() == "checkbox")
        {
            $ret .= $element->render() . $this->renderLabel($element);
        }
        else
        {
            $ret .= $element->render();
        }
    
        if($element->getDescription() != "")
        {
            $ret .= "<div ".($element->id()==""?"":"id='".$element->id()."_desc'")." class='form-description'>".$element->getDescription()."</div>";
        }
        
        $ret .= "</div>";
    
        return $ret;
    }
    
    /**
     * The foot of the default renderer.
     *
     */
    public function foot()
    {
    
    }
    
    public function type()
    {
        return "inline";
    }
}
