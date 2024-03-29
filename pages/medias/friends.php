<?php
/**
 * Elgg medias plugin friends page
 *
 * @package Elggmedias
 */

$owner = elgg_get_page_owner_entity();

elgg_push_breadcrumb($owner->name, "medias/owner/$owner->username");
elgg_push_breadcrumb(elgg_echo('friends'));

elgg_register_title_button();

$title = elgg_echo('medias:friends');

$content = list_user_friends_objects($owner->guid, 'medias', 10, false);
if (!$content) {
	$content = elgg_echo('medias:none');
}

$params = array(
	'filter_context' => 'friends',
	'content' => $content,
	'title' => $title,
);

$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);