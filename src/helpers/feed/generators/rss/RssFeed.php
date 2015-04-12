<?php
namespace ntentan\honam\helpers\feed\generators\rss;

use ntentan\Ntentan;
use ntentan\honam\helpers\feed\generators\Generator;

class RssFeed extends Generator
{
    public function generate()
    {
        $rss = new \SimpleXMLElement('<rss></rss>');
        $rss['version'] = '2.0';
        $rss->addChild('channel');
        foreach($this->properties as $property => $value)
        {
            $property = $this->convertProperty($property);
            $rss->channel->addChild($property, $this->formatProperty($property, $value));
        }
        foreach($this->items as $item)
        {
            $itemElement = $rss->channel->addChild('item');
            foreach($item as $field => $value)
            {
                $value = utf8_encode($value);
                $property = $this->convertProperty($field);
                $itemElement->addChild($property, $this->formatProperty($property, $value));
            }
            if(!isset($item['id']))
            {
                $this->getGUID($itemElement, $item);
            }
        }
        return $rss->asXML();
    }
    
    private function getGUID($itemElement, $item)
    {
        if(isset($item['url']))
        {
            $itemElement->addChild('guid', $item['url']);
            $itemElement->guid['isPermaLink'] = 'true';
        }
        else
        {
            $itemElement->addChild('guid', md5(serialize($item)));
            $itemElement->guid['isPermaLink'] = 'false';
        }        
    }
    
    private function convertProperty($property)
    {
        $properties = array(
            'title' => 'title',
            'url' => 'link',
            'updated' => 'lastBuildDate',
            'summary' => 'description',
            'author' => 'author',
            'category' => 'category',
            'date' => 'pubDate',
            'id' => 'guid',
            'description' => 'description'
        );
        
        return $properties[$property];
    }
    
    private function formatProperty($property, $value)
    {
        switch($property)
        {
            case 'lastBuildDate':
            case 'pubDate':
                return date("r", strtotime($value));
            default:
                return $value;
        }
    }
}
