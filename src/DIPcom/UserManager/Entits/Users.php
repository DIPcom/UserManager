<?php

namespace DIPcom\UserManager\Entits;

use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Kdyby\Doctrine\Entities\BaseEntity;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 */
class Users extends BaseEntity{
    
    
    use Identifier;
    
    /**
     * @ORM\Column(type="string")
     */
    public $name;
    
    /**
     * @ORM\Column(type="string")
     */
    public $email;
    
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    public $phone;
    
    /**
     * @ORM\Column(type="string")
     */
    public $password;
    
    
    
    /**
     * @ORM\Column(type="integer")
     */
    public $user_roles_id;
    
    
    /**
     * @ORM\ManyToOne(targetEntity="UserRoles")
     * @ORM\JoinColumn(name="user_roles_id", referencedColumnName="id")
     * @var \DIP\UserManager\Entits\UserRoles
     **/
    public $role;
    
    
    /**
     * @ORM\Column(type="datetime")
     */
    public $register_date;
    
    
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    public $last_log_date;
    
    
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    public $last_activity_date;
    
    
    /**
     * @ORM\Column(type="text", length=10000, nullable=true)
     */
    public $img_base64;
    
    

    public function __construct() {
        $this->role = new ArrayCollection();
        $this->register_date = new \DateTime();
    }
    

}

