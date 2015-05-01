<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>

form.elastic-search {
	width: 250px;
	float: right;
}

@media screen and (max-width: 1100px) {
	form.elastic-search {
		width: 200px;
	}
}

@media screen and (max-width: 800px) {
	form.elastic-search {
		width: 200px;
	}
}
	
@media screen and (max-width: 640px) {
	form.elastic-search {
		width: 100%;
	}
}

.elastic-search-header {
	bottom: 5px;
	height: 23px;
	position: absolute;
	right: 0;
}
.elastic-search input[type=text] {
	width: 100%;
}
.elastic-search input[type=submit] {
	display: none;
}
.elastic-search input[type=text] {
	-webkit-border-radius: 10px;
	-moz-border-radius: 10px;
	border-radius: 10px;
	border: 1px solid #71b9f7;
	color: white;
	font-size: 12px;
	font-weight: bold;
	padding: 2px 4px 2px 26px;
	background: transparent url(<?php echo elgg_get_site_url(); ?>_graphics/elgg_sprites.png) no-repeat 2px -934px;
	moz-transition: background 0.5s ease;
	transition: background 0.3s ease;
}
.elastic-search input[type=text]:focus, .elgg-search input[type=text]:active {
	background-color: white;
	background-position: 2px -916px;
	border: 1px solid white;
	color: #0054A7;
}

.search-list li {
	padding: 5px 0 0;
}
.search-heading-category {
	margin-top: 20px;
	color: #666666;
}

.search-highlight {
	background-color: #bbdaf7;
}
.search-highlight-color1 {
	background-color: #bbdaf7;
}
.search-highlight-color2 {
	background-color: #A0FFFF;
}
.search-highlight-color3 {
	background-color: #FDFFC3;
}
.search-highlight-color4 {
	background-color: #ccc;
}
.search-highlight-color5 {
	background-color: #4690d6;
}
