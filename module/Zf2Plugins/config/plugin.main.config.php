<?php  
return array( 
		'service_manager' => array(
				'invokables' => array(
						'plugins.toolbar' => 'Zf2Plugins\Toolbar\Collector\PluginsCollector',
				),
		), 
		'zenddevelopertools' => array(
				'profiler' => array(
						'collectors' => array( 
								'plugins.toolbar' => 'plugins.toolbar',
						),
				),
				'toolbar' => array(
						'entries' => array(
								'plugins.toolbar' => 'zend-developer-tools/toolbar/plugins-data',
						),
				),
		),
		
);
?>