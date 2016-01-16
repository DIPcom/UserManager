<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UserRoles
 *
 * @author Mykola Chomenko <mykola.chomenko@dipcom.cz>
 */

namespace DIPcom\UserManager\Entits;

use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Kdyby\Doctrine\Entities\BaseEntity;

/**
 * @ORM\Entity
 */

class UserRoles extends BaseEntity{
    
    use Identifier;
    
    /**
     * @ORM\Column(type="string")
     */
    public $name;
    
    /**
     * @ORM\Column(type="string")
     */
    public $description;
    
    /**
     * @ORM\Column(type="integer", nullable=true )
     */
    public $parent_role;
   
    
    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     */
    public $access;
    
    
    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     */
    public $access_ban;
    
    
    /**
     * 
     * @return array
     */
    public function toArray(){
        return array("name"=>$this->name, "description"=> $this->description, "parent_role"=>$this->parent_role);
    }
    
    
}
