<?php
/**
 * New medias river entry
 *
 * @package medias
 */

$object = $vars['item']->getObjectEntity();
$excerpt = elgg_get_excerpt($object->description);

echo elgg_view('river/item', array(
	'item' => $vars['item'],
	'message' => $excerpt,
	//'attachments' => elgg_view('output/url', array('href' => $object->address)),
));
