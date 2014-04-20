<?php

namespace mcordingley\Scientific;

class StatisticalTest extends \PHPUnit_Framework_TestCase {
	private $datax;
	private $datay;
	private $dataz;

	function __construct() {
		$this->datax = array(1, 2, 3, 4, 5);
		$this->datay = array(10, 11, 12, 13, 14);
		$this->dataz = array(28.8, 27.1, 42.4, 53.5, 90);
	}

	public function test_sum() {
		$this->assertEquals(15, Statistical::sum($this->datax));
		$this->assertEquals(60, Statistical::sum($this->datay));
		$this->assertEquals(241.8, Statistical::sum($this->dataz));
	}

	public function test_sumProduct() {
		$this->assertEquals(120, Statistical::sumProduct($this->datax));
		$this->assertEquals(240240, Statistical::sumProduct($this->datay));
		$this->assertEquals(159339674.88, Statistical::sumProduct($this->dataz));
	}

	public function test_average() {
		$this->assertEquals(3, Statistical::average($this->datax));
		$this->assertEquals(12, Statistical::average($this->datay));
		$this->assertEquals(48.36, Statistical::average($this->dataz));
	}

	public function test_gaverage() {
		$this->assertEquals(2.6051710846973521, Statistical::gaverage($this->datax));
		$this->assertEquals(11.915960744952384, Statistical::gaverage($this->datay));
		$this->assertEquals(43.698324495373498, Statistical::gaverage($this->dataz));
	}

	public function test_sumSquared() {
		$this->assertEquals(55, Statistical::sumSquared($this->datax));
		$this->assertEquals(730, Statistical::sumSquared($this->datay));
		$this->assertEquals(14323.86, Statistical::sumSquared($this->dataz));
	}

	public function test_sumXY() {
		$this->assertEquals(190, Statistical::sumXY($this->datax, $this->datay));
		$this->assertEquals(3050.4, Statistical::sumXY($this->dataz, $this->datay));
	}

	public function test_sse() {
		$this->assertEquals(10, Statistical::sse($this->datax));
		$this->assertEquals(10, Statistical::sse($this->datay));
		$this->assertEquals(2630.412, Statistical::sse($this->dataz));
	}

	public function test_mse() {
		$this->assertEquals(2, Statistical::mse($this->datax));
		$this->assertEquals(2, Statistical::mse($this->datay));
		$this->assertEquals(526.0824, Statistical::mse($this->dataz));
	}

	public function test_covariance() {
		$this->assertEquals(2, Statistical::covariance($this->datax, $this->datay));
		$this->assertEquals(29.76, Statistical::covariance($this->dataz, $this->datay));
	}

	public function test_variance() {
		$this->assertEquals(2, Statistical::variance($this->datax));
		$this->assertEquals(2, Statistical::variance($this->datay));
		$this->assertEquals(526.0824, Statistical::variance($this->dataz));
	}

	public function test_stdDev() {
		$this->assertEquals(1.4142135623730951, Statistical::stdDev($this->datax));
		$this->assertEquals(1.4142135623730951, Statistical::stdDev($this->datay));
		$this->assertEquals(22.936486217378629, Statistical::stdDev($this->dataz));
	}

	public function test_sampleStdDev() {
		$this->assertEquals(1.5811388300841898, Statistical::sampleStdDev($this->datax));
		$this->assertEquals(1.5811388300841898, Statistical::sampleStdDev($this->datay));
		$this->assertEquals(25.643771173522818, Statistical::sampleStdDev($this->dataz));
	}

	public function test_correlation() {
		$this->assertEquals(1.0, Statistical::correlation($this->datax, $this->datay));
		$this->assertEquals(0.91746824725782905, Statistical::correlation($this->dataz, $this->datay));
    }
}