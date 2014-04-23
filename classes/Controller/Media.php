<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Media extends Controller {

	public $config;

	public function before()
	{
		parent::before();

		$this->config = Kohana::$config->load('media');
	}

	public function action_serve()
	{
		$filepath = $this->request->param('filepath');
		$uid = $this->request->param('uid');

		$cfs_file = Kohana::find_file('media', $filepath, FALSE);

		if ( ! $cfs_file)
			throw HTTP_Exception::factory(404);

		// http://www.webscalingblog.com/performance/caching-http-headers-last-modified-and-etag.html
		// Required header when using Last-Modified caching
		$this->response->headers('Cache-Control', 'must-revalidate');

		$date = date('r', filemtime($cfs_file));

		if ($this->request->headers('If-Modified-Since') === $date)
		{
			// Matching modified date, abort the request
			$this->response->status(304);
			return;
		}

		// Load the file content for caching and output
		$contents = file_get_contents($cfs_file);

		if ($this->config->cache)
		{
			// Save the contents to the public directory for future requests
			$public = strtr($this->config->public_dir, array(
				'<uid>/'    => $uid ? $uid.'/' : '',
				'<filepath>' => $filepath,
			));
			$directory = dirname($public);

			if ( ! is_dir($directory))
			{
				// Recursively create the directories needed for the file
				mkdir($directory.'/', 0777, TRUE);
			}

			file_put_contents($public, $contents);

			// We just updated the file, make sure the proper date is sent
			$date = date('r');
		}

		$mime = File::mime_by_ext(pathinfo($cfs_file, PATHINFO_EXTENSION));
		$size = filesize($cfs_file);

		// Set the proper headers to allow caching
		$this->response->headers('Last-Modified', $date);
		$this->response->headers('Content-Type', (string) $mime);
		$this->response->headers('Content-Length', (string) $size);

		// Send the file content as the response
		$this->response->body($contents);
	}
}


