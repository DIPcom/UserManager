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
