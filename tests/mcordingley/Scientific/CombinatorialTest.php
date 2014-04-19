<?php

namespace mcordingley\Scientific;

class NumericalTest extends PHPUnit_Framework_TestCase {
    public function test_factorial() {
		$this->assertEquals(1, Combinatorial::factorial(0));
		$this->assertEquals(1, Combinatorial::factorial(1));
		$this->assertEquals(2, Combinatorial::factorial(2));
		$this->assertEquals(120, Combinatorial::factorial(5));
		$this->assertEquals(3628800, Combinatorial::factorial(10));
	}

	public function test_permutations() {
		$this->assertEquals(1, Combinatorial::permutations(1, 1));
		$this->assertEquals(2, Combinatorial::permutations(2, 1));
		$this->assertEquals(12, Combinatorial::permutations(4, 2));
		$this->assertEquals(120, Combinatorial::permutations(5, 5));
		$this->assertEquals(6720, Combinatorial::permutations(8, 5));
	}

	public function test_combinations() {
		$this->assertEquals(1, Combinatorial::combinations(1, 1));
		$this->assertEquals(2, Combinatorial::combinations(2, 1));
		$this->assertEquals(6, Combinatorial::combinations(4, 2));
		$this->assertEquals(1, Combinatorial::combinations(5, 5));
		$this->assertEquals(56, Combinatorial::combinations(8, 5));
	}
}