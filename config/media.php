<?php defined('SYSPATH') or die('No direct script access.');

return array(
	'route' => 'media/ko/<file>(<sep><hash>).<ext>',
	'regex' => array(
		/**
		 * Pattern to match the file path (without extension)
		 * This pattern will match any file path until one of the following:
		 * - an extension is found
		 * - a forward slash is found, followed by a version number (#.#.#) where # is one or more digits
		 */
		'file' => '(.*?)((?=(\.([a-zA-Z0-9]+)$))|(?=\/(?=([0-9]+\.){3})))',
		// Match the separator between file and hash
		'sep'  => '([\/])(?=([0-9]+\.){3})',
		// Match the hash that is not part of the media file
		'hash' => '([a-zA-Z0-9\.])+(?=[\.][a-zA-Z0-9]+$)',
		// Match the file extension (without the dot)
		'ext'  => '([a-zA-Z0-9]+)$',
	),
	// The public accessible directory
	'public_dir' => DOCROOT.'media/ko',
	// Write the files to the public directory when in production
	'cache'      => Kohana::$environment === Kohana::PRODUCTION,
);
