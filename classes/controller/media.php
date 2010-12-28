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
		// Get the file path from the request
		$file = $this->request->param('file');

		// Find the file extension
		$ext = pathinfo($file, PATHINFO_EXTENSION);

		// Remove the extension from the filename
		$filename = substr($file, 0, -(strlen($ext) + 1));

		if ($path = Kohana::find_file($this->config->cfs_dir, $filename, $ext))
		{
			// Send the file content as the response
			$this->request->response = file_get_contents($path);

			// Save the contents to the public directory for future requests
			$public = $this->config->public_dir.'/'.$file;
			$directory = dirname($public);

			if ( ! is_dir($directory))
			{
				// Recursively create the directories needed for the file
				mkdir($directory.'/', 0777, TRUE);
			}

			file_put_contents($this->config->public_dir.'/'.$file, $this->request->response);
		}
		else
		{
			// Return a 404 status
			$this->request->status = 404;
		}

		// Set the proper headers to allow caching
		$this->request->headers['Content-Type']   = File::mime_by_ext($ext);
		$this->request->headers['Content-Length'] = filesize($path);
		$this->request->headers['Last-Modified']  = date('r', filemtime($path));
	}
}
