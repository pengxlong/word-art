<?php

namespace Sun;

class Config
{
	public $_config;

	public function __construct()
	{
		$this->_config = require_once __DIR__.'/../config/command.php';
	}

	public function get($key)
	{
		return isset($this->_config[$key]) ? $this->_config[$key] : null;
	}
}