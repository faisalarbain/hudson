<?php


namespace TM\Hudson;


use Carbon\Carbon;

class Build
{


	public $url;
	public $published;
	public $title;
	public $buildNo;
	protected $file;
	private $report_name;

	function __construct($report_name, $title, Carbon $published, $file)
	{
		$this->title = $title;
		$this->published = $published;
		preg_match('/\#([0-9]+)/', $title, $buildNo);
		$this->buildNo = $buildNo[1];
		$this->url = "/job/{$report_name}/{$this->buildNo}/";
		$this->report_name = $report_name;
		$this->file = $file;
	}

	public function isToday()
	{
		return $this->published->isToday();
	}

	public function getFile()
	{
		return $this->file;
	}

	public static function make($report_name, $body){
		//get report title
		preg_match('/\<h1\>([\s\S]+)\<\/h1\>/i', $body, $matches);
		preg_match('/Build #[0-9]+/', $matches[0], $builds);
		$title = $builds[0];

		//get report date
		preg_match('/\(([^\)]+)\)/', $matches[0], $dates);
		$date = $dates[1];

		//get report URL
		preg_match_all('/<a href="(artifact[^"]+)/', $body, $files);
		if(isset($files[1][1]) && $filename = $files[1][1]){
			$file = "/job/{$report_name}/lastSuccessfulBuild/$filename";
		}else{
			$file = NULL;
		}

		return new self($report_name, $title, new Carbon($date), $file);
	}


}