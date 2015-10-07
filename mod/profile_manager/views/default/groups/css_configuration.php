

.clearfix:after {
  content: ".";
  display: block;
  clear: both;
  visibility: hidden;
  line-height: 0;
  height: 0;
}

.clearfix {
  display: inline-block;
}

html[xmlns] .clearfix {
  display: block;
}

* html .clearfix {
  height: 1%;
}


.access_selector {
  display: none;
}

.configuration {
    margin-top: 15px;
    //height: 300px;
    margin-bottom: 40px;
}

.instruction {
    color: gray;
}

.phases {
width: 220px;
float: left;
}

.phase_wrapper .triangle {
float: left;
width: 20px;
padding-top: 10px;
}

.phase_wrapper .triangle.light {
color: #222;
}

.phase_wrapper .close {
cursor: pointer;
margin-left: -12px;
float: left;
color: white;
}


.phases .phase {
padding: 10px;
float: left;
width: 170px;
border: 1px solid black;
margin-bottom: 1px;
cursor: pointer;
}

.phases .phase:hover {
//opacity: 0.7;
}

.phases .close {
//border: 1px solid white;
}

.phases .close:hover {
  opacity: 0.7;
  //color: black;
}

.phases .phase.selected {
color: white;
background-color: #4C7CBB;
}

.phases .phase:hover {
    //opacity: 0.7;
}

.activities_panel {
  width: 480px;
  float: left;
  margin-left: 20px;
}

.activities .activity .skills {
    display: block;
    font-size: 0.85em;
}

p.activity:hover {
    cursor: pointer;
    background-color: #eee;
}

.activities .widget {
    font-size: 0.85em;
    display: block;
}

.activities .activity.emphasized {
    background-color: #7C7C7C;
    color: white;
}

.activities_panel label {
  color: black;
  font-weight: normal;
}

.activities_panel p {
margin-bottom: 2px;
font-size: 0.9em;
padding: 3px;
border: 1px solid white;
}

.activities_panel p.selected {
background-color: #a3add2;
color: black;
}

.activities_panel .select_options {
display:none;
}
