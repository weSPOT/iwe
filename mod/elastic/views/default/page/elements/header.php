<script type="text/javascript">
// Copyright 2006-2007 javascript-array.com

var timeout	= 500;
var closetimer	= 0;
var ddmenuitem	= 0;

// open hidden layer
function mopen(id)
{	
	// cancel close timer
	mcancelclosetime();

	// close old layer
	if(ddmenuitem) ddmenuitem.style.visibility = 'hidden';

	// get new layer and show it
	ddmenuitem = document.getElementById(id);
	ddmenuitem.style.visibility = 'visible';

}
// close showed layer
function mclose()
{
	if(ddmenuitem) ddmenuitem.style.visibility = 'hidden';
}

// go close timer
function mclosetime()
{
	closetimer = window.setTimeout(mclose, timeout);
}

// cancel close timer
function mcancelclosetime()
{
	if(closetimer)
	{
		window.clearTimeout(closetimer);
		closetimer = null;
	}
}

// close layer when click-out
document.onclick = mclose; 

</script>

<ul id="sddm">
	<li><a href="/"><?php echo elgg_echo('elastic:home'); ?></a></li>
    <li><a href="#" 
        onmouseover="mopen('m1')" 
        onmouseout="mclosetime()"><?php echo elgg_echo('groups'); ?></a>
        <div id="m1" 
            onmouseover="mcancelclosetime()" 
            onmouseout="mclosetime()">
        <a href="/groups/all"><?php echo elgg_echo('groups:all'); ?></a>
        <?php echo '<a href="/groups/member/'.elgg_get_logged_in_user_entity()->username.'">'; ?><?php echo elgg_echo('groups:yours'); ?></a>
        <?php echo '<a href="/groups/owner/'.elgg_get_logged_in_user_entity()->username.'">'; ?><?php echo elgg_echo('groups:owned'); ?></a>
        <?php echo '<a href="/groups/invitations/'.elgg_get_logged_in_user_entity()->username.'">'; ?><?php echo elgg_echo('groups:invitations'); ?></a>
        </div>
    </li>
    <li><a href="/members"><?php echo elgg_echo('members'); ?></a></li>
<!-- 
    <li><a href="/activity"><?php echo elgg_echo('river:widget:title'); ?></a></li>
 -->
    <li><a href="#" 
        onmouseover="mopen('m2')" 
        onmouseout="mclosetime()"><?php echo elgg_echo('help'); ?></a>
        <div id="m2" 
            onmouseover="mcancelclosetime()" 
            onmouseout="mclosetime()">
        <a href="/screencasts"><?php echo elgg_echo('videos'); ?></a>
        <?php echo '<a href="'.get_phases_help().'">'; ?><?php echo elgg_echo('group_tools:methodology_guides'); ?></a>
        <?php echo '<a href="'.get_widgets_help().'">'; ?><?php echo elgg_echo('widgets'); ?></a>
        <?php echo '<a href="'.get_quizzes().'">'; ?><?php echo elgg_echo('izap-contest:contests'); ?></a>
        <a href="http://wespot.net/en/for-educators" target="_blank"><?php echo elgg_echo('elastic:wespot_web_site'); ?></a>
        </div>
    </li>
</ul>

<?php

function get_phases_help() {
	$help = elgg_get_entities_from_metadata(array(
	  'type' => 'object',
	  'subtype' => 'help_top',
	  'metadata_name_value_pairs' => array('tags' => elgg_echo('group_tools:methodology_guides')),
	));
	if($help) {
	  $url = $help[0]->getURL(); 
	} else { // display English help page by default
	  $help = elgg_get_entities_from_metadata(array(
	  'type' => 'object',
	  'subtype' => 'help_top',
	  'metadata_name_value_pairs' => array('tags' => 'Methodology guides'),
	  ));
	  if($help)
		$url = $help[0]->getURL(); 
	}
	return $url;
}

function get_widgets_help() {
	$help = elgg_get_entities_from_metadata(array(
	  'type' => 'object',
	  'subtype' => 'help_top',
	  'metadata_name_value_pairs' => array('tags' => elgg_echo('widgets')),
	));
	if($help) {
	  $url = $help[0]->getURL(); 
	} else { // display English help page by default
	  $help = elgg_get_entities_from_metadata(array(
	  'type' => 'object',
	  'subtype' => 'help_top',
	  'metadata_name_value_pairs' => array('tags' => 'Inquiry components'),
	  ));
	  if($help)
		$url = $help[0]->getURL(); 
	}
	return $url;
}

function get_quizzes() {
    $quizzes = elgg_get_entities_from_metadata(array(
      'type' => 'object',
      'subtype' => 'help_top',
      'metadata_name_value_pairs' => array('tags' => elgg_echo('izap-contest:contests')),
    ));
    if($quizzes) {
      $url = $quizzes[0]->getURL();
    } else { // display English quizzes page by default
      $quizzes = elgg_get_entities_from_metadata(array(
      'type' => 'object',
      'subtype' => 'help_top',
      'metadata_name_value_pairs' => array('tags' => 'Quizzes'),
      ));
      if($quizzes)
        $url = $quizzes[0]->getURL(); 
    }
    return $url;

}
?>