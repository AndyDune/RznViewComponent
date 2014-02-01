<?php
/**
 * Abstract factory for cache service
 */

namespace RznViewComponent\Service;
use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CacheFactory implements AbstractFactoryInterface
{
    /**
     * Determine if we can create a service with name
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param $name
     * @param $requestedName
     * @return bool
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        return true;
    }

    /**
     * Create service with name
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param $name
     * @param $requestedName
     * @return mixed
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $config = $serviceLocator->get('config');
        if (isset($config['rznviewcomponent']['cache_adapter']))
        {
            $config = $config['rznviewcomponent']['cache_adapter'];
        }
        else
            $config = array(
                'name' => 'filesystem',
                'options' => array(
                    'ttl' => 3600,
                    'dirLevel' => 2,
                    'file_locking' => false,
                    'cacheDir' => 'data/cache',
                    'dirPermission' => 0755,
                    'filePermission' => 0666,
                ),
            );

        return \Zend\Cache\StorageFactory::factory(
            array(
                'adapter' => $config,
                'plugins' => array('serializer'),
            )
        );
    }
} 