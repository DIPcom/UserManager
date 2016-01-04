<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FileManagerExtension
 *
 * @author Mykola Chomenko <mykola.chomenko@dipcom.cz>
 */

namespace DIPcom\UserManager\DI;


use Nette;
use Nette\DI\CompilerExtension;

class UserManagerExtension extends CompilerExtension{
    
   
    
    public function loadConfiguration() {
        
        $builder = $this->getContainerBuilder();
        
        
        $builder->addDefinition($this->prefix('roles'))
		->setClass('DIPcom\UserManager\Roles');
        
        
        $builder->addDefinition($this->prefix('manager'))
		->setClass('DIPcom\UserManager');
        
        
    }
    
   
    
    public function beforeCompile(){
        
        $builder = $this->getContainerBuilder();
        $cache = $builder->getDefinition('doctrine.cache.default.metadata');
        $reader = $builder->getDefinition('annotations.reader');
        
        $builder->getDefinition('doctrine.default.metadataDriver')
                ->addSetup('DIPcms\UserManager\Maping::addDoctrineMaping($service, ?,?,?)', array($this->prefix('@maping'), $cache, $reader));
       
        
    }
    
    
    
     /**
     * @param \Nette\Configurator $configurator
     */
    public static function register(Nette\Configurator $configurator){
        
        $configurator->onCompile[] = function ($config, Nette\DI\Compiler $compiler){
                $compiler->addExtension('userManager', new UserManagerExtension());
        };
    } 
    
  
}
