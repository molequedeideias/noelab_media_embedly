<?php
/**
 * View a media
 *
 * @package Elggmedias
 */

$media = get_entity(get_input('guid'));

$page_owner = elgg_get_page_owner_entity();

$crumbs_title = $page_owner->name;

if (elgg_instanceof($page_owner, 'group')) {
	elgg_push_breadcrumb($crumbs_title, "medias/group/$page_owner->guid/all");
} else {
	elgg_push_breadcrumb($crumbs_title, "medias/owner/$page_owner->username");
}

$title = $media->title;

elgg_push_breadcrumb($title);

$content = elgg_view_entity($media, array('full_view' => true));
$content .= elgg_view_comments($media);

$body = elgg_view_layout('content', array(
	'content' => $content,
	'title' => $title,
	'filter' => '',
	'header' => '',
));

echo elgg_view_page($title, $body);
