<?php

namespace DIPcom\UserManager;

use Nette;


class BaseModel extends Nette\Object{
    

    
    /**
     *
     * @var  \Kdyby\Doctrine\EntityManager 
     */
    protected $em;
    
    
    /**
     * @var \Kdyby\Doctrine\EntityDao
     */
    protected $entit;
    
    
    /**
     * @var string
     */
    public  $table_name = null;
    
    

    public function __construct(\Kdyby\Doctrine\EntityManager $entityManager){
        
        if(!$this->table_name){
            throw new \Exception('Variable $db_nam must be filled!');
        }
        
        $this->em = $entityManager;
        
        $entits = new $this->table_name();
        $this->entit = $this->em->getRepository($entits);
        
    }
      
}
