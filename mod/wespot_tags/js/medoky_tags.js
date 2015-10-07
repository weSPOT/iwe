medoky_tags = {
  test : function() {
    console.debug("Tag JS Working");
  },
  
  init : function() {
    console.debug("Tag JS Working");
    var tag = {id:"tag1", state:"selected"};   
  },

  select_tag : function(select) {
    var new_tag = select.textContent;
    new_tag.trim();	
    
    var tags = ($("#input_tags").val()).split(",");
   
    for (var i=0; i<tags.length; i++){
    	tags[i] = tags[i].trim();
    }
    console.log(tags);
    if (tags.indexOf(new_tag)==-1){
	$("#input_tags").val($("#input_tags").val() + ", " + new_tag);
    	if ($("#input_tags").val().indexOf(",") == 0)
          $("#input_tags").val($("#input_tags").val().substring(1));
    }	
  },
  
  add_recTags: function(tags){
 	 console.log(tags.recommendations);
 	var value = $("#sel_features").val(); 
 	 console.log("feature in addTags"+value);
 	$("#recommended_tags").val(tags.recommendations+" @Features "+value);
 	 if ($("#meritsTags").html().length>0){
 		$("#meritsTags").empty();
 	 }
 	 
     $("#meritsTags").append("<label for=\"medoky_tags_select\">"+elgg.echo('wespot_tags:label')+"</label>");
	 for (var i in tags.recommendations){	     
	 	$("#meritsTags").append( "<div class=\"medoky_tag\" height=\"16px\" width=\"16px\" type=\"image\" onclick=\"medoky_tags.select_tag(this)\">"+elgg.echo(tags.recommendations[i])+"</div>" );
	 }	
	 document.getElementById("meritsTags").style.display = 'inline'; 
 },
};

var medoky_backend = {
	    init : function(backend_url) { 
			medoky_backend.url = backend_url;
			path_evaluationData = "getTagRecommendationsByType/userId/#/courseId/#/algorithm/#/features/#/";
	    },
	    
	    getTagRecommendationPerAlgorithm : function(uid, cid, algorithm, features, callback) {
	        cid = cid ? cid : 1;
	        algorithm = algorithm ? algorithm : "Merits";

	        $("#sel_features").val(features);
	        console.log("features in webservice call "+$("#sel_features").val());
	        var tagRec_parts = path_evaluationData.split("#");
	        var path_tagRec = tagRec_parts[0] + uid + tagRec_parts[1] + cid +tagRec_parts[2] + algorithm+tagRec_parts[3]+features;
	        console.log(medoky_backend.url + path_tagRec);    
	        $.ajax({
	          cache : false,
	          type : "GET",
	          url : medoky_backend.url + path_tagRec,
	          success : function(obj) {
	              console.debug(obj);
	              if (callback)
	            	  callback(obj);
	           },
	            error : function(obj) {
	              console.error(JSON.stringify(obj));
	            }
	        });
	      }	     
	    };
