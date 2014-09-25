<?php


namespace TM\Hudson;


use Guzzle\Http\Client;

class Browser
{

	/**
	 * @var Client
	 */
	private $client;

	function __construct(Client $client)
	{
		$this->client = $client;
	}

	public function setBaseUrl($base_url)
	{
		$this->client->setBaseUrl($base_url);
	}

	public function download($url, $to, $login, $password)
	{
		return $this->client->get($url)->setAuth($login, $password)->setResponseBody($to)->send();
	}

	public function build($report_name, $login, $password)
	{
		$url = "/job/{$report_name}/build?delay=0sec";
		$this->get($url, $login, $password);
	}

	private function get($url, $login, $password)
	{
		return $this->client->get($url)->setAuth($login, $password)->send()->getBody(true);
	}

	public function lastBuild($report_name, $login, $password)
	{
		$url = "/job/{$report_name}/lastBuild";
		return $this->get($url, $login, $password);
	}

	public function lastSuccessBuild($report_name, $login, $password)
	{
		$url = "/job/{$report_name}/lastSuccessfulBuild";
		return $this->get($url, $login, $password);
	}
}