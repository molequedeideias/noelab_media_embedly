<?php
/**
 * Add media page
 *
 * @package Elggmedias
 */

$media_guid = get_input('guid');
$media = get_entity($media_guid);

if (!elgg_instanceof($media, 'object', 'medias') || !$media->canEdit()) {
	register_error(elgg_echo('medias:unknown_media'));
	forward(REFERRER);
}

$page_owner = elgg_get_page_owner_entity();

$title = elgg_echo('medias:edit');
elgg_push_breadcrumb($title);

$vars = medias_prepare_form_vars($media);
$content = elgg_view_form('medias/save', array(), $vars);

$body = elgg_view_layout('content', array(
	'filter' => '',
	'content' => $content,
	'title' => $title,
));

echo elgg_view_page($title, $body);