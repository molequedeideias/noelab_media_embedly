<?php
/**
 * medias helper functions
 *
 * @package medias
 */

/**
 * Prepare the add/edit form variables
 *
 * @param ElggObject $media A media object.
 * @return array
 */
function medias_prepare_form_vars($media = null) {
	// input names => defaults
	$values = array(
		'title' => get_input('title', ''), // medialet support
		'address' => get_input('address', ''),
		'description' => '',
		'access_id' => ACCESS_DEFAULT,
		'tags' => '',
		'shares' => array(),
		'container_guid' => elgg_get_page_owner_guid(),
		'guid' => null,
		'entity' => $media,
	);

	if ($media) {
		foreach (array_keys($values) as $field) {
			if (isset($media->$field)) {
				$values[$field] = $media->$field;
			}
		}
	}

	if (elgg_is_sticky_form('medias')) {
		$sticky_values = elgg_get_sticky_values('medias');
		foreach ($sticky_values as $key => $value) {
			$values[$key] = $value;
		}
	}

	elgg_clear_sticky_form('medias');

	return $values;
}
