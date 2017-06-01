<?php
/**
 * Created by PhpStorm.
 * User: fullcontroll
 * Date: 17.6.1
 * Time: 16.41
 */

namespace AppBundle\Entity;

/**
 * Class AllianceInvitation
 * @package AppBundle\Entity
 * @ORM\Table(name="alliance_notifications")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AllianceInvitationRepository")
 */
class AllianceInvitation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", unique=true)
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private $source;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private $getter;

    /**
     * @ORM\Column(type="date")
     */
    private $sendDate;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $type;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $message;

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
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param mixed $source
     */
    public function setSource($source)
    {
        $this->source = $source;
    }

    /**
     * @return mixed
     */
    public function getGetter()
    {
        return $this->getter;
    }

    /**
     * @param mixed $getter
     */
    public function setGetter($getter)
    {
        $this->getter = $getter;
    }

    /**
     * @return mixed
     */
    public function getSendDate()
    {
        return $this->sendDate;
    }

    /**
     * @param mixed $sendDate
     */
    public function setSendDate($sendDate)
    {
        $this->sendDate = $sendDate;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }



}