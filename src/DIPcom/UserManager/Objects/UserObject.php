<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UserObject
 *
 * @author Mykola Chomenko <mykola.chomenko@dipcom.cz>
 */

namespace DIP\UserManager\Objects;

use Nette;
use Nette\Security\User;

class UserObject extends Nette\Object{
    

    /**
     *
     * @var integer 
     */
    public $id;
    
    /**
     *
     * @var string 
     */
    public $name;
    
    /**
     *
     * @var string 
     */
    public $email;
    
    /**
     *
     * @var string 
     */
    public $phone;
    
    /**
     *
     * @var string 
     */
    public $password;
    
    /**
     *
     * @var integer 
     */
    public $user_roles_id;
    
    /**
     *
     * @var \DIP\Database\UserRoles 
     */
    public $role;
    
    /**
     *
     * @var \DateTime 
     */
    public $register_date;
    
    /**
     *
     * @var \DateTime 
     */
    public $last_log_date;
    
    
    /**
     *
     * @var \DateTime 
     */
    public $last_activity_date;
    
    
    public function __construct(User $user) {
        
        $data = $user->getIdentity();
        if(isset($data->data)){
            foreach($data->data as $name => $value){
                if(property_exists($this, $name)){
                    $this->$name = $value;
                }
            }
        }
    }
    
    
}
