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
	protected $report_name;
	protected $login;
	protected $password;
	protected $URL;

	public function setUp()
	{
		date_default_timezone_set('asia/kuala_lumpur');
		$this->URL = getenv('URL');
		$this->report_name = getenv('REPORT');
		$this->login = getenv('LOGIN');
		$this->password = getenv('PASSWORD');
	}

	/** @test */
	public function can_create_instance()
	{
		$this->assertInstanceOf('TM\Hudson\Report', $this->getHudson());
	}

	/**
	 * @return Report
	 */
	private function getHudson()
	{
		$report = new Report(new Browser(new Client()));

		$report->setBaseUrl($this->URL);

		return $report;
	}

	/** @test */
	public function can_download_and_specify_location()
	{
		$file = $this->getHudson()->download($this->report_name, $this->login, $this->password, 'foo.xls');
		$this->assertFileExists("foo.xls");

		@unlink($file);
	}

	/** @test */
	public function can_download_to_temp_folder()
	{
		$file = $this->getHudson()->download($this->report_name, $this->login, $this->password);
		$this->assertFileExists($file);
		$this->assertContains("/tmp/", $file);

		@unlink($file);
	}
	
	/** @test */
	public function can_get_last_success_build()
	{
		$this->assertInstanceOf('TM\Hudson\Build', $this->getHudson()->getLastSuccessBuild($this->report_name, $this->login, $this->password));
	}


}
 