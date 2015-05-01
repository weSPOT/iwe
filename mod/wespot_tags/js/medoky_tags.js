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
}