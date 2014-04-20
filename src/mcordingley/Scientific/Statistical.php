<?php

namespace mcordingley\Scientific;

class Statistical {
	/**
	 * Sum Function
	 * 
	 * Sums an array of numeric values.  Non-numeric values
	 * are treated as zeroes.
	 * 
	 * @param array $data An array of numeric values
	 * @return float The sum of the elements of the array
	 * @static
	 */
	public static function sum(array $data) {
		$sum = 0;
        
		foreach ($data as $element) {
			if (is_numeric($element)) {
                $sum += $element;
            }
		}
        
		return $sum;
	}

	/**
	 * Product Function
	 * 
	 * Multiplies an array of numeric values.  Non-numeric values
	 * are treated as ones.
	 * 
	 * @param array $data An array of numeric values
	 * @return float The product of the elements of the array
	 * @static
	 */
	public static function product(array $data) {
		$product = 1;
        
		foreach ($data as $element) {
			if (is_numeric($element)) {
                $product *= $element;
            }
		}
        
		return $product;
	}

	/**
	 * Average Function
	 * 
	 * Takes the arithmetic mean of an array of numeric values.
	 * Non-numeric values are treated as zeroes.
	 * 
	 * @param array $data An array of numeric values
	 * @return float The arithmetic average of the elements of the array
	 * @static
	 */
	public static function average(array $data) {
		return self::sum($data) / count($data);
	}

	/**
	 * Geometric Average Function
	 * 
	 * Takes the geometic mean of an array of numeric values.
	 * Non-numeric values are treated as ones.
	 * 
	 * @param array $data An array of numeric values
	 * @return float The geometic average of the elements of the array
	 * @static
	 */
	public static function gaverage(array $data) {
		return pow(self::product($data), 1 / count($data));
	}

	/**
	 * Sum-Squared Function
	 * 
	 * Returns the sum of squares of an array of numeric values.
	 * Non-numeric values are treated as zeroes.
	 * 
	 * @param array $data An array of numeric values
	 * @return float The arithmetic average of the elements of the array
	 * @static
	 */
	public static function sumsquared(array $data) {
		$sum = 0;
        
		foreach ($data as $element) {
			if (is_numeric($element)) {
                $sum += pow($element, 2);
            }
		}
        
		return $sum;
	}


	/**
	 * Sum-XY Function
	 * 
	 * Returns the sum of products of paired variables in a pair of arrays
	 * of numeric values.  The two arrays must be of equal length.
	 * Non-numeric values are treated as zeroes.
	 * 
	 * @param array $datax An array of numeric values
	 * @param array $datay An array of numeric values
	 * @return float The products of the paired elements of the arrays
	 * @static
	 */
	public static function sumXY(array $datax, array $datay) {
		$n = min(count($datax), count($datay));
		$sum = 0.0;
        
		for ($count = 0; $count < $n; $count++) {
			if (is_numeric($datax[$count])) {
                $x = $datax[$count];
            }
			else {
                //Non-numeric elements count as zero.
                $x = 0;
            }

			if (is_numeric($datay[$count])) {
                $y = $datay[$count];
            }
			else {
                //Non-numeric elements count as zero.
                $y = 0;
            }

			$sum += $x * $y;
		}
        
		return $sum;
	}

	/**
	 * Sum-Squared Error Function
	 * 
	 * Returns the sum of squares of errors of an array of numeric values.
	 * Non-numeric values are treated as zeroes.
	 * 
	 * @param array $data An array of numeric values
	 * @return float The sum of the squared errors of the elements of the array
	 * @static
	 */
	public static function sse(array $data) {
		$average = self::average($data);
		$sum = 0.0;
        
		foreach ($data as $element) {
			if (is_numeric($element)) {
                $sum += pow($element - $average, 2);
            }
			else {
                $sum += pow(0 - $average, 2);
            }
		}
        
		return $sum;
	}

	/**
	 * Mean-Squared Error Function
	 * 
	 * Returns the arithmetic mean of squares of errors of an array
	 * of numeric values. Non-numeric values are treated as zeroes.
	 * 
	 * @param array $data An array of numeric values
	 * @return float The average squared error of the elements of the array
	 * @static
	 */
	public static function mse(array $data) {
		return self::sse($data) / count($data);
	}

	/**
	 * Covariance Function
	 * 
	 * Returns the covariance of two arrays.  The two arrays must
	 * be of equal length. Non-numeric values are treated as zeroes.
	 * 
	 * @param array $datax An array of numeric values
	 * @param array $datay An array of numeric values
	 * @return float The covariance of the two supplied arrays
	 * @static
	 */
	public static function covariance(array $datax, array $datay) {
		return self::sumXY($datax, $datay) / count($datax) - self::average($datax) * self::average($datay);
	}

	/**
	 * Variance Function
	 * 
	 * Returns the population variance of an array.
	 * Non-numeric values are treated as zeroes.
	 * 
	 * @param array $data An array of numeric values
	 * @return float The variance of the supplied array
	 * @static
	 */
	public static function variance(array $data) {
		return self::covariance($data, $data);
	}

	/**
	 * Standard Deviation Function
	 * 
	 * Returns the population standard deviation of an array.
	 * Non-numeric values are treated as zeroes.
	 * 
	 * @param array $data An array of numeric values
	 * @return float The population standard deviation of the supplied array
	 * @static
	 */
	public static function stddev(array $data) {
		return sqrt(self::variance($data));
	}

	/**
	 * Sample Standard Deviation Function
	 * 
	 * Returns the sample (unbiased) standard deviation of an array.
	 * Non-numeric values are treated as zeroes.
	 * 
	 * @param array $data An array of numeric values
	 * @return float The unbiased standard deviation of the supplied array
	 * @static
	 */
	public static function sampleStddev(array $data) {
		return sqrt(self::sse($data) / (count($data) - 1));
	}

	/**
	 * Correlation Function
	 * 
	 * Returns the correlation of two arrays.  The two arrays must
	 * be of equal length. Non-numeric values are treated as zeroes.
	 * 
	 * @param array $datax An array of numeric values
	 * @param array $datay An array of numeric values
	 * @return float The correlation of the two supplied arrays
	 * @static
	 */
	public static function correlation($datax, $datay) {
		return self::covariance($datax, $datay) / (self::stddev($datax) * self::stddev($datay));
	}
}
