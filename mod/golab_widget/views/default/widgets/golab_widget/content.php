<?php
	 
//some required params
$golab_widget_url = $vars['entity']->url;

$golab_widget_height = (int) $vars['entity']->height ? (int) $vars['entity']->height : 300;
$golab_widget_container = 'http://shindig2.epfl.ch/gadgets/ifr?nocache=0&url=';

if($golab_widget_url){
?>

<iframe  width="100%" height="<?php echo $golab_widget_height; ?>" src="<?php echo $golab_widget_container . $golab_widget_url; ?>" ><?php echo elgg_echo('golab_widget:no:iframes'); ?></iframe>

<?php 

} else {
        
	echo elgg_echo("golab_widget:none");
        
}

