<?php defined('SYSPATH') or die('No direct script access.');

Route::set('media', 'media/ko/<file>', array('file' => '.*'))
	->defaults(array(
		'controller' => 'media',
		'action'     => 'serve',
	));
