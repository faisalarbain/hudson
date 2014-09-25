<?php
namespace TM\Hudson;


use Carbon\Carbon;
use Guzzle\Http\Client;

class Report
{
	/**
	 * @var Browser
	 */
	private $browser;

	function __construct(Browser $browser)
	{
		$this->browser = $browser;
	}

	public function setBaseUrl($url){
		$this->browser->setBaseUrl($url);
	}

	public function download($report_name, $username, $password, $to = NULL)
	{
		$build = $this->getLastSuccessBuild($report_name, $username, $password);
		$to = $to?:tempnam('/tmp', "hudson-");

		$this->browser->download($build->getFile(), $to, $username, $password);

		return $to;
	}


	public function buildNow($report_name, $login, $password)
	{
		if (!$this->getCurrentBuildingProgress($report_name, $login, $password)) {
			$this->browser->build($report_name, $login, $password);
		}
	}

	public function getCurrentBuildingProgress($report_name, $login, $password)
	{
		$body = $this->browser->lastBuild($report_name, $login, $password);
		return Progress::make($body);
	}

	public function getLastSuccessBuild($report_name, $login, $password)
	{

		$body = $this->browser->lastSuccessBuild($report_name, $login, $password);
		return Build::make($report_name, $body);

	}


} 