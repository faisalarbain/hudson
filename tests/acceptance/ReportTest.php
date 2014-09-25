<?php


use Guzzle\Http\Client;
use TM\Hudson\Browser;
use TM\Hudson\Report;

/**
 * Class ReportTest
 *
 * to run test, your need to pass env variable URL
 * eg:
 * URL='http://localhost' phpunit
 */
class ReportTest extends PHPUnit_Framework_TestCase {
	/** @test */
	public function can_create_instance()
	{
		$this->assertInstanceOf('TM\Hudson\Report', $this->getHudson());
	}

	/** @test */
	public function can_download_and_specify_location()
	{
		$file = $this->getHudson()->download('BTUPortOrderCreated','tmwholesale', 'tmwholesale123', 'foo.xls');
		$this->assertFileExists("foo.xls");

		@unlink($file);
	}

	/** @test */
	public function can_download_to_temp_folder()
	{
		$file = $this->getHudson()->download('BTUPortOrderCreated','tmwholesale', 'tmwholesale123');
		$this->assertFileExists($file);
		$this->assertContains("/tmp/", $file);

		@unlink($file);
	}
	
	/** @test */
	public function can_get_last_success_build()
	{
		$this->assertInstanceOf('TM\Hudson\Build',$this->getHudson()->getLastSuccessBuild('BTUPortOrderCreated','tmwholesale', 'tmwholesale123'));
	}
	
	/**
	 * @return Report
	 */
	private function getHudson()
	{
		$report = new Report(new Browser(new Client()));
		$report->setBaseUrl(getenv('URL'));

		return $report;
	}


}
 