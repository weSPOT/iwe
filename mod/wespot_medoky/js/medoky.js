function MEDoKyRecommendation(type, text, link, linkTitle, description, id, learningObjectId, recAlgorithm) {
  this.fresh = false;
  this.rating = {q0:"undefined", q1:"undefined", q2:"undefined", q3:"undefined"};
    
  this.getIcon = function() {
      return "img/resource.svg";
  };

  this.getType = function() {
    return type;
  };

  this.getId = function() {
    return id;
  };

  this.getText = function() {
    return text;
  };

  this.getDescription = function() {
    return description;
  };

  this.getLink = function() {
    return link;
  };

  this.getLinkTitle = function() {
    return linkTitle;
  };

  this.getLearningObjectId = function() {
    return learningObjectId;
  }
  
  this.getRecAlgorithm = function() {
	    return recAlgorithm;
	  }
  
  this.setRating = function(answer, value) {
	  	this.rating[answer] = value;
  }

  this.getRating = function(answer) {
	    return this.rating[answer];
  }
   
  this.ratingCompleted= function(){
	  if (this.rating["q0"]=="undefined" ||this.rating["q1"]=="undefined"||this.rating["q2"]=="undefined"||this.rating["q3"]=="undefined" )
		  return false;
	  return true;
  }
  
}
MEDoKyRecommendation.TYPE_RESOURCE = "LearningResource";

function RecommendationContainer() {
  var length = 0;
  var container = {};
  var debugId = parseInt(Math.random() * 1000);
  this.getLength = function() {
    return length;
  };

  this.add = function(recommendation) {
    ++length;
    container[recommendation.getId()] = recommendation;
  };

  this.getAndRemove = function(idOrRecommendation) {
    var id = idOrRecommendation;
    if (idOrRecommendation instanceof MEDoKyRecommendation)
      id = idOrRecommendation.getId();
    var rec = this.get(id);
    //this.remove(idOrRecommendation);
    this.remove(id);
    return rec;
  }

  this.remove = function(idOrRecommendation) {
    if (length == 0)
      throw "The Container " + debugId + " is already Empty";
    var id = idOrRecommendation;
    if (idOrRecommendation instanceof MEDoKyRecommendation)
      id = idOrRecommendation.getId();
    delete container[id];
    --length;
  }

  this.get = function(id) {
    return container[id];
  }

  this.isEmpty = function() {
    return length == 0;
  }

  this.getDebugId = function() {
    return debugId;
  }

  this.toArray = function() {
    var array = [];
    for ( var i in container) {
      array.push(container[i]);
    }
    return array;
  }

  this.iterator = function() {
    var array = this.toArray();
    var i = 0;
    return function() {
      if (i < array.length)
        return array[i++];
    };
  }
}

function MEDoKyREcommendations() {
  var recommendationsByType = {};
  var recommendations = new RecommendationContainer();
  recommendationsByType[MEDoKyRecommendation.TYPE_ACTIVITY] = new RecommendationContainer();
  recommendationsByType[MEDoKyRecommendation.TYPE_PEER] = new RecommendationContainer();
  recommendationsByType[MEDoKyRecommendation.TYPE_RESOURCE] = new RecommendationContainer();

  this.put = function(recommendation) {
    recommendationsByType[recommendation.getType()].add(recommendation);
    recommendations.add(recommendation);
  }

  this.getAndRemove = function(idOrRecommendation) {
    var id = idOrRecommendation;
    if (idOrRecommendation instanceof MEDoKyRecommendation)
      id = idOrRecommendation.getId();
    var rec = this.get(id);
    //this.remove(idOrRecommendation);
    this.remove(id);
    return rec;
  }

  this.get = function(id) {
    return recommendations.get(id);
  }

  this.remove = function(id) {
    try {
      var rec = recommendations.getAndRemove(id);
      recommendationsByType[rec.getType()].remove(id);
    } catch (error) {
      throw error;
    }
  }

  this.getAll = function() {
    return recommendations.toArray();
  }

  this.getAllByType = function(type) {  
    return recommendationsByType[type].toArray();
  }
  
  this.getFirstByType = function(type) {
	    return recommendationsByType[type].toArray()[0];
	  }
}

var medoky_recommendation_state = {
  user : elgg.get_logged_in_user_entity(),
  gid : elgg.get_page_owner_guid(),
  basedir : elgg.config.wwwroot + "/mod/wespot_medoky/",
  recommendations : new MEDoKyREcommendations(),
  active_recommendations : "",
  lock : function(id) {
    medoky_recommendation_state.id_detail = id;
    medoky_recommendation_state.locked = true;
  },
  unlock : function() {
    medoky_recommendation_state.locked = false;
  },
  dialog_open : false

};

var medoky_ui = {
  		
  prepareDialogs : function(globalUserId) {
    $("#dia_medoky_detail").dialog({
      dialogClass: 'no-close',
      autoOpen : false,
      height : 310,
      width : 600,
      resizable : false,
      modal : true,
      beforeClose : function(event, ui) {
    	  if (!medoky_recommendation_state.active_recommendation.ratingCompleted()){
   			alert(elgg.echo('wespot_medoky:rating:notComplete'));
   			return false;
   			}
    	return true;
      },
      close : function(event, ui) {
        medoky_ui.closeCallback(globalUserId);
        medoky_recommendation_state.dialog_open = false;
      },
      open : function(event, ui) {
        medoky_recommendation_state.dialog_open = true;
      }
    });
 },

 	closeCallback : function(globalUserId) {
 		var recommendation = medoky_recommendation_state.active_recommendation;
 		var postdata = {
			 "groupId" : parseInt(medoky_recommendation_state.gid),
			 "username" : globalUserId,
			 "externalUID" : medoky_recommendation_state.user.guid.toString(),
			 "recId" : recommendation.getId(),
			 "learningObject" : recommendation.getLink(), 
			 "learningObjectTilte" : recommendation.getLinkTitle(),
			 "question0" :    recommendation.getRating("q0"),
			 "question1" : recommendation.getRating("q1"),
			 "question2" : recommendation.getRating("q2"),
			 "question3" : recommendation.getRating("q3"),
			 "algorithm" : recommendation.getRecAlgorithm()
 		};
 		console.log(JSON.stringify(postdata));
 		medoky.confirmRecommendation(recommendation.getId());
 		logServer_backend.addEvaluationData(JSON.stringify(postdata));
 	},

  displayRecommendationInSidebar : function(recommendation) {
	//medoky_recommendation_state.active_recommendations.push(recommendation);
	
    var clazz = "pointy"
    if (recommendation.fresh)
      clazz += " fresh";
    
    $("#medoky_sidebar_recommendations_" + recommendation.getType()).empty();
    var rec = $("#medoky_sidebar_recommendations_" + recommendation.getType()).create("a", {
      class : clazz,
      onclick : "children[0].className='fa fa-play faa-horizontal'; window.open(\"" + recommendation.getLink()
                + "\", \"Learning Object\", \"scrollbars=1,width=800,height=600, scrollbars=yes,resizable=yes\"); medoky.storeLOClick("
                + recommendation.getLearningObjectId() + "); medoky_ui.displayRatingDialog('" + recommendation.getId() + "')"
    }).slideUp(0);
    
    rec.create("i", {
      class : "fa fa-play faa-horizontal animated",
      style : "margin-right:10px;"      
    });

    var txt = recommendation.getLinkTitle();
    rec.create("txt", txt);
    rec.slideDown(300);
  },

  displayRecommendationsInSidebar : function() {
    medoky_ui.displayRecommendationTypeInSidebar(MEDoKyRecommendation.TYPE_RESOURCE);
  },

  displayRecommendationTypeInSidebar : function(type) {
    var recsByType = medoky_recommendation_state.recommendations.getAllByType(type);
    if (recsByType.length == 0)
      return;
    medoky_ui.displayRecommendationInSidebar(recsByType[0]);
  },


  displayRatingDialog : function(recommendationId) {
	  	var header = $("#ui-dialog-title-dia_medoky_detail").empty();
	  	//headline.createtitle="<span style='float:left'><?php echo elgg_echo('wespot_medoky:rating:title');?></span>
	  	var headline = $("#medoky_recommmendation_detail_header").empty();
	    var body = $("#medoky_recommendation_detail_top3").empty();
	    var footer = $("#medoky_recommendation_detail_footer").empty();
	    var firstID =  recommendationId;
	    //medoky_recommendation_state.active_recommendations = [];
	    var recommendation = medoky_recommendation_state.recommendations.get(recommendationId);
	    medoky_recommendation_state.active_recommendation = recommendation;  
	    header.create("txt", elgg.echo('wespot_medoky:header:greet')+ medoky_recommendation_state.user.name+elgg.echo('wespot_medoky:header:info'));
        
	    this.addEvaluationQuestion(headline, "q0", recommendation, 'wespot_medoky:rating:title');
	    
	    headline.create("hr");
	    // create body
	    this.addEvaluationQuestion(body, "q1", recommendation, 'wespot_medoky:rating:questionOne');
	    this.addEvaluationQuestion(body, "q2", recommendation, 'wespot_medoky:rating:questionTwo');
	    this.addEvaluationQuestion(body, "q3", recommendation, 'wespot_medoky:rating:questionThree');
	    
	
	    footer.create("input", {
	    	id : recommendation.getId(),
	    	type:"button",
	    	name: "medoky_recommendation_eval",
	    	value: elgg.echo('wespot_medoky:footer:info'),
	    	style:"width:auto",
	    	onclick: "$('#dia_medoky_detail').dialog('close'); "	
	    });

	    medoky.log("follow recommendation", {
	      course : medoky_recommendation_state.gid,
	      phase : "0",
	      subphase : "wespot_medoky"
	    });

	    $("#dia_medoky_detail").dialog("open");
	    medoky_ui.displayRecommendationDetail(firstID);

	  },
  
    addEvaluationQuestion: function (body, question, recommendation, text){
    
    	var div_css = {
 	    		"padding-top":"3%", 
 	    		"padding-bottom":"5%"
 	    }
    	div = body.create("div");	
    	div.css(div_css);    	
    	
    	var span_css = {
 	    		"width":"75%", 
 	    		"float" : "left"
 	    }
       
    	var espan = div.create("span", {
	          id : "medoky_recommendation_eval_li"
	      });
	   	    
    	espan.text(elgg.echo(text));
    	espan.css(span_css);
	   	this.addStarRating(div, recommendation, question, false);
  }, 

  addStarRating : function (ul, recommendation, question, animate) {
	    var span_rating = ul.create("span", {
	        id : "medoky_recommendation_rating_" + recommendation.getId()+question,
	        style:"float:right"
	    });
	    
	    
	    for (var r = 1; r <= 5; r ++){
	    	span_rating.create("img", {
		        class : "medoky_star pointy",
		        src : medoky_recommendation_state.basedir + "img/star.svg",
		        width : "22px",
		        height : "22px",
		        onclick : " $(\"#medoky_recommendation_rating_" + recommendation.getId()+question
		            + "\").data(\"rating\","+r+"); medoky.log('rating',{recommandationId: '" + recommendation.getId()+question
		            + "', rating: " + r + "}); medoky.addRating('"+recommendation.getId()+"','"+question+"','"+r+"');"
		      });
	    }
	 	    
	      span_rating.mousemove(function(evt) {
	        var posX = evt.pageX - $(this).offset().left;
	        var rating = Math.floor(posX / $(this).width() * 5);
	        for (var i = 0; i < this.childNodes.length; ++i) {
	          if (i <= rating)
	            $(this.childNodes[i]).attr("src", medoky_recommendation_state.basedir + "img/star_active.svg");
	          else
	            $(this.childNodes[i]).attr("src", medoky_recommendation_state.basedir + "img/star.svg");
	        }
	      });
	      span_rating.hover(function(evt) {
	        // well, nothing actually
	      }, function(evt) {

	        for (var i = 0; i < 5; ++i) {
	          if (i < $(this).data("rating"))
	            $(this.childNodes[i]).attr("src", medoky_recommendation_state.basedir + "img/star_active.svg");
	          else
	            $(this.childNodes[i]).attr("src", medoky_recommendation_state.basedir + "img/star.svg");
	        }
	      });
  },
  
  
 
  displayRecommendationDetail : function(id) {
	    if (medoky_recommendation_state.locked)
	      return;
	    if (!(medoky_recommendation_state.id_detail === undefined))
	      $("#medoky_recommendation_id_" + medoky_recommendation_state.id_detail).slideUp(300, function() {
	        medoky_recommendation_state.unlock();
	      });
	    if (!(medoky_recommendation_state.id_detail === id)) {
	      $("#medoky_recommendation_id_" + id).slideDown(300, function() {
	        medoky_recommendation_state.unlock();
	      });
	      medoky_recommendation_state.lock(id);
	    } else {
	      setTimeout(function() {
	        medoky_recommendation_state.id_detail = undefined;
	      }, 300);
	    }
	    medoky.log("read recommendation description", {
	      recommendationId : id
	    });
	  },
};



var logServer_backend = {
    init : function(backend_url) { 
		logServer_backend.url = backend_url;
		path_evaluationData = "addResourceEvaluationInfo";
    },
    
    addEvaluationData : function(payload) {
        $.ajax({
          cache : false,
          type : "POST",
          url : logServer_backend.url +path_evaluationData,
          data : payload,
          dataType : "json",
          contentType : "application/json; charset=utf-8",
          success : function(obj) {
              console.debug(obj);
           },
            error : function(obj) {
              console.error(JSON.stringify(obj));
            }
       });
    }   
};

var medoky_backend = {
  init : function(backend_url) { 
    if (!medoky_recommendation_state.gid || (medoky_recommendation_state.gid == medoky_recommendation_state.user.guid)
        || (elgg.page_owner.owner_guid == medoky_recommendation_state.user.guid)) {
      $(". medoky_main").hide();
      return false;
    } else {
      $(".medoky_main").removeClass("medoky_main");
      medoky_backend.url = backend_url;
      return true;
    }
  },

    
  url : "", // set in init function
  path_trigger : "trigger/userId/#/courseId/#/number/#/environment/#",
  path_trigger_by_type : "triggerRecommendation/userId/#/courseId/#/environment/#/type/#",
  path_getrecommendation : "getRecommendation/recommendationId/",
  path_valuation : "valuations",
  
  
  trigger : function(uid, callback, cid, num, environ) {
    cid = cid ? cid : 1;
    num = num ? num : 5;
    environ = environ ? environ : "textBased";

    var path_trigger_parts = medoky_backend.path_trigger.split("#");
    var path_trigger = path_trigger_parts[0] + uid + path_trigger_parts[1] + cid + path_trigger_parts[2] + num
        + path_trigger_parts[3] + environ;
    
    $.ajax({
      cache : false,
      type : "GET",
      url : medoky_backend.url + path_trigger,
      success : function(obj) {
        console.debug(obj);
        if (callback)
          callback(obj.recommendationId);
      },
      error : function(obj) {
        console.error(JSON.stringify(obj));
      }
    });
  },

  triggerByType : function(uid, callback, cid, type, environ) {
    cid = cid ? cid : 1;
    environ = environ ? environ : "textBased";

    var path_trigger_parts = medoky_backend.path_trigger_by_type.split("#");
    var path_trigger = path_trigger_parts[0] + uid + path_trigger_parts[1] + cid + path_trigger_parts[2] + environ
        + path_trigger_parts[3] + type;
    $.ajax({
      cache : false,
      type : "GET",
      url : medoky_backend.url + path_trigger,
      success : function(obj) {
        console.debug(obj);
        if (callback)
          callback(obj.recommendationId);
      },
      error : function(obj) {
        console.error(JSON.stringify(obj));
      }
    });
  },

  getRecommentations : function(rid, overwrite, callback) {
    $.ajax({
      cache : false,
      type : "GET",
      url : medoky_backend.url + medoky_backend.path_getrecommendation + rid,
      success : function(obj) {
        console.debug(obj);
	//TODO: check validity again	
        if(obj.recommendations!=0){
            document.getElementById("medoky_recommendation_title").style.display = 'block';
        }

        if (obj.status == "pending") {
          setTimeout(function() {
            medoky_backend.getRecommentations(rid, overwrite, callback);
          }, 100);

          return;
       }

       if (overwrite)
          medoky_recommendation_state.recommendations = new MEDoKyREcommendations();

        for ( var i in obj.recommendations) {
          var rec = obj.recommendations[i];
          medoky_recommendation_state.recommendations.put(new MEDoKyRecommendation(rec.type, rec.recommendationText,
              rec.link, rec.linkTitle, rec.explanation, rec.id, rec.learningObjectId, rec.recommendationAlgorithm));
        }
 
	if (callback) {
          callback();
        }
      },
      error : function(obj) {
        console.error(JSON.stringify(obj));
      }
    });
  },
  
  updateValuation : function(payload, callback) {
    $.ajax({
      cache : false,
      type : "POST",
      url : medoky_backend.url + "FCATool/" + medoky_backend.path_valuation,
      data : payload,
      dataType : "json",
      contentType : "application/json; charset=utf-8",
      success : function(obj) {
        if (callback)
          callback(obj);
      }
    });
  }

};

var medoky = {

  log : function(verb, payload) {
    console.trace();
    console.debug("verb");
    console.debug("payload");
    payload.userId = medoky_recommendation_state.user.guid;
    context = {
    	      course : elgg.get_page_owner_guid(),
    	      user : medoky_recommendation_state.user.guid, 
    	      phase : "7",
    	      widget_type : "recommendation_plugin"
    	    };
    console.debug("context");
    try {
      // initial test
      post_to_stepup(window.location.href, verb, context, payload);
    } catch (error) {
      console.log(error);
    }
  },

  addRating : function(recommendationId, answer, rating){
	  var recommendation = medoky_recommendation_state.recommendations.get(recommendationId);
	  recommendation.setRating(answer, rating);
  },
  
  storeLOClick : function(lo_id) {
    var postdata = {
      "id" : parseInt(medoky_recommendation_state.gid),
      "externalUID" : medoky_recommendation_state.user.guid.toString(),
      "learningObjectId" : lo_id,
      "course" : true
    };
    medoky_backend.updateValuation(JSON.stringify(postdata));
  },

  fetchRecommendations : function(callback, num, refresh) {
    if (!medoky_recommendation_state.gid)
      return;
      medoky_backend.trigger(medoky_recommendation_state.user.guid, function(recs_id) {
      medoky_backend.getRecommentations(recs_id, true, function(recommendations) {
        if (callback)
          callback(refresh);
      });
    }, medoky_recommendation_state.gid, num ? num : 3);

  },

  resetView : function() {
    $("#medoky_recommendation_detail_top3").empty();
    medoky_ui.displayRecommendationsInSidebar();
  },

  confirmRecommendation : function(id) {
    var rec = medoky_recommendation_state.recommendations.getAndRemove(id);
    var type = rec.getType();

    console.debug("rec array in confirm Recommendation"+medoky_recommendation_state.recommendations.getAllByType(type).length);
    // remove from active recommendations, not needed, but keeps it clean
    var recommendations = medoky_recommendation_state.recommendations.getAllByType(type);
   // var active_index = medoky_recommendation_state.active_recommendations.indexOf(rec);
   // medoky_recommendation_state.active_recommendations.splice(active_index, 1);
    
    for ( var i in recommendations) {
//      if (medoky_recommendation_state.active_recommendations.indexOf(recommendations[i]) != -1)
//        continue;
       
      this.resetView();     
      return;
    }
    
    // if new recommendations need to be pulled from backend
    if (medoky_recommendation_state.recommendations.getAllByType(type).length == 0) {
      medoky.fetchRecommendationsByType(type, function() {
        recommendations = medoky_recommendation_state.recommendations.getAllByType(type);
        var num = 0;
               
        for ( var i in recommendations) {
          // should never happen // TODO: test this, is it neccessary to check?
         //if (medoky_recommendation_state.active_recommendations.indexOf(recommendations[i]) != -1)
           // continue;
          medoky.resetView();
          return;
        }
      });
    }
  },

  fetchRecommendationsByType : function(type, callback) {
    medoky_backend.triggerByType(medoky_recommendation_state.user.guid, function(recs_id) {
      medoky_backend.getRecommentations(recs_id, false, function(recommendations) {
        if (callback)
          callback();
      });
    }, medoky_recommendation_state.gid, type);
  },

  pollRecommendations : function() {
    if (medoky_recommendation_state.dialog_open)
      medoky_ui.closeCallback = medoky.pollRecommendations;
    else {
      medoky_ui.closeCallback = function() {
      }
      medoky_recommendation_state.animating = true;
      medoky.fetchRecommendations(medoky.resetView);
    }

  }
};
