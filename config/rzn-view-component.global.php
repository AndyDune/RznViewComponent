<?php
/**
 * RznViewComponent Module (https://github.com/AndyDune/RznViewComponent)
 *
 * @link https://github.com/AndyDune/RznViewComponent for the canonical source repository
 * @license http://www.opensource.org/licenses/mit-license.php  MIT License
 */

return array(
    'rznviewcomponent' => array(
        'cache_service' => 'cache_view_component',
        'cache_adapter' => array(
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
        'cache_remove_item_key' => 'slay_component_cache', // add parameter into request string to clear cache with current page
        'cache_allow'   => false, // check cache adapter options and set true to enable component cache
        'view_script_prefix' => '',
        'use_result_object' => false // set TRUE if you need retrieve extra data from component
    )
);
