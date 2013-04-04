<?php
namespace Cogipix\CogimixMPDBundle\Model;
class PlaylistMPD
{

    private $id;

    private $alias;

    private $serverAlias;

    private $name;

    public function __construct($serverAlias, $name)
    {
        $this->serverAlias = $serverAlias;
        $this->alias = $serverAlias . $name;
        $this->name = $name;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getAlias()
    {
        return $this->alias;
    }

    public function setAlias($alias)
    {
        $this->alias = $alias;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getServerAlias()
    {
        return $this->serverAlias;
    }

    public function setServerAlias($serverAlias)
    {
        $this->serverAlias = $serverAlias;
    }

}
