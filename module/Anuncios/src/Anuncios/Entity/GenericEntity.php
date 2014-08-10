<?php
/**
 * Created by PhpStorm.
 * User: mteixeira
 * Date: 8/8/14
 * Time: 11:57 AM
 */

namespace Anuncios\Entity;


class GenericEntity
{
    /**
     * get Entity
     * @param bool $arrayToMap
     */
    public function __construct($arrayToMap = false)
    {
        if ($arrayToMap) {
            foreach(get_object_vars($this) as $attr_name => $attr_value) {
                $this->{$attr_name} = $arrayToMap[$attr_name];
                if (empty($this->{$attr_name})) {
                    $this->{$attr_name} = 0;
                }
            }
        }

    }

    public function toArray()
    {
        return (array) $this;
    }
} 