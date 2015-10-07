<?php
	 
//some required params
$golab_lab_url = $vars['entity']->url;

$golab_lab_height = (int) $vars['entity']->height ? (int) $vars['entity']->height : 300;

if($golab_lab_url){
?>

<iframe  width="100%" height="<?php echo $golab_lab_height; ?>" src="<?php echo $golab_lab_url; ?>" ><?php echo elgg_echo('golab_lab:no:iframes'); ?></iframe>

<?php 

} else {
        
	echo elgg_echo("golab_lab:none");
        
}

