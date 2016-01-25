<?php

namespace DIPcom\UserManager;
use Nette;
use DIPcom\UserManager\Entits\UserRoles;
use Nette\Application\UI\Presenter;

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
    */
    public function removeRole($id){
        if($id !== 1){
          $role = $this->getRole($id);
          if($role){
              $this->em->remove($role);
              $this->em->flush();
          }
        }
    }
    
    
    
    
    /**
     * 
     * @param string $name
     * @param string $access
     * @param string $access_ban
     * @param string $description
     * @return UserRoles
     */
    public function addRole($name, $access, $access_ban, $description = null){
        $role = new \DIPcom\UserManager\Entits\UserRoles();
        $role->name = $name;
        $role->access = $access;
        $role->access_ban = $access_ban;
        $role->description = $description;
        
        $this->em->persist($role);
        $this->em->flush();
        return $role;
    }
    
    
    
    
    /**
     * @param integer $role_id
     * @param string $name
     * @param string $access
     * @param string $access_ban
     * @param string $description
     * @return UserRoles
     */
    public function editRole($role_id, $name, $access, $access_ban, $description = null){
        $role = $this->getRole($role_id);
        $role->name = $name;
        $role->access = $access;
        $role->access_ban = $access_ban;
        $role->description = $description;
        
        $this->em->persist($role);
        $this->em->flush();
        return $role;
    }
    
    
    
    
    /**
     * 
     * @param integer $id
     * @return UserRoles
     */
    public function getRole($id){
        
        return $this->entit->findOneBy(array('id'=>$id));
        
    }
    
    /**
     * 
     * @param type $user_rols
     * @param Presenter|string $presenter
     */
    public function isAccesUser(UserRoles $user_rols, $presenter){
        

        $is_ = array();
        if(is_a($presenter, '\Nette\Application\UI\Presenter')){
           $is_ = explode(':',$presenter->name);
           $is_[] = $presenter->getAction();
        }else{
            $is_ = explode(':',$presenter);
        }    


        
        $search = array();
        $return = true;
        if(!$user_rols->access_ban && $user_rols->access){
            $search = $user_rols->access;
        }elseif($user_rols->access_ban && !$user_rols->access){
            $search = $user_rols->access_ban;
            $return = false;
        }
        
        if($search){
            $search = preg_replace("/[\n\r\s+]/","",$search);
            $a = explode(',',$search);
            foreach($a as $v){
                $v = explode(':',$v);
                $count = 0;
                foreach($v as $i => $vi){
                    if(isset($is_[$i]) && $vi == $is_[$i] || isset($is_[$i]) && $vi == "*"){
                        $count++;
                    }else{
                        break;
                    }
                }
                if(count($v) == $count){
                    return $return;
                }
            }
        }
        return true;
    }
    
    
}
