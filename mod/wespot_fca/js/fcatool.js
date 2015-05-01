state = {
  basedir : "",
  user : {},
  id_last_attr : 3,
  id_last_obj : 4,
  backend_objects : {},
  backend_attributes : {},
  backend_l_objects : {},
  current_l_objects : {},
  new_objects : {},
  new_attributes : {},
  active_l_objects : {},
  obj_index : -1,
  attr_index : -1,
  item_id : undefined,
  domain : undefined,
  inited_attr : false,
  inited_obj : false,
  msie : false,
  editing : false,
  hover_elem : null,
  gid : -1,
  g_name : "",
  owner_id : -1,
  teacher : false,
  load_domain : true,
  current_item : undefined,
  edit_current_item : true,
  select_do_create : false,
  type : undefined,
  learner_lattice_learner : undefined,
  adminIDs : []
};

entity_types = {
  object : 0,
  attribute : 1,
  domain : 2,
  learningobject : 3
};


backend = {
  url : "", // set in init function
  path_object : "object",
  path_objects : "objects",
  path_attribute : "attribute",
  path_attributes : "attributes",
  path_learning_objects : "learningObjects",
  path_domain : "domain",
  path_learner_domain : "learnerDomain",
  path_domainheaders : "domainHeaders",
  path_courses : "courses",
  path_concept : "concept",
  path_valuation : "valuations",
  path_course_domains : "courseDomains",
  path_identify : "identify",
  path_learners_for_domain : "learners",

  identify : function(payload, callback) {
    $.ajax({
      cache : false,
      type : "POST",
      url : backend.url + backend.path_identify,
      data : payload,
      dataType : "json",
      contentType : "application/json; charset=utf-8",
      success : function(obj) {
        if (callback)
          callback(obj);
      },
      error : function() {
        alert("Could not connect to Back-End! The FCA Tool will not work!");
      }
    });
  },

  get_valuations : function(callback) {
    $.ajax({
      cache : false,
      type : "GET",
      url : backend.url + backend.path_learner_domain + "/" + state.domain.id + "/" + backend.path_valuation,
      success : function(obj) {
        if (callback)
          callback(obj);
      },
      error : function(obj) {
        console.error(JSON.stringify(obj));
      }
    });
  },

  get_objects : function(callback) {
    $.ajax({
      cache : false,
      type : "GET",
      url : backend.url + backend.path_objects,
      success : function(obj) {
        for ( var id in obj) {
          obj[id].id = id;
          state.backend_objects[id] = obj[id];
          for ( var i in obj[id].learningObjects) {
            state.active_l_objects[obj[id].learningObjects[i].id] = obj[id].learningObjects[i];
          }
          for ( var i in obj[id].learningObjectsByLearners) {
            state.active_l_objects[obj[id].learningObjectsByLearners[i].id] = obj[id].learningObjectsByLearners[i];
          }
        }
        if (callback)
          callback();
      },
      error : function(obj) {
        console.error(JSON.stringify(obj));
      }
    });
  },

  get_attributes : function(callback) {
    $.ajax({
      cache : false,
      type : "GET",
      url : backend.url + backend.path_attributes,
      success : function(obj) {
        for ( var id in obj) {
          obj[id].id = id;
          state.backend_attributes[id] = obj[id];
          for ( var i in obj[id].learningObjects) {
            state.active_l_objects[obj[id].learningObjects[i].id] = obj[id].learningObjects[i];
          }
          for ( var i in obj[id].learningObjectsByLearners) {
            state.active_l_objects[obj[id].learningObjectsByLearners[i].id] = obj[id].learningObjectsByLearners[i];
          }
        }
        if (callback)
          callback();
      },
      error : function(obj) {
        console.error(JSON.stringify(obj));
      }
    });
  },

  get_l_objects : function(callback) {
    $.ajax({
      cache : false,
      type : "GET",
      url : backend.url + backend.path_learning_objects,
      success : function(obj) {
        for ( var id in obj) {
          obj[id].id = id;
          state.backend_l_objects[id] = obj[id];
        }
        if (callback)
          callback();
      },
      error : function(obj) {
        console.error(JSON.stringify(obj));
      }
    });
  },

  get_domain_learners : function(callback) {
    $.ajax({
      cache : false,
      type : "GET",
      data : {
        id : state.domain.id
      },
      dataType : "json",
      contentType : "application/json; charset=utf-8",
      url : backend.url + backend.path_learners_for_domain,
      success : function(obj) {
        if (callback)
          callback(obj);
      },
      error : function(obj) {
        console.error(JSON.stringify(obj));
      }
    });
  },

  get_learner_lattice : function(learnerId, callback) {
    $.ajax({
      cache : false,
      type : "GET",
      dataType : "json",
      contentType : "application/json; charset=utf-8",
      url : backend.url + backend.path_courses + "/" + state.gid + "/domains/" + state.domain.id + "/"
          + backend.path_learners_for_domain + "/" + learnerId + "/lattice",
      success : function(obj) {
        if (callback)
          callback(obj);
      },
      error : function(obj) {
        console.error(JSON.stringify(obj));
      }
    });
  },

  create_l_objects : function(payload, callback) {
    $.ajax({
      cache : false,
      type : "POST",
      url : backend.url + backend.path_learning_objects,
      data : payload,
      dataType : "json",
      contentType : "application/json; charset=utf-8",
      success : callback
    });
  },

  create_objects : function(payload, callback) {
    $.ajax({
      cache : false,
      type : "POST",
      url : backend.url + backend.path_objects,
      data : payload,
      dataType : "json",
      contentType : "application/json; charset=utf-8",
      success : callback
    });
  },

  create_attributes : function(payload, callback) {
    $.ajax({
      cache : false,
      type : "POST",
      url : backend.url + backend.path_attributes,
      data : payload,
      dataType : "json",
      contentType : "application/json; charset=utf-8",
      success : callback
    });
  },

  create_domain : function(payload, callback) {
    $.ajax({
      cache : false,
      type : "POST",
      url : backend.url + backend.path_domain,
      data : payload,
      dataType : "json",
      contentType : "application/json; charset=utf-8",
      success : function(obj) {
        callback(obj);
      }
    });
  },

  update_domain : function(payload, callback) {
    $.ajax({
      cache : false,
      type : "POST",
      url : backend.url + backend.path_domain + "/" + state.domain.id,
      data : payload,
      dataType : "json",
      contentType : "application/json; charset=utf-8",
      success : function(obj) {
        callback(obj);
      }
    });
  },

  approve_domain : function(payload, callback) {
    $.ajax({
      cache : false,
      type : "POST",
      dataType : "json",
      contentType : "application/json; charset=utf-8",
      url : backend.url + backend.path_domain + "/" + payload + "/approve",
      success : function(obj) {
        callback(obj);
      }
    });
  },

  share_domain : function(payload, callback) {
    $.ajax({
      cache : false,
      type : "POST",
      dataType : "json",
      contentType : "application/json; charset=utf-8",
      url : backend.url + backend.path_domain + "/" + state.domain.id + "/shareTo/" + payload.id + "/"
          + encodeURIComponent(payload.name),
      success : function(obj) {
        callback(obj);
      }
    });
  },

  update_attribute : function(payload, callback) {
    $.ajax({
      cache : false,
      type : "POST",
      url : backend.url + backend.path_domain + "/" + state.domain.id + "/" + backend.path_attribute,
      data : payload,
      dataType : "json",
      contentType : "application/json; charset=utf-8",
      success : function(obj) {
        callback(obj);
      }
    });
  },

  update_object : function(payload, callback) {
    $.ajax({
      cache : false,
      type : "POST",
      url : backend.url + backend.path_domain + "/" + state.domain.id + "/" + backend.path_object,
      data : payload,
      dataType : "json",
      contentType : "application/json; charset=utf-8",
      success : function(obj) {
        callback(obj);
      }
    });
  },

  update_attributes : function(payload, callback) {
    $.ajax({
      cache : false,
      type : "POST",
      url : backend.url + backend.path_domain + "/" + state.domain.id + "/" + backend.path_attributes,
      data : payload,
      dataType : "json",
      contentType : "application/json; charset=utf-8",
      success : function(obj) {
        callback(obj);
      }
    });
  },

  update_objects : function(payload, callback) {
    $.ajax({
      cache : false,
      type : "POST",
      url : backend.url + backend.path_domain + "/" + state.domain.id + "/" + backend.path_objects,
      data : payload,
      dataType : "json",
      contentType : "application/json; charset=utf-8",
      success : function(obj) {
        callback(obj);
      }
    });
  },

  update_concept : function(payload, callback) {
    $.ajax({
      cache : false,
      type : "POST",
      url : backend.url + backend.path_concept,
      data : payload,
      dataType : "json",
      contentType : "application/json; charset=utf-8",
      success : function(obj) {
        callback(obj);
      }
    });
  },

  update_valuation : function(payload, callback) {
    $.ajax({
      cache : false,
      type : "POST",
      url : backend.url + backend.path_valuation,
      data : payload,
      dataType : "json",
      contentType : "application/json; charset=utf-8",
      success : function(obj) {
        callback(obj);
      }
    });
  },

  get_course_domains : function(callback) {
    $.ajax({
      cache : false,
      type : "GET",
      url : backend.url + backend.path_course_domains,
      success : function(obj) {
        callback(obj);
      },
      error : function(obj) {
        console.error(JSON.stringify(obj));
      }
    });
  },

  get_domains : function(id, callback) {

    // TODO: move where it belongs
    try {
      // browser bugs
      $(".scaled-frame").attr("src", "");
      while ($(".scaled-frame").length != 0)
        $(".scaled-frame").remove();
      while ($("#ifr_preview").length != 0)
        $("#ifr_preview").remove();
    } catch (not_an_error) {
    }

    var payload = {
      "id" : id
    };
    $.ajax({
      cache : false,
      type : "GET",
      data : payload,
      url : backend.url + backend.path_domainheaders,
      success : function(obj) {
        callback(obj);
      },
      error : function(obj) {
        console.error(JSON.stringify(obj));
      }
    });
  },

  get_domain : function(id, callback) {
    var payload = {
      "id" : id
    };
    $.ajax({
      cache : false,
      type : "GET",
      data : payload,
      contentType : "text/plain; charset=utf-8",
      url : backend.url + backend.path_domain,
      success : function(obj) {
        callback(obj);
      },
      error : function(obj) {
        console.error(JSON.stringify(obj));
      }
    });
  },

  get_learner_domain : function(did, uid, callback) {

    $.ajax({
      cache : false,
      type : "GET",
      contentType : "text/plain; charset=utf-8",
      url : backend.url + backend.path_courses + "/" + state.gid + "/domains/" + did + "/"
          + backend.path_learners_for_domain + "/" + uid + "/",
      success : function(obj) {
        callback(obj);
      },
      error : function(obj) {
        console.error(JSON.stringify(obj));
      }
    });
  }
};

util = {

    
   contains :function (a, obj) {
   var i = a.length;
   while (i--) {
       console.log(a[i]+" a to o "+obj);
       if (a[i] === obj) {
           return true;
       }
    }
    return false;
  },

  parse_params : function() {
    var params = window.location.search.replace("?", "").split("&");
    if (params.length < 3)
      window.alert(elgg.echo('wespot_fca:err_launch'));
    for ( var i in params) {
      var param = params[i].split("=");
      if (param[0] == "gid")
        state.gid = param[1];
      else if (param[0] == "name")
        state.g_name = param[1];
      else if (param[0] == "uid")
        state.owner_id = parseInt(param[1]);
      else if (param[0] == "blank")
        state.load_domain = false;
    }
    for ( var i in params) {
      var param = params[i].split("=");
      if ((state.load_domain) && (param[0] == "did"))
        state.load_domain = param[1];
    }
    if (state.adminIDs.indexOf(state.user.guid.toString()) > -1)
      state.teacher = true;

    $("#btn_to_group")
        .attr("onclick", "window.location='" + elgg.get_site_url() + "/groups/profile/" + state.gid + "'");
  },

  init_state : function() {
    if (!window.history.state)
      window.history.pushState("FCA", "FCA", location.href);
  },

  set_state : function(id) {
    var url = window.location.href.replace("&blank=true", "");
    var param_str = window.location.search.replace("&blank=true", "");
    var new_url = url.substring(0, url.length - param_str.length + 1);
    var params = param_str.replace("?", "").split("&");
    for ( var i in params) {
      var param = params[i].split("=");
      if (param[0] == "did") {
        // params[i]="did="+id;
        for ( var h in params) {
          if (h != i)
            new_url = new_url + params[h] + "&";
        }
        new_url += "did=" + id;
        window.history.replaceState("FCA", "FCA", new_url);
        return;
      }
    }
    window.history.replaceState("FCA", "FCA", url + "&did=" + id);

  },
  setup_msie : function() {
    if ($(".msie_fca").length > 0)
      state.msie = true;
    // IE COMPAT
    if (!console.debug) {
      console.debug = console.log;
    }
    // IE COMPAT taken from https://github.com/jaubourg/ajaxHooks
    if (state.msie) {
      jQuery.ajaxTransport(function(s) {
        if (s.crossDomain && s.async) {
          if (s.timeout) {
            s.xdrTimeout = s.timeout;
            delete s.timeout;
          }
          var xdr;
          return {
            send : function(_, complete) {
              function callback(status, statusText, responses, responseHeaders) {
                xdr.onload = xdr.onerror = xdr.ontimeout = jQuery.noop;
                xdr = undefined;
                complete(status, statusText, responses, responseHeaders);
              }
              xdr = new XDomainRequest();
              xdr.onload = function() {
                callback(200, "OK", {
                  text : xdr.responseText
                }, "Content-Type: " + xdr.contentType);
              };
              xdr.onerror = function() {
                callback(404, "Not Found");
              };
              xdr.onprogress = jQuery.noop;
              xdr.ontimeout = function() {
                callback(0, "timeout");
              };
              xdr.timeout = s.xdrTimeout || Number.MAX_VALUE;
              xdr.open(s.type, s.url);
              xdr.send((s.hasContent && s.data) || null);
            },
            abort : function() {
              if (xdr) {
                xdr.onerror = jQuery.noop;
                xdr.abort();
              }
            }
          };
        }
      });
      ui.display_ie_warning();
    } else {
      try {
        if (XDomainRequest)
          ui.display_ie_warning(1);
      } catch (error) {
      }
    }
  },

  switch_student : function() {
    $("#btn_open").removeAttr("onclick");
    $("#btn_open").click(function() {
      backend.get_domains(state.gid, ui.list_domains);
    });
    $("#btn_save").hide();
    $("#btn_from_existing").hide();
    $("#btn_approve").hide();
    $("#btn_new").hide();
    $("#main_table").hide();
    $("#dia_vis").hide();
    $("#span_latticeview").css({
      "margin-top" : "75px",
      "padding-left" : "7px"
    });
  },

  filter_items : function(entityType) {
    var objs = {};
    var current = undefined;
    var pack = util.setup_by_type(entityType);
    var currentObjects = pack.buttons;

    for ( var i in pack.items)
      objs[i] = pack.items[i];
    if (pack.index != -1)
      current = $(pack.prefix + pack.index).data(pack.key);

    if (current)
      current = current.id;

    for (var i = 0; i < currentObjects.length; ++i) {
      var obj = $(currentObjects[i]);
      if (obj.data(pack.key)) {
        if (obj.data(pack.key).id in pack.items) {
          if (current != objs[obj.data(pack.key).id].id)
            delete objs[obj.data(pack.key).id];
        }
      }
    }

    for ( var i in pack.new_items)
      objs[i] = pack.new_items[i];

    for (var i = 0; i < currentObjects.length; ++i) {
      var obj = $(currentObjects[i]);
      if (obj.data(pack.key)) {
        if (obj.data(pack.key).id in pack.new_items) {
          if (current != objs[obj.data(pack.key).id].id) {
            delete objs[obj.data(pack.key).id];
          }
        }
      }
    }
    return objs;
  },

  filter_l_lobjects : function(object) {
    var l_objs = {};
    var obj_los = {};
    for ( var i in object.learningObjects) {
      obj_los[object.learningObjects[i].id] = object.learningObjects[i];
    }
    for ( var i in object.learningObjectsByLearners) {
      obj_los[object.learningObjectsByLearners[i].id] = object.learningObjectsByLearners[i];
    }
    for ( var i in state.backend_l_objects)
      if (!(i in obj_los))
        l_objs[i] = state.backend_l_objects[i];
    return l_objs;
  },

  replace_items : function(obj, entityType) {
    var pack = util.setup_by_type(entityType);

    for ( var o in obj) {
      var object = {
        "name" : obj[o].name,
        "description" : obj[o].description,
        "id" : o,
        "learningObjects" : obj[o].learningObjects,
        "learningObjectsByLearners" : obj[o].learningObjectsByLearners
      };
      pack.items[o] = object;
      for ( var i in object.learningObjects) {
        state.active_l_objects[object.learningObjects[i].id] = object.learningObjects[i];
      }
      for ( var i in object.learningObjectsByLearners) {
        state.active_l_objects[object.learningObjectsByLearners[i].id] = object.learningObjectsByLearners[i];
      }
      var currentObjects = pack.buttons;
      for (var i = 0; i < currentObjects.length; ++i) {
        if ($.data(currentObjects[i], pack.key).id == obj[o].id) {
          $(currentObjects[i]).data(pack.key, object);
        }
      }
      delete pack.new_items[obj[o].id];
    }
  },

  underConstruction : function(selector) {
    $(selector).css({
      "background-image" : "url(" + state.basedir + "img/uc.png)",
      "background-size" : "100% 100%"
    });
  },

  getSize : function(obj) {
    var size = 0;
    for ( var key in obj) {
      if (obj.hasOwnProperty(key))
        size++;
    }
    return size;
  },

  // taken from
  // http://stackoverflow.com/questions/1584370/how-to-merge-two-arrays-in-javascript
  unique : function(array) {
    var a = array.concat();
    for (var i = 0; i < a.length; ++i) {
      for (var j = i + 1; j < a.length; ++j) {
        if (a[i] === a[j])
          a.splice(j--, 1);
      }
    }
    return a;
  },

  containsConcept : function(array, concept) {
    for (i in array) {
      if (concept.id == array[i].id)
        return true;
    }
    return false;
  },

  clear_iframe : function() {
    try {
      // browser bugs
      $(".scaled-frame").attr("src", "");
      while ($(".scaled-frame").length != 0)
        $(".scaled-frame").remove();
      while ($("#ifr_preview").length != 0)
        $("#ifr_preview").remove();
    } catch (not_an_error) {
    }
  },

  setup_by_type : function(entityType) {
    console.debug("Get by Type: " + entityType);
    var id_buttons = $(".btn_obj");
    var key = logic.key_item;
    var items = state.backend_objects;
    var new_items = state.new_objects;
    var index = state.obj_index;
    var updatefunc = backend.update_object;
    var updatefunc_multi = backend.update_objects;
    var prefix_id = "#obj_";
    var dia = $("#dia_set_item");
    dia.attr("title", elgg.echo('wespot_fca:obj:set'));
    dia.dialog("option", "title", elgg.echo('wespot_fca:obj:set'));
    var sel = $("#sel_set_item");
    try {
      sel.autocomple.destroy();
    } catch (not_an_error) {
    }
    var textarea = document.getElementById("text_descr_item");
    var btn = $("#btn_choose_item_ok");
    btn.removeAttr("onclick");
    btn.unbind("click");
    btn.attr("onclick", "logic.choose_item(" + entityType + ")");
    var btn_cancel = $("#btn_choose_item_cancel");
    var inited = state.inited_obj;

    $("#btn_item_edit").unbind("click");
    $("#btn_item_edit").removeAttr("onclick");
    $("#btn_item_edit").attr("onclick", "ui.display_item_edit(" + entityType + ")");
    $(".layout_select_name").empty();
    $(".layout_select_name").create("txt", elgg.echo('wespot_fca:obj:sel'));

    if (entityType == entity_types.attribute) {
      $(".layout_select_name").empty();
      $(".layout_select_name").create("txt", elgg.echo('wespot_fca:attr:sel'));
      dia.attr("title", elgg.echo('wespot_fca:attr:set'));
      dia.dialog("option", "title", elgg.echo('wespot_fca:attr:set'));
      console.debug(dia.attr("title"));
      id_buttons = $(".btn_attr");
      items = state.backend_attributes;
      new_items = state.new_attributes;
      index = state.attr_index;
      updatefunc = backend.update_attribute;
      updatefunc_multi = backend.update_attributes;
      prefix_id = "#attr_";
      inited = state.inited_attr;
    }
    var pack = {
      buttons : id_buttons,
      key : key,
      items : items,
      new_items : new_items,
      index : index,
      update_function : updatefunc,
      update_function_multiple : updatefunc_multi,
      prefix : prefix_id,
      dialog : dia,
      select : sel,
      textarea_descr : textarea,
      btn_ok : btn,
      btn_cancel : btn_cancel,
      inited : inited
    };
    return pack;
  }
};

logic = {
  key_item : "item",
  key_lo : "l_object",

  init : function(basedir, backend_url, files, groups, owners) {
  
    state.basedir = basedir;
    state.files = files;
    state.groups = groups;
    state.adminIDs = [];
    state.user = elgg.get_logged_in_user_entity();
    for ( var id in owners) {
      state.adminIDs.push(owners[id].toString());
      console.debug("owner: "+owners[id].toString());
    }
    // state.adminIDs = [ state.user.guid.toString() ];
    console.debug(owners);
   
    util.parse_params();
    util.init_state();
    ui.prepare_table();
    console.debug((state.adminIDs.indexOf(state.user.guid.toString()) > -1));
    console.debug(state.teacher);
    backend.url = backend_url;
    $(window).resize(function() {
      ui.resize();
    });

    backend.identify(JSON.stringify({
      user : {
        "name" : state.user.name,
        "description" : state.user.url,
        "externalUID" : state.user.guid.toString(),
        "teacher" : state.teacher
      },
      "cid" : state.gid,
      "cOwner" : "" + state.owner_id,
      "cName" : state.g_name,
      "externalCourseOperatorIDs": state.adminIDs
    }), function(id) {
      state.internalUID = parseInt(id);

      ui.setup_btn_hover();
      ui.prepare_dialogs();
      util.setup_msie();

      var tmp_w = $(".btn_attr").width();
      var tmp_h = $(".btn_attr").height();
      $(".td_attr").css("width", tmp_h + 15);
      $(".td_attr").css("height", tmp_w + 15);
      var los = [];
      for ( var f in state.files) {
        if (state.files[f].name && (state.files[f].name.trim() != "")) {
          if (state.files[f].name && (state.files[f].name.trim() != "")) {
            var lo = {
              "name" : state.files[f].name,
              "description" : state.files[f].description.replace(/(<([^>]+)>)/ig, ""),
              "data" : state.files[f].data,
              "id" : Date.now(),
              "externalUID" : state.user.guid
            };
            los.push(lo);
          }
        }
      }

      backend.create_l_objects(JSON.stringify(los), function(obj) {
        backend.get_l_objects(function() {
          if (!state.teacher) {
            util.switch_student();
            if (state.load_domain) {
              backend.get_domains(state.gid, ui.show_initial_domain);
            } else {
              logic.enable_disable();
            }
          } else {
            if (state.load_domain) {
              backend.get_domains('-1', ui.show_initial_domain);
            } else {
              logic.enable_disable();
            }
          }
          if (state.msie) {
            console.debug("OH NO, IE! initilaizing objects and attributes on-demand");
          } else {
            backend.get_objects(function() {
              backend.get_attributes(function() {
              });
            });
          }
        });
      });

      if (!state.teacher) {
        logic.log("open fca tool", {});
      }
    });
  },

  log : function(verb, payload) {
    console.trace();
    // payload.userId = state.user.guid;
    context = {
      course : elgg.get_page_owner_guid(),
      user : state.user.guid, 
      phase : "7",
      widget_type : "fcatool"
    };
    try {
      post_to_stepup(window.location.href, verb, context, payload);
    } catch (error) {
      console.log(error);
    }
    console.log("POST TO STEPUP: " + verb);
    console.log("POST TO STEPUP add info:");
    console.log(context);
    console.log(payload);
    console.log("POST TO STEPUP add info end");
  },

  remove_lo : function(lo, object, o) {
    for ( var i in object.learningObjects) {
      if (object.learningObjects[i] == lo) {
        delete state.active_l_objects[object.learningObjects[i].id];
        object.learningObjects.splice(i, 1);
        break;
      }
    }
    for ( var i in object.learningObjectsByLearners) {
      if (object.learningObjectsByLearners[i] == lo) {
        delete state.active_l_objects[object.learningObjectsByLearners[i].id];
        object.learningObjectsByLearners.splice(i, 1);
        break;
      }
    }
    logic.save_item(object, o);
  },

  set_l_object : function(object) {
    $(".item_description").empty();
    util.clear_iframe();
    var objects = util.filter_l_lobjects(object);
    var sel = $("#sel_set_lo");
    sel.removeData();
    sel.click(function() {
      $("#sel_set_lo").val("");
      $("#sel_set_lo").removeData();
    });
    var items = [];
    for ( var obj in objects) {
      items.push({
        label : objects[obj].name,
        value : objects[obj].name,
        data : obj
      });
    }

    try {
      sel.autocomplete.destroy();
    } catch (not_an_error) {
    }

    sel.autocomplete({
      // this is needed because of the old jQueryUI version used
      source : function(request, response) {
        var results = $.ui.autocomplete.filter(items, request.term);

        if (results.length == 0) {
          state.select_do_create = request.term;
        } else
          state.select_do_create = false;
        results.splice(0, 0, {
          value : request.term,
          label : "create " + request.term
        });
        response(results);
      }
    });

    sel.bind("autocompleteselect", function(event, ui) {
      if (!ui.item.data)
        window.ui.create_lo(ui.item.value);
      else {
        $(this).blur();
        console.debug("Choose: " + ui.item.type);
        window.ui.display_lo_description(ui.item);
        $("#sel_set_lo").data(logic.key_lo, ui.item);
      }
    });

    $("#dia_set_lo").dialog("open");
    $("#dia_set_lo_content").css("background", "rgba(255,255,255,0.6)");

  },

  set_lo : function() {
    var data = $("#sel_set_lo").data(logic.key_lo);
    if (!data && $("#sel_set_lo").val()) {
      ui.create_lo($("#sel_set_lo").val());
    }
    if (!data)
      return;
    if (state.teacher)
      state.current_item.learningObjects.push(state.backend_l_objects[data.data]);
    else {
      state.current_item.learningObjectsByLearners.push(state.backend_l_objects[data.data]);
    }
    if (!state.teacher) {
      logic.log("add learning object", {
        loName : data.label,
        loUrl : state.backend_l_objects[data.data].data,
        latticeItem : state.current_item.name,
        itemId : state.current_item.id,
        domainName : state.domain.name,
        domainId : state.domain.id
      });
    }

    for ( var i in state.current_item.learningObjects) {
      state.active_l_objects[state.current_item.learningObjects[i].id] = state.current_item.learningObjects[i];
    }
    for ( var i in state.current_item.learningObjectsByLearners) {
      state.active_l_objects[state.current_item.learningObjectsByLearners[i].id] = state.current_item.learningObjectsByLearners[i];
    }

    $("#dia_set_lo").dialog("close");
    logic.save_item(state.current_item, state.type, false, data.data);

  },

  export_csv : function() {
    var objs = [];
    var attrs = [ "\"\"" ];

    $(".btn_attr").each(function() {
      var attr_name = "\"" + $(this).val().replace(/\"/g, "\\\"") + "\"";
      attrs.push(attr_name);
    });
    objs.push(attrs);
    $(".btn_obj").each(function() {
      var obj_name = "\"" + $(this).val().replace(/\"/g, "\\\"") + "\"";
      var o = [ obj_name ];
      objs.push(o);
    });
    var index = 0;
    $("#matrix_main > table > tbody > tr").each(function() {
      var current_obj = objs[++index];
      $(this).find("input").each(function() {
        current_obj.push((this.checked ? "x" : ""));
      });
    });
    var csv = "";
    for ( var line in objs) {
      for (var i = 0; i < objs[line].length; ++i) {
        if (i > 0)
          csv += ", ";
        csv += objs[line][i];
      }
      csv += "\n";
    }
    window.location.href = 'data:text/csv;charset=UTF-8,' + encodeURIComponent(csv);
  },

  save_item : function(object, entityType, hideDialog, newLO) {
    console.trace();
    console.debug(state);
    logic.enable_disable(); // TODO CLEAN UP
    if (state.conceptId)
      lattice.update_info(state.conceptId, sendLogData = false);

    var pack = util.setup_by_type(entityType);
    var updatefunc = pack.update_function;

    if (object.id in pack.items) { // update!
      var obj = pack.items[object.id];
      obj.name = object.name;
      obj.description = object.description;
      for ( var l in obj.learningObjects) {
        // check yourself before you wreck yourself
        if (obj.learningObjects[l].owner) {
          obj.learningObjects[l].owner.objects = {}; // cannot parse
          obj.learningObjects[l].owner.attributes = {};
        }
      }
      for ( var l in obj.learningObjectsByLearners) {
        // check yourself before you wreck yourself
        if (obj.learningObjectsByLearners[l].owner) {
          obj.learningObjectsByLearners[l].owner.objects = {}; // cannot parse
          obj.learningObjectsByLearners[l].owner.attributes = {};
        }
      }
      if (state.domain.id) {
        updatefunc(JSON.stringify(obj), function(resp) {
          pack.items[object.id] = resp;
          if (!state.teacher) {

            backend.get_valuations(function(obj) {
              ui.set_item(pack.index, entityType, object.id);
              console.debug(obj);
              lattice.update_valuation(obj);
              if (newLO)
                logic.consume_lo(newLO);
            });
          } else {
            if (!hideDialog)
              ui.set_item(pack.index, entityType, object.id);
          }
        });
      } else {
        ui.set_item(pack.index, entityType, object.id);
      }
    } else {
      var obj = pack.new_items[object.id];
      obj.name = object.name;
      obj.description = object.description;
      ui.set_item(pack.index, entityType, object.id);
    }
  },

  save_items : function(entityType) {
    var pack = util.setup_by_type(entityType);

    var updatefunc = pack.update_function_multiple;

    var objects = [];
    for ( var i in pack.items) {
      var obj = pack.items[i];
      obj.name = pack.items[i].name;
      obj.description = pack.items[i].description;
      for ( var l in obj.learningObjects) {
        // check yourself before you wreck yourself
        if (obj.learningObjects[l].owner) {
          obj.learningObjects[l].owner.objects = {}; // cannot parse
          obj.learningObjects[l].owner.attributes = {};
        }
      }
      for ( var l in obj.learningObjectsByLearners) {
        // check yourself before you wreck yourself
        if (obj.learningObjectsByLearners[l].owner) {
          obj.learningObjectsByLearners[l].owner.objects = {}; // cannot parse
          obj.learningObjectsByLearners[l].owner.attributes = {};
        }
      }
      objects.push(obj);
    }
    updatefunc(JSON.stringify(objects), function(resp) {
      for ( var i in resp)
        pack.items[resp[i].id] = resp[i];
    });
  },

  choose_item : function(entityType) {
    var pack = util.setup_by_type(entityType);
    if (!(state.select_do_create === false)) {
      var dat = {
        item : {
          value : state.select_do_create
        }
      };
      pack.select.trigger("autocompleteselect", dat);
      pack.select.val(state.select_do_create);
      state.select_do_create = false;
      return;
    }

    pack.dialog.dialog("close");

    var id = state.item_id;
    var item;
    if (id in pack.items) {
      item = pack.items[id];
    } else
      item = pack.new_items[id];

    $(pack.prefix + pack.index).prop("value", item.name).data(pack.key, item);
    state.attr_index = -1;
    state.obj_index = -1;
    util.filter_items(entityType);
    state.current_item = undefined;
  },

  create_mapping : function(name, description) {

    var mapping = {};
    var orderedAttribs = [];
    var orderedObjects = [];

    var attributes = $(".btn_attr");
    for (var i = 0; i < attributes.length; ++i) {
      orderedAttribs.push($("#" + attributes[i].id).data(logic.key_item).id);
    }

    var objects = $(".btn_obj");
    for (var i = 0; i < objects.length; ++i) {
      orderedObjects.push($("#" + objects[i].id).data(logic.key_item).id);
    }

    var checks = $(":checkbox:checked");
    for (var c = 0; c < checks.length; ++c) {

      try {
        var btn = $("#obj_" + (checks[c]).id.split("_")[1]);
        var obj = btn.data(logic.key_item);

        var attr = $("#attr_" + (checks[c]).id.split("_")[3]).data(logic.key_item);
        if (!(obj.id.toString() in mapping)) {
          mapping[obj.id.toString()] = [];

        } else {

        }
        mapping[obj.id.toString()].push(attr.id);

      } catch (error) {
      }
    }

    var result = {
      "name" : name,
      "description" : description,
      "attributes" : orderedAttribs,
      "objects" : orderedObjects,
      "mapping" : mapping
    };

    return result;
  },

  rem_object : function(index) {

    $("#dia_rem_obj").dialog("close");
    $(".tr_obj_" + index).remove();/*
                                     * var tmp_w = $(".btn_attr").width(); var
                                     * tmp_h = $(".btn_attr").height();
                                     * $(".td_attr").css("width", tmp_h + 15);
                                     * $(".td_attr").css("height", tmp_w + 15);
                                     */
  },

  rem_attribute : function(index) {

    $("#dia_rem_attr").dialog("close");
    $(".td_attr_" + index).remove();/*
                                     * var tmp_w = $(".btn_attr").width(); var
                                     * tmp_h = $(".btn_attr").height();
                                     * $(".td_attr").css("width", tmp_h + 15);
                                     * $(".td_attr").css("height", tmp_w + 15);
                                     */

    var tbl = $($("#matrix_attr_head").children().get(0));

    tbl.width(35 + ($(".btn_attr").length) * 35);
    tbl = $($("#matrix_main").children().get(0));

    tbl.width(35 + ($(".btn_attr").length) * 35);
  },

  create_domain : function(name, description) {
    logic.save(function() {
      var domain = logic.create_mapping(name, description);
      domain.externalUIDs = state.adminIDs;
      domain.externalCourseID = state.gid;
      domain.courseName = state.g_name;
      backend.create_domain(JSON.stringify(domain), function(obj) {

        $("#dia_create_domain").dialog("close");
        alert(elgg.echo('wespot_fca:info_saved'));
        state.domain = obj;
        $("#h_domain_name").empty().create("txt", obj.name);
        logic.save_items(entity_types.object);
        logic.save_items(entity_types.attribute);
        ui.display_lattice();
        $("#btn_save, #btn_approve, #btn_from_existing").show();
        logic.enable_disable();
      });
    });
  },

  create_lo : function(name, description, data) {
    if (data === undefined || data.trim() == "") {
      alert(elgg.echo("wespot_fca:l_obj:url"));
      return;
    }
    $("#sel_set_lo").removeData();

    if ((data.toLowerCase().indexOf("http://") == -1) && (data.toLowerCase().indexOf("https://") == -1))
      data = "http://" + data;
    var lo = {
      name : name,
      description : description,
      data : data,
      id : Date.now(),
      externalUID : state.user.guid
    };

    // this is not pretty!
    // on the other hand, anything more would be a waste since learning objects
    // are pretty undefined as of now
    backend.create_l_objects(JSON.stringify([ lo ]), function(obj) {
      for ( var i in obj) {
        lo = obj[i];
        lo.id = i;
      }
      state.backend_l_objects[lo.id] = lo;
      $("#sel_set_lo").data(logic.key_lo, {
        description : lo.description,
        data : lo.id
      });
      $("#sel_set_lo").val(lo.name);
      ui.display_lo_description({
        description : lo.description,
        data : lo.id
      });
      $("#dia_create_lo").dialog("close");

    });
  },

  approve_domain : function() {
    $("#dia_rem_attr").dialog("close");
    backend.approve_domain(state.domain.id, function() {
      state.domain.approved = true;
      logic.enable_disable();
      alert("Domain approved and pubished!");
    });
  },

  save_domain : function() {
    if (state.domain) {
      logic.save(function() {
        var domain = logic.create_mapping(state.domain.name, state.domain.description);
        domain.externalUIDs = state.adminIDs;
        domain.externalCourseID = state.gid;
        domain.courseName = state.g_name;
        backend.update_domain(JSON.stringify(domain), function(obj) {

          $("#dia_create_domain").dialog("close");
          alert(elgg.echo('wespot_fca:info_saved'));
          state.domain = obj;

          util.set_state(state.domain.id);
          logic.save_items(entity_types.object);
          logic.save_items(entity_types.attribute);
          ui.display_lattice();
        });
      });
    } else
      $('#dia_create_domain').dialog('open');
  },

  check_save : function() {
    var currentObjects = $(".btn_obj");
    for (var i = 0; i < currentObjects.length; ++i) {
      // if (!$.hasData(currentObjects[i])) {
      if (!$(currentObjects[i]).data(logic.key_item)) {
        alert(elgg.echo('wespot_fca:err_undefined', [ elgg.echo('wespot_fca:objs') ]));
        return false;
      }
    }

    currentObjects = $(".btn_attr");
    for (var i = 0; i < currentObjects.length; ++i) {
      // if (!$.hasData(currentObjects[i])) {
      if (!$(currentObjects[i]).data(logic.key_item)) {
        alert(elgg.echo('wespot_fca:err_undefined', [ elgg.echo('wespot_fca:attrs') ]));
        return false;
      }
    }
    return true;
  },
  save : function(callback) {
    var objects = [];
    for ( var i in state.new_objects)
      objects.push(state.new_objects[i]);

    var attributes = [];
    for ( var i in state.new_attributes)
      attributes.push(state.new_attributes[i]);

    backend.create_objects(JSON.stringify(objects), function(obj) {

      util.replace_items(obj, entity_types.object);
      backend.create_attributes(JSON.stringify(attributes), function(attr) {

        util.replace_items(attr, entity_types.attribute);

        if (callback)
          callback();
      });
    });
  },

  enable_disable : function() {
    $("#btn_share").hide();
    if (state.domain && state.domain.approved) {
      
      if (state.adminIDs.indexOf(state.user.guid.toString()) > -1)
        // if (state.owner_id == state.domain.owner.externalUid)
        $("#btn_share").show();
      $(".input").prop("disabled", true);
      $(".btn_del_attr").css("visibility", "hidden");
      $(".btn_del_obj").css("visibility", "hidden");
      $(".add_buttons").css("visibility", "hidden");
      $("#btn_new, #btn_from_existing, #btn_open").prop("disabled", false);
      $("#btn_save, #btn_approve, #btn_approve").hide();
      $(".btn_move_right, .btn_move_left, .btn_move_up, .btn_move_down").hide();
      $(".to_be_hidden").hide();
      if (state.teacher) {
        $("#btn_show_learner_lattice").prop("disabled", false);
        $("#btn_csv").show();
        $("#btn_csv").prop("disabled", false);
      } else
        $("#btn_show_learner_lattice").hide();
    } else {
      $("#btn_show_learner_lattice").hide();
      $(".input").prop("disabled", false);
      $(".btn_del_attr").css("visibility", "visible");
      $(".btn_del_obj").css("visibility", "visible");
      $(".add_buttons").css("visibility", "visible");
      if (state.teacher)
        $("#btn_save, #btn_approve, #btn_from_existing").show();
      $(".to_be_hidden").show();
    }
    $(".always_on").removeProp("disabled");
    if (!state.domain) {
      $("#btn_from_existing, #btn_approve, #btn_csv").hide();
    }
  },

  populate_domain : function(domain, teacher) {
    state.domain = domain;
    $("#h_domain_name").empty().create("txt", domain.name);
    var num_attributes = Object.keys(domain.mapping.attributes).length;
    var num_objects = Object.keys(domain.mapping.objects).length;

    while ($(".td_attr").length < num_attributes) {
      ui.append_attribute();
    }
    while ($(".btn_del_obj").length < num_objects) {
      ui.append_object();
    }

    for (var currentAttribs = $(".td_attr").length; currentAttribs > num_attributes; currentAttribs = $(".td_attr").length) {
      var index = $(".td_attr")[0].childNodes[2].id.split("_")[1];
      logic.rem_attribute(index);
    }
    while ($(".btn_del_obj").length > num_objects) {
      logic.rem_object($($(".btn_del_obj")[0]).prop("id").split("_")[3]);
    }
    backend.get_objects(backend.get_attributes(function() {
      setTimeout(function() {
        var index = 0;
        for ( var a in domain.mapping.attributes) {

          var id = "#attr_" + $(".td_attr")[index].childNodes[2].id.split("_")[1];

          var attribute = JSON.parse(a);
          $(id).data(logic.key_item, attribute);
          $(id).prop("value", attribute.name);
          $(id).prop("title", attribute.name);
          ++index;
        }

        $(".check").prop("checked", false);
        var objects = $(".btn_del_obj");
        index = 0;
        for ( var o in domain.mapping.objects) {
          var id = "#obj_" + ($(objects[index]).prop("id").split("_")[3]);
          var object = $.parseJSON(o);

          $(id).data(logic.key_item, object);
          $(id).prop("value", object.name);
          $(id).prop("title", object.name);
          var mapped = domain.mapping.objects[o];

          for (var m = 0; m < mapped.length; ++m) {
            var currentA = $(".btn_attr");
            for (var a = 0; a < currentA.length; ++a) {
              if ($("#" + currentA[a].id).data(logic.key_item).id == mapped[m].id) {
                $(id + "_" + currentA[a].id).prop("checked", true);
              }
            }
          }
          ++index;
        }

        if (state.msie) {
          setTimeout(function() {
            ui.display_lattice();
          }, 300);
        } else {
          ui.display_lattice();
        }
        state.teacher_lattice = state.domain.formalContext;
        logic.enable_disable();
      }, 1000);
    }));
  },

  display_share_dialog : function(owned_groups) {
 
    for(var i = 0; i < owned_groups.length; i++){
	    console.log(i + " = " + owned_groups[i]);
	}
    $("#sel_set_course").empty();
    backend.get_course_domains(function(courses) {
      var blacklist = [];
      for ( var c in courses) {
        if (courses[c].indexOf(state.domain.id) != -1)
         { blacklist.push(c);
           console.log("blacklist"+c);
         }
      }
      for ( var i in state.groups) {
	console.log(state.groups[i].id); 
        console.log(util.contains(owned_groups,state.groups[i].id));
        console.log("on blacklist: "+blacklist.indexOf(state.groups[i].id));
       if ((state.groups[i].id != state.gid) && (blacklist.indexOf(state.groups[i].id) == -1) && (owned_groups.indexOf(state.groups[i].id)!=-1)) {
          
         $("#sel_set_course").create("option", {
            value : state.groups[i].id
          }).create("txt", $("<div/>").html(state.groups[i].name).text());
        }
      }
      $("#sel_set_course").prop("selectedIndex", "-1");
      $("#dia_share_dom").dialog("open");
      $(".item_description").empty();
    })
  },

  load : function(domainid, teacher) {
    state.conceptId = undefined;
    if (state.teacher)
      $("#btn_save, #btn_approve, #btn_from_existing").show();
    $("#dia_set_dom").dialog("close");
    util.set_state(domainid);
    if (state.teacher) {
      backend.get_domain(domainid, function(domain) {
        logic.populate_domain(domain, teacher);
      });
    } else {
      backend.get_learner_domain(domainid, state.user.guid.toString(), function(domain) {
        logic.populate_domain(domain);
      });
    }
  },

  consume_lo : function(id) {
    var postdata = {
      "id" : state.domain.id,
      "externalUID" : state.user.guid.toString(),
      "learningObjectId" : id,
      "course" : false
    };
    backend.update_valuation(JSON.stringify(postdata), lattice.update_valuation);
    logic.log("consume learning object", {
      loName : state.backend_l_objects[id].name,
      loUrl : state.backend_l_objects[id].data,
      loId : id,
      domainName : state.domain.name,
      domainId : state.domain.id
    });
    $("#btn_lo_" + id).addClass("btn_lo_clicked");
  }
};

ui = {

  prepare_table : function() {
    $("#matrix_main").scroll(function(e) {
      $("#matrix_obj_head").scrollTop($("#matrix_main").scrollTop());
      $("#matrix_attr_head").scrollLeft($("#matrix_main").scrollLeft());
    });
  },

  prepare_dialogs : function() {
    $("#dia_set_item").dialog({
      autoOpen : false,
      height : 320,
      width : 510,
      resizable : false,
      modal : true,
      beforeClose : ui.clear_dialog
    });/*
         * $("#dia_set_attr").dialog({ autoOpen : false, height : 320, width :
         * 510, resizable : false, modal : true, beforeClose : ui.clear_dialog
         * });
         */
    $("#dia_set_dom").dialog({
      autoOpen : false,
      height : 200,
      width : 400,
      resizable : false,
      modal : true
    });
    $("#dia_share_dom").dialog({
      autoOpen : false,
      height : 200,
      width : 400,
      resizable : false,
      modal : true
    });
    $("#dia_set_lo").dialog({
      autoOpen : false,
      height : 410,
      width : 450,
      resizable : false,
      modal : true
    });
    $("#dia_add_obj").dialog({
      autoOpen : false,
      height : 200,
      width : 400,
      resizable : false,
      modal : true
    });
    $("#dia_add_attr").dialog({
      autoOpen : false,
      height : 200,
      width : 400,
      resizable : false,
      modal : true
    });
    $("#dia_rem_attr").dialog({
      autoOpen : false,
      height : 200,
      width : 400,
      resizable : false,
      modal : true
    });
    $("#dia_rem_obj").dialog({
      autoOpen : false,
      height : 200,
      width : 400,
      resizable : false,
      modal : true
    });
    $("#dia_publish_domain").dialog({
      autoOpen : false,
      height : 200,
      width : 400,
      resizable : false,
      modal : true
    });
    $("#dia_create_obj").dialog({
      autoOpen : false,
      height : 200,
      width : 400,
      resizable : false,
      modal : true
    });
    $("#dia_create_attr").dialog({
      autoOpen : false,
      height : 200,
      width : 400,
      resizable : false,
      modal : true
    });

    $("#dia_create_domain").dialog({
      autoOpen : false,
      height : 200,
      width : 400,
      resizable : false,
      modal : true
    });

    $("#dia_create_lo").dialog({
      autoOpen : false,
      height : 200,
      width : 400,
      resizable : false,
      modal : true
    });

    if (state.teacher) {

      $("#dia_vis").dialog({
        autoOpen : false,
        height : 160,
        resizable : false,
        modal : false,
        dialogClass : 'no-close',
        closeOnEscape : false,
        beforeclose : function() {
          return false;
        }
      }).dialogExtend({
        minimize : true,
        maximize : false,
        events : {
          "beforeMinimize" : function() {
            $("#btn_show_learner_lattice").hide();
            $("#sel_show_learner_lattice").hide();
            $('#ui-dialog-title-dia_vis').css("width", '90%');

          },
          "beforeRestore" : function() {
            $('#ui-dialog-title-dia_vis').css("width", 'auto');
            if (!state.learner_lattice_learner) {
              $("#btn_show_learner_lattice").show();
            } else {
              $("#sel_show_learner_lattice").show();
            }
          }
        }
      });
    }
    ui.hide_learner_lattice_dropdown();

  },

  try_show_save_dialog : function() {
    if (logic.check_save())
      logic.save_domain();
  },

  try_show_create_dialog : function() {
    if (logic.check_save())
      $('#dia_create_domain').dialog('open');
  },

  display_help : function() {
    window.open("http://www.youtube.com/watch?v=yrjsM_X0u5s", "FCA_HELP", "width=800,height=600, scrollbars=yes,resizable=yes");
  },

  clear_dialog : function() {
    state.current_item = undefined;
  },

  setup_btn_hover : function() {
    for (var i = 0; i < $(".btn_obj").length; ++i) {
      var btn = $($(".btn_obj").get(i));
      ui.setup_hover_obj(btn);
    }

    for (var i = 0; i < $(".btn_attr").length; ++i) {
      var btn = $($(".btn_attr").get(i));
      ui.setup_hover_attr(btn);
    }
  },

  move_down : function(id) {
    if (!($($(".tr_obj_" + id).get(0)).next().hasClass("obj_tail"))) {
      console.trace();
      $(".tr_obj_" + id).each(function(index) {
        $(this).next().after($($(".tr_obj_" + id).get(index)));
      });
    }
    $(".btn_move_right").hide();
    $(".btn_move_left").hide();
    $(".btn_move_up").hide();
    $(".btn_move_down").hide();
  },
  move_up : function(id) {
    if ($($(".tr_obj_" + id).get(0)).prev()) {
      $(".tr_obj_" + id).each(function(index) {
        $(this).prev().before($($(".tr_obj_" + id).get(index)));
      });
    }
    $(".btn_move_right").hide();
    $(".btn_move_left").hide();
    $(".btn_move_up").hide();
    $(".btn_move_down").hide();
  },

  move_left : function(id) {
    var td = $(".td_attr_" + id);
    if (!$(td.get(0)).prev().prop("class"))
      return;
    td.each(function() {
      var prev = $(this).prev();
      $(this).detach().insertBefore(prev);
    });
    $(".btn_move_right").hide();
    $(".btn_move_left").hide();
    $(".btn_move_up").hide();
    $(".btn_move_down").hide();
  },

  move_right : function(id) {
    var td = $(".td_attr_" + id);
    if (!(td.next().next().get(0)))
      return;
    td.each(function() {
      var next = $(this).next();

      $(this).detach().insertAfter(next);
    });
    $(".btn_move_right").hide();
    $(".btn_move_left").hide();
    $(".btn_move_up").hide();
    $(".btn_move_down").hide();
  },

  setup_hover_obj : function(btn) {
    $(".btn_move_down").hover(function() {
      state.hover = 1;
    }, function() {
      setTimeout(function() {
        if (state.hover != null) {
          state.hover = null;
          $(".btn_move_down").hide();
          $(".btn_move_up").hide();
        }
      }, 100);
    });
    $(".btn_move_up").hover(function() {
      state.hover = 1;
    }, function() {
      setTimeout(function() {
        if (state.hover != null) {

          state.hover = null;
          $(".btn_move_down").hide();
          $(".btn_move_up").hide();
        }
      }, 100);
    });
    btn.hover(function() {
      state.hover = null;
      setTimeout(function() {
        if (!state.domain || !state.domain.approved) {
          $(".btn_move_down").hide();
          $(".btn_move_up").hide();
          $("#btn_move_up_" + btn.prop("id").split("_")[1]).show();
          $("#btn_move_down_" + btn.prop("id").split("_")[1]).show();
        }
      }, 150);
    }, function() {
      setTimeout(function() {
        if (!state.hover) {
          $(".btn_move_down").hide();
          $(".btn_move_up").hide();
        }
      }, 150);
    });
  },

  setup_hover_attr : function(btn) {
    $(".btn_move_left").hover(function() {
      state.hover = 1;
    }, function() {
      setTimeout(function() {
        if (state.hover != null) {
          state.hover = null;
          $(".btn_move_left").hide();
          $(".btn_move_right").hide();
        }
      }, 100);
    });
    $(".btn_move_right").hover(function() {
      state.hover = 1;
    }, function() {
      setTimeout(function() {
        if (state.hover != null) {

          state.hover = null;
          $(".btn_move_left").hide();
          $(".btn_move_right").hide();
        }
      }, 100);
    });
    btn.hover(function() {
      if (!state.domain || !state.domain.approved) {
        state.hover = null;
        setTimeout(function() {
          $(".btn_move_left").hide();
          $(".btn_move_right").hide();
          $("#btn_move_right_" + btn.prop("id").split("_")[1]).show();
          $("#btn_move_left_" + btn.prop("id").split("_")[1]).show();

        }, 150);
      }
    }, function() {
      setTimeout(function() {
        if (!state.hover) {
          $(".btn_move_left").hide();
          $(".btn_move_right").hide();
        }
      }, 150);
    });
  },

  create_lo : function(name) {
    $("#input_create_lo_name").val(name);
    $("#dia_create_lo").dialog("open");
    $("#input_create_lo_description").val("");
  },

  set_item : function(index, entityType, id) {
    $("#lo_item").show();
    $("#label_lo").show();
    state.select_do_create = false;
    var pack = util.setup_by_type(entityType);

    var data = $(pack.prefix + index);

    pack.select.val("");
    ui.prepare_dialog(entityType);
    (entityType == entity_types.attribute) ? state.attr_index = index : state.obj_index = index;

    state.item_id = undefined;

    if (!id && data.data(pack.key))
      id = data.data(pack.key).id;
    if (id) {
      ui.display_item_description(id, entityType);
    }

    if (state.msie && !(pack.inited)) {
      if (entityType == entity_types.attribute) {
        state.inited_attr = true;
        backend.get_attributes(function() {
          ui.set_item(index, entityType, id);
        });
      } else {
        state.inited_obj = true;
        backend.get_objects(function() {
          ui.set_item(index, entityType, id);
        });
      }
    } else {
      var objects = util.filter_items(entityType);

      var items = [];
      for ( var obj in objects) {
        items.push({
          label : objects[obj].name,
          value : objects[obj].name,
          data : obj
        });
      }
      state.type = entityType;
      pack.select.autocomplete({
        // this is needed because of the old jQueryUI version used
        source : function(request, response) {
          var results = $.ui.autocomplete.filter(items, request.term);

          if (results.length == 0) {
            state.select_do_create = request.term;
          } else
            state.select_do_create = false;
          results.splice(0, 0, {
            value : request.term,
            label : "create " + request.term
          });
          response(results);
        }
      });

      pack.select.bind("autocompleteselect", function(event, ui) {
        if (!ui.item.data)
          window.ui.prepare_item_edit(state.type, true);
        else {
          $(this).blur();
          console.debug("Choose: " + ui.item.type);
          window.ui.display_item_description(ui.item.data, state.type);
        }
      });

      var title = (entityType == entity_types.attribute) ? elgg.echo('wespot_fca:attr:set') : elgg
          .echo('wespot_fca:obj:set');
      $("#dia_set_item").attr("title", title);
      $("#dia_set_item").dialog("open");
      if ((state.domain && state.domain.approved) || !state.teacher) {
        $("#btn_item_edit").hide();
        $(".to_be_hidden").hide();
      } else {
        $("#btn_item_edit").show();
        $(".to_be_hidden").show();
      }

    }
    if (id)
      pack.select.blur();
    else {
      pack.select.val("");
      $("#btn_item_edit").hide();
    }
  },

  prepare_dialog : function(entityType) {

    state.editing = false;
    var btn = $("#btn_choose_item_ok");
    var btn_cancel = $("#btn_choose_item_cancel");

    btn.removeAttr("onclick");
    btn_cancel.removeAttr("onclick");
    btn.unbind("click");
    btn_cancel.unbind("click");
    btn.val(elgg.echo('wespot_fca:ok'));
    btn_cancel.click(function() {
      $('#dia_set_item').dialog('close');
    });
    btn.click(function() {
      logic.choose_item(entityType);
    });
    $(".text_description").empty();
    $(".descr_detail").hide();
    $(".btn_edit").hide();

    var sel = $("#sel_set_item");

    sel.blur(function() {
      try {
        $(this).val(state.current_item.name);
      } catch (not_an_error) {
      }
    });
    sel.click(function() {
      $(this).val("");
      // ui.set_item(state.attr_index, entityType);
    });

  },

  add_object : function() {
    $(".item_description").empty();
    $("#dia_add_obj").dialog("open");
  },

  add_attribute : function() {
    $(".item_description").empty();
    $("#dia_add_attr").dialog("open");
  },

  append_attribute : function() {

    var id = ++state.id_last_attr;

    $("<col class=\"td_attr_" + id + "\">").insertBefore($($(".col_tail")).get(0));
    $("<col class=\"td_attr_" + id + "\">").insertBefore($($(".col_tail")).get(1));
    var i = 0;
    var tails = $(".tail");
    var cb;
    var len = tails.length;
    var tbl = $($("#matrix_attr_head").children().get(0));
    tbl.width(35 + ($(".btn_attr").length + 1) * 35);
    tbl = $($("#matrix_main").children().get(0));
    tbl.width(35 + ($(".btn_attr").length + 1) * 35);
    for ( var elem in tails) {
      if (i == 0) {
        var td = $(document.createElement("td"));
        td.addClass("right td_attr_" + id);
        td.create("input", {
          type : "image",
          src : state.basedir + "img/delete.svg",
          width : "16px",
          height : "16px",
          alt : "x",
          title : elgg.echo('wespot_fca:attr:rem'),
          id : "btn_del_attr_" + id,
          class : "input btn_del_attr",
          onclick : "ui.rem_attribute(" + id + ")"
        });
        td.insertBefore($(tails[elem]));
      } else if (i == 1) {
        var td = $(document.createElement("td"));
        td.addClass("td_attr td_attr_" + id);
        td.create("input", {
          type : "image",
          src : state.basedir + "img/left.svg",
          id : "btn_move_left_" + id,
          width : "16px",
          height : "40px",
          alt : "&lt",
          title : elgg.echo('wespot_fca:move_left'),
          class : "input btn_move_left",
          onclick : "ui.move_left(" + id + ")"
        });
        td.create("input", {
          type : "image",
          src : state.basedir + "img/right.svg",
          id : "btn_move_right_" + id,
          width : "16px",
          height : "40px",
          alt : "&gt",
          title : elgg.echo('wespot_fca:move_right'),
          class : "input btn_move_right",
          onclick : "ui.move_right(" + id + ")"
        });
        td.create("input", {
          type : "button",
          id : "attr_" + id,
          class : "input btn_attr col always_on",
          value : elgg.echo('wespot_fca:attr:dummy') + " " + (id + 1),
          onclick : "ui.set_item(" + id + ",entity_types.attribute)"
        });
        td.insertBefore($(tails[elem]));
        ui.setup_hover_attr($("#attr_" + id));
      } else if (i < len - 1) {
        $("<td></td>").insertBefore($(tails[elem]));
        cb = $(tails[elem]).prev();
        var obj_index = $(cb.prev().children().get(0)).prop("id").split("_")[1];
        cb.prop("class", "cb_attr td_attr_" + id);
        cb.create("input", {
          "type" : "checkbox",
          "class" : "input check",
          "id" : "obj_" + obj_index + "_attr_" + id
        });

      } else if (i == len - 1) {
        $("<td class=\"td_attr_" + id + "\"></td>").insertBefore($(tails[elem]));
      }
      ++i;
    }
  },

  append_object : function() {

    var id = ++state.id_last_obj;
    var tails = $(".obj_tail");
    var tr_cb = $("<tr class=\"tr_obj tr_obj_" + id + "\" ></tr>");
    var tr_btn = $("<tr class=\"tr_obj_" + id + "\" ></tr>");
    tr_cb.insertBefore($(tails.get(1)));
    tr_btn.insertBefore($(tails.get(0)));
    var attrs = $(".btn_attr");
    var td = tr_btn.create("td", {
      "class" : "left"
    });

    td.create("input", {
      "type" : "image",
      "src" : state.basedir + "img/up.svg",
      "alt" : "^",
      "title" : elgg.echo('wespot_fca:move_up'),
      "id" : "btn_move_up_" + id,
      "class" : "input btn_move_up",
      "onclick" : "ui.move_up(" + id + ")",
      "width" : "40px",
      "height" : "16px"
    });
    td.create("input", {
      "type" : "image",
      "src" : state.basedir + "img/down.svg",
      "alt" : "v",
      "title" : elgg.echo('wespot_fca:move_down'),
      "id" : "btn_move_down_" + id,
      "class" : "input btn_move_down",
      "onclick" : "ui.move_down(" + id + ")",
      "width" : "40px",
      "height" : "16px"
    });

    td.create("input", {
      "type" : "image",
      "src" : state.basedir + "img/delete.svg",
      "alt" : "x",
      "title" : elgg.echo('wespot_fca:obj:rem'),
      "id" : "btn_del_obj_" + id,
      "class" : "input btn_del_obj",
      "onclick" : "ui.rem_object(" + id + ")"
    });
    ui.setup_hover_obj(td.create("input", {
      "type" : "button",
      "id" : "obj_" + id,
      "class" : "input btn_obj always_on",
      "value" : elgg.echo('wespot_fca:obj:dummy') + " " + (id + 1),
      "onclick" : "ui.set_item(" + id + ",entity_types.object)"
    }));

    for (var a = 0; a < attrs.length; ++a) {

      var attr_id = $(attrs[a]).prop("id").split("_")[1];
      tr_cb.append("<td class=\" cb_attr td_attr_" + attr_id
          + "\"><input type=\"checkbox\" class=\"input check\" id=\"obj_" + id + "_attr_" + attr_id + "\" /></td>");
    }
    tr_cb.append("<td class=\"tail\" style=\"background-color: #fff\"></td>");
  },

  rem_attribute : function(index) {
    if ($(".td_attr").length == 1) {
      alert(elgg.echo('wespot_fca:err_rem_only', [ elgg.echo('wespot_fca:attr') ]));
      return;
    }

    if (!$("#attr_" + index).data(logic.key_item)) {
      logic.rem_attribute(index);
      return;
    }

    $(".item_description").empty();
    $("#span_rem_attr").empty();

    $("#span_rem_attr").create("txt", $("#attr_" + index).prop("value"));
    $("#btn_rem_attr_yes").click(function() {
      logic.rem_attribute(index);
    });
    $("#dia_rem_attr").dialog("open");
  },

  rem_object : function(index) {
    if ($(".btn_del_obj").length == 1) {
      alert(elgg.echo('wespot_fca:err_rem_only', [ elgg.echo('wespot_fca:obj') ]));
      return;
    }

    if (!$("#obj_" + index).data(logic.key_item)) {
      logic.rem_object(index);
      return;
    }

    $(".item_description").empty();
    $("#span_rem_obj").empty();

    $("#span_rem_obj").create("txt", $("#obj_" + index).prop("value"));
    $("#btn_rem_obj_yes").click(function() {
      logic.rem_object(index);
    });
    $("#dia_rem_obj").dialog("open");
  },

  create_object : function() {
    $(".item_description").empty();
    $("#dia_create_obj").dialog("open");
  },

  create_attribute : function() {
    $(".item_description").empty();
    $("#dia_create_attr").dialog("open");
  },

  display_share_ok_error : function(o) {
    $("#dia_share_dom").dialog("close");
    if (o) {
      alert('OK!');
    }
  },

  display_description : function(select) {
    $(".item_description").empty();
    if (select.selectedIndex != -1) {
      var str_domain = select.options[select.selectedIndex].value;
      if (str_domain != "-1") {
        ui.display_domain_description(select);
      } else {
        $("#btn_choose_dom_ok").prop("disabled", true);
      }
    }
  },

  display_lo_description : function(item) {
    util.clear_iframe();
    $(".item_description").empty();
    var preview = $(document.createElement("div"));
    preview.attr("id", "ifr_preview");
    preview.create("iframe", {
      class : "scaled-frame",
      src : state.backend_l_objects[item.data].data
    });
    console.debug(state.backend_l_objects[item.data]);
    preview.insertBefore(".item_description");
    $(".item_description").create("txt", state.backend_l_objects[item.data].description);
    if (state.backend_l_objects[item.data].owner) {
      $(".item_description").create("br");
      $(".item_description").create("txt",
          "(" + elgg.echo('wespot_fca:created_by') + " " + state.backend_l_objects[item.data].owner.name + ")");
    }
  },

  display_domain_description : function(select) {
    var obj = JSON.parse(select.options[select.selectedIndex].value);
    $("#btn_choose_dom_ok").prop("disabled", false);

    $(".item_description").create("txt", obj.description);

    if (obj.owner) {
      $(".item_description").create("br");
      $(".item_description").create("txt", "(" + elgg.echo('wespot_fca:created_by') + " " + obj.owner.name + ")");
    }

  },

  set_lo : function(object, select, entityType) {
    $("#btn_choose_lo_ok").unbind("click");
    $("#btn_choose_lo_ok").click(function() {
      logic.save_item(object.name, select, object.description, entityType);// name,
    });
  },

  create_lo_div : function(lo, object, div_lo, entityType, byLearner) {

    var div = div_lo.create("div", {
      id : "lo_" + lo.id,
      "class" : "span_lo"
    });
    var tdiv = div.create("div", {
      "class" : byLearner ? "txt_lo learner_lo" : "txt_lo"
    }).click(function() {
      ui.show_lo_popup(lo.id, lo.data);
    });
    tdiv.create("txt", lo.name);
    var buttons = div.create("div", {
      class : "div_lo_buttons"
    });
    /*
     * buttons.create("input", { type : "image", "class" : "input btn_lo", src :
     * state.basedir + "img/edit.svg", width : "16px", height : "16px"
     * }).click(function() {
     * 
     * });
     */
    buttons.create("input", {
      type : "image",
      "class" : "input btn_lo",
      src : state.basedir + "img/delete.svg",
      width : "16px",
      height : "16px"
    }).click(function() {
      logic.remove_lo(lo, object, entityType);
    });
    if (state.teacher)
      div.hover(function() {
        ui.hide_lo_buttons();
        ui.show_lo_buttons(this);
      }, function() {
        ui.hide_lo_buttons();
      });
  },

  display_learning_objects : function(object, entityType) {
    var div_lo = $("#lo_item");
    div_lo.empty();

    for ( var i in object.learningObjects) {
      ui.create_lo_div(object.learningObjects[i], object, div_lo, entityType, false);
    }
    for ( var i in object.learningObjectsByLearners) {
      ui.create_lo_div(object.learningObjectsByLearners[i], object, div_lo, entityType, true);
    }
    if (!state.domain || !state.domain.global) {
      div_lo.append("<br>");
      div_lo.create("input", {
        type : "image",
        src : state.basedir + "img/add.svg",
        width : "22px",
        height : "22px",
        class : "input btn_add_lo always_on",
        title : elgg.echo("wespot_fca:l_objs:add")
      }).click(function() {
        logic.set_l_object(object, entityType);
      });
    }
  },

  display_item_description : function(id, entityType) {
    $(".div_lo").empty();
    $(".btn_edit").show();

    var pack = util.setup_by_type(entityType);
    state.item_id = id;
    $(".text_description").empty();
    $(".descr_detail").show();
    $(".text_description").show();
    $(pack.textarea_descr).prop("readonly", true);
    if (pack.items[id]) {
      $(".text_description").val(pack.items[id].description);
      ui.display_learning_objects(pack.items[id], entityType);
      pack.select.val(pack.items[id].name);
      state.current_item = pack.items[id];
    } else {
      state.current_item = pack.new_items[id];
      $(".text_description").val(pack.new_items[id].description);
      ui.display_learning_objects(pack.new_items[id], entityType);
      pack.select.val(pack.new_items[id].name);
    }
  },

  show_lo_buttons : function(lo) {
    $(lo).children(".txt_lo").css("border-bottom-right-radius", "5px");
    $(lo).children(".txt_lo").css("background-color", "#fff");

    $(lo).children(".div_lo_buttons").css("z-index", "1");
  },

  hide_lo_buttons : function(lo) {
    $(".div_lo_buttons").css("z-index", "-1");
    $(".txt_lo").css("border-bottom-right-radius", "3px");
    $(".txt_lo").css("background-color", "rgba(255,255,255,0.9)");
  },

  cancel_item_edit : function(entityType, item) {
    state.editing = false;
    state.current_item = undefined;
    var pack = util.setup_by_type(entityType);
    // no item was selected, a new one was being created
    if (!item) {
      $(pack.textarea_descr).val("");
      ui.set_item(pack.index, entityType);
    } else {
      $("#lo_item").show();
      $("#label_lo").show();
      $(pack.textarea_descr).val(item.description);
      pack.select.val(item.name);
      ui.set_item(pack.index, entityType, item.id);
    }
  },

  prepare_item_edit : function(entityType, clear) {
    state.edit_current_item = !clear;
    if (clear) {
      $(".div_lo").empty();
      $(".text_description").val("");
    }
    $(".btn_edit").hide();
    var pack = util.setup_by_type(entityType);
    $("#lo_item").hide();
    $("#label_lo").hide();
    try {
      pack.select.autocomplete("destroy");
    } catch (not_an_error) {
    }
    pack.select.unbind("click");
    pack.select.unbind("blur");
    pack.btn_ok.removeAttr("onclick");
    pack.btn_cancel.removeAttr("onclick");
    pack.btn_ok.unbind("click");
    pack.btn_cancel.unbind("click");
    pack.btn_ok.val(elgg.echo('wespot_fca:save'));
    pack.btn_ok.click(function() {
      var item;
      if (state.edit_current_item)
        item = state.current_item;
      state.current_item = undefined;
      if (!item) {
        var item = {
          id : Date.now(),
          learningObjects : []
        };
        pack.new_items[item.id] = item;
      }
      item.name = pack.select.val();
      item.description = $(pack.textarea_descr).val();
      logic.save_item(item, entityType);
    });

    pack.btn_cancel.click(function() {
      var item = state.current_item;
      ui.cancel_item_edit(entityType, item);
    });
    $(".descr_detail").show();
    $(pack.textarea_descr).prop("readonly", false);
    pack.select.focus();
  },

  display_item_edit : function(entityType) {
    state.editing = !state.editing;
    ui.prepare_item_edit(entityType);
  },

  show_initial_domain : function(courses) {
    for ( var id in courses) {
      if (state.load_domain in courses[id].domains) {
        logic.load(state.load_domain, state.teacher);
        return;
      }

      var dId = undefined;
      if (courses[id].externalCourseID == state.gid) {
        for ( var d in courses[id].domains) {
          var dom = courses[id].domains[d];
          console.debug(dom);
          console.debug(state.user.guid);
          var owned = false;
          for ( var x in dom.owners) {
            if (dom.owners[x].externalUid == state.user.guid) {
              owned = true;
              break;
            }
          }
          if (dom.approved || owned) {
            if (dId === undefined)
              dId = d;
            else {
              if (dId > d)
                dId = d;
            }
          }
        }
        if (!(dId === undefined)) {
          logic.load(dId, state.teacher);
          return;
        }
      }
    }
    // if (!state.teacher)
    // loads IBL domain in case no other domain is assigned to the inquiry
    /*
     * for ( var id in courses) { if (courses[id].externalCourseID == "-1") for (
     * var d in courses[id].domains) { logic.load(d, state.teacher); return; } }
     */
    // $("#btn_approve, #btn_from_existing").hide();
  },

  list_domains : function(courses) {
    $("#sel_set_dom").empty();
    
    for ( var id in courses) {
      // this is true for teh domain IBL. Since IBL is considered global.
      /*if (courses[id].externalCourseID == "-1") {
        continue; // uncomment this if IBL shell be displayed again.
        for ( var d in courses[id].domains) {
          courses[id].domains[d].id = d;
          $("#sel_set_dom").create("option", {
            value : JSON.stringify(courses[id].domains[d])
          }).create("txt", courses[id].domains[d].name);
        }
      } else {*/

 //       var showInquiry = true;
//        if (showInquiry) {

  //        }
	var selected_domains = new Array();
        for ( var d in courses[id].domains) {
	  courses[id].domains[d].id = d;
          var dom = courses[id].domains[d];
          var owned = false;
          for ( var x in dom.owners) {
              if (dom.owners[x].externalUid == state.user.guid) {
         	     owned = true;
     	 	     break;
            }
          }
          if (dom.approved || owned) {
            selected_domains[selected_domains.length]= courses[id].domains[d];
	  }
        }


	if (selected_domains.length>0){
        // moves the current course to the top of the list
		if (courses[id].externalCourseID == state.gid) {
          	for ( var index = selected_domains.length-1; index>=0; index --) {
              		$("#sel_set_dom").createPrepend("option", {
   	           	value : JSON.stringify(selected_domains[index])
   	        	 }).create("txt", "   \u2192 " + selected_domains[index].name);
	   	}
        $("#sel_set_dom").createPrepend("option", {
        	value : "-1"
	        }).prop("disabled", true).create("txt",
	        elgg.echo("wespot_fca:course") + ": " + decodeURIComponent(courses[id].name));	
		}
   	
	  else{
          	$("#sel_set_dom").create("option", {
              	value : "-1"
           	}).prop("disabled", true).create("txt",
                	elgg.echo("wespot_fca:course") + ": " + decodeURIComponent(courses[id].name));	
	           
	           for ( var index in selected_domains) {
	              $("#sel_set_dom").create("option", {
	   	           value : JSON.stringify(selected_domains[index])
	   	         }).create("txt", "   \u2192 " + selected_domains[index].name);
	           }         
	      	}		
		  }
      //}
    }
    $("#sel_set_dom").prop("selectedIndex", "-1");
    $("#dia_set_dom").dialog("open");
    $(".item_description").empty();
  },

  display_lattice : function() {
    if (state.teacher) {
      backend
          .get_domain_learners(function(learners) {
            state.learner_lattice_learner = undefined;
            $("#btn_show_learner_lattice").remove();
            $("#sel_show_learner_lattice").remove();
            $('#ui-dialog-title-dia_vis').css("width", 'auto');

            if (learners.length > 0) {
              $(
                  '<input type="button" class="input" id="btn_show_learner_lattice" onclick="ui.show_learner_lattice_dropdown()"'
                      + ' value="'
                      + (state.learner_lattice_learner == undefined ? "Show learner Lattice"
                          : state.learner_lattice_learner)
                      + '" /> <select id="sel_show_learner_lattice"'
                      + ' onblur="ui.hide_learner_lattice_dropdown()" onchange="lattice.display_learner_lattice(this)"></select>')
                  .insertAfter("#ui-dialog-title-dia_vis");
              $('#sel_show_learner_lattice').hover(function() {
                $("#sel_show_learner_lattice").focus();
                $("#dia_vis").dialog('option', 'draggable', false);
                console.debug("no drag");
              }, function() {
                setTimeout(function() {
                  $("#dia_vis").dialog('option', 'draggable', true);
                  console.debug("drag");
                }, 300)
              });
            }

            lattice.init("#canvas_lattice", $(window).width() - 350, $(window).height() - 100, "#div_lattice_info");
            lattice.draw();



                        
            $("#vis_loading").show();
            $("#canvas_lattice").hide();
            $("#div_lattice_info").hide();
            setTimeout(function() {
              $("#vis_loading").hide();
              $("#canvas_lattice").show();
              $("#div_lattice_info").show(100);
              lattice.switch_view();
            }, 1500);
            $("#cb_latticeview").prop("checked", false);
            $("#dia_vis").dialog("option", "title",
                elgg.echo('wespot_fca:lattice:tax') + " '" + state.domain.name + "'");
            $("#dia_vis").dialog("option", "width", $("#canvas_lattice").prop("width") + 240);
            $("#dia_vis").dialog("option", "height", $("#canvas_lattice").prop("height") + 50);
            try {
              $("#dia_vis").dialog("open").dialogExtend("minimize");
            } catch (error) {
            }
            $("#dia_vis").fadeTo(0, 0);
            $("#dia_vis").fadeTo(1000, 1);
            if (state.domain.approved) {
              ui.hide_learner_lattice_dropdown();
            }
            console.debug("MIN!");
            $("#dia_vis").dialogExtend("minimize");

          });
    } else {
      $("#dia_vis").show();
      $("#dia_vis").css("width", "100%");
      $("#dia_vis").css("height", $(window).height() - 150 + "px");
      lattice.init("#canvas_lattice", $("#dia_vis").width() - 220, $("#dia_vis").height(), "#div_lattice_info");
      lattice.draw();
      $("#vis_loading").show();
      $("#canvas_lattice").hide();
      $("#div_lattice_info").hide();
      setTimeout(function() {
        $("#vis_loading").hide();
        $("#canvas_lattice").show();
        $("#div_lattice_info").show(100);
        lattice.switch_view();
      }, 1500);
      $("#cb_latticeview").prop("checked", false);

      $("#dia_vis").fadeTo(0, 0);
      $("#dia_vis").fadeTo(1000, 1);
    }
  },
  
  upload_file: function(){
    window.open(elgg.get_site_url() + "file/add/" + state.gid,"_blank","width=800,height=600,scrollbars=yes,resizable=yes");
  },

  show_lo_popup : function(id, url) {
    if (!state.teacher) {
      logic.consume_lo(id);
    }
    window.open(url, "Learning Object", "width=800,height=600,scrollbars=yes,resizable=yes");
  },

  show_learner_lattice_dropdown : function() {
    $("#btn_show_learner_lattice").hide();
    backend.get_domain_learners(function(learners) {
      if (learners.length == 0) {
        return;
      }
      console.debug(learners);
      var sel = $("#sel_show_learner_lattice");
      sel.empty();
      sel.create("option", {
        value : state.internalUID
      }).create("txt", "Default");
      for ( var i in learners) {
        sel.create("option", {
          value : learners[i].externalUid
        }).create("txt", learners[i].name);
      }
      sel.prop("selectedIndex", -1);
      $("#sel_show_learner_lattice").prop("value", elgg.echo("wespot_fca:lattice:show_user") + "  \u25BC");
      $("#sel_show_learner_lattice").show()
    });
  },

  hide_learner_lattice_dropdown : function() {
    if (!state.learner_lattice_learner) {
      $("#btn_show_learner_lattice").show();
      $("#btn_show_learner_lattice").prop("value", elgg.echo("wespot_fca:lattice:show_user") + "  \u25BC");
      $("#sel_show_learner_lattice").hide();
    }
  },

  resize : function() {
    // TODO this is a hack, but since elgg ships an ancient version on jQuery we
    // are limited to hacks
    if (state.teacher) {
      var d_state = $("#dia_vis").data("dialog-state");

      lattice.resize($(window).width() - 350, $(window).height() - 100);
      $("#dia_vis").dialog("option", "width", $("#canvas_lattice").prop("width") + 240);
      $("#dia_vis").dialog("option", "height", $("#canvas_lattice").prop("height") + 50);
      if (d_state == "minimized") {
        $("#dia_vis").dialogExtend("minimize");
      }
    } else {
      $("#dia_vis").css("height", $(window).height() - 100 + "px");
      lattice.resize($("#dia_vis").width() - 220, $("#dia_vis").height());
    }
  },

  display_ie_warning : function(flag) {
    var y;

    flag ? y = 180 : y = 200;
    var text = "<hr/>Consider switching to <a class=\"iewarn\" target=\"_blank\" "
        + "href=\"https://www.google.com/chrome\">Google Chrome</a> or "
        + "<a class=\"iewarn\" target=\"_blank\" href=\"http://www.mozilla.org/firefox/\">Mozilla Firefox</a>";
    if (!flag)
      text = "Warning, your Browser is not fully supported! You will experience bad performance and display problems!"
          + text;
    else
      text = "Warning, your Browser is not fully supported! This may lead to rendering glitches, layout problems and other minor annoyances"
          + text;
    $("#dia_vis").parent().create("div", {
      id : "dia_ie_warn",
      title : "Compatibility Warning"
    }).append(text);
    $("#dia_ie_warn").dialog({
      autoOpen : true,
      height : y,
      modal : true,
      width : 350
    });
  }

};

var offset = { x: 0, y: 0 };

interact("#div_lattice_info")
  .resizable({
    edges: { left: true, right: false, bottom: true, top: false }
  })
  .on('resizemove', function (event) {
    var target = event.target;

    // update the element's style
    target.style.width  = (event.rect.width )-7.5 +'px';
    target.style.height = event.rect.height +'px';

    // translate when resizing from top or left edges
    offset.x += event.deltaRect.left;
    offset.y += event.deltaRect.top;
    
    console.log("left "+event.deltaRect.left);
    console.log("top "+event.deltaRect.top);
    
    target.style.marginLeft = (217.5 - event.rect.width) + 'px';
    
    /*target.style.transform = ('translate('
                              + offset.x + 'px,'
                              + offset.y + 'px)');*/

  //  target.textContent = event.rect.width + '×' + event.rect.height;
  });


