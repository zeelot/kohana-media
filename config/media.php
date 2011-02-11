<?php defined('SYSPATH') or die('No direct script access.');

return array(
	'route' => 'media/(<uid>/)kohana/<filepath>',
	'regex' => array(
		/**
		 * Pattern to match the file path
		 */
		'filepath' => '.*',
		// Match the unique string that is not part of the media file
		'uid' => '.*?',
	),
	// The public accessible directory where the file will be copied
	'public_dir' => DOCROOT.'media/<uid>/kohana/<filepath>',
	// Write the files to the public directory when in production
	'cache'      => Kohana::$environment === Kohana::PRODUCTION,
);
