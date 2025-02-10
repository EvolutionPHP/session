<?php
/**
 * @package EvolutionScript
 * @author: EvolutionScript S.A.C.
 * @Copyright (c) 2010 - 2020, EvolutionScript.com
 * @link http://www.evolutionscript.com
 */

namespace EvolutionPHP\Session;

use EvolutionPHP\HTTP\Request;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\NativeFileSessionHandler;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;

class Session
{
	private $session;
	private $config = [
		'name' => 'PHPSESSID',
		'expiration' => 120, //Number of minutes, set 0 means expire when the browser is closed.
		'save_path' => null //If it is null, save_path directory will be taken form php.ini
	];

	public function set_config(array $config)
	{
		foreach ($this->config as $key => $value) {
			if(isset($config[$key])) {
				$this->config[$key] = $config[$key];
			}
		}
	}

	public function start($config = array())
	{
		if(count($config) > 0) {
			$this->set_config($config);
		}
		$sessionStorage = new NativeSessionStorage();
		if(isset($this->config['save_path'])) {
			$sessionStorage->setSaveHandler(new NativeFileSessionHandler($this->config['save_path']));
		}
		$sessionStorage->setName($this->config['name']);
		$sessionStorage->setOptions([
			'cookie_lifetime' => $this->config['expiration']*60,

		]);
		$this->session = new \Symfony\Component\HttpFoundation\Session\Session($sessionStorage, new AttributeBag());
		$request = Request::init();
		if($request->is_cli()){
			return;
		}
		$this->session->start();
	}



	/**
	 * @param $key
	 * @return bool
	 */
	public function has($key)
	{
		return $this->session->has($key);
	}

	/**
	 * @param null $key
	 * @return mixed
	 */
	public function get($key=NULL)
	{
		return $this->session->get($key);
	}

	/**
	 * @param $key
	 * @param null $value
	 */
	public function set($key, $value=NULL)
	{
		if(is_array($key)){
			foreach ($key as $k => $v){
				$this->session->set($k, $v);
			}
		}else{
			$this->session->set($key, $value);
		}
	}

	/**
	 * @param $key
	 */
	public function remove($key)
	{
		if(is_array($key)){
			foreach ($key as $item){
				$this->session->remove($item);
			}
		}else{
			$this->session->remove($key);
		}
	}


	/**
	 * @param $key
	 * @param false $remove
	 * @return bool
	 */
	public function hasFlash($key, $remove=false)
	{
		$result = $this->session->getFlashBag()->has($key);
		if($remove){
			$this->session->getFlashBag()->clear();
		}
		return $result;
	}

	/**
	 * @param $key
	 * @return mixed|null
	 */
	public function getFlash($key){
		$result = $this->session->getFlashBag()->get($key);
		if(count($result) > 0){
			return $result[0];
		}else{
			return null;
		}
	}

	/**
	 * @param $key
	 * @param $value
	 */
	public function setFlash($key, $value)
	{
		$this->session->getFlashBag()->set($key, $value);
	}



	public function clear()
	{
		$this->session->clear();
	}

	public function destroy()
	{
		$this->session->invalidate();
	}
}