<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use \Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class User
 * @package AppBundle\Entity
 * @ORM\Table(name="champions_users")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @UniqueEntity(fields="email", message="Email already taken")
 * @UniqueEntity(fields="username", message="Username already taken")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="date")
     */
    private $dateCreated;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $status;
    /**
     * @ORM\Column(type="string", length=25, unique=true)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min="6",
     *      max="25",
     *      minMessage="Your Username must be at least {{ limit }} characters long",
     *      maxMessage="Your Username cannot be longer than {{ limit }} characters"
     * )
     */
    private $username;
    /**
     * @Assert\NotBlank(groups={"Registration"})
     * @Assert\Length(max=4096)
     * @Assert\Length(
     *      min="10",
     *      max="50",
     *      minMessage="Your Password must be at least {{ limit }} characters long",
     *      maxMessage="Your Password cannot be longer than {{ limit }} characters"
     * )
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=64, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="json_array")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="string", length=2)
     * @Assert\Country()
     */
    private $country;

    /**
     * @ORM\Column(type="string", nullable=true )
     */
    private $passRecoverHash;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $passRecoverTimeStamp;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Alliance")
     * @ORM\JoinColumn(name="alliance_id", referencedColumnName="id")
     */
    private $alliance;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $allianceRole;

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    public function getSalt()
    {
        // TODO: Implement getSalt() method.
        return null;
    }

    public function getRoles()
    {
        return $this->roles;

    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
        return null;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set username
     *
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }
    
    /**
     * @return mixed
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param mixed $plainPassword
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;
    }

    /**
     * @param mixed $roles
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return mixed
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * @param mixed $dateCreated
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getPassRecoverHash()
    {
        return $this->passRecoverHash;
    }

    /**
     * @param mixed $passRecoverHash
     */
    public function setPassRecoverHash($passRecoverHash)
    {
        $this->passRecoverHash = $passRecoverHash;
    }

    /**
     * @return mixed
     */
    public function getPassRecoverTimeStamp()
    {
        return $this->passRecoverTimeStamp;
    }

    /**
     * @param mixed $passRecoverTimeStamp
     */
    public function setPassRecoverTimeStamp($passRecoverTimeStamp)
    {
        $this->passRecoverTimeStamp = $passRecoverTimeStamp;
    }

    /**
     * @return mixed
     */
    public function getAlliance()
    {
        return $this->alliance;
    }

    /**
     * @param mixed $alliance
     */
    public function setAlliance($alliance)
    {
        $this->alliance = $alliance;
    }

    /**
     * @return mixed
     */
    public function getAllianceRole()
    {
        return $this->allianceRole;
    }

    /**
     * @param mixed $allianceRole
     */
    public function setAllianceRole($allianceRole)
    {
        $this->allianceRole = $allianceRole;
    }


}
