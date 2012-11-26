<?php
/**
* Elgg medias save action
*
* @package medias
*/

gatekeeper();

$title = strip_tags(get_input('title'));
$description = get_input('description');
$address = get_input('address');
$access_id = get_input('access_id');
$tags = get_input('tags');
$guid = get_input('guid');
$share = get_input('share');
$container_guid = get_input('container_guid', elgg_get_logged_in_user_guid());

elgg_make_sticky_form('medias');

//embedly api Authentication
require_once (dirname(dirname(dirname(__FILE__)))) . '/lib/Embedly.php';	

    $projectapp = $site->sitename;
  	$contactapp = $site->siteemail;
 	$api = new Embedly_API(array(
              'user_agent' => 'Mozilla/5.0 (compatible; '. $projectapp .'/elgg; '. $contactapp .')'
             ));			
			 			    
    //embedly api Data
    $oembeds = $api->oembed(array('url' => $address));
	//

// don't use elgg_normalize_url() because we don't want
// relative links resolved to this site.
if ($address && !preg_match("#^((ht|f)tps?:)?//#i", $address)) {
	$address = "http://$address";
}

//noelab: don't control title
if (!$address || !filter_var($address, FILTER_VALIDATE_URL)) {
	register_error(elgg_echo('medias:save:failed'));
	forward(REFERER);
}

if ($guid == 0) {
	$media = new ElggObject;
	$media->subtype = "medias";
	$media->container_guid = (int)get_input('container_guid', $_SESSION['user']->getGUID());
	$new = true;
} else {
	$media = get_entity($guid);
	if (!$media->canEdit()) {
		system_message(elgg_echo('medias:save:failed'));
		forward(REFERRER);
	}
}

$tagarray = string_to_tag_array($tags);

//noelab embedly
$entity->address = $address;

		//embedly api Values 		
		foreach ($oembeds as $k => $oembed) {
    	$oembed = (array) $oembed;				
				
				//embedly api Save
				$media->oembed_html = $oembed['html'];
				$media->oembed_type = $oembed['type'];
				$media->oembedtitle = $oembed['title'];
				$media->oembed_url = $oembed['url'];
				$media->oembed_author_name = $oembed['author_name'];
				$media->oembed_description = $oembed['description'];
				//$media->oembed_author_url= $oembed['author_url'];
				$media->oembed_provider_name = $oembed['provider_name'];
				//$media->mediaprovider_url = $oembed['provider_url'];
				$media->oembed_thumbnail_url = $oembed['thumbnail_url'];
				$media->oembed_thumbnail_width = $oembed['thumbnail_width'];
				$media->oembed_thumbnail_height = $oembed['thumbnail_height'];
			    
				
 		}

        if(!$title) {
			$title =  $media->oembedtitle;
		}
		
$media->title = $title;
$media->address = $address;
$media->description = $description;
$media->access_id = $access_id;
$media->tags = $tagarray;

if ($media->save()) {

	elgg_clear_sticky_form('medias');

	// @todo
	if (is_array($shares) && sizeof($shares) > 0) {
		foreach($shares as $share) {
			$share = (int) $share;
			add_entity_relationship($media->getGUID(), 'share', $share);
		}
	}
	system_message(elgg_echo('medias:save:success'));

	//add to river only if new
	if ($new) {
		add_to_river('river/object/medias/create','create', elgg_get_logged_in_user_guid(), $media->getGUID());
	}

	forward($media->getURL());
} else {
	register_error(elgg_echo('medias:save:failed'));
	forward("medias");
}
