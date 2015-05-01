(function( $ ){
  $.fn.create = function() {
    var wrapped=true;
    var numAtts= 2;
    var index=numAtts-1;
    if(arguments.length == numAtts+1){
      wrapped=arguments[arguments.length-1];
      numAtts++;
    }
    if(arguments[0] == "txt") {
      var elem=document.createTextNode(arguments[1]);
      numAtts++;
    }else
      var elem= document.createElement(arguments[0]);
    if(arguments.length==numAtts) {
      var atts = arguments[index];
        for(var attr in atts){
          $(elem).attr(attr,atts[attr]);
        }
    }
    this.append(elem);
    if(wrapped)
      return $(elem);
    else
      return elem;
  };
})( jQuery );
