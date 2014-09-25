<?php


namespace TM\Hudson;


class Progress
{

	private $min;
	private $sec;

	function __construct($min, $sec)
	{
		$this->min = $min;
		$this->sec = $sec;
	}

	public static function make($body) {
		if (preg_match_all('/Estimated remaining time: ([^"]+)/i', $body, $remaining_time)) {
			$time = isset($remaining_time[1][0]) ? $remaining_time[1][0] : "N/A";
			if ($time == "N/A") {
				return new Progress(5, 0); //default check after 5 minutes
			}

			$hour = self::getDuration($time, "hr");
			$min = self::getDuration($time, "min") + ($hour * 60);
			$sec = self::getDuration($time, "sec");

			return new self($min, $sec);

		}


		return false;
	}

	private static function getDuration($time, $unit)
	{
		if (preg_match("/([0-9]+) $unit/i", $time, $matches)) {
			$matches = $matches[1];
			return (int)$matches;
		} else {
			return 0;
		}
	}

	public function toSeconds()
	{
		return ($this->min * 60) + ($this->sec);
	}

	function __toString()
	{
		return "$this->min min $this->sec sec";
	}


}