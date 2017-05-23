<?php
/**
 * Created by PhpStorm.
 * User: fullcontroll
 * Date: 17.5.21
 * Time: 10.30
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * Class CountriesData
 * @package AppBundle\Entity
 * @ORM\Table(name="data_countries")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CountriesDataRepository")
 */
class CountriesData
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private $shortName;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getshortName()
    {
        return $this->shortName;
    }

    /**
     * @param mixed $shortName
     */
    public function setshortName($shortName)
    {
        $this->shortName = $shortName;
    }

    /**
     * @return mixed
     */
    public function getname()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }



}