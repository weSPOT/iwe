function MEDoKyRecommendation(type, text, link, linkTitle, description, id, learningObjectId) {
  this.fresh = false;

  this.getIcon = function() {
    if (type == MEDoKyRecommendation.TYPE_ACTIVITY)
      return "img/activity.svg";
    else if (type == MEDoKyRecommendation.TYPE_PEER)
      return "img/peer.svg";
    else if (type == MEDoKyRecommendation.TYPE_RESOURCE)
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
}
MEDoKyRecommendation.TYPE_ACTIVITY = "LearningActivity";
MEDoKyRecommendation.TYPE_PEER = "LearningPeer";
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
    this.remove(idOrRecommendation);
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
    this.remove(idOrRecommendation);
    return rec;
  }

  this.get = function(id) {
    return recommendations.get(id);
  }

  this.remove = function(idOrRecommendation) {
    try {
      var rec = recommendations.getAndRemove(idOrRecommendation);
      recommendationsByType[rec.getType()].remove(idOrRecommendation);
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
}

var medoky_recommendation_state = {
  user : elgg.get_logged_in_user_entity(),
  gid : elgg.get_page_owner_guid(),
  basedir : elgg.config.wwwroot + "/mod/wespot_medoky/",
  recommendations : new MEDoKyREcommendations(),
  active_recommendations : [],
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
  prepareDialogs : function() {
    $("#dia_medoky_detail").dialog({
      autoOpen : false,
      height : 310,
      width : 600,
      resizable : false,
      modal : true,
      close : function(event, ui) {
        medoky_recommendation_state.dialog_open = false;
        medoky_ui.closeCallback();
      },
      open : function(event, ui) {
        medoky_recommendation_state.dialog_open = true;
      }
    });
  },

  closeCallback : function() {
  },

  displayRecommendationInSidebar : function(recommendation) {
    var clazz = "pointy"
    if (recommendation.fresh)
      clazz += " fresh";
    var rec = $("#medoky_sidebar_recommendations_" + recommendation.getType()).empty().create("a", {
      class : clazz,
      onclick : "medoky_ui.displayRecommendationDialog('" + recommendation.getType() + "')"
    }).slideUp(0);

    rec.create("img", {
      src : medoky_recommendation_state.basedir + recommendation.getIcon(),
      width : "22px",
      height : "22px",
      class : "medoky_recommendation"
    });

    
    var txt = $("#resourceInstruction").val(); 
    var recType = recommendation.getType();
    if (recType == MEDoKyRecommendation.TYPE_ACTIVITY)
      txt = $("#activityInstruction").val(); 
    else if (recType == MEDoKyRecommendation.TYPE_PEER)
      txt = $("#peerInstruction").val(); 
    rec.create("txt", txt);
    rec.slideDown(300);
  },

  displayRecommendationsInSidebar : function() {
    medoky_ui.displayRecommendationTypeInSidebar(MEDoKyRecommendation.TYPE_ACTIVITY);
    medoky_ui.displayRecommendationTypeInSidebar(MEDoKyRecommendation.TYPE_PEER);
    medoky_ui.displayRecommendationTypeInSidebar(MEDoKyRecommendation.TYPE_RESOURCE);
  },

  displayRecommendationTypeInSidebar : function(type) {
    var recsByType = medoky_recommendation_state.recommendations.getAllByType(type);
    if (recsByType.length == 0)
      return;
    medoky_ui.displayRecommendationInSidebar(recsByType[0]);
  },

  displayRecommendationDialog : function(type) {
    var header = $("#medoky_recommmendation_detail_header").empty();
    var ul = $("#medoky_recommendation_detail_top3").empty();
    var footer = $("#medoky_recommendation_detail_footer").empty();
    header.create("txt", elgg.echo('wespot_medoky:header:greet')+ medoky_recommendation_state.user.name+elgg.echo('wespot_medoky:header:info'));
    var recsByType = medoky_recommendation_state.recommendations.getAllByType(type);
    var firstID;
    medoky_recommendation_state.active_recommendations = [];
    for ( var i in recsByType) {
      var recommendation = recsByType[i];
      if (i == 0)
        firstID = recommendation.getId();
      if (i > 2)
        break;
      medoky_ui.addRecommendationDetail(ul, recommendation, false);
    }
    footer.create("hr");
    footer.create("a", {
      class : "pointy bold"
    }).create("txt", elgg.echo('wespot_medoky:link'));
    footer.create("txt", elgg.echo('wespot_medoky:footer:info'));

    medoky.log("open recommendation widget", {
      course : medoky_recommendation_state.gid,
      phase : "0",
      subphase : "wespot_medoky"
    });

    $("#dia_medoky_detail").dialog("open");
    medoky_ui.displayRecommendationDetail(firstID);

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

  addRecommendationDetail : function(ul, recommendation, animate) {
    medoky_recommendation_state.active_recommendations.push(recommendation);
    var clazz = "rec";
    if (recommendation.fresh)
      clazz += " fresh";
    var li = ul.create("li", {
      class : clazz,
      id : "medoky_recommendation_detail_id_" + recommendation.getId()
    });
    if (animate) {
      li.slideUp(0);
    }

    var span = li.create("span", {
      class : "medoky_recommendation_title pointy",
      onclick : "medoky_ui.displayRecommendationDetail('" + recommendation.getId() + "')"
    });
    span.create("img", {
      src : medoky_recommendation_state.basedir + recommendation.getIcon(),
      width : "22px",
      height : "22px",
      class : "medoky_recommendation"
    });
    span.create("txt", recommendation.getText());

    var span_rating = li.create("span", {
      id : "medoky_recommendation_rating_" + recommendation.getId()
    });
    span_rating.create("img", {
      class : "medoky_star pointy",
      src : medoky_recommendation_state.basedir + "img/star.svg",
      width : "22px",
      height : "22px",
      onclick : " $(\"#medoky_recommendation_rating_" + recommendation.getId()
          + "\").data(\"rating\", 1); medoky.log('rating',{recommandationId: '" + recommendation.getId()
          + "', rating: " + 1 + "});"
    });
    span_rating.create("img", {
      class : "medoky_star pointy",
      src : medoky_recommendation_state.basedir + "img/star.svg",
      width : "22px",
      height : "22px",
      onclick : " $(\"#medoky_recommendation_rating_" + recommendation.getId()
          + "\").data(\"rating\", 2); medoky.log('rating',{recommandationId: '" + recommendation.getId()
          + "', rating: " + 2 + "});"
    });
    span_rating.create("img", {
      class : "medoky_star pointy",
      src : medoky_recommendation_state.basedir + "img/star.svg",
      width : "22px",
      height : "22px",
      onclick : " $(\"#medoky_recommendation_rating_" + recommendation.getId()
          + "\").data(\"rating\", 3);  medoky.log('rating',{recommandationId: '" + recommendation.getId()
          + "', rating: " + 3 + "});"
    });
    span_rating.create("img", {
      class : "medoky_star pointy",
      src : medoky_recommendation_state.basedir + "img/star.svg",
      width : "22px",
      height : "22px",
      onclick : " $(\"#medoky_recommendation_rating_" + recommendation.getId()
          + "\").data(\"rating\", 4); medoky.log('rating',{recommandationId: '" + recommendation.getId()
          + "', rating: " + 4 + "});"
    });
    span_rating.create("img", {
      class : "medoky_star pointy",
      src : medoky_recommendation_state.basedir + "img/star.svg",
      width : "22px",
      height : "22px",
      onclick : " $(\"#medoky_recommendation_rating_" + recommendation.getId()
          + "\").data(\"rating\", 5); medoky.log('rating',{recommandationId: '" + recommendation.getId()
          + "', rating: " + 5 + "});"
    });
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

    li.create("img", {
      class : "medoky_star pointy medoky_recommendation_ok",
      src : medoky_recommendation_state.basedir + "img/ok.svg",
      width : "22px",
      height : "22px",
      onclick : "medoky.confirmRecommendation('" + recommendation.getId() + "')"
    });
    var detail = li.create("div", {
      id : "medoky_recommendation_id_" + recommendation.getId(),
      class : "medoky_recommendation_detail"
    }).hide();

    var recommendedResource = recommendation.getLink();
    if (recommendedResource) {
      detail.create("txt", elgg.echo("Recommended Resource: "));
      detail.create(
          "a",
          {
            class : "pointy bold",
            onclick : "window.open(\"" + recommendation.getLink()
                + "\", \"Learning Object\", \"width=800,height=600\"); medoky.storeLOClick("
                + recommendation.getLearningObjectId() + ");"
          }).create("txt", recommendation.getLinkTitle());
      detail.create("br");
    }

    detail.create("txt", recommendation.getDescription());
    if (animate) {
      li.slideDown(300);
    }
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
              rec.link, rec.linkTitle, rec.explanation, rec.id, rec.learningObjectId));
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
    console.log(verb);
    console.log(payload);
    payload.userId = medoky_recommendation_state.user.guid;
    try {
      // initial test
      post_to_stepup(window.location.href, verb, {
        course : elgg.get_page_owner_guid()
      }, payload);
    } catch (error) {
      console.log(error);
    }
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
    if (medoky_recommendation_state.animating)
      return;
    medoky_recommendation_state.animating = true;
    console.debug(id);
    $("#medoky_recommendation_detail_id_" + id + ", #medoky_sidebar_recommendation_id_" + id).prop("disabled", true);
    $("#medoky_recommendation_detail_id_" + id + ", #medoky_sidebar_recommendation_id_" + id).slideUp(300, function() {
    $("#medoky_recommendation_detail_id_" + id + ", #medoky_sidebar_recommendation_id_" + id).remove();
      medoky_recommendation_state.animating = false;
    });

    medoky.log("follow recommendation", {
      recommendationId : id
    });
    var rec = medoky_recommendation_state.recommendations.getAndRemove(id);
    var type = rec.getType();

    // remove from active recommendations, not needed, but keeps it clean
    var recommendations = medoky_recommendation_state.recommendations.getAllByType(type);
    var active_index = medoky_recommendation_state.active_recommendations.indexOf(rec);
    medoky_recommendation_state.active_recommendations.splice(active_index, 1);

    var ul = $("#medoky_recommendation_detail_top3");
    for ( var i in recommendations) {
      if (medoky_recommendation_state.active_recommendations.indexOf(recommendations[i]) != -1)
        continue;
      medoky_ui.addRecommendationDetail(ul, recommendations[i], true);
      return;
    }
    if (medoky_recommendation_state.recommendations.getAllByType(type).length == 0) {
      medoky.fetchRecommendationsByType(type, function() {
        recommendations = medoky_recommendation_state.recommendations.getAllByType(type);
        var num = 0;
        for ( var i in recommendations) {
          // should never happen
          if (medoky_recommendation_state.active_recommendations.indexOf(recommendations[i]) != -1)
            continue;
          medoky_ui.addRecommendationDetail(ul, recommendations[i], true);
          if (++num > 2)
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
