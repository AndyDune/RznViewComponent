RznViewComponent
================

A ZF2 module that add view helper IncludeComponent. Lightweight instrument for insert into the page a finished local block witch implements chain: model-template-cache.

The plugin includes three elements: service, view and cache.


## Basic Using

```	
    <?= $this->includeComponent('bank-info', 	
                                'component/bank-info', 	
                                array('id' = $this->id), 
                                array()) ?>
```
 
#### There are 4 parameters:
 
 1. Registered service name.
 2. View file name. It point as for view helper "partial"
 3. Initial data to betray to service.
 4. Local configuration array.
 


## Installation via Composer

### Steps 

#### 1. Add to composer.
```
    "require" : {
        "andydune/rzn-view-component": "dev-master"
    }
```

#### 2. Create *rzn-view-component.global.php* in *config/autoload* with configuration (/config/module.config.php) if you need to change configuration.
```
    /module.config.php to /config/autoload/rzn-view-component.global.php
```

#### 3. Add module to application config (/config/application.config.php)
```
   ...
   'modules' => array(
        'RznViewComponent',
   ),
   ...
```
## Configuration

Here is basic configuration:
```php
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
        'cache_remove_item_key' => 'slay_component_cache', 
        'cache_allow'   => false, // check cache adapter options and set true to enable component cache
        'view_script_prefix' => '', // not used yet
        'use_result_object' => false
    )
); 
```
If you want to make changes copy /module.config.php to /config/autoload/rzn-view-component.global.php or insert part of configuration array into your application config.

### Config parameters

#### 1. cache_service

Name of cache service. Module has its own desctipted cashe service "cache_view_component".You can specify you own service which implements Zend\Cache\Storage\StorageInterface.


#### 2. cache_adapter

Configuration array for Zend cache factory. Here is default values.

#### 3. cache_remove_item_key

Parameter specifies the name of a variable from a query, and a positive value which resets the cache component of the current sute page. Specify a blank value, if you want to disable this ability.


Example: `http://mymegasite.com/catalog/frogs.html?slay_component_cache=1`

#### 3. use_result_object

By default view helper `includeComponent` returns html to show on a site page. If you need helper returns extra data (tile for block, result flag) set `true`. What values component will return you need to define in the fourth parameter of helper. For this purpose use the key `result_key_return`.

Example:
```php
    <?= $this->includeComponent('bank-info', 'component/bank-info', 	
                                array('id' = $this->id), 
                                array('result_key_return' => array('title'))) ?>
```

Value for `title` comes from data array which service `bank-info` transfers into view.
Result object implements array interface and has magic function `__toString`.

## Finally
 Plugin can be used. The detailed description will be provided later.
