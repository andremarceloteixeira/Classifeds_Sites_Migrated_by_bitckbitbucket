<?php

namespace Application\Components;


class Translate
{


    private static $translateService = null;

    
    public static function setTranslate ($translate)
    {
        self::$translateService = $translate;
    }

    public static function setLocale ($locale = "pt_BR")
    {
        self::$translateService->setLocale($locale);
    }

    public static function setTranslatePath ()
    {
        self::$translateService->addTranslationFilePattern("gettext",
                __DIR__ . "/../../../language/", "%s.mo");
    }
   
    public static function t ($text = "", $params = array())
    {
        if (null !== self::$translateService) {
            $text = self::$translateService->translate($text);
        }
        if (is_array($params)) {
            return vsprintf($text, $params);
        } else {
            return sprintf($text, $params);
        }
    }
}