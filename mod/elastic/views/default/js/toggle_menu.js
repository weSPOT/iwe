

function toggleContent(e){
    var a = $(this);
	//var card = a.attr("data-bind-target");
    var div = $("#"+a.attr("data-bind-target"));
    div.slideToggle(400);
   
    toggleIcon(div);
    
    e.preventDefault();
    return false;
}

function init(){
    $("a[data-bind-action='toggle-content']").click(toggleContent);
}

function toggleIcon(card)
{
   
    id= card.attr("id");

    obj1 = $(document.getElementById("icon1-"+id));
    obj2 = $(document.getElementById("icon2-"+id));
        
    if (!obj1.is(':visible')) // If not visible
        obj2.fadeToggle(200, function(){obj1.fadeToggle(200);});
    else
        obj1.fadeToggle(200, function(){obj2.fadeToggle(200);});
 

    return false;
    
}

$(document).ready(init);





