<?php
/**
 * Medias Tests
 *
 * @package medias
 */

//add admingatekeeper

$title = elgg_echo('medias:test');

$content = elgg_view('medias/test/embedly', $vars);

$body = elgg_view_layout('content', array(
	'filter' => '',
	'content' => $content,
	'title' => $title,
));

echo elgg_view_page($title, $body);