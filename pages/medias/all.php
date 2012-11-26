<?php
/**
 * Elgg medias plugin everyone page
 *
 * @package Elggmedias
 */

elgg_pop_breadcrumb();
elgg_push_breadcrumb(elgg_echo('medias'));

elgg_register_title_button();

$offset = (int)get_input('offset', 0);
$content = elgg_list_entities(array(
	'type' => 'object',
	//noelab
	'subtype' => 'medias',
	'limit' => 10,
	'offset' => $offset,
	'full_view' => false,
	'view_toggle_type' => false
));

$title = elgg_echo('medias:everyone');

$body = elgg_view_layout('content', array(
	'filter_context' => 'all',
	'content' => $content,
	'title' => $title,
	'sidebar' => elgg_view('medias/sidebar'),
));

echo elgg_view_page($title, $body);