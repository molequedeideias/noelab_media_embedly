<?php
/**
 * Elgg media view
 *
 * @package Elggmedias
 */

$full = elgg_extract('full_view', $vars, FALSE);
$media = elgg_extract('entity', $vars, FALSE);

if (!$media) {
	return;
}

$owner = $media->getOwnerEntity();
$owner_icon = elgg_view_entity_icon($owner, 'tiny');
$container = $media->getContainerEntity();
$categories = elgg_view('output/categories', $vars);

$link = filter_tags(elgg_view('output/url', array('href' => $media->address, 'rel' => 'nofollow')));
$description = elgg_view('output/longtext', array('value' => $media->description, 'class' => 'pbl'));

$owner_link = elgg_view('output/url', array(
	'href' => "medias/owner/$owner->username",
	'text' => $owner->name,
));
$author_text = elgg_echo('byline', array($owner_link));

$tags = elgg_view('output/tags', array('tags' => $media->tags));
$date = elgg_view_friendly_time($media->time_created);

$comments_count = $media->countComments();
//only display if there are commments
if ($comments_count != 0) {
	$text = elgg_echo("comments") . " ($comments_count)";
	$comments_link = elgg_view('output/url', array(
		'href' => $media->getURL() . '#comments',
		'text' => $text,
	));
} else {
	$comments_link = '';
}

$metadata = elgg_view_menu('entity', array(
	'entity' => $vars['entity'],
	'handler' => 'medias',
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz',
));

$subtitle = "$author_text $date $categories $comments_link";

// do not show the metadata and controls in widget view
if (elgg_in_context('widgets')) {
	$metadata = '';
}

if ($full && !elgg_in_context('gallery')) {
	$header = elgg_view_title($media->title);

	$params = array(
		'entity' => $media,
		'title' => false,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
		'tags' => $tags,
	);
	$params = $params + $vars;
	$list_body = elgg_view('object/elements/medias_summary', $params);
	$media_info = elgg_view_image_block($owner_icon, $list_body);
    
	//noelab mini_oembed_description
	$oembed_html = $media->oembed_html;
	$oembed_type= $media->oembed_type;
	$oembed_url= $media->oembed_url;
	$oembed_title= $media->oembed_title;
	
	switch($oembed_type) {
		case 'photo':
			$oembed_content_html = "<img src=\"" . $oembed_url . "\" alt=\"" . $oembed_title . "\" title=\"" . $oembed_title . "\"</img>";			
		break;  
		case 'link':
  		case 'rich':
  		case 'video':
            $oembed_content_html = $oembed_html; 
		case 'error':
        default:
	}
	
	//noelab oembed_metadata
    $oembed_provider_name = $media->oembed_provider_name;
	$oembed_author_name =  $media->oembed_author_name;
	$oembed_description =  $media->oembed_description;
	
	$mediadesc = "<p><b>" . elgg_echo('mediaembedly:aboutitem') . "</b></p>";
	if(strlen($oembed_description) > 620) {
             $mini_description = strip_tags($oembed_description);
			 substr($mini_description,0,620) . "...";                    
	}else {
             $oembed_description;
    }
	
	if ($oembed_author_name != '') { 
           	$mediaauthor = "<p><b>" . elgg_echo('mediaembedly:mediaauthor') . ": </b> " . $oembed_author_name . "</p>";
	}
    
	$mediavia = "<p><b>Via: </b> " . $oembed_provider_name . "</p>";
	
	echo <<<HTML
$header
$media_info
<div class="media elgg-content mts">
	<div class="elgg-grid clearfix">
	    <div class="elgg-col elgg-col-3of4">
		    <div class="elgg-inner pvm prl mediaembedly_wrapper">
		        $oembed_content_html
		     </div>
	         </div>
	         <div class="elgg-col elgg-col-1of4">
		         <div class="elgg-inner pvm mediaembedly_meta">
		             $mediadesc
		             $oembed_description
					 $mediaauthor
					 $mediavia	
		         </div>
	         </div>
	     </div>
	$description
</div>
HTML;

} elseif (elgg_in_context('gallery')) {
	echo <<<HTML
<div class="medias-gallery-item">
	<h3>$media->title</h3>
	<p class='subtitle'>$owner_link $date ciao2gallery</p>
</div>
HTML;
} else {
	// brief view
	$entity = $vars['entity'];
	$url = $media->address;
	$display_text = $url;
	$excerpt = elgg_get_excerpt($media->oembed_description);
	if ($excerpt) {
		$excerpt = "$excerpt";
	}

	if (strlen($url) > 25) {
		$bits = parse_url($url);
		if (isset($bits['host'])) {
			$display_text = $bits['host'];
		} else {
			$display_text = elgg_get_excerpt($url, 100);
		}
	}
    
	//cancellare non serve
	$link = filter_tags(elgg_view('output/url', array(
		'href' => $media->address,
		'text' => $display_text,
		'rel' => 'nofollow',
	)));
	
	//noelab: mettere if thumb ok altrimenti default icon
	if (!array_key_exists('mediathumbnail_url', $oembed_thumbnail_url)) {
			$thumb_oembed = "<a href=\"{$entity->getURL()}\"><img class=\"medias-thumb-oembed\" src=\"{$media->oembed_thumbnail_url}\" /></a>";
    } else {
    	echo "error thumb default";
	}
	$content = "<div class=\"elgg-grid clearfix\">
	                 <div class=\"elgg-col elgg-col-1of5\">
		                   <div class=\"elgg-inner pvm prl\">
		                       {$thumb_oembed}
		                   </div>
	                 </div>
	                 <div class=\"elgg-col elgg-col-4of5\">
		                   <div class=\"elgg-inner pvm\">
		                   {$excerpt}
		                   </div>
	                 </div>
	            </div>";

	$params = array(
		'entity' => $media,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
		'tags' => $tags,
		'content' => $content,
	);
	$params = $params + $vars;
	$body = elgg_view('object/elements/medias_summary', $params);
	
	echo elgg_view_image_block($owner_icon, $body);
}