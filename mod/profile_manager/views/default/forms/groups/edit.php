<script>

    current_skills_by_phases = {}

    function init() {
        $('#phase1').addClass('current').siblings('.triangle').html('▷')

        $.each($('.phases .phase'), function( index, value ) {
            var phase = $(this).attr('id').replace('phase', '')
            if(selected_phases().indexOf(phase) > -1) {
                $(this).addClass('selected')
            }
        })

        var activities = selected_activities()
        $.each($('.activity input'), function( index, value ) {
            var activity_id = $(this).attr('class')
            $(this).prop('checked', activities.indexOf(activity_id) > -1)
        })

        // enable all activities in deselected phases
        for(var phase = 1; phase <= 6; phase++) {
            if(selected_phases().indexOf(phase) == -1) {
              var one_checked = false;
              $.each($('.phase_' + phase + '.activities .activity input'), function( index, input ) {
                  if($(input).is(":checked")) { one_checked = true }
              })
              if(!one_checked) { //if no activity is selected for non-active phases, we offer all activities by default.. otherwise the last state
                $.each($('.phase_' + phase + '.activities .activity input'), function( index, input ) {
                  $(input).prop('checked', true)
                  var activity_id = $(input).attr('class')
                  $('#selected_activities input').val(selected_activities().replace(activity_id, '').replace('  ', ' '))
                  $('#selected_activities input').val(selected_activities() + ' ' + activity_id)
                })
              }
            }
        }
    }

    function current_phase() {
        return $('.phase.current').attr('id').replace('phase', '')
    }

    function refresh() {
        $('.activities').hide()
        var activities = $('.phase_' + current_phase() + '.activities')
        activities.show()
        if(current_skill_hash() == 0)
        {
            activities.find('.activity').removeClass('emphasized')
        } else
        {
            activities.find('.activity').removeClass('emphasized')
            activities.find('.' + current_skill_hash()).addClass('emphasized')
        }
        $('.current_phase').text(current_phase())
    }

    function init_skill_selector() {
        var select = $('#select_skill');
        select.empty();
        select.append('<option value=0>All</option>')
        $('.select_options #phase_'+current_phase()+' li').each(function(index) {
            select.append('<option value='+$(this).attr('class')+'>' + $(this).text() + '</option>')
            //console.log('<option value='+$(this).attr('class')+'>' + $(this).text() + '</option>')
        })
    }

    function current_skill_hash() {
        return $('#select_skill').val()
    }

    function selected_phases() {
        return $('#selected_phases input').val()
    }

    function selected_activities() {
        return $('#selected_activities input').val()
    }

    $(document).ready(function()
    {
        init()
        init_skill_selector()
        refresh()

        $(".phases .phase").mouseenter(function(e) {
            $('.triangle').html('')
            $(this).siblings('.triangle').html('▷')
        })

        $(".phases").mouseleave(function(e) {
            $('.triangle').html('')
            $('.phase.current').siblings('.triangle').html('▷')
        })

        $(".phases .phase").click(function(e) {
            $('.triangle').html('')
            $('.phase').removeClass('current')

            $(this).addClass('selected')

            var phase = $(this).attr('id').replace('phase', '')
            if(selected_phases().indexOf(phase) == -1) {
                $('#selected_phases input').val(selected_phases() + phase)
            }

            $(this).addClass('current')
            $(this).siblings('.triangle').html('▷')
            init_skill_selector()
            var skill = current_skills_by_phases[current_phase()]
            if(skill) $('#select_skill').val(skill)
            refresh()
        })

        $(".phase_wrapper .close").click(function(e) {
            $(this).siblings('.phase').removeClass('selected')
            var phase = $(this).closest('.phase_wrapper').find('.phase').attr('id').replace('phase', '')
            $('#selected_phases input').val(selected_phases().replace(phase, ''))
        })

        $("p.activity").click(function(e) {
            var input = $(this).find('input')
            if(!$(e.target).is('input')) {
                input.prop("checked", !input.prop("checked"))
                input_changed(input)
            }
        })

        function input_changed(input) {
            var activity_id = input.attr('class')
            if(input.is(":checked")) {
                $('#selected_activities input').val(selected_activities() + ' ' + activity_id)
            } else {
                $('#selected_activities input').val(selected_activities().replace(activity_id, '').replace('  ', ' '))
            }
        }

        $(".activity input").change(function(e) {
            input_changed($(this))
        })

        $('#select_skill').change(function(e) {
            refresh()
            current_skills_by_phases[current_phase()] = $(this).val()
        })
    })

</script>

<?php
/**
* Profile Manager
*
* Overrules group edit form to support options (radio, dropdown, multiselect)
*
* @package profile_manager
* @author ColdTrick IT Solutions
* @copyright Coldtrick IT Solutions 2009
* @link http://www.coldtrick.com/
*/

elgg_load_library('elgg:wespot_phases');

// new groups default to open membership
if (isset($vars['entity'])) {
	$membership = $vars['entity']->membership;
	$access = $vars['entity']->access_id;
	if ($access != ACCESS_PUBLIC && $access != ACCESS_LOGGED_IN) {
		// group only - this is done to handle access not created when group is created
		$access = ACCESS_PRIVATE;
	}
} else {
	$membership = ACCESS_PUBLIC;
	$access = ACCESS_PUBLIC;
}

?>

<div>
	<label><?php echo elgg_echo("groups:icon"); ?></label><br />
	<?php echo elgg_view("input/file", array('name' => 'icon')); ?>
</div>
<div>
	<label><?php echo elgg_echo("groups:name"); ?></label><br />
	<?php echo elgg_view("input/text", array(
		'name' => 'name',
		'value' => $vars['entity']->name,
	));
	?>
</div>

<?php

// retrieve group fields
$group_fields = profile_manager_get_categorized_group_fields();

if(count($group_fields["fields"]) > 0){
	$group_fields = $group_fields["fields"];

	foreach($group_fields as $field) {
		$metadata_name = $field->metadata_name;

		// get options
		$options = $field->getOptions();

		// get type of field
		$valtype = $field->metadata_type;

		// get title
		$title = $field->getTitle();

		// get value
		$value = '';
		if($metadata = $vars['entity']->$metadata_name) {
			if (is_array($metadata)) {
				foreach($metadata as $md) {
					if (!empty($value)) $value .= ', ';
					$value .= $md;
				}
			} else {
				$value = $metadata;
			}
		}

		$line_break = '<br />';
		if ($valtype == 'longtext') {
			$line_break = '';
		}
		echo '<div><label>';
		echo $title;
		echo "</label>";

		if($hint = $field->getHint()){
			?>
			<span class='custom_fields_more_info' id='more_info_<?php echo $metadata_name; ?>'></span>
			<span class="custom_fields_more_info_text" id="text_more_info_<?php echo $metadata_name; ?>"><?php echo $hint;?></span>
			<?php
		}

		echo $line_break;

		if($valtype == "dropdown"){
			// add div around dropdown to let it act as a block level element
			echo "<div>";
		}

		echo elgg_view("input/{$valtype}", array(
			'name' => $metadata_name,
			'value' => $value,
			'options' => $options
		));

		if($valtype == "dropdown"){
			echo "</div>";
		}

		echo '</div>';
	}
}

?>


<br>

<?php if(inquiry_supports_new_configuration_interface($vars['entity'])) : ?>

<label>Configuration interface</label>
<span class="instruction">Make a selection of the phases you want to include in your inquiry (Use the cross X to delete phases). If you leave the check marks in front of the suggested activities, the system will set up these activities for you automatically.
<br>
<br>
The drop-down menu called 'Filter by skill' shows you how the suggested activities in each phase are related to skills you might want your students to develop (leave the default, if you want all possible skills to be practiced and tracked by the system).
<!--    Then for each phase select skills you want your students to develop (an automatic suggestion for activities in which these skills can be practiced will ppear)-->
</span>

<div class="configuration clearfix">
    <div class='phases'>
        <?php
        for ($phase = 1; $phase <= phase_count(); $phase++) {
            echo('<div class="phase_wrapper clearfix">');
            echo('<div class="triangle"></div>');
            echo('<div id="phase'.$phase.'" class="phase">');
            echo($phase.'. <span>'.phase_name($phase).'</span>');
            echo('</div>');
            echo('<div class="close">X</div>');
            echo('</div>');
        }
        ?>

        <div id="selected_phases">
            <?php echo elgg_view('input/hidden', array('name' => 'phases', 'value' => $vars['entity']->phases !== null ? $vars['entity']->phases : '123456')); ?>
        </div>
        <div id="selected_activities">
            <?php echo elgg_view('input/hidden', array('name' => 'activities', 'value' => isset($vars['entity']) ? implode(' ', enabled_activities($vars['entity']->guid, null, true)) : implode(' ', all_activities_ids()))); ?>
        </div>
    </div>

    <div class='activities_panel'>
        <h2>Activities for phase <span class="current_phase"></span>:</h2>
        <form accept-charset="UTF-8" action="" method="post">
            <label for="select_skill">Filter by skill:</label>
            <select id="select_skill" name="select_skill"></select>
        </form>

        <div class="select_options">
            <?php
            for ($phase = 1; $phase <= phase_count(); $phase++) {
                echo('<ul id="phase_'.$phase.'">');
                foreach(skills_for_phase($phase) as $skill) {
                    echo('<li class='.md5($skill).'>'.$skill.'</li>');
                }
                echo('</ul>');
            }
            ?>
        </div>

        <p class="instruction">
            List of suggested activities (these activities will be configured to show up in your inquiry, but they can always be removed, or other ones added by you, depending on the way you want to design your inquiry):
        </p>

        <?php
        for ($phase = 1; $phase <= phase_count(); $phase++) {
            echo('<div class="phase_'.$phase.' activities">');
            foreach(phase_tasks($phase) as $task) {
                $hashes = Array();
                foreach($task['skills'] as $skill) {
                    array_push($hashes, md5(strip_brackets($skill)));
                }
                $classes = implode(' ', array_unique($hashes));

                echo('<p class="activity '.$classes.'">');
                echo('<input type="checkbox" name="" value="" class="'.$task['activity_id'].'""><b>'.$task['title'].'</b></input>');
                echo(' <span class="skills"><b></b> '.$task['activity'].'</span>');
                echo(' <span class="skills"><b>Skills:</b> '.implode(', ', $task['skills']).'</span>');
                echo(' <span class="skills"><b>Widget:</b> '.normalize_widget_type($task['widget']).'</span>');
                echo('</p>');
            }
            echo('</div>');
        }
        ?>
    </div>

</div>

<?php endif; ?>

<div>
	<label>
		<?php echo elgg_echo('groups:membership'); ?><br />
		<?php echo elgg_view('input/access', array(
			'name' => 'membership',
			'value' => $membership,
			'options_values' => array(
				ACCESS_PRIVATE => elgg_echo('groups:access:private'),
				ACCESS_PUBLIC => elgg_echo('groups:access:public')
			)
		));
		?>
	</label>
</div>
<?php

    if (elgg_get_plugin_setting('hidden_groups', 'groups') == 'yes') {
		$this_owner = $vars['entity']->owner_guid;

		if (!$this_owner) {
			$this_owner = elgg_get_logged_in_user_guid();
		}
		$access_options = array(
			ACCESS_PRIVATE => elgg_echo('groups:access:group'),
			ACCESS_LOGGED_IN => elgg_echo("LOGGED_IN"),
			ACCESS_PUBLIC => elgg_echo("PUBLIC")
		);

		?>

	<div>
		<label>
				<?php echo elgg_echo('groups:visibility'); ?><br />
				<?php echo elgg_view('input/access', array(
					'name' => 'vis',
					'value' =>  $access,
					'options_values' => $access_options,
				));
				?>
		</label>
	</div>

	<?php
}

$tools = elgg_get_config('group_tool_options');
if ($tools) {
    if($tools->sort) {
        usort($tools, create_function('$a,$b', 'return strcmp($a->label,$b->label);'));
    }
	foreach ($tools as $group_option) {
		$group_option_toggle_name = $group_option->name . "_enable";
		if ($group_option->default_on) {
			$group_option_default_value = 'yes';
		} else {
			$group_option_default_value = 'no';
		}
		$value = $vars['entity']->$group_option_toggle_name ? $vars['entity']->$group_option_toggle_name : $group_option_default_value;
		?>
		<div>
			<label>
				<?php echo $group_option->label; ?><br />
			</label>
			<?php
			echo elgg_view("input/radio", array(
				"name" => $group_option_toggle_name,
				"value" => $value,
				'options' => array(
					elgg_echo('groups:yes') => 'yes',
					elgg_echo('groups:no') => 'no',
				)
			));
			?>
		</div>
		<?php
	}
}
?>
<div class="elgg-foot">
	<?php

	if (isset($vars['entity'])) {
		echo elgg_view('input/hidden', array(
			'name' => 'group_guid',
			'value' => $vars['entity']->getGUID(),
		));
	}

	echo elgg_view('input/submit', array('value' => elgg_echo('save')));

	if (isset($vars['entity'])) {
		$delete_url = 'action/groups/delete?guid=' . $vars['entity']->getGUID();
		echo elgg_view('output/confirmlink', array(
			'text' => elgg_echo('groups:delete'),
			'href' => $delete_url,
			'confirm' => elgg_echo('groups:deletewarning'),
			'class' => 'elgg-button elgg-button-delete float-alt',
		));
	}
	?>
</div>
