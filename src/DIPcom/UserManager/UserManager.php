<?php

namespace DIPcom\UserManager;

use Nette;
use Nette\Security\Passwords;
use DIPcom\UserManager\Entits\Users;

class UserManager extends BaseModel implements Nette\Security\IAuthenticator{
    
        /**
         *
         * @var string 
         */
        public $table_name = '\DIPcom\UserManager\Entits\Users';
        
        
        /**
         *
         * @var DIP\Roles
         */
        public $role;
        
        
        
        public function __construct(
                \Kdyby\Doctrine\EntityManager $entityManager,
                Roles $role
                ) {
            parent::__construct($entityManager);
            $this->role = $role;
        }

        
         /**
         * 
         * @param integer $userId
         * @return bolean
         */
        public function userExists($userId){
            return empty($this->users->findBy(array('id'=>$userId)))?false:true;
        }
        
        

	/**
	 * Performs an authentication against e.g. database.
	 * and returns IIdentity on success or throws AuthenticationException
	 * @return IIdentity
	 * @throws AuthenticationException
	 */
	public function authenticate(array $login_data){
            
            $email = $login_data[0];
            $password = $login_data[1];
            
            $user = $this->entit->findOneBy(array('email'=>$email));
            
            if(!empty($user) && Passwords::verify($password, $user->password)){
                $user->last_log_date = new \DateTime('NOW');
                $this->em->flush();
                
                return new Nette\Security\Identity($user->id, $user->role->name, $user);
            }
            
            return false;
	}
        
        
        
        
        
        
        /**
         * 
         * @param array $data
         * @throws \Exception
         * @return \DIP\Database\Users
         */
        public function createAccount($username, $email, $password, $role_id){
            
           
            $role = $this->role->getRole($role_id);
            
            $account = new Users();
            $account->name = $username;
            $account->email = $email;
            $account->password = Passwords::hash($password);
            $account->role = $role;
            
            $this->em->persist($account);
            $this->em->flush();
            return $account;
        }
        
        public function updateUser($user_id, array $values){
            
            $user = $this->getUser($user_id);
            
            foreach($values as $i => $v){
                
                if(property_exists($user, $i)){
                    
                    if($i == 'password'){
                        $v = Passwords::hash($v);
                    }
                    
                    if($i == 'role'){
                        $v = $this->role->getRole($v);
                    }
                    
                    $user->$i = $v;
                }
            }

            $this->em->persist($user);
            $this->em->flush();
            
        }

        
        /**
         * 
         * @param int $length
         * @param string $chars
         * @return string
         */
        public function rand_passwd( $length = 8, $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789' ) {
            return substr( str_shuffle( $chars ), 0, $length );
        }
        
        
        
        public function updateFacebookId($user_id,$fb_id){
            $this->database->update('users', ['facebook_id'=> $fb_id])
                    ->where('id=%i',$user_id)->execute();
        }
        
        
        
        
        public function issetFacebookUser($user_fb_id, $user_email){
            $result = $this->database->select('*')
                    ->from('users')
                    ->where('facebook_id=%i',$user_fb_id)->or('email=%s',$user_email)
                    ->fetch();
            return $result;
        }
        
        
        
        
        
        
        public function loginOrCreateAcounFacebook($fbdata){
            $email = isset($fbdata['email'])?$fbdata['email']:null;
            $first_name = $fbdata['first_name'];
            $last_name = $fbdata['last_name'];
            $facebook_id = $fbdata['id'];
            $sex = $fbdata['gender'];
            $user = $this->issetFacebookUser($facebook_id, $email);
            
            if($user){
                if(!$user['facebook_id']){
                    $this->updateFacebookId($user['id'], $facebook_id);
                }
            }else{
                $this->add($first_name, $last_name, false, $email, $sex, $facebook_id);
            }
            
            return $facebook_id;
            
        }
        
        
        
        
        /**
         * 
         * @return ArrayObject[]|\DIPcom\UserManager\Entits\Users
         */
        public function getUsers(){
            return $this->entit->findAll();
        }
        
        
        
        
        /**
         * 
         * @return \DIPcom\UserManager\Entits\Users
         */
        public function getUser($user_id){
            return $this->entit->find($user_id);
        }
        
        
        
        
        /**
         * 
         * @param integer $user_id
         * @return boolean
         */
        public function removeUser($user_id){
            $user = $this->entit->findOneBy(array('id'=>$user_id));
            if($user && (int)$user_id !== 1){
                $this->em->remove($user);
                $this->em->flush();
                return true;
            }
            return false;
        }
        



       
        

}
 