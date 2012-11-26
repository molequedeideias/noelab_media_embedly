<?php
/**
 * Elgg medias widget
 *
 * @package medias
 */

$max = (int) $vars['entity']->num_display;

$options = array(
	'type' => 'object',
	'subtype' => 'medias',
	'container_guid' => $vars['entity']->owner_guid,
	'limit' => $max,
	'full_view' => FALSE,
	'pagination' => FALSE,
);
$content = elgg_list_entities($options);

echo $content;

if ($content) {
	$url = "medias/owner/" . elgg_get_page_owner_entity()->username;
	$more_link = elgg_view('output/url', array(
		'href' => $url,
		'text' => elgg_echo('medias:more'),
	));
	echo "<span class=\"elgg-widget-more\">$more_link</span>";
} else {
	echo elgg_echo('medias:none');
}
