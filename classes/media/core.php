<?php defined('SYSPATH') or die('No direct script access.');

class Media_Core {

	public static function url($filepath)
	{
		return Route::url('media', array(
			'filepath' => $filepath,
			'uid'      => Kohana::$config->load('media')->uid,
		));
	}
}