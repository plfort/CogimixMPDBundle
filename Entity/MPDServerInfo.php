<?php
namespace Cogipix\CogimixMPDBundle\Entity;
use Cogipix\CogimixCommonBundle\Entity\User;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 *
 * @author plfort - Cogipix
 * @ORM\Entity
 * @UniqueEntity(fields="alias",message="Alias already used",groups={"Create"})
 */
class MPDServerInfo
{

    /**
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", nullable=false)
     * @Assert\NotBlank(message="This value should not be blank",groups={"Create"})
     */
    protected $name;

    /**
     * @ORM\Column(type="string", nullable=false)
     * @Assert\NotBlank(message="This value should not be blank",groups={"Create"})
     */
    protected $alias;

    /**
     * @ORM\Column(type="string", nullable=false)
     * @Assert\NotBlank(message="This value should not be blank",groups={"Create"})
     */
    protected $host;

    /**
     * @ORM\Column(type="string", nullable=false)
     * @Assert\NotBlank(message="This value should not be blank",groups={"Create"})
     */
    protected $port;

    /**
     * @ORM\Column(type="string", nullable=false)
     * @Assert\NotBlank(message="This value should not be blank",groups={"Create"})
     */
    protected $streamUrl;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $password;

    /**
     * @ORM\ManyToOne(targetEntity="Cogipix\CogimixCommonBundle\Entity\User")
     * @var unknown_type
     */
    protected $user;

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getAlias()
    {
        return $this->alias;
    }

    public function setAlias($alias)
    {
        $this->alias = $alias;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser(User $user)
    {
        $this->user = $user;
    }

    public function getHost()
    {
        return $this->host;
    }

    public function setHost($host)
    {
        $this->host = $host;
    }

    public function getPort()
    {
        return $this->port;
    }

    public function setPort($port)
    {
        $this->port = $port;
    }

    public function getStreamUrl()
    {
        return $this->streamUrl;
    }

    public function setStreamUrl($streamUrl)
    {
        $this->streamUrl = $streamUrl;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

}
