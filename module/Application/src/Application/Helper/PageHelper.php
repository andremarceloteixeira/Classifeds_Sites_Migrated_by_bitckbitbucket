<?php

namespace Application\Helper;

use Zend\View\Helper\AbstractHelper;
class PageHelper extends AbstractHelper
{
    public $serviceLocator;

    public function __construct ($locator)
    {
       $this->serviceLocator = $locator;
    }
    
    public function __invoke ()
    {     
        return $this;
    }

    //_resize;
    public function getUrl($type = null, $path = null, $r = true)
    {
        switch ($type)
        {
            case 'image' :
                $url = $this->getHost() . '/data/uploads/'. $path ;
                if ($r) {
                    $url = $this->getHost() . '/data/uploads/resize/'. $path ;
                }
                $url = $this->imageExists($url) ? $url : $this->getHost() . '/data/uploads/placeholder.png';
                break;
            default :
                $url = $this->getHost() . '/' .$path;
                break;
        }
        return $this->getProtocol(). '://' . $url;
    }

    public function getBigImage($path = null)
    {
        $url = $this->getHost() . '/data/uploads/big/'. $path ;
        $url = $this->imageExists($url) ? $url : $this->getHost() . '/data/uploads/big/placeholder.png';
        return $this->getProtocol(). '://' . $url;
    }

    public function getDestaqueImage($path = null)
    {
        $url = $this->getHost() . '/data/uploads/destaque/'. $path ;
        $url = $this->imageExists($url) ? $url : $this->getHost() . '/data/uploads/destaque/placeholder.png';
        return $this->getProtocol(). '://' . $url;
    }

    private function imageExists($url)
    {
        $url =  $this->getProtocol(). '://' .$url;
        if(!empty($url)) {
            if (getimagesize($url) !== false) {
                return true;
            }
        }
        return false;
    }

    public function getRequest()
    {
        return $this->serviceLocator->get('request');
    }


    public function getHost() 
    {
       return $this->getRequest()->getUri()->getHost(); 
    }


    public function getDescription($url)
    {
        return substr($url, 0, 30) . '...';
    }
      
    public function getProtocol() 
    {
       return $this->getRequest()->getUri()->getScheme(); 
    }
}