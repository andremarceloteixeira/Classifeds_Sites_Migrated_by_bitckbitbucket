<?php
/**
 * Created by PhpStorm.
 * User: mteixeira
 * Date: 8/6/14
 * Time: 6:38 PM
 */

namespace Anuncios\Model;


class ConfigModel {

    /**
     * 30 dias em que os anuncios expiram
     */
    const EXPIRACAO_ANUNCIOS = 30;


    /**
     * Get current Time
     * //return $date->format('Y-m-d H:i:s');
     * @return \DateTime
     */
    public  function getCurrentTime()
    {
        return new \DateTime();
    }

    /**
     * Expiration date
     * @return string
     */
    public function generateExpirationDate()
    {
        $date = new \DateTime();
        $date->modify('+'.self::EXPIRACAO_ANUNCIOS . ' days');
        return $date;
    }

    public function createShortLinkMapping()
    {
        $microTime = sha1(microtime(true));
        $requestPathGenerated = substr($microTime, 0, 6) .'-'. substr($microTime, -2) ;
        return $requestPathGenerated;
    }

    public function escapeHtmlHacking($input)
    {
        //return (new \Zend\Escaper\Escaper('utf-8'))->escapeHtmlAttr($input);
        return htmlspecialchars($input);
    }
    public function unescape($input)
    {
        return htmlspecialchars_decode($input);
    }

    /**
     * @param $action
     * @param $string
     * @return bool|string
     */
    public function encryptDecrypt($action, $string)
    {
        $output = false;
        $key = 'My strong random secret key';
        // initialization vector
        $iv = md5(md5($key));
        if( $action == 'encrypt' ) {
            $output = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $string, MCRYPT_MODE_CBC, $iv);
            $output = base64_encode($output);
        } else if( $action == 'decrypt' ){
            $output = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($string), MCRYPT_MODE_CBC, $iv);
            $output = rtrim($output, "");
        }
        return (string) $output;
    }
} 