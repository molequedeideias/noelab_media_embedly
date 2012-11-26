<?php
/**
 * Elgg medias plugin
 *
 * @package Elggmedias
 */

elgg_register_event_handler('init', 'system', 'medias_init');

/**
 * media init
 */
function medias_init() {

	$root = dirname(__FILE__);
	elgg_register_library('elgg:medias', "$root/lib/medias.php");

	// actions
	$action_path = "$root/actions/medias";
	elgg_register_action('medias/save', "$action_path/save.php");
	elgg_register_action('medias/delete', "$action_path/delete.php");
	elgg_register_action('medias/share', "$action_path/share.php");

	// menus
	elgg_register_menu_item('site', array(
		'name' => 'medias',
		'text' => elgg_echo('medias'),
		'href' => 'medias/all'
	));

	elgg_register_plugin_hook_handler('register', 'menu:page', 'medias_page_menu');
	elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'medias_owner_block_menu');

	elgg_register_page_handler('medias', 'medias_page_handler');

	elgg_extend_view('css/elgg', 'medias/css');
	elgg_extend_view('js/elgg', 'medias/js');

	elgg_register_widget_type('medias', elgg_echo('medias'), elgg_echo('medias:widget:description'));

	// Register granular notification for this type
	register_notification_object('object', 'medias', elgg_echo('medias:new'));

	// Listen to notification events and supply a more useful message
	elgg_register_plugin_hook_handler('notify:entity:message', 'object', 'medias_notify_message');

	// Register a URL handler for medias
	elgg_register_entity_url_handler('object', 'medias', 'media_url');

	// Register entity type for search
	elgg_register_entity_type('object', 'medias');

	// Groups
	add_group_tool_option('medias', elgg_echo('medias:enablemedias'), true);
	elgg_extend_view('groups/tool_latest', 'medias/group_module');
	
	//noelab embedly
	$jquery_embedly_url = '//scripts.embed.ly/jquery.embedly.min.js';
    elgg_register_js('jquery_embedly', $jquery_embedly_url);
	//elgg_load_js('jquery_embedly');
	
}

/**
 * Dispatcher for medias.
 *
 * URLs take the form of
 *  All medias:        medias/all
 *  User's medias:     medias/owner/<username>
 *  Friends' medias:   medias/friends/<username>
 *  View media:        medias/view/<guid>/<title>
 *  New media:         medias/add/<guid> (container: user, group, parent)
 *  Edit media:        medias/edit/<guid>
 *  Group medias:      medias/group/<guid>/all
 *  medialet:          medias/medialet/<guid> (user)
 *
 * Title is ignored
 *
 * @param array $page
 */
function medias_page_handler($page) {
	elgg_load_library('elgg:medias');

	elgg_push_breadcrumb(elgg_echo('medias'), 'medias/all');

	// old group usernames
	if (substr_count($page[0], 'group:')) {
		preg_match('/group\:([0-9]+)/i', $page[0], $matches);
		$guid = $matches[1];
		if ($entity = get_entity($guid)) {
			medias_url_forwarder($page);
		}
	}

	// user usernames
	$user = get_user_by_username($page[0]);
	if ($user) {
		medias_url_forwarder($page);
	}

	$pages = dirname(__FILE__) . '/pages/medias';

	switch ($page[0]) {
		case "all":
			include "$pages/all.php";
			break;

		case "owner":
			include "$pages/owner.php";
			break;

		case "friends":
			include "$pages/friends.php";
			break;

		case "read":
		case "view":
			set_input('guid', $page[1]);
			include "$pages/view.php";
			break;

		case "add":
			gatekeeper();
			include "$pages/add.php";
			break;

		case "edit":
			gatekeeper();
			set_input('guid', $page[1]);
			include "$pages/edit.php";
			break;

		case 'group':
			group_gatekeeper();
			include "$pages/owner.php";
			break;
		
		//tests
		case "test":
			include "$pages/test.php";
			break;

		default:
			return false;
	}

	elgg_pop_context();

	return true;
}

/**
 * Forward to the new style of URLs
 *
 * @param string $page
 */
function medias_url_forwarder($page) {
	global $CONFIG;

	if (!isset($page[1])) {
		$page[1] = 'items';
	}

	switch ($page[1]) {
		case "read":
			$url = "{$CONFIG->wwwroot}medias/view/{$page[2]}/{$page[3]}";
			break;
		case "inbox":
			$url = "{$CONFIG->wwwroot}medias/inbox/{$page[0]}";
			break;
		case "friends":
			$url = "{$CONFIG->wwwroot}medias/friends/{$page[0]}";
			break;
		case "add":
			$url = "{$CONFIG->wwwroot}medias/add/{$page[0]}";
			break;
		case "items":
			$url = "{$CONFIG->wwwroot}medias/owner/{$page[0]}";
			break;
		case "test":
			$url = "{$CONFIG->wwwroot}medias/test/{$page[0]}";
			break;
	}

	register_error(elgg_echo("changemedia"));
	forward($url);
}

/**
 * Populates the ->getUrl() method for mediaed objects
 *
 * @param ElggEntity $entity The mediaed object
 * @return string mediaed item URL
 */
function media_url($entity) {
	global $CONFIG;

	$title = $entity->title;
	$title = elgg_get_friendly_title($title);
	return $CONFIG->url . "medias/view/" . $entity->getGUID() . "/" . $title;
}

/**
 * Add a menu item to an ownerblock
 * 
 * @param string $hook
 * @param string $type
 * @param array  $return
 * @param array  $params
 */
function medias_owner_block_menu($hook, $type, $return, $params) {
	if (elgg_instanceof($params['entity'], 'user')) {
		$url = "medias/owner/{$params['entity']->username}";
		$item = new ElggMenuItem('medias', elgg_echo('medias'), $url);
		$return[] = $item;
	} else {
		if ($params['entity']->medias_enable != 'no') {
			$url = "medias/group/{$params['entity']->guid}/all";
			$item = new ElggMenuItem('medias', elgg_echo('medias:group'), $url);
			$return[] = $item;
		}
	}

	return $return;
}

/**
 * Returns the body of a notification message
 *
 * @param string $hook
 * @param string $entity_type
 * @param string $returnvalue
 * @param array  $params
 */
function medias_notify_message($hook, $entity_type, $returnvalue, $params) {
	$entity = $params['entity'];
	$to_entity = $params['to_entity'];
	$method = $params['method'];
	if (($entity instanceof ElggEntity) && ($entity->getSubtype() == 'medias')) {
		$descr = $entity->description;
		$title = $entity->title;
		global $CONFIG;
		$url = elgg_get_site_url() . "view/" . $entity->guid;
		if ($method == 'sms') {
			$owner = $entity->getOwnerEntity();
			return $owner->name . ' ' . elgg_echo("medias:via") . ': ' . $url . ' (' . $title . ')';
		}
		if ($method == 'email') {
			$owner = $entity->getOwnerEntity();
			return $owner->name . ' ' . elgg_echo("medias:via") . ': ' . $title . "\n\n" . $descr . "\n\n" . $entity->getURL();
		}
		if ($method == 'web') {
			$owner = $entity->getOwnerEntity();
			return $owner->name . ' ' . elgg_echo("medias:via") . ': ' . $title . "\n\n" . $descr . "\n\n" . $entity->getURL();
		}

	}
	return null;
}