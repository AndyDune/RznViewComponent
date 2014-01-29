RznViewComponent
================

A ZF2 module that add view helper IncludeComponent.

The plugin includes three elements: service, view and cache.

Example:
		
	<?= $this-includeComponent('bank-info', 	
                               'component/bank-info', 	
                               array('id' = $this-id), 
                               array()) ?>
 
 These are 4 parameters:
 1. Registered service name.
 2. View file name. It point as for view helper "partial"
 3. Initial data to betray to service.
 4. Local configuration array.
 
 Plugin can be used. The detailed description will be provided later.