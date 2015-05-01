<?php
$basedir = $CONFIG->url . "/mod/wespot_fca/";

$options = array (
  'type' => 'object',
  'subtype' => 'file',
  
  // 'owner_guid' => get_entity ( $inquiryId )->owner_guid,
  'container_guid' => $inquiryId 
);

$files = elgg_get_entities ( $options );
$files_json = array ();
foreach ( $files as $file ) {
  $json_file = array (
    'name' => $file->title,
    'description' => $file->description,
    'data' => $CONFIG->url . 'file/view/' . $file->guid 
  );
  $files_json [] = $json_file;
}

$user_entity = elgg_get_logged_in_user_entity ();
$g = $user_entity->getGroups ( "", 999, 0 );
$grps = array ();
$owned_groups = array ();

foreach ( $g as $obj ) {
  $grp = array (
    "name" => $obj->name,
    "id" => "" . $obj->guid 
  );
  $operators= get_group_operators($obj);
  foreach($operators as $owner){
   if ($owner->getGUID()== elgg_get_logged_in_user_guid ())
     $owned_groups[]=$obj->guid ;
  }
  $grps [] = $grp;
}
//elgg_log(print_r($owned_groups), 'DEBUG');
$group = get_entity ( $_GET['gid']);
$owners = array();
$operators= get_group_operators($group);
foreach ($operators as $op){
  array_push($owners,$op->getGUID());
}
?>
<link type="text/css" href="<?php echo $basedir; ?>css/smoothness/jquery-ui.css" rel="Stylesheet" />
<link type="text/css" href="<?php echo $basedir; ?>css/fca.css" rel="Stylesheet" />

<script src="<?php echo $basedir; ?>js/jquery-create.js"></script>
<script type="text/javascript" src="<?php echo $basedir; ?>js/interact.js"></script>
<script type="text/javascript" src="<?php echo $basedir; ?>js/fcatool.js"></script>
<script type="text/javascript" src="<?php echo $basedir; ?>js/arbor/arbor.js"></script>
<script type="text/javascript" src="<?php echo $basedir; ?>js/lattice.js"></script>
<script type="text/javascript" src="<?php echo $basedir; ?>js/jquery.dialogextend.js"></script>
<script>
var owned_groups=new Array();
var first="<?php echo json_encode($owned_groups); ?>";
var str = first.slice(1, first.length-1)
//console.log("string groups: "+str);
owned_groups=str.split(',');

$(function(){
// production server
//logic.init("<?php echo $basedir; ?>","http://css-kti.tugraz.at:8080/MEDoKyService/rest/FCATool/", <?php echo json_encode($files_json); ?>, <?php echo json_encode($grps); ?>, <?php echo json_encode($owners); ?> );

// test server
logic.init("<?php echo $basedir; ?>","http://css-kmi.tugraz.at/MEDoKyService/rest/FCATool/", <?php echo json_encode($files_json); ?>, <?php echo json_encode($grps); ?>, <?php echo json_encode($owners); ?> );
//logic.init("<?php echo $basedir; ?>","http://192.168.1.1:8080/MEDoKyService/rest/FCATool/", <?php echo json_encode($files_json); ?>, <?php echo json_encode($grps); ?>, <?php echo json_encode($owners); ?> );
});
</script>
<table id="toolbar">
  <tr>
    <td class="toolbar"><input type="image" class="input" src="<?php echo $basedir; ?>img/new.svg" width="48px"
      height="48px" alt="New" title="<?php echo elgg_echo('wespot_fca:domain:new'); ?>" id="btn_new"
      onclick="window.location=window.location+'&blank=true'" /></td>
    <td class="toolbar"><input type="image" class="input" src="<?php echo $basedir; ?>img/from_existing.svg"
      width="48px" height="48px" alt="From Existing" title="<?php echo elgg_echo('wespot_fca:domain:from_existing'); ?>"
      id="btn_from_existing" onclick="ui.try_show_create_dialog()" /></td>
    <td class="toolbar"><input type="image" class="input" src="<?php echo $basedir; ?>img/open.svg" width="48px"
      height="48px" alt="Open" title="<?php echo elgg_echo('wespot_fca:domain:open'); ?>" id="btn_open"
      onclick="backend.get_domains('-1', ui.list_domains)" /></td>
    <td class="toolbar"><input type="image" class="input" src="<?php echo $basedir; ?>img/save.svg" width="48px"
      height="48px" alt="Save" title="<?php echo elgg_echo('wespot_fca:domain:save'); ?>" id="btn_save"
      onclick="ui.try_show_save_dialog()" /></td>
    <td class="toolbar"><input type="image" class="input" src="<?php echo $basedir; ?>img/excel.svg" width="48px"
      height="48px" alt="Export to CSV" title="<?php echo elgg_echo('wespot_fca:domain:csv'); ?>" id="btn_csv"
      onclick="logic.export_csv()" /></td>
    <td class="toolbar"><input type="image" class="input" src="<?php echo $basedir; ?>img/approve.svg" width="48px"
      height="48px" alt="Approve" title="<?php echo elgg_echo('wespot_fca:domain:approve'); ?>" id="btn_approve"
      onclick="$('#dia_publish_domain').dialog('open')" /></td>
    <td class="toolbar"><input type="image" class="input always_on" src="<?php echo $basedir; ?>img/share.svg"
      width="48px" height="48px" alt="Share" title="<?php echo elgg_echo('wespot_fca:domain:share'); ?>" id="btn_share"
      onclick="logic.display_share_dialog(owned_groups)"/></td>
    <td class="toolbar" style="vertical-align: middle; padding-left: 2em"><h1 id="h_domain_name"></h1></td>
    <td class="toolbar fca_help"><input type="image" class="input always_on"
      title="<?php echo elgg_echo('wespot_fca:tofile'); ?>" id="btn_to_file" src="<?php echo $basedir; ?>img/upload.svg"
      width="48px" height="48px" onclick="ui.upload_file()"> <input type="image" id="btn_help" class="input always_on"
      src="<?php echo $basedir; ?>img/help.svg" width="48px" height="48px"
      title="<?php echo elgg_echo('wespot_fca:displayhelp'); ?>" onclick="ui.display_help()"> <input type="image"
      class="input always_on" title="<?php echo elgg_echo('wespot_fca:togroup'); ?>" id="btn_to_group"
      src="<?php echo $basedir; ?>img/back_group.svg" width="48px" height="48px"></td>
  </tr>
</table>
<hr />

<div id="main_table">
  <table id="matrix_wrapper">
    <tr>
      <td>
        <!-- dummy -->
      </td>
      <td>
        <div id="matrix_attr_head">
          <table>
            <col class="td_attr_0">
            <col class="td_attr_1">
            <col class="td_attr_2">
            <col class="td_attr_3">
            <col class="col_tail">
            <tr class="col_del">
              <td class="right td_attr_0"><input type="image" src="<?php echo $basedir; ?>img/delete.svg" width="16px"
                height="16px" alt="x" title="<?php echo elgg_echo('wespot_fca:attr:rem'); ?>" id="btn_del_attr_0"
                class="input btn_del_attr" onclick="ui.rem_attribute(0)" /></td>
              <td class="right td_attr_1"><input type="image" src="<?php echo $basedir; ?>img/delete.svg" width="16px"
                height="16px" alt="x" title="<?php echo elgg_echo('wespot_fca:attr:rem'); ?>" id="btn_del_attr_1"
                class="input btn_del_attr" onclick="ui.rem_attribute(1)" /></td>
              <td class="right td_attr_2"><input type="image" src="<?php echo $basedir; ?>img/delete.svg" width="16px"
                height="16px" alt="x" title="<?php echo elgg_echo('wespot_fca:attr:rem'); ?>" id="btn_del_attr_2"
                class="input btn_del_attr" onclick="ui.rem_attribute(2)" /></td>
              <td class="right td_attr_3"><input type="image" src="<?php echo $basedir; ?>img/delete.svg" width="16px"
                height="16px" alt="x" title="<?php echo elgg_echo('wespot_fca:attr:rem'); ?>" id="btn_del_attr_3"
                class="input btn_del_attr" onclick="ui.rem_attribute(3)" /></td>
              <td class="tail"></td>
            </tr>
            <tr>
              <td class="td_attr td_attr_0"><input type="image" src="<?php echo $basedir; ?>img/left.svg"
                id="btn_move_left_0" width="16px" height="40px" alt="&lt;"
                title="<?php echo elgg_echo('wespot_fca:move_left'); ?>" class="input btn_move_left"
                onclick="ui.move_left(0)" /><input type="image" src="<?php echo $basedir; ?>img/right.svg"
                id="btn_move_right_0" width="16px" height="40px" alt="&gt;"
                title="<?php echo elgg_echo('wespot_fca:move_right'); ?>" class="input btn_move_right"
                onclick="ui.move_right(0)" /><input type="button" id="attr_0" class="input btn_attr col always_on"
                value="<?php echo elgg_echo('wespot_fca:attr:dummy'); ?> 1"
                onclick="ui.set_item(0,entity_types.attribute)" /></td>
              <td class="td_attr td_attr_1"><input type="image" src="<?php echo $basedir; ?>img/left.svg"
                id="btn_move_left_1" width="16px" height="40px" alt="&lt;"
                title="<?php echo elgg_echo('wespot_fca:move_left'); ?>" class="input btn_move_left"
                onclick="ui.move_left(1)" /><input type="image" src="<?php echo $basedir; ?>img/right.svg"
                id="btn_move_right_1" width="16px" height="40px" alt="&gt;"
                title="<?php echo elgg_echo('wespot_fca:move_right'); ?>" class="input btn_move_right"
                onclick="ui.move_right(1)" /><input type="button" id="attr_1" class="input  btn_attr col always_on"
                value="<?php echo elgg_echo('wespot_fca:attr:dummy'); ?> 2"
                onclick="ui.set_item(1,entity_types.attribute)" /></td>
              <td class="td_attr td_attr_2"><input type="image" src="<?php echo $basedir; ?>img/left.svg"
                id="btn_move_left_2" width="16px" height="40px" alt="&lt;"
                title="<?php echo elgg_echo('wespot_fca:move_left'); ?>" class="input btn_move_left"
                onclick="ui.move_left(2)" /><input type="image" src="<?php echo $basedir; ?>img/right.svg"
                id="btn_move_right_2" width="16px" height="40px" alt="&gt;"
                title="<?php echo elgg_echo('wespot_fca:move_right'); ?>" class="input btn_move_right"
                onclick="ui.move_right(2)" /><input type="button" id="attr_2" class="input  btn_attr col always_on"
                value="<?php echo elgg_echo('wespot_fca:attr:dummy'); ?> 3"
                onclick="ui.set_item(2,entity_types.attribute)" /></td>
              <td class="td_attr td_attr_3"><input type="image" src="<?php echo $basedir; ?>img/left.svg"
                id="btn_move_left_3" width="16px" height="40px" alt="&lt;"
                title="<?php echo elgg_echo('wespot_fca:move_left'); ?>" class="input btn_move_left"
                onclick="ui.move_left(3)" /><input type="image" src="<?php echo $basedir; ?>img/right.svg"
                id="btn_move_right_3" width="16px" height="40px" alt="&gt;"
                title="<?php echo elgg_echo('wespot_fca:move_right'); ?>" class="input btn_move_right"
                onclick="ui.move_right(3)" /><input type="button" id="attr_3" class="input  btn_attr col always_on"
                value="<?php echo elgg_echo('wespot_fca:attr:dummy'); ?> 4"
                onclick="ui.set_item(3,entity_types.attribute)" /></td>
              <td class="tail add_buttons" style="background-color: #fff; vertical-alignment: middle"><input
                type="image" class="input" style="border: none; padding-left: 10px;"
                src="<?php echo $basedir; ?>img/plus.svg" width="16px" height="16px" alt="+"
                title="<?php echo elgg_echo('wespot_fca:attr:add'); ?>" onclick="ui.append_attribute()" /> <!--<?php echo elgg_echo('wespot_fca:attr:add'); ?>--></td>
            </tr>
          </table>
        </div>
      </td>
    </tr>
    <tr>
      <td class="vc">
        <div id="matrix_obj_head">
          <table>
            <tr class="tr_obj_0">
              <td class="left"><input type="image" src="<?php echo $basedir; ?>img/up.svg" width="40px" height="16px"
                alt="^" title="<?php echo elgg_echo('wespot_fca:move_up'); ?>" id="btn_move_up_0"
                class="input btn_move_up" onclick="ui.move_up(0)" /> <input type="image"
                src="<?php echo $basedir; ?>img/down.svg" width="40px" height="16px" alt="v"
                title="<?php echo elgg_echo('wespot_fca:move_down'); ?>" id="btn_move_down_0"
                class="input btn_move_down" onclick="ui.move_down(0)" /> <input type="image"
                src="<?php echo $basedir; ?>img/delete.svg" width="16px" height="16px" alt="x"
                title="<?php echo elgg_echo('wespot_fca:obj:rem'); ?>" id="btn_del_obj_0" class="input btn_del_obj"
                onclick="ui.rem_object(0)" /> <input type="button" id="obj_0" class="input btn_obj always_on"
                value="<?php echo elgg_echo('wespot_fca:obj:dummy'); ?> 1" onclick="ui.set_item(0,entity_types.object)" /></td>
            </tr>
            <tr class="tr_obj_1">
              <td class="left"><input type="image" src="<?php echo $basedir; ?>img/up.svg" width="40px" height="16px"
                alt="^" title="<?php echo elgg_echo('wespot_fca:move_up'); ?>" id="btn_move_up_1"
                class="input btn_move_up" onclick="ui.move_up(1)" /> <input type="image"
                src="<?php echo $basedir; ?>img/down.svg" width="40px" height="16px" alt="v"
                title="<?php echo elgg_echo('wespot_fca:move_down'); ?>" id="btn_move_down_1"
                class="input btn_move_down" onclick="ui.move_down(1)" /> <input type="image"
                src="<?php echo $basedir; ?>img/delete.svg" width="16px" height="16px" alt="x"
                title="<?php echo elgg_echo('wespot_fca:obj:rem'); ?>" id="btn_del_obj_1" class="input btn_del_obj"
                onclick="ui.rem_object(1)" /> <input type="button" id="obj_1" class="input btn_obj always_on"
                value="<?php echo elgg_echo('wespot_fca:obj:dummy'); ?> 2" onclick="ui.set_item(1,entity_types.object)" /></td>
            </tr>
            <tr class="tr_obj_2">
              <td class="left"><input type="image" src="<?php echo $basedir; ?>img/up.svg" width="40px" height="16px"
                alt="^" title="<?php echo elgg_echo('wespot_fca:move_up'); ?>" id="btn_move_up_2"
                class="input btn_move_up" onclick="ui.move_up(2)" /> <input type="image"
                src="<?php echo $basedir; ?>img/down.svg" width="40px" height="16px" alt="v"
                title="<?php echo elgg_echo('wespot_fca:move_down'); ?>" id="btn_move_down_2"
                class="input btn_move_down" onclick="ui.move_down(2)" /> <input type="image"
                src="<?php echo $basedir; ?>img/delete.svg" width="16px" height="16px" alt="x"
                title="<?php echo elgg_echo('wespot_fca:obj:rem'); ?>" id="btn_del_obj_2" class="input btn_del_obj"
                onclick="ui.rem_object(2)" /><input type="button" id="obj_2" class="input btn_obj always_on"
                value="<?php echo elgg_echo('wespot_fca:obj:dummy'); ?> 3" onclick="ui.set_item(2,entity_types.object)" /></td>
            </tr>
            <tr class="tr_obj_3">
              <td class="left"><input type="image" src="<?php echo $basedir; ?>img/up.svg" width="40px" height="16px"
                alt="^" title="<?php echo elgg_echo('wespot_fca:move_up'); ?>" id="btn_move_up_3"
                class="input btn_move_up" onclick="ui.move_up(3)" /> <input type="image"
                src="<?php echo $basedir; ?>img/down.svg" width="40px" height="16px" alt="v"
                title="<?php echo elgg_echo('wespot_fca:move_down'); ?>" id="btn_move_down_3"
                class="input btn_move_down" onclick="ui.move_down(3)" /> <input type="image"
                src="<?php echo $basedir; ?>img/delete.svg" width="16px" height="16px" alt="x"
                title="<?php echo elgg_echo('wespot_fca:obj:rem'); ?>" id="btn_del_obj_3" class="input btn_del_obj"
                onclick="ui.rem_object(3)" /><input type="button" id="obj_3" class="input btn_obj always_on"
                value="<?php echo elgg_echo('wespot_fca:obj:dummy'); ?> 4" onclick="ui.set_item(3,entity_types.object)" /></td>
            </tr>
            <tr class="tr_obj_4">
              <td class="left"><input type="image" src="<?php echo $basedir; ?>img/up.svg" width="40px" height="16px"
                alt="^" title="<?php echo elgg_echo('wespot_fca:move_up'); ?>" id="btn_move_up_4"
                class="input btn_move_up" onclick="ui.move_up(4)" /> <input type="image"
                src="<?php echo $basedir; ?>img/down.svg" width="40px" height="16px" alt="v"
                title="<?php echo elgg_echo('wespot_fca:move_down'); ?>" id="btn_move_down_4"
                class="input btn_move_down" onclick="ui.move_down(4)" /> <input type="image"
                src="<?php echo $basedir; ?>img/delete.svg" width="16px" height="16px" alt="x"
                title="<?php echo elgg_echo('wespot_fca:obj:rem'); ?>" id="btn_del_obj_4" class="input btn_del_obj"
                onclick="ui.rem_object(4)" /><input type="button" id="obj_4" class="input btn_obj always_on"
                value="<?php echo elgg_echo('wespot_fca:obj:dummy'); ?> 5" onclick="ui.set_item(4,entity_types.object)" /></td>
            </tr>
            <tr class="obj_tail">
              <td id="tr_add_obj_btn" class="center add_buttons"><input type="image" class="input" style="border: none"
                src="<?php echo $basedir; ?>img/plus.svg" width="16px" height="16px" alt="+"
                title="<?php echo elgg_echo('wespot_fca:obj:add'); ?>" id="btn_obj_add" class="fixed_height"
                onclick="ui.append_object()" /> <!--<?php echo elgg_echo('wespot_fca:obj:add'); ?> --></td>
            </tr>
          </table>
        </div>
      </td>
      <td class="vc">
        <div id="matrix_main">
          <table>
            <col class="td_attr_0">
            <col class="td_attr_1">
            <col class="td_attr_2">
            <col class="td_attr_3">
            <col class="col_tail">

            <tr class="tr_obj tr_obj_0">

              <td class="cb_attr td_attr_0"><input type="checkbox" class="input check" id="obj_0_attr_0" /></td>
              <td class="cb_attr td_attr_1"><input type="checkbox" class="input check" id="obj_0_attr_1" /></td>
              <td class="cb_attr td_attr_2"><input type="checkbox" class="input check" id="obj_0_attr_2" /></td>
              <td class="cb_attr td_attr_3"><input type="checkbox" class="input check" id="obj_0_attr_3" /></td>
              <td class="tail" style="background-color: #fff"></td>
            </tr>
            <tr class="tr_obj tr_obj_1">

              <td class="cb_attr td_attr_0"><input type="checkbox" class="input check" id="obj_1_attr_0" /></td>
              <td class="cb_attr td_attr_1"><input type="checkbox" class="input check" id="obj_1_attr_1" /></td>
              <td class="cb_attr td_attr_2"><input type="checkbox" class="input check" id="obj_1_attr_2" /></td>
              <td class="cb_attr td_attr_3"><input type="checkbox" class="input check" id="obj_1_attr_3" /></td>
              <td class="tail" style="background-color: #fff"></td>
            </tr>
            <tr class="tr_obj tr_obj_2">

              <td class="cb_attr td_attr_0"><input type="checkbox" class="input check" id="obj_2_attr_0" /></td>
              <td class="cb_attr td_attr_1"><input type="checkbox" class="input check" id="obj_2_attr_1" /></td>
              <td class="cb_attr td_attr_2"><input type="checkbox" class="input check" id="obj_2_attr_2" /></td>
              <td class="cb_attr td_attr_3"><input type="checkbox" class="input check" id="obj_2_attr_3" /></td>
              <td class="tail" style="background-color: #fff"></td>
            </tr>
            <tr class="tr_obj tr_obj_3">

              <td class="cb_attr td_attr_0"><input type="checkbox" class="input check" id="obj_3_attr_0" /></td>
              <td class="cb_attr td_attr_1"><input type="checkbox" class="input check" id="obj_3_attr_1" /></td>
              <td class="cb_attr td_attr_2"><input type="checkbox" class="input check" id="obj_3_attr_2" /></td>
              <td class="cb_attr td_attr_3"><input type="checkbox" class="input check" id="obj_3_attr_3" /></td>
              <td class="tail" style="background-color: #fff"></td>
            </tr>
            <tr class="tr_obj  tr_obj_4">

              <td class="cb_attr td_attr_0"><input type="checkbox" class="input check" id="obj_4_attr_0" /></td>
              <td class="cb_attr td_attr_1"><input type="checkbox" class="input check" id="obj_4_attr_1" /></td>
              <td class="cb_atthttp://cafeerde.com/r td_attr_2"><input type="checkbox" class="input check"
                id="obj_4_attr_2" /></td>
              <td class="cb_attr td_attr_3"><input type="checkbox" class="input check" id="obj_4_attr_3" /></td>
              <td class="tail" style="background-color: #fff"></td>
            </tr>
            <tr class="obj_tail">

              <td class="td_attr_0"></td>
              <td class="td_attr_1"></td>
              <td class="td_attr_2"></td>
              <td class="td_attr_3"></td>
              <td class="tail" style="background-color: #fff"></td>
            </tr>
          </table>
        </div>
      </td>
    </tr>
  </table>
</div>
<div id="dia_set_item" title="<?php echo elgg_echo('wespot_fca:obj:set'); ?>">
  <div id="dia_set_obj_content">
    <table>
      <tr>
        <td class="layout_select_name"></td>
        <td><input id="sel_set_item" type="text" class="sel_set" title="" /></td>
        <td><input type="image" id="btn_item_edit" class="btn_edit to_be_hidden" title=""
          src="<?php echo $basedir; ?>img/edit.svg" width="24px" height="24px" title="" /></td>
      </tr>
      <tr class="descr_detail">
        <td class="layout_select"><?php echo elgg_echo('wespot_fca:description'); ?></td>
        <td><textarea id="text_descr_item" rows="5" cols="35" class="text_description" title=""></textarea></td>
        <td></td>
      </tr>
      <tr class="descr_detail">
        <td class="layout_select" id="label_lo"><?php echo elgg_echo('wespot_fca:l_objs'); ?></td>
        <td>
          <div id="lo_item" class="div_lo"></div>
        </td>
        <td></td>
      </tr>
    </table>
  </div>
  <div class="choice">
    <hr>
    <input id="btn_choose_item_cancel" type="button" class="input_pad to_be_hidden"
      value="<?php echo elgg_echo('wespot_fca:cancel'); ?>" onclick="$('#dia_set_item').dialog('close')"
      title="<?php echo elgg_echo('wespot_fca:cancel'); ?>" /> <input id="btn_choose_item_ok"
      class="input_pad to_be_hidden" type="button" value="<?php echo elgg_echo('wespot_fca:ok'); ?>"
      title="<?php echo elgg_echo('wespot_fca:ok'); ?>" />
  </div>
</div>
<div id="dia_set_lo" title="<?php echo elgg_echo('wespot_fca:l_objs:add'); ?>">
  <div id="dia_set_lo_content">
    <span style="line-height: 2em;"><?php echo elgg_echo('wespot_fca:l_obj:sel'); ?></span> <input type="text"
      id="sel_set_lo" class="select_basic" title=""></input>
    <p class="item_description">
  
  </div>
  <hr>
  <div class="choice">
    <input id="btn_choose_lo_cancel" type="button" class="input_pad"
      value="<?php echo elgg_echo('wespot_fca:cancel'); ?>" title="<?php echo elgg_echo('wespot_fca:cancel'); ?>"
      onclick="$('#dia_set_lo').dialog('close')" /> <input id="btn_choose_lo_ok" class="input_pad" type="button"
      value="<?php echo elgg_echo('wespot_fca:ok'); ?>" title="<?php echo elgg_echo('wespot_fca:ok'); ?>"
      onclick="logic.set_lo()" />
  </div>
</div>

<div id="dia_set_dom" title="<?php echo elgg_echo('wespot_fca:domain:open'); ?>">
  <div id="dia_set_dom_content">
  <?php echo elgg_echo('wespot_fca:domain:sel'); ?><select id="sel_set_dom" class="select_basic"
      onchange="ui.display_description(this, entity_types.domain)" title=""></select>
    <p class="item_description"></p>
  </div>
  <div class="choice">
    <hr>
    <input id="btn_choose_dom_cancel" type="button" class="input_pad"
      value="<?php echo elgg_echo('wespot_fca:cancel'); ?>" title="<?php echo elgg_echo('wespot_fca:cancel'); ?>"
      onclick="$('#dia_set_dom').dialog('close')" /> <input id="btn_choose_dom_ok" class="input_pad" class="input_pad"
      type="button" value="<?php echo elgg_echo('wespot_fca:ok'); ?>" title="<?php echo elgg_echo('wespot_fca:ok'); ?>"
      onclick="logic.load(JSON.parse($('#sel_set_dom').get(0).options[$('#sel_set_dom').get(0).selectedIndex].value).id, state.teacher)" />
  </div>
</div>

<div id="dia_share_dom" title="<?php echo elgg_echo('wespot_fca:domain:share'); ?>">
  <div id="dia_share_dom_content">
  <?php echo elgg_echo('wespot_fca:course:sel'); ?><select id="sel_set_course" class="select_basic" title=""
      onchange="$('#btn_share_dom_ok').enable()"></select>
    <p class="item_description"></p>
  </div>
  <div class="choice">
    <hr>
    <input id="btn_share_dom_cancel" type="button" class="input_pad"
      value="<?php echo elgg_echo('wespot_fca:cancel'); ?>" title="<?php echo elgg_echo('wespot_fca:cancel'); ?>"
      onclick="$('#dia_share_dom').dialog('close')" /> <input id="btn_share_dom_ok" class="input_pad" class="input_pad"
      type="button" value="<?php echo elgg_echo('wespot_fca:ok'); ?>" title="<?php echo elgg_echo('wespot_fca:ok'); ?>"
      onclick="backend.share_domain({id:$('#sel_set_course').get(0).options[$('#sel_set_course').get(0).selectedIndex].value, name:$('#sel_set_course').get(0).options[$('#sel_set_course').get(0).selectedIndex].textContent},ui.display_share_ok_error)" />
  </div>
</div>


<div id="dia_rem_obj" title="<?php echo elgg_echo('wespot_fca:obj:rem'); ?>">
  <p id="dia_rem_obj_content">
    Are you sure that you want to delete the object '<span id="span_rem_obj"></span>'?.
  </p>
  <div class="choice">
    <hr>
    <input id="btn_rem_obj_no" type="button" class="input_pad" value="<?php echo elgg_echo('wespot_fca:no'); ?>"
      title="<?php echo elgg_echo('wespot_fca:no'); ?>" onclick="$('#dia_rem_obj').dialog('close')" /> <input
      id="btn_rem_obj_yes" type="button" class="input_pad" value="<?php echo elgg_echo('wespot_fca:yes'); ?>"
      title="<?php echo elgg_echo('wespot_fca:yes'); ?>" />
  </div>
</div>

<div id="dia_rem_attr" title="<?php echo elgg_echo('wespot_fca:attr:rem'); ?>">
  <p id="dia_rem_attr_content">
    Are you sure that you want to remove the attribute '<span id="span_rem_attr"></span>'?
  </p>
  <div class="choice">
    <hr>
    <input id="btn_rem_attr_no" type="button" class="input_pad" value="<?php echo elgg_echo('wespot_fca:no'); ?>"
      title="<?php echo elgg_echo('wespot_fca:no'); ?>" onclick="$('#dia_rem_attr').dialog('close')" /> <input
      id="btn_rem_attr_yes" type="button" class="input_pad" value="<?php echo elgg_echo('wespot_fca:yes'); ?>"
      title="<?php echo elgg_echo('wespot_fca:yes'); ?>" />
  </div>
</div>

<div id="dia_publish_domain" title="<?php echo elgg_echo('wespot_fca:domain:approve'); ?>">
  <p id="dia_publish_domain_content">
    <?php echo elgg_echo('wespot_fca:domain:approve_sure'); ?><span id="span_publish_domain"></span>
  </p>
  <div class="choice">
    <hr>
    <input id="btn_publish_domain_no" type="button" class="input_pad" value="<?php echo elgg_echo('wespot_fca:no'); ?>"
      title="<?php echo elgg_echo('wespot_fca:no'); ?>" onclick="$('#dia_publish_domain').dialog('close')" /> <input
      id="btn_publish_domain_yes" type="button" class="input_pad" value="<?php echo elgg_echo('wespot_fca:yes'); ?>"
      title="<?php echo elgg_echo('wespot_fca:yes'); ?>" onclick="$('#dia_publish_domain').dialog('close'); logic.approve_domain()" />
  </div>
</div>

<div id="dia_create_obj" title="Create New Object">
  <table>
    <tr>
      <td><?php echo elgg_echo('wespot_fca:name'); ?>:</td>
      <td><input type="text" id="input_create_obj_name" title="" /></td>
    </tr>
    <tr>
      <td><?php echo elgg_echo('wespot_fca:description'); ?>:</td>
      <td><input type="text" id="input_create_obj_description" title="" /></td>
    </tr>
  </table>
  <div class="choice">
    <hr>
    <input id="btn_create_obj_cancel" type="button" class="input_pad"
      value="<?php echo elgg_echo('wespot_fca:cancel'); ?>" title="<?php echo elgg_echo('wespot_fca:cancel'); ?>"
      onclick="ui.set_item(state.obj_index,0)" /> <input id="btn_create_obj_ok" class="input_pad" type="button"
      value="<?php echo elgg_echo('wespot_fca:ok'); ?>" title="<?php echo elgg_echo('wespot_fca:ok'); ?>"
      onclick="logic.create_object($('#input_create_obj_name').prop('value'),$('#input_create_obj_description').prop('value'))" />
  </div>
</div>

<div id="dia_create_attr" title="Create New Attribute">
  <table>
    <tr>
      <td><?php echo elgg_echo('wespot_fca:name'); ?>:</td>
      <td><input type="text" id="input_create_attr_name" title="" /></td>
    </tr>
    <tr>
      <td><?php echo elgg_echo('wespot_fca:description'); ?>:</td>
      <td><input type="text" id="input_create_attr_description" title="" /></td>
    </tr>
  </table>
  <div class="choice">
    <hr>
    <input id="btn_create_attr_cancel" type="button" class="input_pad"
      value="<?php echo elgg_echo('wespot_fca:cancel'); ?>" title="<?php echo elgg_echo('wespot_fca:cancel'); ?>"
      onclick="ui.set_item(state.attr_index,1)" /> <input id="btn_create_attr_ok" class="input_pad" type="button"
      value="<?php echo elgg_echo('wespot_fca:ok'); ?>" title="<?php echo elgg_echo('wespot_fca:ok'); ?>"
      onclick="logic.create_attribute($('#input_create_attr_name').prop('value'),$('#input_create_attr_description').prop('value'))" />
  </div>
</div>

<div id="dia_create_domain" title="Create New Domain">
  <table>
    <tr>
      <td><?php echo elgg_echo('wespot_fca:name'); ?>:</td>
      <td><input type="text" id="input_create_domain_name" title="" /></td>
    </tr>
    <tr>
      <td><?php echo elgg_echo('wespot_fca:description'); ?>:</td>
      <td><input type="text" id="input_create_domain_description" title="" /></td>
    </tr>
  </table>
  <div class="choice">
    <hr>
    <input id="btn_create_domain_cancel" type="button" class="input_pad"
      value="<?php echo elgg_echo('wespot_fca:cancel'); ?>" title="<?php echo elgg_echo('wespot_fca:cancel'); ?>"
      onclick="$('#dia_create_domain').dialog('close')" /> <input id="btn_create_domain_ok" type="button"
      class="input_pad" value="<?php echo elgg_echo('wespot_fca:ok'); ?>"
      title="<?php echo elgg_echo('wespot_fca:ok'); ?>"
      onclick="logic.create_domain($('#input_create_domain_name').prop('value'),$('#input_create_domain_description').prop('value'))" />
  </div>
</div>

<div id="dia_create_lo" title="Create New Learning Object">
  <table>
    <tr>
      <td><?php echo elgg_echo('wespot_fca:name'); ?>:</td>
      <td><input type="text" id="input_create_lo_name" title="" /></td>
    </tr>
    <tr>
      <td>Url:</td>
      <td><input type="text" id="input_create_lo_description" title="" /></td>
    </tr>
  </table>
  <div class="choice">
    <hr>
    <input id="btn_create_lo_cancel" type="button" class="input_pad always_on"
      value="<?php echo elgg_echo('wespot_fca:cancel'); ?>" title="<?php echo elgg_echo('wespot_fca:cancel'); ?>"
      onclick="$('#dia_create_lo').dialog('close')" /> <input id="btn_create_lo_ok" type="button"
      class="input_pad always_on" value="<?php echo elgg_echo('wespot_fca:ok'); ?>"
      title="<?php echo elgg_echo('wespot_fca:ok'); ?>"
      onclick="logic.create_lo($('#input_create_lo_name').prop('value'),'',$('#input_create_lo_description').prop('value'))" />
  </div>
</div>


<div id="dia_vis" title="">
  <img src="<?php echo $basedir; ?>img/loading.gif" id="vis_loading" />
  <table>
    <tr>
      <td>
      <span id="span_latticeview"> <input type="checkbox" id="cb_latticeview" style="width: 20px"
          onclick="lattice.switch_view()" />
          </span>
           <?php echo elgg_echo('wespot_fca:lattice:show_full'); ?>
            <span id="span_taxonomy_selectall"> <input type="button" class="input always_on" id="btn_taxonomy_selectall"
            onclick="lattice.enable_all()" value="<?php echo elgg_echo('wespot_fca:lattice:selectall'); ?>"
            title="<?php echo elgg_echo('wespot_fca:lattice:selectall'); ?>" />
        
      </span></td>
      <td rowspan="2" style="vertical-align: top;">
        <div id="div_lattice_info"></div>
      </td>
    </tr>
    <tr>
      <td>
        <div id="div_lattice_vis">
            <canvas id="canvas_lattice">
     		</canvas>
        </div>
      </td>
    </tr>
  </table>
</div>
<!--[if lt IE 7 ]>  <div class="msie_fca"></div> <![endif]-->
<!--[if IE 7 ]>     <div class="msie_fca"></div> <![endif]-->
<!--[if IE 8 ]>     <div class="msie_fca"></div> <![endif]-->
<!--[if IE 9 ]>     <div class="msie_fca"></div> <![endif]-->
