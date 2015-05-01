lattice = {
  offset : 0.05,
  renderer : undefined,
  sys : undefined,
  done : false,
  info : {},
  canvas : {},
  initial : true,
  latticeview : false,
  taxonomy_edges : [],
  node_top : {},
  node_bot : {},
  lo_shown : false,
  suspended : false,
  color_bg_active : "rgba(255,255,255,1)",
  color_bg_inactive : "rgba(230,230,230,0.5)",

  init_canvas : function(canvas, width, height, info) {
    $(canvas).prop("width", width);
    $(canvas).prop("height", height);
    if (state.teacher)
      $(info).height($(canvas).prop("height"));
    else {
      $(info).css({
        "height" : $(canvas).prop("height") - 5 + "px",
        "marginBottom" : "5px"
      });
    }
    lattice.canvas = $(canvas).get(0);
    lattice.info = $(info).get(0);
  },

  resize : function(width, height) {
    if (lattice.sys) {
      $(lattice.canvas).prop("width", width);
      $(lattice.canvas).prop("height", height);
      if (state.teacher)
        $(lattice.info).height($(lattice.canvas).prop("height"));
      else {
        $(lattice.info).css({
          "height" : $(lattice.canvas).prop("height") - 5 + "px",
          "marginBottom" : "5px"
        });
      }
      lattice.sys.screenSize(lattice.canvas.width, lattice.canvas.height);
      lattice.renderer.redraw();
    }
  },

  init_renderer : function() {
    var particleSystem;

    lattice.renderer = {
      init : function(system) {
        particleSystem = system;
        particleSystem.screenSize(lattice.canvas.width, lattice.canvas.height);
        particleSystem.screenPadding(50);
        lattice.renderer.initMouseHandling();
      },

      redraw : function() {
        var ctx = lattice.canvas.getContext("2d");
        ctx.fillStyle = "white";
        ctx.fillRect(0, 0, lattice.canvas.width, lattice.canvas.height);
        try {
          if (lattice.suspended) {

            ctx.textAlign = 'center';
            ctx.strokeStyle = lattice.color_bg_active;
            ctx.font = "bold 10pt sans-serif";
            var x = ($(lattice.canvas).width() / 2) | 0;
            var y = ($(lattice.canvas).height() / 2) | 0;
            ctx.strokeText(elgg.echo('wespot_fca:lattice:single_concept'), x, y);

            ctx.fillStyle = "black";
            ctx.fillText(elgg.echo('wespot_fca:lattice:single_concept'), x, y);
          } else {
            particleSystem.eachEdge(lattice.paint_edge);
            particleSystem.eachNode(lattice.paint_node);
          }
        } catch (not_inited) {
        }
      },

      initMouseHandling : lattice.init_mouse
    };
  },

  init_particelsystem : function() {
    lattice.sys = arbor.ParticleSystem(1000, 1500, 0.95);
    lattice.sys.parameters({
      gravity : true,
      fps : 24,
      precision : 0
    });
    lattice.sys.renderer = lattice.renderer;
  },

  init_mouse : function() {
    var dragged = null;
    var handler = {
      clicked : function(e) {
        if (lattice.suspended)
          return;
        var pos = $(lattice.canvas).offset();
        _mouseP = arbor.Point(e.pageX - pos.left, e.pageY - pos.top);
        dragged = lattice.sys.nearest(_mouseP);
        if (lattice.latticeview || dragged.node.data.enabled) {
          if (dragged && dragged.node !== null) {
            dragged.node.fixed = true;
          }
          if (!dragged.node.data.active || dragged.node.data.selected || lattice.initial) {
            lattice.initial = false;
            lattice.sys.eachNode(function(_n, ___pt) {
              _n.data.active = false;
              _n.data.objActive = false;
              _n.data.attrActive = false;
              _n.data.selected = false;
            });
            lattice.sys.eachEdge(function(_e, _pt1, _pt2) {
              _e.data.active = false;
            });
            if (((dragged.node == lattice.node_bot) || (dragged.node == lattice.node_top)) && lattice.latticeview)
              lattice.enable_all();
            else
              lattice.enable_upper(lattice.sys.getEdgesFrom(dragged.node));

            lattice.enable_lower(lattice.sys.getEdgesTo(dragged.node));
            dragged.node.data.objActive = true;
            dragged.node.data.attrActive = true;
            $("#btn_taxonomy_selectall").show();

          } else {
            lattice.sys.eachNode(function(_n, ___pt) {
              _n.data.selected = false;
            });
          }
          dragged.node.data.active = true;
          dragged.node.data.selected = true;
          for ( var c in state.domain.formalContext.concepts) {
            if (state.domain.formalContext.concepts[c].id == dragged.node.data.conceptid) {
              lattice.update_info(c, sendLogData=true);
            }
          }

          $(lattice.canvas).bind('mousemove', handler.dragged);
          $(window).bind('mouseup', handler.dropped);
          return false;
        }
      },
      dragged : function(e) {
        if (lattice.suspended)
          return;
        var pos = $(lattice.canvas).offset();
        var s = arbor.Point(e.pageX - pos.left, e.pageY - pos.top);
        if (dragged && dragged.node !== null) {
          var p = lattice.sys.fromScreen(s);
          if (!dragged.node.data.fixed)
            dragged.node.p = p;
        }
        return false;
      },
      dropped : function(e) {
        if (lattice.suspended)
          return;
        if (dragged === null || dragged.node === undefined)
          return;

        if (dragged.node !== null && !dragged.node.data.fixed) {
          dragged.node.fixed = false;
        }
        dragged = null;
        $(lattice.canvas).unbind('mousemove', handler.dragged);
        $(window).unbind('mouseup', handler.dropped);
        _mouseP = null;
        return false;
      }
    };
    $(lattice.canvas).mousedown(handler.clicked);
  },

  init : function(sel_canvas, width, height, sel_info) {
    lattice.done = false;
    lattice.initial = true;
    $(sel_info).empty();
    $(sel_info).append(elgg.echo('wespot_fca:lattice:select_node'));

    lattice.init_canvas(sel_canvas, width, height, sel_info);
    if (lattice.renderer == undefined)
      lattice.init_renderer();
    if (lattice.sys == undefined)
      lattice.init_particelsystem();
    else {
      lattice.sys.screenSize(lattice.canvas.width, lattice.canvas.height);
      lattice.sys.parameters({
        repulsion : 1000,
        stiffness : 1500,
        friction : 0.95,
        precision : 0
      });
    }
    lattice.latticeview = true;
    lattice.taxonomy_edges = [];
  },

  reset_props : function() {
    $(lattice.info).empty();
    $(lattice.info).append(elgg.echo('wespot_fca:lattice:select_node'));
    lattice.done = false;
    lattice.initial = true;
    lattice.taxonomy_edges = [];
    lattice.sys.parameters({
      repulsion : 1000,
      stiffness : 1500,
      friction : 0.95,
      precision : 0
    });
  },

  paint_node : function(node, pt) {

    if (lattice.latticeview || node.data.enabled) {

      var ctx = lattice.canvas.getContext("2d");
      ctx.lineWidth = 2;
      var w = 10;
      if (node.data.obj == "" && node.data.attr == "")
        w = 2;
      if (node.data.selected) {
        ctx.closePath();
        ctx.fillStyle = "rgba(0,60,180,1)";
        ctx.beginPath();
        ctx.arc((pt.x), (pt.y), w + 5, 0, 2 * Math.PI, true);
        ctx.fill();
        ctx.closePath();
      }
      ctx.textAlign = "center";
      if (node.data.active)
        ctx.strokeStyle = lattice.color_bg_active;
      else
        ctx.strokeStyle = lattice.color_bg_inactive;
      ctx.beginPath();
      ctx.arc((pt.x), (pt.y), w, 0, 2 * Math.PI, true);

      ctx.stroke();
      if (node.data.active)
        ctx.fillStyle = lattice.color_bg_active;
      else
        ctx.fillStyle = lattice.color_bg_inactive;
      ctx.fill();
      ctx.closePath();
      ctx.beginPath();

      ctx.arc((pt.x), (pt.y), w - 1, 0, 2 * Math.PI, true);
      if (node.data.active)
        if (w == 2)
          ctx.strokeStyle = "rgba(50,50,50,1)";
        else
          ctx.strokeStyle = "rgba(0,0,0,1)";
      else {
        if (w == 2)
          ctx.strokeStyle = "rgba(50,50,50,0.5)";
        else
          ctx.strokeStyle = "rgba(0,0,0,0.5)";
      }
      ctx.stroke();

      var col = "rgba(255,255,255,0.5)";
      if (node.data.active)
        col = "rgba(255,255,255,1)";
      if (node.data.isAttributeConcept) {
        if (node.data.active) {
          col = "rgba(" + node.data.color_attr + ",1)";
        } else {
          col = "rgba(" + node.data.color_attr + ",0.5)";
        }
      }

      ctx.closePath();
      ctx.beginPath();
      ctx.fillStyle = col;
      ctx.arc((pt.x), (pt.y - 0.5), w - 2, 0, Math.PI, true);
      ctx.fill();
      ctx.closePath();

      col = "rgba(255,255,255,0.5)";
      if (node.data.active)
        col = "rgba(255,255,255,1)";
      if (node.data.isObjectConcept) {
        if (node.data.active) {
          col = "rgba(" + node.data.color_obj + ",1)";

        } else {
          col = "rgba(" + node.data.color_obj + ",0.5)";

        }
      }
      ctx.closePath();
      ctx.beginPath();
      ctx.fillStyle = col;
      ctx.arc((pt.x), (pt.y + 0.5), w - 2, 1 * Math.PI, 2 * Math.PI, true);
      ctx.fill();
      ctx.closePath();

      if (node.data.objActive) {
        ctx.strokeStyle = lattice.color_bg_active;
        ctx.font = "bold 10pt sans-serif";
        ctx.strokeText(node.data.obj, pt.x, pt.y + 10 + 1.5 * w);
        ctx.fillStyle = "rgba(0,0,0,1)";

        ctx.fillStyle = "black";
        ctx.fillText(node.data.obj, pt.x, pt.y + 10 + 1.5 * w);
      }
      if (node.data.attrActive) {
        ctx.strokeStyle = lattice.color_bg_active;
        ctx.font = "bold 10pt sans-serif";
        ctx.strokeText(node.data.attr, pt.x, pt.y - 1.5 * w - 3);
        ctx.fillStyle = "rgba(0,0,0,1)";
        ctx.fillStyle = "black";
        ctx.fillText(node.data.attr, pt.x, pt.y - 1.5 * w - 3);
      }
      if (w != 2) {
        ctx.beginPath();
        if (node.data.active)
          ctx.strokeStyle = "rgba(0,0,0,1)";
        else
          ctx.strokeStyle = "rgba(0,0,0,0.5)";
        ctx.moveTo(pt.x - w + 2, pt.y);
        ctx.lineTo(pt.x + w - 2, pt.y);
        ctx.stroke();
        ctx.closePath();
      }
    }
  },

  paint_edge : function(edge, pt1, pt2) {
    if ((lattice.latticeview && !edge.data.taxonomy) || (edge.data.enabled)) {
      var ctx = lattice.canvas.getContext("2d");
      var offset = 15;
      if (edge.target.data.obj == "" && edge.target.data.attr == "")
        offset = 5;
      var arrow = 10 + offset;
      if (edge.data.active) {
        ctx.fillStyle = lattice.color_bg_active;
        ctx.strokeStyle = lattice.color_bg_active;
      } else {
        ctx.fillStyle = lattice.color_bg_inactive;
        ctx.strokeStyle = lattice.color_bg_inactive;
      }
      ctx.lineWidth = 4;
      ctx.beginPath();
      var angle = Math.atan2(pt2.y - pt1.y, pt2.x - pt1.x);
      ctx.moveTo(pt1.x, pt1.y);
      ctx.lineTo(pt2.x - arrow * Math.cos(angle), pt2.y - arrow * Math.sin(angle));
      ctx.stroke();
      ctx.moveTo(pt2.x - offset * Math.cos(angle), pt2.y - offset * Math.sin(angle));
      ctx.beginPath();
      ctx.lineTo(pt2.x - arrow * Math.cos(angle - Math.PI / 20), pt2.y - arrow * Math.sin(angle - Math.PI / 20));
      ctx.lineTo(pt2.x - arrow * Math.cos(angle + Math.PI / 20), pt2.y - arrow * Math.sin(angle + Math.PI / 20));
      ctx.lineTo(pt2.x - offset * Math.cos(angle), pt2.y - offset * Math.sin(angle));
      ctx.closePath();
      ctx.fill();
      ctx.stroke();
      if (edge.data.active) {
        ctx.fillStyle = "rgba(0,0,0, 0.8)";
        ctx.strokeStyle = "rgba(0,0,0, 0.8)";
      } else {
        ctx.fillStyle = "rgba(200,200,200, 0.2)";
        ctx.strokeStyle = "rgba(200,200,200, 0.2)";
      }
      ctx.lineWidth = 1;
      ctx.beginPath();
      ctx.moveTo(pt1.x, pt1.y);

      ctx.lineTo(pt2.x - (arrow) * Math.cos(angle), pt2.y - (arrow) * Math.sin(angle));
      ctx.stroke();
      ctx.moveTo(pt2.x - offset * Math.cos(angle), pt2.y - offset * Math.sin(angle));
      ctx.beginPath();
      ctx.lineTo(pt2.x - arrow * Math.cos(angle - Math.PI / 20), pt2.y - arrow * Math.sin(angle - Math.PI / 20));
      ctx.lineTo(pt2.x - arrow * Math.cos(angle + Math.PI / 20), pt2.y - arrow * Math.sin(angle + Math.PI / 20));
      ctx.lineTo(pt2.x - offset * Math.cos(angle), pt2.y - offset * Math.sin(angle));
      ctx.closePath();
      ctx.fill();

      if (edge.source.p.y < edge.target.p.y) {
        if (lattice.done)
          return;
        edge.target.p.y = -lattice.offset;
      }
    }
  },

  enable_all : function() {
    lattice.sys.eachEdge(function(edge, pt) {
      edge.data.active = true;
      edge.source.data.active = true;
      edge.source.data.objActive = true;
      edge.target.data.attrActive = true;
    });
    lattice.sys.renderer.redraw();
    // $("#btn_taxonomy_selectall").hide();
  },

  enable_upper : function(edges) {
    for ( var n in edges) {
      lattice.enable_upper(lattice.sys.getEdgesFrom(edges[n].target));
    }
    for ( var n in edges) {
      edges[n].data.active = true;
      edges[n].target.data.active = true;
      edges[n].target.data.attrActive = true;
    }
  },

  enable_lower : function(edges) {
    for ( var n in edges) {
      lattice.enable_lower(lattice.sys.getEdgesTo(edges[n].source));
    }
    for ( var n in edges) {
      edges[n].data.active = true;
      edges[n].source.data.active = true;
      edges[n].source.data.objActive = true;
    }
  },

  switch_view : function() {
    lattice.latticeview = !lattice.latticeview;

    if (lattice.latticeview) {
      lattice.sys.eachEdge(function(edge, pt1, pt2) {
        if (edge.data.taxonomy)
          lattice.sys.pruneEdge(edge);
      });
      $("#dia_vis").dialog("option", "title", elgg.echo('wespot_fca:lattice:lattice') + " '" + state.domain.name + "'");

      // $("#btn_taxonomy_selectall").hide();
      console.debug("hiding");
    } else {
      $("#dia_vis").dialog("option", "title", elgg.echo('wespot_fca:lattice:tax') + " '" + state.domain.name + "'");

    }

    if (!lattice.latticeview) {
      for ( var e in lattice.taxonomy_edges) {
        var pre = lattice.sys.getNode(lattice.taxonomy_edges[e].source);
        var post = lattice.sys.getNode(lattice.taxonomy_edges[e].target);
        lattice.sys.addEdge(pre, post, {
          "active" : true,
          "enabled" : pre.data.enabled && post.data.enabled,
          "taxonomy" : true

        });
      }
      $("#btn_taxonomy_selectall").show();
      console.debug("showing");
    }

    lattice.renderer.redraw();
  },

  set_item : function(cIndex, type, id) {
    state.conceptId = cIndex;
    ui.set_item(-1, type, id);
  },

  update_info : function(c, sendLogData) {
    console.debug("Update info ");
    console.debug(c);
//    $("#span_taxonomy_selectall").animate({
//      paddingRight : "5px"
//    }, 300);

    $(lattice.info).empty();
    console.trace();
    console.debug(c);
    lattice.lo_shown = false;

    
    
    $(lattice.info).animate({
      marginLeft : "0px",
      width : "210px"
    },300);

    $("#btn_show_lo").prop("src", state.basedir + "img/left_s.svg");

    var concept = state.domain.formalContext.concepts[c];

    if (!state.teacher && sendLogData) {
      logic.log("click on concept", {
        conceptID : concept.id
      });
    }

    var learningObjects = {};
    for ( var os in concept.objects) {
      var o = JSON.parse(os);
      o = state.backend_objects[o.id];
      for ( var lo in o.learningObjects) {
        learningObjects[o.learningObjects[lo].id] = o.learningObjects[lo];
        learningObjects[o.learningObjects[lo].id].byLearner = false;
      }
      for ( var lo in o.learningObjectsByLearners) {
        learningObjects[o.learningObjectsByLearners[lo].id] = o.learningObjectsByLearners[lo];
        learningObjects[o.learningObjectsByLearners[lo].id].byLearner = true;
      }
    }
    for ( var os in concept.attributes) {
      var o = JSON.parse(os);
      o = state.backend_attributes[o.id];
      for ( var lo in o.learningObjects) {
        learningObjects[o.learningObjects[lo].id] = o.learningObjects[lo];
        learningObjects[o.learningObjects[lo].id].byLearner = false;
      }
      for ( var lo in o.learningObjectsByLearners) {
        learningObjects[o.learningObjectsByLearners[lo].id] = o.learningObjectsByLearners[lo];
        learningObjects[o.learningObjectsByLearners[lo].id].byLearner = true;
      }
    }

    var num_lo = Object.keys(learningObjects).length;

    var table = $(lattice.info).create("table", {
      style : "background-color: inherit; vertical-align: bottom; table-layout: auto"
    });
    var tr = table.create("tr", {
      style : "background-color: inherit"
    });

    // Name txt
    tr.create("td", {
      style : "background-color: inherit; width: 174px"
    }).append(elgg.echo(state.domain.global ? 'wespot_fca:intent_ibl' : 'wespot_fca:intent') + ":");

    // Edit icon
    var t_edit = tr.create("td", {
      style : "background-color: inherit"
    });
    if (state.teacher && !(state.domain.approved)) {
      t_edit.create("input", {
        id : "btn_concept_edit",
        "class" : "input",
        style : "border-width:0px; padding-left: 3px",
        type : "image",
        src : state.basedir + "img/edit.svg",
        width : "16px",
        height : "16px",
        onclick : "lattice.enable_editing()"
      });
    }

    var rowspan;
    // Object.keys(concept.objects).length > 0 ? rowspan = "6" : rowspan = "7";
    rowspan = "6";
    // learning object names
    for ( var lo in learningObjects) {
      var isClicked = false
      if (concept.clickedLearningObjects != undefined) {
        for (i in concept.clickedLearningObjects) {
          if (learningObjects[lo].id == concept.clickedLearningObjects[i]) {
            isClicked = true;
            break;
          }
        }
      }
      var class_lo_button = "input lattice_lo col";
      if (isClicked)
        class_lo_button += " btn_lo_clicked";
      if (learningObjects[lo].byLearner)
        class_lo_button += " learner_lo";
      tr.create("td", {
        rowspan : rowspan,
        class : "td_spacer, td_lo"
      }).create("input", {
        id : "btn_lo_" + learningObjects[lo].id,
        type : "button",
        class : class_lo_button,
        style : "margin-left: -61px; margin-right: -61px; text-overflow: ellipsis; overflow: hidden;",
        value : learningObjects[lo].name
      }).data("url", learningObjects[lo].data)

      .click(function() {
        var loID = parseInt(this.id.split("_")[2]);
        ui.show_lo_popup(loID, $(this).data("url"));
        console.debug(this.id);
      }).hover(function() {
        $(this).addClass("lattice_lo_hover");
      }, function() {
        $(this).removeClass("lattice_lo_hover");
      });
    }

    // spacer
    tr.create("td", {
      "class" : "tr_spacer"
    });

    //
    //
    // next line
    tr = table.create("tr", {
      style : "background-color: inherit"
    });

    // input
    var t_name = tr.create("td", {
      style : "background-color: inherit",
      colspan : "2"
    });
    var input_name = t_name.create("input", {
      id : "input_concept_name",
      type : "text",
      value : concept.description
    }).prop("disabled", true);

    // spacer
    tr.create("td", {
      "class" : "tr_spacer"
    });

    //
    //
    // next line
    tr = table.create("tr", {
      style : "background-color: inherit"
    });

    // Description txt
    tr.create("td", {
      style : "background-color: inherit",
      colspan : "2"
    }).append(elgg.echo(state.domain.global ? 'wespot_fca:extent_ibl' : 'wespot_fca:extent') + ":");

    // spacer
    tr.create("td", {
      "class" : "tr_spacer"
    });

    //
    //
    // next line
    tr = table.create("tr", {
      style : "background-color: inherit"
    });

    // input
    var t_descr = tr.create("td", {
      style : "background-color: inherit",
      colspan : "2"
    });
    t_descr.create("input", {
      id : "input_concept_description",
      type : "text",
      value : concept.name
    }).prop("disabled", true);

    // spacer
    tr.create("td", {
      "class" : "tr_spacer"
    });

    //
    //
    // next line
    tr = table.create("tr", {
      style : "background-color: inherit"
    });

    // vertical space
    tr.create("td", {
      style : "background-color: inherit: height: 30px",
      colspan : "2"
    });

    // spacer
    tr.create("td", {
      "class" : "tr_spacer"
    });

    //
    //
    // next line
    tr = table.create("tr", {
      style : "background-color: inherit"
    });

    // Attributes txt
    tr.create("td", {
      style : "background-color: inherit",
      colspan : "2"
    }).append(
        Object.keys(concept.attributes).length > 0 ? elgg.echo(state.domain.global ? 'wespot_fca:attrs_ibl'
            : 'wespot_fca:attrs')
            + ":" : "(No Attributes)");

    var btn_expand = tr.create("td", {
      rowspan : Object.keys(concept.objects).length + Object.keys(concept.attributes).length + 2,
      "class" : "tr_spacer",
      style : "vertical-align:middle"
    }).create("input", {
      type : "image",
      id : "btn_show_lo",
      class : "input",
      style : "border: none; height: 30px; width: 16px; margin-left: 0px; margin-right: 0px;",
      height : "30px",
      width : "16px",
      src : state.basedir + "img/right_s.svg"
    }).click(function() {
      if (!lattice.lo_shown) {
        $(".td_lo").show();
        $("#btn_lo_show").prop("disabled", true);
        $(".lattice_o_a").css("white-space", "nowrap");
        setTimeout(function() {
          $(".lattice_o_a").css("white-space", "normal");

          $("#btn_lo_show").prop("disabled", false);
        }, 290);
        t_edit.css("padding-right", "3px");
        t_name.css("padding-right", "3px");
        t_descr.css("padding-right", "3px");
        $("#btn_show_lo").prop("src", state.basedir + "img/left_s.svg");
        $(lattice.info).animate({
          marginLeft : "-" + num_lo * 28 + "px",
          width : "+=" + num_lo * 28 + "px"
        }, 300);
//        $("#span_taxonomy_selectall").animate({
//          paddingRight : "" + (5 + (num_lo * 28)) + "px"
//        }, 300);
      } else {
        $(".td_lo").hide();
        $("#btn_lo_show").prop("disabled", false);
        $(".lattice_o_a").css("white-space", "normal");
        t_edit.css("padding-right", "0px");
        t_name.css("padding-right", "0px");
        t_descr.css("padding-right", "0px");
        $("#btn_show_lo").prop("src", state.basedir + "img/right_s.svg");
        $(lattice.info).animate({
          marginLeft :"0px",
          width : "210px"
        }, 300, function() {
          $("#btn_lo_show").prop("disabled", false);
        });
//        $("#span_taxonomy_selectall").animate({
//          paddingRight : "5px"
//        }, 300);
      }
      lattice.lo_shown = !lattice.lo_shown;
    });

    if (Object.keys(concept.objects).length > 0)
      for ( var lo in learningObjects) {
        tr.create("td", {
          class : "td_spacer, td_lo"
        });
      }

    tr.create("td", {
      "class" : "tr_spacer"
    });

    //
    //
    // next line
    if (Object.keys(concept.attributes).length > 0)
      tr = table.create("tr", {
        style : "background-color: inherit"
      });

    len = 0;
    for ( var os in concept.attributes) {
      var o = JSON.parse(os);
      var css = "vertical-align: middle; background-color: white; border-left:1px solid black; border-right:1px solid black;";
      if ((state.domain.approved || !state.teacher) && !state.domain.global)
        css += "; cursor: pointer;";
      if (len == 0)
        css += "; border-top: 1px solid black";
      if (len == Object.keys(concept.attributes).length - 1)
        css += "; border-bottom: 1px solid black";
      var click_cb = (((state.domain.approved || !state.teacher) && !state.domain.global) ? "lattice.set_item(" + c
          + ", " + entity_types.attribute + ", " + o.id + ")" : "");
      tr.create("td", {
        id : "info_obj_" + o.id,
        colspan : "2",
        class : "lattice_o_a",
        title : "" + o.description,
        onclick : click_cb,
        style : css
      }).create("txt", " \u26ab " + o.name);
      console.debug(concept.id);
      for ( var lo in learningObjects) {
        var tmp = tr.create("td", {
          class : "td_spacer, td_lo",
          style : "vertical-align:middle; border: 1px solid black; text-align: center; background-color:white"
        });

        for ( var ol in state.backend_attributes[(o.id)].learningObjects) {
          if (state.backend_attributes[(o.id)].learningObjects[ol].id == lo) {
            tmp.create("txt", "x");
            var av = $("#btn_lo_" + state.backend_attributes[(o.id)].learningObjects[ol].id);
            av[o.id] = 1;
            $("#btn_lo_" + state.backend_attributes[(o.id)].learningObjects[ol].id);
          }
        }
        for ( var ol in state.backend_attributes[(o.id)].learningObjectsByLearners) {
          if (state.backend_attributes[(o.id)].learningObjectsByLearners[ol].id == lo) {
            tmp.create("txt", "x");
            var av = $("#btn_lo_" + state.backend_attributes[(o.id)].learningObjectsByLearners[ol].id);
            av[o.id] = 1;
            $("#btn_lo_" + state.backend_attributes[(o.id)].learningObjectsByLearners[ol].id);
          }
        }
      }
      if (len < Object.keys(concept.attributes).length - 1) {
        tr = table.create("tr", {
          style : "background-color: inherit"
        });
      }
      ++len;
    }

    var tr = $(lattice.info).create("table", {
      style : "background-color: inherit"
    }).create("tr", {
      style : "background-color: inherit"
    });

    tr.create("td", {
      style : "width:20px; background-color: inherit"
    }).create("input", {
      id : "cb_concept_taxonomy",
      type : "checkbox"
    }).prop("checked", concept.partOfTaxonomy).prop("disabled", true);
    tr.create("td", {
      style : "background-color: inherit"
    }).append(elgg.echo('wespot_fca:lattice:part_of'));
    $(lattice.info).create("input", {
      id : "btn_concept_save",
      type : "button",
      "class" : "input",
      value : elgg.echo('wespot_fca:save'),
      onclick : "lattice.update_concept(" + c + ")"
    }).hide();

    //
    //
    // next line
    tr = table.create("tr", {
      style : "background-color: inherit"
    });

    // Objects txt
    tr.create("td", {
      style : "background-color: inherit",
      colspan : "2"
    }).append(
        Object.keys(concept.objects).length > 0 ? elgg.echo(state.domain.global ? 'wespot_fca:objs_ibl'
            : 'wespot_fca:objs')
            + ":" : "(No Objects)");

    if (Object.keys(learningObjects).length == 0)
      btn_expand.hide();
    //
    //
    // next line
    var len = 0;
    if (Object.keys(concept.objects).length > 0)
      tr = table.create("tr", {
        style : "background-color: inherit"
      });
    for ( var os in concept.objects) {
      var o = JSON.parse(os);
      var css = "vertical-align: middle; background-color: white; border-left:1px solid black; border-right:1px solid black;";
      if (state.domain.approved || !state.teacher)
        css += "; cursor: pointer;";
      if (len == 0)
        css += "; border-top: 1px solid black";
      if (len == Object.keys(concept.objects).length - 1)
        css += "; border-bottom: 1px solid black";
      var click_cb = ((state.domain.approved || !state.teacher) ? "lattice.set_item(" + c + ", " + entity_types.object
          + ", " + o.id + ")" : "");
      tr.create("td", {
        id : "info_obj_" + o.id,
        colspan : "2",
        title : "" + o.description,
        onclick : click_cb,
        class : "lattice_o_a",
        style : css
      }).create("txt", " \u26ab " + o.name);
      for ( var lo in learningObjects) {
        var tmp = tr.create("td", {
          class : "td_spacer, td_lo",
          style : "vertical-align: middle; border: 1px solid black; text-align: center; background-color: white"
        });

        for ( var ol in state.backend_objects[(o.id)].learningObjects) {
          if (state.backend_objects[(o.id)].learningObjects[ol].id == lo) {
            tmp.create("txt", "x");
            var ov = $("#btn_lo_" + state.backend_objects[(o.id)].learningObjects[ol].id);
            ov[o.id] = 1;
            $("#btn_lo_" + state.backend_objects[(o.id)].learningObjects[ol].id);
          }
        }
        for ( var ol in state.backend_objects[(o.id)].learningObjectsByLearners) {
          if (state.backend_objects[(o.id)].learningObjectsByLearners[ol].id == lo) {
            tmp.create("txt", "x");
            var ov = $("#btn_lo_" + state.backend_objects[(o.id)].learningObjectsByLearners[ol].id);
            ov[o.id] = 1;
            $("#btn_lo_" + state.backend_objects[(o.id)].learningObjectsByLearners[ol].id);
          }
        }

      }
      if (len < Object.keys(concept.objects).length - 1) {
        tr = table.create("tr", {
          style : "background-color: inherit"
        });
      }
      ++len;
    }

    $(".td_lo").hide();
  },

  enable_editing : function() {
    $("#btn_concept_edit").prop("disabled", true);
    $("#btn_concept_edit").hide();
    $("#input_concept_name").prop("disabled", false);
    $("#input_concept_description").prop("disabled", false);
    $("#cb_concept_taxonomy").prop("disabled", false);
    $("#btn_concept_save").show();
  },
  disable_editing : function() {
    $("#btn_concept_edit").prop("disabled", false);
    $("#btn_concept_edit").show();
    $("#input_concept_name").prop("disabled", true);
    $("#input_concept_description").prop("disabled", true);
    $("#cb_concept_taxonomy").prop("disabled", true);
    $("#btn_concept_save").hide();
    lattice.renderer.redraw();
  },

  update_concept : function(c) {

    var concept = state.domain.formalContext.concepts[c];
    var partOfTaxonomy = concept.partOfTaxonomy;
    concept.description = $("#input_concept_name").get(0).value;
    concept.name = $("#input_concept_description").get(0).value;
    concept.partOfTaxonomy = $("#cb_concept_taxonomy").prop("checked");
    concept.index = c;
    concept.domainId = state.domain.id;
    concept.objects = {};
    concept.attributes = {};
    concept.successors = [];
    concept.taxonomySuccessors = [];

    lattice.sys.getNode(concept.id).data.obj = concept.name;
    lattice.sys.getNode(concept.id).data.attr = concept.description;
    backend.update_concept(JSON.stringify(concept), function(formalContext) {
      state.domain.formalContext = formalContext;
      if (concept.partOfTaxonomy != partOfTaxonomy) {
        lattice.update_vis(state.domain);
      } else
        lattice.disable_editing();
    });
    lattice.renderer.redraw();
  },

  update_valuation : function(obj) {
    state.domain.formalContext.bottom.clickedLearningObjects = obj[(state.domain.formalContext.bottom.id.toString())].clickedLearningObjects;
    state.domain.formalContext.bottom.valuations = obj[(state.domain.formalContext.bottom.id.toString())].valuations;
    for ( var c in state.domain.formalContext.concepts) {
      var concept = state.domain.formalContext.concepts[c];
      concept.clickedLearningObjects = obj[concept.id.toString()].clickedLearningObjects;
      concept.clickedLearningObjects = obj[concept.id.toString()].clickedLearningObjects;
      var valuations = obj[concept.id.toString()].valuations;
      concept.valuations = valuations
      var node = lattice.sys.getNode(concept.id);
      node.data.color_obj = lattice.calc_color(valuations[0]);
      node.data.color_attr = lattice.calc_color(valuations[1]);
    }
    state.domain.formalContext.top.clickedLearningObjects = obj[(state.domain.formalContext.top.id.toString())].clickedLearningObjects;
    state.domain.formalContext.top.valuations = obj[state.domain.formalContext.top.id.toString()].valuations;
    lattice.renderer.redraw();
  },

  display_learner_lattice : function(select) {
    if (select.selectedIndex > 0) {
      var learnerId = select.options[select.selectedIndex].value;
      console.debug(learnerId);
      backend.get_learner_lattice(learnerId, function(obj) {
        state.domain.formalContext = obj;
        lattice.update_vis(state.domain);
        lattice.renderer.redraw();
        state.learner_lattice_learner = $(select.options[select.selectedIndex]).text();
        ui.hide_learner_lattice_dropdown();
      });
    } else {
      state.domain.formalContext = state.teacher_lattice;
      lattice.update_vis(state.domain);
      lattice.renderer.redraw();
      state.learner_lattice_learner = "";
      ui.hide_learner_lattice_dropdown();
    }
  },

  update_vis : function(domain) {
    state.domain = domain;
    lattice.reset_props();
    lattice.draw();
    $("#vis_loading").show();
    $("#canvas_lattice").hide();
    $("#div_lattice_info").hide();
    setTimeout(function() {
      $("#vis_loading").hide();
      $("#canvas_lattice").show();
      $("#div_lattice_info").show(100);
    }, 1500);
    lattice.disable_editing();
  },

  create_node : function(concept, mass, y, x, fixed) {
    var sys = lattice.sys;
    var data = {
      "shape" : "dot",
      "isObjectConcept" : concept.objectConcept,
      "isAttributeConcept" : concept.attributeConcept,
      "mass" : mass ? mass : 1,
      "y" : y,
      "conceptid" : concept.id,
      "active" : true,
      "fixed" : fixed ? fixed : false,
      "enabled" : true
    };
    if (x) {
      data.x = x;
    }
    var pre = sys.addNode(concept.id, data);
    return pre;
  },

  draw_node : function(concept, y) {
    var sys = lattice.sys;
    var pre;
    if (sys.getNode(concept.id))
      pre = sys.getNode(concept.id);
    else
      pre = lattice.create_node(concept, 1, y);
    pre.data.isObjectConcept = concept.objectConcept;
    if (concept.valuations.length) {
      pre.data.color_obj = lattice.calc_color(concept.valuations[0]);
      pre.data.color_attr = lattice.calc_color(concept.valuations[1]);
    }
    pre.data.enabled = concept.partOfTaxonomy;
    pre.data.obj = concept.name;
    pre.data.attr = concept.description;
    pre.data.uniqueAttributes = concept.uniqueAttributes;

  },

  draw_edge : function(concept, y) {
    var arr = concept.successors;
    var successors = util.unique(arr.concat(concept.taxonomySuccessors));
    var sys = lattice.sys;
    var pre = sys.getNode(concept.id);
    for ( var s in successors) {
      var post = sys.getNode(successors[s].id);
      if (post) {
        var isTaxonomy = (!util.containsConcept(concept.successors, successors[s]))
            && (util.containsConcept(concept.taxonomySuccessors, successors[s]));
        post.p.y = y;
        if (!isTaxonomy) {
          sys.addEdge(pre, post, {
            "active" : true,
            "enabled" : (pre.data.enabled && post.data.enabled) && !isTaxonomy,
            "taxonomy" : isTaxonomy
          });
        } else {
          lattice.taxonomy_edges.push({
            "source" : concept.id,
            "target" : successors[s].id
          });
        }
      }
    }

  },

  calc_color : function(valuation) {
    if (valuation >= 0.95)
      return ("0,180,0");
    else if (valuation >= 0.5)
      return ("255,200,0");
    return ("255,0,0");
  },

  draw : function() {

    $(lattice.info).css({
      marginLeft : "0px",
      width : "210px"
    });
    lattice.sys.eachEdge(function(edge, pt1, pt2) {
      lattice.sys.pruneEdge(edge);
    });
    lattice.sys.eachNode(function(node, pt) {
      lattice.sys.pruneNode(node);
    });

    var concepts = state.domain.formalContext.concepts;
    var y = concepts.length > 10 ? 10 : 5;

    var first = true;
    if (concepts.length == 1) {
      lattice.suspended = true;
      lattice.update_info(0, sendLogData=true);

    } else {
      lattice.suspended = false;
      for ( var c in concepts) {

        if (first) {
          var botnode = lattice.create_node(concepts[c], 2, y, 0.5, concepts.length > 2);
          botnode.data.fixed = concepts.length > 2;

          botnode.data.color_obj = lattice.calc_color(concepts[c].valuations[0]);
          botnode.data.color_attr = lattice.calc_color(concepts[c].valuations[1]);

          lattice.node_bot = botnode;
        } else if (concepts[c].successors.length == 0) {
          var topnode = lattice.create_node(concepts[c], 2, 0, 0.5, concepts.length > 2);
          topnode.data.fixed = concepts.length > 2;

          topnode.data.color_obj = lattice.calc_color(concepts[c].valuations[0]);
          topnode.data.color_attr = lattice.calc_color(concepts[c].valuations[1]);

          lattice.node_top = topnode;
        }
        lattice.draw_node(concepts[c], y);

        y -= 0.1;
        first = false;
      }

    }

    y = concepts.length > 10 ? 10 : 5;
    for ( var c in concepts) {
      lattice.draw_edge(concepts[c], y);
      y -= 0.05;
    }
    setTimeout(function() {
      lattice.sys.parameters({
        repulsion : 400,
        precision : 0.9,
        stiffenss : 3000
      });
      lattice.done = true;

    }, 1500);

    for (var i = 0; i < 100; ++i) {
      setTimeout(function() {
        if (!lattice.done) {
          lattice.sys.parameters({
            repulsion : 1500 - i * 10
          });
        }
      }, i * 50);
    }

  }
};
