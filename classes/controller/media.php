<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Media extends Controller {

	public $config;

	public function before()
	{
		parent::before();

		$this->config = Kohana::config('media');
	}

	public function action_serve()
	{
		$file = $this->request->param('file');
		$sep = $this->request->param('sep');
		$uid = $this->request->param('uid');
		$ext = $this->request->param('ext');

		$cfs_file = Kohana::find_file('media', $file, $ext);

		if ( ! $cfs_file)
			throw new Kohana_Http_Exception_404;

		// Send the file content as the response
		$this->response->body(file_get_contents($cfs_file));

		if ($this->config->cache)
		{
			// Save the contents to the public directory for future requests
			$public = $this->config->public_dir.'/'.$file.$sep.$uid.'.'.$ext;
			$directory = dirname($public);

			if ( ! is_dir($directory))
			{
				// Recursively create the directories needed for the file
				mkdir($directory.'/', 0777, TRUE);
			}

			file_put_contents($public, $this->request->response);
		}

		// Set the proper headers to allow caching
		$this->response->headers('Content-Type', (string) File::mime_by_ext($ext));
		$this->response->headers('Content-Length', (string) filesize($cfs_file));
		$this->response->headers('Last-Modified', (string) date('r', filemtime($cfs_file)));
	}
}
