<?php

namespace DIPcom\UserManager;

use Nette;
use Nette\Security\Passwords;
use DIPcom\UserManager\Entits\Users;
use Nette\Utils\Image;
use Nette\Http\FileUpload;


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
                
                $user_session = (array)$user;
                $user_session['role'] =  $user->role->toArray();
                end($user_session);
                unset($user_session[key($user_session)]);
                unset($user_session['password']);
                unset($user_session['img_base64']);

                return new Nette\Security\Identity($user->id, $user->role->name, (array)$user_session);
            }
            
            throw new \Nette\Security\AuthenticationException("Login filed");
	}
        
        
        
        /**
         * 
         * @param \Nette\Http\FileUpload $file
         * @return string
         */
        private function getImageBase64(\Nette\Http\FileUpload $file){
            $type = $file->getContentType();
            $img = Image::fromFile($file);
            $img->resize(100, 100, Image::EXACT);
            $img_string = "";
            switch ($type){
                case 'image/png': $img_string = $img->toString(Image::PNG);
                    break;
                case 'image/jpeg': $img_string = $img->toString(Image::JPEG);
                    break;
                case 'image/gif': $img_string = $img->toString(Image::GIF);
                    break;
            }
            return 'data:'.$type.';base64,'.base64_encode($img_string);
            
        }
        
        
        
        
        
        
        
        /**
         * 
         * @param array $data
         * @throws \Exception
         * @return \DIP\Database\Users
         */
        public function createAccount($username, $email, $password, $role_id, FileUpload $file = null){
            
           
            $role = $this->role->getRole($role_id);
            
            $account = new Users();
            $account->name = $username;
            $account->email = $email;
            $account->password = Passwords::hash($password);
            $account->role = $role;
            
            if($file){
                $account->img_base64 = $this->getImageBase64($file);
            }
            
            $this->em->persist($account);
            $this->em->flush();
            return $account;
        }
        
        
        
        /**
         * 
         * @param integer $user_id
         * @return string
         */
        public function getUserImgBase64($user_id){
            $user = $this->entit->findOneBy(array('id'=>$user_id));
           
            if($user){
                return $user->img_base64;
            }
            return null;
        }
        
        
        
        /**
         * 
         * @param integer $user_id
         * @param array $values
         */
        public function updateUser($user_id, $name = null, $email = null, $password = null, $role = null, \Nette\Http\FileUpload $file = null){
            
            $user = $this->getUser($user_id);
            
            if($name){ $user->name = $name;}
            if($email){ $user->email = $email;}
            if($password){ $user->password = Passwords::hash($password);}
            if($role){ $user->role = $this->role->getRole($role); }
            if($file){ $user->img_base64 = $this->getImageBase64($file); }
            
            
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
         * @parma string $email
         * @return \DIPcom\UserManager\Entits\Users
         */
        public function getUserByEmail($email){
            return $this->entit->findOneBy(array('email'=>$email));
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
 