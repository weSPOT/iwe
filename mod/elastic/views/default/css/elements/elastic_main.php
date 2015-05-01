<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>


body {
	margin: 0;
	padding: 0;
	height: 100%;
	font-family: arial;
}

p {
	color: #333;
}

.clearfix {
	float: none;
	clear: both;
}

.comhype-logo img {
	margin-top: -7px;
}

/*************** site wrapper  **************
 * this color will be the footer color
 * **/

div#elastic-site-wrapper {
	min-height:100%;
	height: auto;
	background-color: #333;
}


div.elastic-wrapper {
	display: block;
}

div.elastic-wrapper-inside {
	display: block;
	width: 1000px;
	margin: 0 auto;
}

@media screen and (max-width: 1100px) {
	div.elastic-wrapper-inside {
		width: 800px
	}
}

@media screen and (max-width: 800px) {
	div.elastic-wrapper-inside {
		width: 640px
	}
}
@media screen and (max-width: 640px) {
	div.elastic-wrapper-inside {
		width: 100%
	}
	.elgg-col-1of3 {
		display: block;
		float: none;
		width: 100%;
	}
	.profile elgg-col-2of3 {
		display: block;
		float: none;
		width: 100%;
	}
	.profile {
		display: block;
		float: none;
		width: 100%;
	}
	
	div#profile-owner-block {
		display: block;
		float: none;
		width: 100%;
	}
	
	div#profile-details {
		display: block;
		float: none;
		width: 100%;
	}
	
}

div.elastic-content-wrapper {
	display: block;
	padding: 0;
}

div.elastic-content {
	display: block;
	padding: 10px;
}



/*************** TOPBAR ****************/

div#elastic-topbar {
	display: block;
	min-height: 40px;
	background-color: #094AB2;
	color: #FFF;
}

/*************** HEADER ****************/

div#elastic-header {
	display: block;
	background-color: #2A7CD4;
}

div#elastic-header-content {

	background-color: #3A8CE4;
}


/*************** MAIN ****************/

div#elastic-main-wrapper {
	background-color: white;
}

div#elastic-main-content {
	background-color: white;
}

div#elastic-column-right {
	width: 250px;
	min-height: 100px;
	float: right;
	display: block;
}

div#elastic-column-main {
	display: block;
	min-height: 100px;
	position: relative;
	float: left;
	width: 750px;
}

@media screen and (max-width: 1100px) {
	div#elastic-column-right {
		width: 200px;
	}

	div#elastic-column-main {
		width: 600px;
	}
}

@media screen and (max-width: 800px) {
	div#elastic-column-right {
		width: 200px;
	}
	div#elastic-column-main {
		width: 440px;
	}
}
	
@media screen and (max-width: 640px) {
	div#elastic-column-right {
		width: 100%;
	}
	div#elastic-column-main {
		width: 100%;
	}

}



/*************** FOOTER ****************/


div#elastic-footer {
   width:100%;
   color: #FFF;  
   font-size: 14px;
   min-height: 100px;
   color: #AAA;
   font-size: 90%;
}

div#elastic-footer-content {
	background-color: #444;
}

div#elastic-footer p {
	color: #AAA;
}


div#elastic-footer-left {
	width: 250px;
	float: left;
}

div#elastic-footer-main {
	width: 500px;
	float: left;
}

div#elastic-footer-right {
	width: 250px;
	float: left;
}


@media screen and (max-width: 1100px) {
	div#elastic-footer-left {
		width: 200px;
	}
	div#elastic-footer-main {
		width: 400px;
	}
	div#elastic-footer-right {
		width: 200px;
	}
}

@media screen and (max-width: 800px) {
	div#elastic-footer-left {
		width: 200px;
	}
	div#elastic-footer-main {
		width: 240px;
	}
	div#elastic-footer-right {
		width: 200px;
	}
}
	
@media screen and (max-width: 640px) {
	div#elastic-footer-left {
		width: 100%;
		float: none;
		border-bottom: 2px dotted #333;
	}
	div#elastic-footer-main {
		width: 100%;
		float: none;
		border-bottom: 2px dotted #333;
	}
	div#elastic-footer-right {
		width: 100%;
		float: none;
	}
}




/*************** MAIN-MENU ****************/



div.elastic-menu-wrapper {
	padding: 5px 0;
}

div#elastic-main-menu {
	display: block;
}

div#elastic-main-menu ul {
	padding: 0;
	margin: 0;
}

div#elastic-main-menu li {
	color: #DDD;
	padding: 0;
	line-height: 28px;
	font-size: 14px;
}

div#elastic-main-menu li a{
	text-decoration:none;
	color: #FFF;
}
div#elastic-main-menu li a:hover{
	text-shadow: 1px 2px 2px rgba(0,0,0,0.7);
}

div.elastic-item {
	float: left;
	padding: 0px 10px;
	margin-right: 3px;
	margin-bottom: 3px;
	text-shadow: 1px 1px 2px rgba(0,0,0,0.4);
	font-size: 12.5px;
}

div.elastic-item:hover {
	text-shadow: 1px 2px 2px rgba(0,0,0,0.7);
	text-decoration: underline;
	//border-bottom: 2px solid #FFF;

}


div#elastic-main-menu li:hover {
	background: #000;
}



@media screen and (max-width: 480px){
	div#elastic-main-menu li {
		display: block;
		line-height: 32px;
		margin-bottom: 5px;
	}
	div#elastic-main-menu li:last-child {
		margin-bottom: 0;
	}
}


/**************** modules ******************/

div.elgg-module {
	padding: 0;
}

div.elgg-module div.elgg-head{
	background-color: #FFF;
}

div.elgg-module div.elgg-body{
	background-color: #FFF;
	border-top: 1px solid #DEDEDE;
}

div.elastic-content div.elgg-body, div.elastic-content div.elgg-head{
	background-color: transparent;
}

li.elgg-menu-item-site-logo {
	height: 18px;
}



div#IE8less {

}













