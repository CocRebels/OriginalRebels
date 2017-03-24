<?php
/**
 * Created by PhpStorm.
 * User: artur
 * Date: 3/23/17
 * Time: 9:46 AM
 */

namespace AppBundle\Security;


class Hashing
{

    public function getHash($key)
    {
        $modus = count($key) % 10;
        $hash = md5($key);
        $generatedHash = substr($hash, $modus,$modus+20);

        return $generatedHash;
    }
}