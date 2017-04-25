<?php
/**
 * Created by PhpStorm.
 * User: artur
 * Date: 4/18/17
 * Time: 11:03 PM
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Alliance
 * @package AppBundle\Entity
 * @ORM\Table(name="alliance")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AllianceRepository")
 * @UniqueEntity(fields="name", message="This alliance name is already taken")
 */
class Alliance
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min="6",
     *     max="26",
     *     minMessage="Your alliance name should be atleast {{ limit }} characters!",
     *     maxMessage="Your alliance name cannot be longer than {{ limit }} characters!",
     *     )
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min="12",
     *     max="255",
     *     minMessage="Your alliance description is to short, should be atleast {{ limit }} characters!",
     *     maxMessage="Your alliance description is to long, can't be more than {{ limit }} characters",
     * )
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     */
    private $userNumber;

    /**
     * @ORM\Column(type="string")
     */
    private $allianceOwner;

    /**
     * @return mixed
     */
    public function getName()
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

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getUserNumber()
    {
        return $this->userNumber;
    }

    /**
     * @param mixed $userNumber
     */
    public function setUserNumber($userNumber)
    {
        $this->userNumber = $userNumber;
    }

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
    public function getAllianceOwner()
    {
        return $this->allianceOwner;
    }

    /**
     * @param mixed $allianceOwner
     */
    public function setAllianceOwner($allianceOwner)
    {
        $this->allianceOwner = $allianceOwner;
    }



}