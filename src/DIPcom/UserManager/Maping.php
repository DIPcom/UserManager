<?php

/**
 * @author Mykola Chomenko <mykola.chomenko@dipcom.cz>
 */
namespace DIPcms\UserManager;

use Nette;
use Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain;
use Kdyby\Doctrine\Mapping\AnnotationDriver;
use Kdyby\DoctrineCache\Cache;
use Doctrine\Common\Annotations\CachedReader;

class Maping extends Nette\Object{
    

    
    
    /**
     *
     * @var array 
     */
    public $maping_doctrine = array(
        'namespace' => "DIPcom\UserManager\\",
        'directory' => __DIR__,
    );
    
    
    /**
     * 
     * @param \Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain $service
     * @param \DIP\FileManager\AddMaping $maping
     */
    public static function addDoctrineMaping(MappingDriverChain $service, AddMaping $maping, Cache $cache,CachedReader $reader){
       
        $nestedDriver = new AnnotationDriver(
                array($maping->maping_doctrine['namespace'] => $maping->maping_doctrine['directory']), 
                $reader,
                $cache
        );
        
        $service->addDriver($nestedDriver, $maping->maping_doctrine['namespace']);

    }  
    
    
}
