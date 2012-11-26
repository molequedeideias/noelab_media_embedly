<?php
/**
 * Delete a media
 *
 * @package medias
 */

$guid = get_input('guid');
$media = get_entity($guid);

if (elgg_instanceof($media, 'object', 'medias') && $media->canEdit()) {
	$container = $media->getContainerEntity();
	if ($media->delete()) {
		system_message(elgg_echo("medias:delete:success"));
		if (elgg_instanceof($container, 'group')) {
			forward("medias/group/$container->guid/all");
		} else {
			forward("medias/owner/$container->username");
		}
	}
}

register_error(elgg_echo("medias:delete:failed"));
forward(REFERER);
