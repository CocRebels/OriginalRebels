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
    public function __construct($id)
    {
        $this->id = $id;
        $this->encodedId = $id*8456;
        $this->decodedId = $id/8456;
    }

    public function generateHash()
    {
            if (is_string($this->id))
            {
                $modusNumber = count($this->id) % 11;
                $hash = md5($this->id);
            }
            else {
                $modusNumber = $this->encodedId % 11;
                $hash = md5($this->encodedId);
            }
            $generatedHash = substr($hash, $modusNumber, $modusNumber + 19);

            return $generatedHash;
    }

}