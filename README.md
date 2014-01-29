RznViewComponent
================

A ZF2 module that add view helper IncludeComponent.

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

## Finaly
 Plugin can be used. The detailed description will be provided later.
