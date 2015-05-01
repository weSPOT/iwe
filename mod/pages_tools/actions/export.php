<?php
/**
 * actual export script
 */

	$guid = (int) get_input("guid");
	// preg_match('/([0-9]+)/', $_SERVER['QUERY_STRING'], $matches);
  // $guid = $matches[0];
  // $guid = elgg_get_page_owner_guid();
	$format = strtolower(get_input("format", "a4"));
	$font = get_input("font", "times"); // don't see this as its not an option in the popup and its not used here anyway
	$include_subpages = (int) get_input("include_children");
	$include_index = (int) get_input("include_index");
   
	if(!empty($guid)){
		if($page = get_entity($guid)){
      
      // get the language of the Inquiry from the extra field 'inquiry_language'
      $language = get_entity($guid)->inquiry_language;
      # error_log('language: '.$language); # DEBUG
      
			// this could take a while
			set_time_limit(0);
			
			// string for the content
      if ($language!="Bulgarian" && $language!="Ukranian" && $language!="Russian" && $language != "Slovenian") {
        $htmltop = "<html><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" /></head><body> ";
      } else {
        $htmltop = "<html><head><meta http-equiv=\"Content-Type\" content=\"text/html; windows-1251\" /></head><body> ";
      }
			$html = "";
      $indexhtml = "";

			/*
			// make index
			if(!empty($include_index)){
				$html .= "<h3>" . elgg_echo("pages_tools:export:index") . "</h3>";
				
				$html .= "<ul>";
				$html .= "<li>" . elgg_view("output/url", array("text" => $page->title, "href" => "#page_" . $page->getGUID(), "title" => $page->title)) . "</li>";
				
				// include subpages
				if(!empty($include_subpages) && ($sub_index = pages_tools_render_index($page))){
					$html .= $sub_index;
				}
				
				$html .= "</ul>";
				$html .= "<p style='page-break-after:always;'></p>";
			}
			
			// print page
			$html .= "<h3>" . elgg_view("output/url", array("text" => $page->title, "href" => false, "name" => "page_" . $page->getgUID())) . "</h3>";
			$html .= elgg_view("output/longtext", array("value" => $page->description));
			$html .= "<p style='page-break-after:always;'></p>";
			
			// print subpages
			if(!empty($include_subpages) && ($child_pages = pages_tools_render_childpages($page))){
				$html .= $child_pages;
			}
			*/
      
      // start with the title and description of the Inquiry
      $htmltop .= "<h1>" . $page->name . "</h1>";
			$htmltop .= "<p>" . elgg_view("output/longtext", array("value" => $page->description)) . "</p>";
      
      // now add the content of all of the sections
      // if(!empty($include_subpages) <- should work this in somehow
      
      # index lines were:
      # $indexhtml .= "<a href='".$t['url']."'>" . $t['title'] . "</a><br />";
      
      $temp = export_inquiry_hypothesis($guid);
      if (count($temp) > 0) {
        $indexhtml .= "<li>Hypotheses:<br />";
        $html .= "<h2>Hypotheses</h2>";
        foreach ($temp as $t) {
          $indexhtml .= $t['title'] . "<br />";
          $html .= "<h3>" . $t['title'] . "</h3>";
          $html .= "<p>" . $t['description'] . "</p>";
        }
        $indexhtml .= "</li>";
        $html .= "<hr />";
      }

      $temp = export_inquiry_notes($guid);
      if (count($temp) > 0) {
        $indexhtml .= "<li>Notes:<br />";
        $html .= "<h2>Notes</h2>";
        foreach ($temp as $t) {
          $indexhtml .= $t['title'] . "<br />";
          $html .= "<h3>" . $t['title'] . "</h3>";
          $html .= "<p>" . $t['description'] . "</p>";
        }
        $indexhtml .= "</li>";
        $html .= "<hr />";
      }
      
     $temp = export_inquiry_conclusions($guid);
      if (count($temp) > 0) {
        $indexhtml .= "<li>Conclusions:<br />";
        $html .= "<h2>Conclusions</h2>";
        foreach ($temp as $t) {
          $indexhtml .= $t['title'] . "<br />";
          $html .= "<h3>" . $t['title'] . "</h3>";
          $html .= "<p>" . $t['description'] . "</p>";
        }
        $indexhtml .= "</li>";
        $html .= "<hr />";
      }
      
     $temp = export_inquiry_reflection($guid);
      if (count($temp) > 0) {
        $indexhtml .= "<li>Reflections:<br />";
        $html .= "<h2>Reflections</h2>";
        foreach ($temp as $t) {
          $indexhtml .= $t['title'] . "<br />";
          $html .= "<h3>" . $t['title'] . "</h3>";
          $html .= "<p>" . $t['description'] . "</p>";
        }
        $indexhtml .= "</li>";
        $html .= "<hr />";
      }
      
     $temp = export_inquiry_files($guid);
      if (count($temp) > 0) {
        $indexhtml .= "<li>Files:<br />";
        $html .= "<h2>Files</h2>";
        foreach ($temp as $t) {
          $indexhtml .= $t['title'] . "<br />";
          $html .= "<h3>" . $t['title'] . "</h3>";
          $html .= "<p>" . $t['description'] . "</p>";
        }
        $indexhtml .= "</li>";
        $html .= "<hr />";
      }

     $temp = export_inquiry_pages($guid);
      if (count($temp) > 0) {
        $indexhtml .= "<li>Pages:<br />";
        $html .= "<h2>Pages</h2>";
        foreach ($temp as $t) {
          $indexhtml .= $t['title'] . "<br />";
          $html .= "<h3>" . $t['title'] . "</h3>";
          $html .= "<p>" . $t['description'] . "</p>";
        }
        $indexhtml .= "</li>";
        $html .= "<hr />";
      }

      $temp = export_inquiry_questions($guid);
      if (count($temp) > 0) {
        $indexhtml .= "<li>Questions:<br />";
        $html .= "<h2>Questions</h2>";
        foreach ($temp as $t) {          
          $indexhtml .= $t['title'] . "<br />";
          $html .= "<h3>" . $t['title'] . "</h3>";
          $html .= "<p>" . $t['description'] . "</p>";
        }
        $indexhtml .= "</li>";
        $html .= "<hr />";
      }
      
      $temp = export_inquiry_answers($guid);
      if (count($temp) > 0) {
        $indexhtml .= "<li>Answers:<br />";
        $html .= "<h2>Answers</h2>";
        foreach ($temp as $t) {
          $indexhtml .= $t['title'] . "<br />";
          $html .= "<h3>" . $t['title'] . "</h3>";
          $html .= "<p>" . $t['answer'] . "</p>";
        }
        $indexhtml .= "</li>";
        $html .= "<hr />";
      }

      $temp = export_inquiry_mindmaps($guid);
      if (count($temp) > 0) {
        $indexhtml .= "<li>Mindmaps:<br />";
        $html .= "<h2>Mindmaps</h2>";
        foreach ($temp as $t) {
          $indexhtml .= $t['title'] . "<br />";
          $html .= "<h3>" . $t['title'] . "</h3>";
          $html .= "<p>" . $t['description'] . "</p>";
        }
        $indexhtml .= "</li>";
        $html .= "<hr />";
      }
      
      $temp = export_inquiry_blogs($guid);
      if (count($temp) > 0) {
        $indexhtml .= "<li>Blogs:<br />";
        $html .= "<h2>Blogs</h2>";
        foreach ($temp as $t) {
          $indexhtml .= $t['title'] . "<br />";
          $html .= "<h3>" . $t['title'] . "</h3>";
          $html .= "<p>" . $t['description'] . "</p>";
        }
        $indexhtml .= "</li>";
        $html .= "<hr />";
      }
      
      $temp = export_inquiry_discussions($guid);
      if (count($temp) > 0) {
        $indexhtml .= "<li>Discussions:<br />";
        $html .= "<h2>Discussions</h2>";
        foreach ($temp as $t) {
          $indexhtml .= $t['title'] . "<br />";
          $html .= "<h3>" . $t['title'] . "</h3>";
          $html .= "<p>" . $t['description'] . "</p>";
        }
        $indexhtml .= "</li>";
        $html .= "<hr />";
      }
      
      // if chosen, add the index at the beginning
      if(!empty($include_index)){
        $html = $htmltop."<h2>Index</h2>".$indexhtml."<hr />".$html;
      } else {
        $html = $htmltop.$html;
      }
      
			// load PDF library
			elgg_load_library("dompdf");
			
			// render everything
			try {
				$dompdf = new DOMPDF();
				$dompdf->set_paper($format);
				$dompdf->load_html($html);
				$dompdf->render();
				$dompdf->stream(elgg_get_friendly_title($page->name) . ".pdf");
				exit();
			} catch(Exception $e){
				register_error($e->getMessage());
			}
		} else {
			register_error(elgg_echo("InvalidParameterException:GUIDNotFound", array($guid)));
		}
	} else {
		register_error(elgg_echo("InvalidParameterException:MissingParameter"));
	}
	
	forward(REFERER);