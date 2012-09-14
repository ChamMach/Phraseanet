<?php

namespace Proxies\__CG__\Entities;

use Alchemy\Phrasea\Application;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ORM. DO NOT EDIT THIS FILE.
 */
class ValidationSession extends \Entities\ValidationSession implements \Doctrine\ORM\Proxy\Proxy
{
    private $_entityPersister;
    private $_identifier;
    public $__isInitialized__ = false;
    public function __construct($entityPersister, $identifier)
    {
        $this->_entityPersister = $entityPersister;
        $this->_identifier = $identifier;
    }
    /** @private */
    public function __load()
    {
        if (!$this->__isInitialized__ && $this->_entityPersister) {
            $this->__isInitialized__ = true;

            if (method_exists($this, "__wakeup")) {
                // call this after __isInitialized__to avoid infinite recursion
                // but before loading to emulate what ClassMetadata::newInstance()
                // provides.
                $this->__wakeup();
            }

            if ($this->_entityPersister->load($this->_identifier, $this) === null) {
                throw new \Doctrine\ORM\EntityNotFoundException();
            }
            unset($this->_entityPersister, $this->_identifier);
        }
    }

    /** @private */
    public function __isInitialized()
    {
        return $this->__isInitialized__;
    }


    public function getId()
    {
        if ($this->__isInitialized__ === false) {
            return (int) $this->_identifier["id"];
        }
        $this->__load();
        return parent::getId();
    }

    public function setName($name)
    {
        $this->__load();
        return parent::setName($name);
    }

    public function getName()
    {
        $this->__load();
        return parent::getName();
    }

    public function setDescription($description)
    {
        $this->__load();
        return parent::setDescription($description);
    }

    public function getDescription()
    {
        $this->__load();
        return parent::getDescription();
    }

    public function setArchived($archived)
    {
        $this->__load();
        return parent::setArchived($archived);
    }

    public function getArchived()
    {
        $this->__load();
        return parent::getArchived();
    }

    public function setCreated($created)
    {
        $this->__load();
        return parent::setCreated($created);
    }

    public function getCreated()
    {
        $this->__load();
        return parent::getCreated();
    }

    public function setUpdated($updated)
    {
        $this->__load();
        return parent::setUpdated($updated);
    }

    public function getUpdated()
    {
        $this->__load();
        return parent::getUpdated();
    }

    public function setExpires($expires)
    {
        $this->__load();
        return parent::setExpires($expires);
    }

    public function getExpires()
    {
        $this->__load();
        return parent::getExpires();
    }

    public function setReminded($reminded)
    {
        $this->__load();
        return parent::setReminded($reminded);
    }

    public function getReminded()
    {
        $this->__load();
        return parent::getReminded();
    }

    public function setBasket(\Entities\Basket $basket)
    {
        $this->__load();
        return parent::setBasket($basket);
    }

    public function getBasket()
    {
        $this->__load();
        return parent::getBasket();
    }

    public function addValidationParticipant(\Entities\ValidationParticipant $participants)
    {
        $this->__load();
        return parent::addValidationParticipant($participants);
    }

    public function getParticipants()
    {
        $this->__load();
        return parent::getParticipants();
    }

    public function getParticipant(\User_Adapter $user, Application $app)
    {
        $this->__load();
        return parent::getParticipant($user, $app);
    }

    public function setInitiatorId($initiatorId)
    {
        $this->__load();
        return parent::setInitiatorId($initiatorId);
    }

    public function getInitiatorId()
    {
        $this->__load();
        return parent::getInitiatorId();
    }

    public function isInitiator(\User_Adapter $user)
    {
        $this->__load();
        return parent::isInitiator($user);
    }

    public function setInitiator(\User_Adapter $user)
    {
        $this->__load();
        return parent::setInitiator($user);
    }

    public function getInitiator(Application $app)
    {
        $this->__load();
        return parent::getInitiator($app);
    }

    public function isFinished()
    {
        $this->__load();
        return parent::isFinished();
    }

    public function getValidationString(Application $app, \User_Adapter $user)
    {
        $this->__load();
        return parent::getValidationString($app, $user);
    }


    public function __sleep()
    {
        return array('__isInitialized__', 'id', 'initiator_id', 'created', 'updated', 'expires', 'basket', 'participants');
    }

    public function __clone()
    {
        if (!$this->__isInitialized__ && $this->_entityPersister) {
            $this->__isInitialized__ = true;
            $class = $this->_entityPersister->getClassMetadata();
            $original = $this->_entityPersister->load($this->_identifier);
            if ($original === null) {
                throw new \Doctrine\ORM\EntityNotFoundException();
            }
            foreach ($class->reflFields AS $field => $reflProperty) {
                $reflProperty->setValue($this, $reflProperty->getValue($original));
            }
            unset($this->_entityPersister, $this->_identifier);
        }

    }
}