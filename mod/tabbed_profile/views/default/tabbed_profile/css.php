.elgg-tabs .tabbed-profile-add {
  background-color: #6B7A2A;
}

.elgg-tabs .tabbed-profile-add a:hover {
  background: none repeat scroll 0 0 #3387CA;
  border-radius: 3px;
}

.elgg-tabs .tabbed-profile-edit {
  display: none;
}

.elgg-tabs li:hover .tabbed-profile-edit {
  display: inline-block;
  vertical-align: middle;
  margin-left: 6px;
}

.elgg-tabs li a.tabbed-profile {
  display: inline-block;
}

.tabbed_profile_iframe_height {
  width: 60px;
}

li.tabbed-profile-sortable, li.tabbed-profile-sortable a {
  cursor: move;
}

li.tabbed-profile-sortable a span.tabbed-profile-edit {
  cursor: pointer;
}

#profile-tabs-container {
  margin-top: 10px;
  margin-bottom: 10px;
}

.arrow_box, .arrow_box_last, .arrow_box_selected {
	position: relative;
	background: #d5d5d5;
	border: 10px solid #ffffff;
}
.arrow_box:after, .arrow_box:before, .arrow_box_last:after, .arrow_box_last:before, .arrow_box_selected:after, .arrow_box_selected:before {
	left: 100%;
	top: 50%;
	border: solid transparent;
	content: " ";
	height: 0;
	width: 0;
	position: absolute;
	pointer-events: none;
}
.arrow_box:after {
	border-color: rgba(213, 213, 213, 0);
	border-left-color: #d5d5d5;
	border-width: 10px;
	margin-top: -10px;
}
.arrow_box_selected:after{
	border-color: rgba(213, 213, 213, 0);
	border-left-color: #4674B8;
	border-width: 10px;
	margin-top: -10px;
}
.arrow_box:before, .arrow_box_selected:before {
	border-color: rgba(194, 225, 245, 0);
	border-left-color: #ffffff;
	border-width: 24px;
	margin-top: -24px;
}