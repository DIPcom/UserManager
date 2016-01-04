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

namespace DIP\Roles\Entits;

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
    
    
}
