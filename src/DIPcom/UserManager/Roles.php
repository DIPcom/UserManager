<?php

namespace DIPcom\UserManager;
use Nette;
use DIPcom\UserManager\Entits\UserRoles;

class Roles extends BaseModel{
    
    /**
     *
     * @var string 
     */
    public  $table_name = '\DIPcom\UserManager\Entits\UserRoles';
    
    
    /**
     * 
     * @return ArrayObject|UserRoles
     */
    public function getRoles(){
        
        return $this->entit->findAll();
        
    }
    
    
    /**
     * 
     * @param integer $id
     * @return UserRoles
     */
    public function getRole($id){
        
        return $this->entit->findOneBy(array('id'=>$id));
        
    }
    
}
