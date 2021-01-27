<?php defined('ABSPATH') || exit;

class JsonManifest {
	private $manifest;

	public function __construct($manifest_path) {
		if (file_exists($manifest_path)) {
			$this->manifest = json_decode(file_get_contents($manifest_path), true);
		} else {
			$this->manifest = [];
		}
	}

	public function get() {
		return $this->manifest;
	}

	public function getPath($key = '', $default = null) {
		$collection = $this->manifest;
		if (is_null($key)) {
			return $collection;
		}
		if (isset($collection[$key])) {
			return $collection[$key];
		}
		foreach (explode('.', $key) as $segment) {
			if (!isset($collection[$segment])) {
				return $default;
			} else {
				$collection = $collection[$segment];
			}
		}
		return $collection;
	}
}

function mbbn_asset_path($filename) {
	$dist_path = plugin_dir_path(MB_BREAKING_NEWS_FILE) . 'dist/';
	$dist_url  = plugin_dir_url(MB_BREAKING_NEWS_FILE) . 'dist/';
	$file      = basename($filename);
	static $manifest;

	if (empty($manifest)) {
		$manifest_path = $dist_path . 'assets.json';
		$manifest = new JsonManifest($manifest_path);
	}

	$files = $manifest->get();

	if (array_key_exists($filename, $files)) {

		return $dist_url  . $files[$filename];
	} else {
		return $dist_url . $filename;
	}
}
