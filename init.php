<?php defined('SYSPATH') or die('No direct script access.');

$config = Kohana::config('media');

Route::set('media', $config->route, $config->regex)
	->defaults(array(
		'controller' => 'media',
		'action'     => 'serve',
	));

unset($config);
