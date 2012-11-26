<?php
/**
 * medias English language file
 */

$english = array(

	/**
	 * Menu items and titles
	 */
	'medias' => "Medias",
	'medias:add' => "Add Media",
	'medias:edit' => "Edit Media",
	'medias:owner' => "%s's Medias",
	'medias:friends' => "Friends' Medias",
	'medias:everyone' => "All site Medias",
	'medias:this:group' => "Media in %s",
	'medias:inbox' => "Medias inbox",
	'medias:moremedias' => "More Medias",
	'medias:more' => "More",
	'medias:with' => "Share with",
	'medias:new' => "A new media",
	'medias:via' => "via medias",
	'medias:address' => "Address of the resource to media",
	'medias:none' => 'No medias',

	'medias:delete:confirm' => "Are you sure you want to delete this resource?",

	'medias:numbertodisplay' => 'Number of medias to display',

	'medias:shared' => "shared",
	'medias:visit' => "Visit resource",
	'medias:recent' => "Recent medias",

	'river:create:object:medias' => '%s shared %s',
	'river:comment:object:medias' => '%s commented on a media %s',
	'medias:river:annotate' => 'a comment on this media',
	'medias:river:item' => 'an item',

	'item:object:medias' => 'medias',

	'medias:group' => 'Group medias',
	'medias:enablemedias' => 'Enable group medias',
	'medias:nogroup' => 'This group does not have any medias yet',
	'medias:more' => 'More medias',

	'medias:no_title' => 'No title',

	/**
	 * Widget 
	 */
	'medias:widget:description' => "Display your latest medias.",

	
	/**
	 * Status messages
	 */

	'medias:save:success' => "Your item was successfully posted.",
	'medias:delete:success' => "Your media item was successfully deleted.",

	/**
	 * Error messages
	 */

	'medias:save:failed' => "Your media could not be saved. Make sure you've entered a title and address and then try again.",
	'medias:delete:failed' => "Your media could not be deleted. Please try again.",
	
	/**
    * Oembed Metadata 
    */	
            
    'mediaembedly:aboutitem' => "About this Media",
	'mediaembedly:mediaauthor' => 'Published by',
);

add_translation('en', $english);