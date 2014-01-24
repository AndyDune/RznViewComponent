<?php
return array(

    'rznviewcomponent' => array(
        'cache_service' => 'cache',
        'cache_allow'   => true,
        'view_script_prefix' => ''
    ),

    'service_manager' => array(
        'factories' => array(
            'Zend\Cache\StorageFactory' => function() {
                    return Zend\Cache\StorageFactory::factory(
                        array(
                            'adapter' => array(
                                'name' => 'filesystem',
                                'options' => array(
                                    'ttl' => 3600,
                                    'dirLevel' => 2,
                                    'file_locking' => false,
                                    'cacheDir' => 'data/cache',
                                    'dirPermission' => 0755,
                                    'filePermission' => 0666,
                                ),
                            ),
                            'plugins' => array('serializer'),
                        )
                    );
                }
        ),
        'aliases' => array(
            'cache' => 'Zend\Cache\StorageFactory',
        ),
    ),

    'view_helpers' => array(
        'invokables' => array(
            'includeComponent' => 'RznViewComponent\View\Helper\IncludeComponent',
        ),
    ),
);
