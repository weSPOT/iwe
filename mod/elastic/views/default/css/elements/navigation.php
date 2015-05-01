<?php
/**
 * Navigation
 *
 * @package Elgg.Core
 * @subpackage UI
 */
?>

/* ***************************************
	PAGINATION
*************************************** */
.elgg-pagination {
	margin: 10px 0;
	display: block;
	text-align: center;
}
.elgg-pagination li {
	display: inline;
	margin: 0 6px 0 0;
	text-align: center;
}
.elgg-pagination a, .elgg-pagination span {
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
	border-radius: 4px;
	
	padding: 2px 6px;
	color: #4690d6;
	border: 1px solid #4690d6;
	font-size: 12px;
}
.elgg-pagination a:hover {
	background: #4690d6;
	color: white;
	text-decoration: none;
}
.elgg-pagination .elgg-state-disabled span {
	color: #CCCCCC;
	border-color: #CCCCCC;
}
.elgg-pagination .elgg-state-selected span {
	color: #555555;
	border-color: #555555;
}

/* ***************************************
	TABS
*************************************** */
.elgg-tabs {
	display: block;
	text-align: center;    
	margin-bottom: 4px;
	width: auto;
}
.elgg-tabs li {
    display: inline-block;
    vertical-align: top;
}
.elgg-tabs li:first-child a {
    border-left-width: 1px;
}
.elgg-tabs a { 
    display: block;
    margin: 0;
    padding: 2px 8px 4px;
    text-align: center;
    text-decoration: none;
    width: auto;
}
.elgg-tabs .elgg-state-selected {
	background-color: #4674B8; 
}
.elgg-tabs .elgg-state-selected a {
	color: #FFFFFF;
}

/* ***************************************
	BREADCRUMBS
*************************************** */
.elgg-breadcrumbs {
	font-size: 80%;
	font-weight: bold;
	line-height: 1.2em;
	color: #bababa;
}
.elgg-breadcrumbs > li {
	display: inline-block;
	padding: 3px 0;
}
.elgg-breadcrumbs > li:after {
	content: "\003E";
	padding: 0 4px;
	font-weight: normal;
}
.elgg-breadcrumbs > li > a {
	display: inline-block;
	color: #999;
}
.elgg-breadcrumbs > li > a:hover {
	color: #0054a7;
	text-decoration: underline;
}

.elgg-main .elgg-breadcrumbs {
	position: relative;

}

/* ***************************************
	TOPBAR MENU ELSATIC
*************************************** */
.elgg-menu-elastic-top {
	float: left;
}

.elgg-menu-elastic-top > li {
	float: left;
}

.elgg-menu-elastic-top > li > a {
	padding-top: 2px;
	color: #eee;
	margin: 1px 15px 0;
}

.elgg-menu-elastic-top > li > a:hover {
	color: #4690D6;
	text-decoration: none;
}

.elgg-menu-elastic-top-settings {
	float: right;
	display: block;
}

.elgg-menu-elastic-top .elgg-icon {
	vertical-align: middle;
	margin-top: -1px;
}

.elgg-menu-elastic-top > li > a.elgg-elastic-top-logo {
	margin-top: 0;
	padding-left: 5px;
	width: 38px;
	height: 20px;
}

.elgg-menu-elastic-top > li > a.elgg-elastic-top-avatar {
	width: 18px;
	height: 18px;
}

.elgg-menu-elastic-drop > li > a {
	color: #FFF;
	font-size: 120%;
	padding: 10px 0;
	border-top: 1px solid #296AD2;
}

div#elastic-toggle-button a {
	color: #EEE;
}


div#elastic-drop-menu-wrapper {
	display: none;
}

div#elastic-drop-menu-wrapper {
	display: none;
}

.elgg-menu-elastic-top-a_menu-after {
	float: right;
	display: none;
}

div#elastic-css-wrapper {
	display: none;
}

div#toplogo {
	width: auto;
}	


@media screen and (max-width: 640px) {
	.elgg-menu-elastic-top-a_menu-after {
		float: right;
		display: block;
	}
	.elgg-menu-elastic-top-settings {
		display: none;
	}
	div#elastic-css-wrapper {
		display: block;
	}
	div#toplogo {
		width: 30px;
	}	
	div#toplogo > img{
		margin: 0;
	}	

}

@media (max-device-width:940px) {
	div#toplogo {
		width: 60px;
	}	
	div#toplogo > img{
		margin: 0;
	}	
}

@media screen and (min-width:641px) {
	div#elastic-drop-menu-wrapper {
		display: none;
	}
}

/* ***************************************
	SITE MENU
*************************************** */
.elgg-menu-site {
	z-index: 1;
}

.elgg-menu-site > li > a {
	font-weight: bold;
	padding: 3px 13px 0px 13px;
	height: 20px;
}

.elgg-menu-site > li > a:hover {
	text-decoration: none;
}

.elgg-menu-site-default {
	position: absolute;
	bottom: 0;
	left: 0;
	height: 23px;
}

.elgg-menu-site-default > li {
	float: left;
	margin-right: 1px;
}

.elgg-menu-site-default > li > a {
	color: white;
}

.elgg-menu-site > li > ul {
	display: none;
	background-color: white;
}

.elgg-menu-site > li:hover > ul {
	display: block;
}

.elgg-menu-site-default > .elgg-state-selected > a,
.elgg-menu-site-default > li:hover > a {
	background: white;
	color: #555;

	-webkit-box-shadow: 2px -1px 1px rgba(0, 0, 0, 0.25);
	-moz-box-shadow: 2px -1px 1px rgba(0, 0, 0, 0.25);
	box-shadow: 2px -1px 1px rgba(0, 0, 0, 0.25);


}

.elgg-menu-site-more {
	position: relative;
	//left: -1px;
	width: 100%;
	min-width: 150px;
	//border: 1px solid #999;
	border-top: 0;

	-webkit-box-shadow: 1px 1px 1px rgba(0, 0, 0, 0.25);
	-moz-box-shadow: 1px 1px 1px rgba(0, 0, 0, 0.25);
	box-shadow: 1px 1px 1px rgba(0, 0, 0, 0.25);
}

.elgg-menu-site-more > li > a {
	background-color: white;
	color: #555;

	-webkit-border-radius: 0;
	-moz-border-radius: 0;
	border-radius: 0;

	-webkit-box-shadow: none;
	-moz-box-shadow: none;
	box-shadow: none;
}

.elgg-menu-site-more > li > a:hover {
	background: #4690D6;
	color: white;
}

.elgg-menu-site-more > li:last-child > a,
.elgg-menu-site-more > li:last-child > a:hover {

}

.elgg-more > a:before {
	content: "\25BC";
	font-size: smaller;
	margin-right: 4px;
}

/* ***************************************
	TITLE
*************************************** */
.elgg-menu-title {
	float: right;
}

.elgg-menu-title > li {
	display: inline-block;
	margin-left: 4px;
}

/* ***************************************
	FILTER MENU
*************************************** */
.elgg-menu-filter {
	margin-bottom: 5px;
	border-bottom: 2px solid #ccc;
	display: table;
	width: 100%;
}
.elgg-menu-filter > li {
	float: left;
	border: 2px solid #ccc;
	border-bottom: 0;
	background: #eee;
	margin: 0 0 0 10px;
	
	-webkit-border-radius: 5px 5px 0 0;
	-moz-border-radius: 5px 5px 0 0;
	border-radius: 5px 5px 0 0;
}
.elgg-menu-filter > li:hover {
	background: #dedede;
}
.elgg-menu-filter > li > a {
	text-decoration: none;
	display: block;
	padding: 3px 10px 0;
	text-align: center;
	height: 21px;
	color: #999;
}
.elgg-menu-filter > li > a:hover {
	background: #dedede;
	color: #4690D6;
}
.elgg-menu-filter > .elgg-state-selected {
	border-color: #ccc;
	background: white;
}
.elgg-menu-filter > .elgg-state-selected > a {
	position: relative;
	top: 2px;
	background: white;
}

/* ***************************************
	PAGE MENU
*************************************** */
.elgg-menu-page {
	margin-bottom: 15px;
}

.elgg-menu-page a {
	display: block;
	
	-webkit-border-radius: 8px;
	-moz-border-radius: 8px;
	border-radius: 8px;
	
	background-color: white;
	margin: 0 0 3px;
	padding: 2px 4px 2px 8px;
}
.elgg-menu-page a:hover {
	background-color: #0054A7;
	color: white;
	text-decoration: none;
}
.elgg-menu-page li.elgg-state-selected > a {
	background-color: #4690D6;
	color: white;
}
.elgg-menu-page .elgg-child-menu {
	display: none;
	margin-left: 15px;
}
.elgg-menu-page .elgg-menu-closed:before, .elgg-menu-opened:before {
	display: inline-block;
	padding-right: 4px;
}
.elgg-menu-page .elgg-menu-closed:before {
	content: "\002B";
}
.elgg-menu-page .elgg-menu-opened:before {
	content: "\002D";
}

/* ***************************************
	HOVER MENU
*************************************** */
.elgg-menu-hover {
	display: none;
	position: absolute;
	z-index: 10000;

	overflow: hidden;

	min-width: 165px;
	max-width: 250px;
	border: solid 1px;
	border-color: #E5E5E5 #999 #999 #E5E5E5;
	background-color: #FFF;
	
	-webkit-box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.50);
	-moz-box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.50);
	box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.50);
}
.elgg-menu-hover > li {
	border-bottom: 1px solid #ddd;
}
.elgg-menu-hover > li:last-child {
	border-bottom: none;
}
.elgg-menu-hover .elgg-heading-basic {
	display: block;
}
.elgg-menu-hover a {
	padding: 2px 8px;
	font-size: 92%;
}
.elgg-menu-hover a:hover {
	background: #ccc;
	text-decoration: none;
}
.elgg-menu-hover-admin a {
	color: red;
}
.elgg-menu-hover-admin a:hover {
	color: white;
	background-color: red;
}

/* ***************************************
	SITE FOOTER
*************************************** */
.elgg-menu-footer > li,
.elgg-menu-footer > li > a {
	display: inline-block;
	color: #999;
}

.elgg-menu-footer > li:after {
	content: "\007C";
	padding: 0 4px;
}

.elgg-menu-footer-default {
	float: right;
}

.elgg-menu-footer-alt {
	float: left;
}

/* ***************************************
	GENERAL MENU
*************************************** */
.elgg-menu-general > li,
.elgg-menu-general > li > a {
	display: inline-block;
	color: #999;
}

.elgg-menu-general > li:after {
	content: "\007C";
	padding: 0 4px;
}

/* ***************************************
	ENTITY AND ANNOTATION
*************************************** */
<?php // height depends on line height/font size ?>
.elgg-menu-entity, elgg-menu-annotation {
	float: right;
	margin-left: 15px;
	font-size: 90%;
	color: #aaa;
	line-height: 16px;
	height: 16px;
}
.elgg-menu-entity > li, .elgg-menu-annotation > li {
	margin-left: 15px;
}
.elgg-menu-entity > li > a, .elgg-menu-annotation > li > a {
	color: #aaa;
}
<?php // need to override .elgg-menu-hz ?>
.elgg-menu-entity > li > a, .elgg-menu-annotation > li > a {
	display: block;
}
.elgg-menu-entity > li > span, .elgg-menu-annotation > li > span {
	vertical-align: baseline;
}

/* ***************************************
	OWNER BLOCK
*************************************** */
.elgg-menu-owner-block li a {
	display: block;
	
	-webkit-border-radius: 8px;
	-moz-border-radius: 8px;
	border-radius: 8px;
	
	background-color: white;
	margin: 3px 0 5px 0;
	padding: 2px 4px 2px 8px;
}
.elgg-menu-owner-block li a:hover {
	background-color: #0054A7;
	color: white;
	text-decoration: none;
}
.elgg-menu-owner-block li.elgg-state-selected > a {
	background-color: #4690D6;
	color: white;
}

/* ***************************************
	LONGTEXT
*************************************** */
.elgg-menu-longtext {
	float: right;
}

/* ***************************************
	RIVER
*************************************** */
.elgg-menu-river {
	float: right;
	margin-left: 15px;
	font-size: 90%;
	color: #aaa;
	line-height: 16px;
	height: 16px;
}
.elgg-menu-river > li {
	display: inline-block;
	margin-left: 5px;
}
.elgg-menu-river > li > a {
	color: #aaa;
	height: 16px;
}
<?php // need to override .elgg-menu-hz ?>
.elgg-menu-river > li > a {
	display: block;
}
.elgg-menu-river > li > span {
	vertical-align: baseline;
}

/* ***************************************
	SIDEBAR EXTRAS (rss, bookmark, etc)
*************************************** */
.elgg-menu-extras {
	margin-bottom: 15px;
}

/* ***************************************
	WIDGET MENU
*************************************** */
.elgg-menu-widget > li {
	position: absolute;
	top: 4px;
	display: inline-block;
	width: 18px;
	height: 18px;
	padding: 2px 2px 0 0;
}

.elgg-menu-widget > .elgg-menu-item-collapse {
	left: 5px;
}
.elgg-menu-widget > .elgg-menu-item-delete {
	right: 5px;
}
.elgg-menu-widget > .elgg-menu-item-settings {
	right: 25px;
}

/* ***************************************
	HEADER MENU
*************************************** */
#sddm
{	margin: 0;
	padding: 0;
	z-index: 30}

#sddm li
{	margin: 0;
	padding: 0;
	list-style: none;
	float: left}

#sddm li a
{	display: block;
	margin: 0 1px 0 0;
	padding: 4px 10px;
	width: 60px;
	background: #3A8CE4;
	color: #FFF;
	text-align: center;
	text-decoration: none}

#sddm li a:hover
{	background: #49A3FF}

#sddm div
{	list-style-type: none;
    position: absolute;
    z-index: 1;
	padding: 0;
    margin: 0;
    text-align: left;
    visibility: hidden;
	background: #dedede;
	border: 1px solid #5970B2}

	#sddm div a
	{	position: relative;
		display: block;
		margin: 0;
		padding: 5px 10px;
		width: auto;
		white-space: nowrap;
		text-align: left;
		text-decoration: none;
		background: #dedede;
		color: #2875DE}

	#sddm div a:hover
	{	background: #49A3FF;
		color: #FFF}
