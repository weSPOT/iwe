<?php
if (elgg_is_logged_in()){
	$user = elgg_get_logged_in_user_entity();
	$invitations = get_group_invitations($user->getGUID());
	$groups = get_user_groups($user->getGUID());
	if (is_array($invitations) && !empty($invitations))
		// forward to my invitations
		forward ('groups/invitations/'.$user->username);
	else if(is_array($groups) && !empty($groups))
		// forward to my inquiries
		forward ('groups/member/'.$user->username);
	else
		// forward to all inquiries
		forward ('groups/all');
} 
// else
// 	// forward to default login page (DEV site only)
// 	forward ('login');
	
function get_group_invitations($user_guid, $return_guids = FALSE) {
	$ia = elgg_set_ignore_access(TRUE);
	$groups = elgg_get_entities_from_relationship(array(
		'relationship' => 'invited',
		'relationship_guid' => $user_guid,
		'inverse_relationship' => TRUE,
		'limit' => 0,
	));
	elgg_set_ignore_access($ia);

	if ($return_guids) {
		$guids = array();
		foreach ($groups as $group) {
			$guids[] = $group->getGUID();
		}

		return $guids;
	}

	return $groups;
}

function get_user_groups($user_guid) {
	$user_groups = elgg_get_entities_from_relationship(array('relationship'=> 'member', 'relationship_guid'=> $user_guid, 'inverse_relationship'=> false, 'type'=> 'group', 'limit'=> false));
	return $user_groups;
}
?>

<!DOCTYPE HTML>
<html>
	<head>
		<title>weSPOT inquiry</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<!--[if lte IE 8]><script src="css/ie/html5shiv.js"></script><![endif]-->
		<script src="mod/elastic/js/jquery.min.js"></script>
		<script src="mod/elastic/js/jquery.dropotron.min.js"></script>
		<script src="mod/elastic/js/jquery.scrollgress.min.js"></script>
		<script src="mod/elastic/js/skel.min.js"></script>
		<script src="mod/elastic/js/skel-layers.min.js"></script>
		<script src="mod/elastic/js/init.js"></script>
		 <link rel="stylesheet" type="text/css" href="mod/elastic/css/form_style.css" />
			<link rel="stylesheet" href="mod/elastic/css/skel.css" />
			<link rel="stylesheet" href="mod/elastic/css/style.css" />
			<link rel="stylesheet" href="mod/elastic/css/style-wide.css" />
		<script language="javascript" type="text/javascript">
		function popitup(url) {
			newwindow = window.open(url,'name','location=1,status=0,scrollbars=0,width=800,height=570');
			if (window.focus) {newwindow.focus()}
			return false;
		}
		</script>	
	<!--[if lte IE 8]><link rel="stylesheet" href="mod/elastic/css/ie/v8.css" /><![endif]-->
	</head>
	<body class="landing">

		<!-- Banner -->
			<section id="banner">
				
				<h2>Welcome</h2>
                                
			<section class="main">
				<form class="form-3" action="action/login" method="post">
				
				<p><?php echo elgg_view("elgg_social_login/login"); ?></p>
				<p style="font-size: 14px;"><a href='http://wespot-arlearn.appspot.com/Account.jsp' onclick="return popitup('http://wespot-arlearn.appspot.com/Account.jsp')">Register for a weSPOT account</a></p>
				<p style="font-size: 14px;"><a href='http://wespot-arlearn.appspot.com/ResetPassword.html' onclick="return popitup('http://wespot-arlearn.appspot.com/ResetPassword.html')">Reset your password</a></p>
    
				    <p class="clearfix">

				    </p>       
				</form>​
			</section>
				
			</section>

		<!-- Main -->
			<section id="main" class="container">
		
				<section class="box special">
					<header class="major">
						The weSPOT inquiry space lets you create, share and perform scientific inquiries either individually or in groups.<br/>
						To learn more about what weSPOT offers to teachers, students and developers visit the <a href='http://wespot.net/en/for-educators' target='_blank'>project web site</a>.			
					</header>
					<span class="image featured"><img src="mod/elastic/images/pic01.jpg" alt="" /></span>
				</section>
	</body>
</html>