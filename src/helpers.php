<?php

use Sun\Config;

if (!function_exists('config')) {
	/**
	 * Get config by key
	 * 
	 * @param  string $key
	 * @return mixed
	 */
	function config($key = null)
	{
		if (null === $key) {
			return null;
		}

		$config = new Config();
		return $config->get($key);
	}
}