<?php

namespace mcordingley\Scientific;

class Numerical {
    // Non-instantiable
    private function __construct() {}
	
	/**
	 * erf
	 * 
	 * Returns the real error function of a number.
	 * An approximation from Abramowitz and Stegun is used.
	 * Maximum error is 1.5e-7. More information can be found at
	 * http://en.wikipedia.org/wiki/Error_function#Approximation_with_elementary_functions
	 * 
	 * @param float $x Argument to the real error function
	 * @return float A value between -1 and 1
	 * @static
	 */
	public static function erf($x) {
		if ($x < 0) {
            return -self::erf(-$x);
        }

		$t = 1 / (1 + 0.3275911 * $x);
		return 1 - (0.254829592 * $t - 0.284496736 * pow($t, 2) + 1.421413741 * pow($t, 3) + -1.453152027 * pow($t, 4) + 1.061405429 * pow($t, 5)) * exp(-pow($x, 2));
	}
	
	/**
	 * ierf
	 * 
	 * Returns the inverse real error function of a number.
	 * More information can be found at
	 * http://en.wikipedia.org/wiki/Error_function#Inverse_function
	 * 
	 * @param float $x Argument to the real error function
	 * @return float A value between -1 and 1
	 * @static
	 */
	public static function ierf($x) {
		//To increase accuracy, keep adding on terms from the series expansion.
		return 1/2 * pow(M_PI, 0.5) * ($x + M_PI * pow($x, 3) / 12 + 7*pow(M_PI, 2) * pow($x, 5) / 480 + 127*pow(M_PI, 3) * pow($x, 7) / 40320 + 4369 * pow(M_PI, 4) * pow($x, 9) / 5806080 + 34807 * pow(M_PI, 5) * pow($x, 11) / 182476800);
	}
	
	/**
	 * gamma
	 * 
	 * Returns the gamma function of a number.
	 * The gamma function is a generalization of the factorial function
	 * to non-integer and negative non-integer values. 
	 * The relationship is as follows: gamma(n) = (n - 1)!
	 * Stirling's approximation is used.  Though the actual gamma function
	 * is defined for negative, non-integer values, this approximation is
	 * undefined for anything less than or equal to zero.
	 * 
	 * @param float $x Argument to the gamma function
	 * @return float The gamma of $x
	 * @static
	 */
	public static function gamma($x) {
		//Lanczos' Approximation from Wikipedia
		
		// Coefficients used by the GNU Scientific Library
		$g = 7;
		$p = array(0.99999999999980993, 676.5203681218851, -1259.1392167224028,
			 771.32342877765313, -176.61502916214059, 12.507343278686905,
			 -0.13857109526572012, 9.9843695780195716e-6, 1.5056327351493116e-7);
		 
		// Reflection formula
		if ($x < 0.5) {
            return M_PI / (sin(M_PI*$x) * self::gamma(1 - $x));
        }
		else {
			$x--;
			$y = $p[0];
			
			for ($i = 1; $i < $g+2; $i++) {
                $y += $p[$i] / ($x + $i);
            }
			
			$t = $x + $g + 0.5;
			return pow(2 * M_PI, 0.5) * pow($t, $x + 0.5) * exp(-$t) * $y;
		}
	}

	/**
	 * gammaln
	 * 
	 * Returns the natural logarithm of the gamma function.  Useful for
	 * scaling.
	 * 
	 * @param float $x Argument to the gamma function
	 * @return float The natural log of gamma of $x
	 * @static
	 */
	public static function gammaln($x) {
		//Thanks to jStat for this one.
		$cof = array(76.18009172947146, -86.50532032941677, 24.01409824083091, -1.231739572450155, 0.1208650973866179e-2, -0.5395239384953e-5);

		$tmp = $x + 5.5;
		$tmp -= ($x + 0.5) * log($tmp);
		$ser = 1.000000000190015;
		$y = $x;
        
		for ($j = 0; $j < 6; $j++) {
            $ser += $cof[$j] / ++$y;
        }

		return log( 2.5066282746310005 * $ser / $x) - $tmp;
	}

	/**
	 * igamma
	 * 
	 * Returns the inverse of the gamma function.  The relative error of the
	 * principal branch peaks at 1.5 near the lower bound (i.e. igamma(0.885603))
	 * and approaches zero the higher the argument to this function.
	 * The secondary branch is not fully covered by the approximation and so
	 * will have much higher error.
	 * 
	 * @param float $x The result of the gamma function
	 * @param bool $principal True for the principal branch, false for the secondary (e.g. gamma(x) where x < 1.461632)
	 * @return float The argument to the gamma function
	 * @static
	 */
	public static function igamma($x, $principal = true) {
		//Source: http://mathforum.org/kb/message.jspa?messageID=342551&tstart=0
		
		if ($x < 0.885603) {
            return NAN;
        }
		
        // gamma(1.461632) == 0.885603, the positive minimum of gamma
		//$k = 1.461632;
		$c = 0.036534; //pow(2 * M_PI, 0.5) / M_E - self::gamma($k);
		$lx = log(($x + $c) / 2.506628274631); //pow(2 * M_PI, 0.5)); == 2.506628274631
        
		return $lx / self::lambert($lx / M_E, $principal) + 0.5;
	}

	/**
	 * digamma
	 * 
	 * Returns the digamma function of a number
	 * 
	 * @param float $x Argument to the digamma function
	 * @return The result of the digamma function
	 * @static
	 */
	public static function digamma($x) {
		//Algorithm translated from http://www.uv.es/~bernardo/1976AppStatist.pdf
		$s = 1.0e-5;
		$c = 8.5;
		$s3 = 8.33333333e-2;
		$s4 = 8.33333333e-3;
		$s5 = 3.968253968e-2;
		$d1 = -0.5772156649;

		$y = $x;
		$retval = 0;

		if ($y <= 0) {
            return NAN;
        }

		if ($y <= $s) {
            return $d1 - 1/$y;
        }

		while ($y < $c) {
			$retval = $retval - 1/$y;
			$y++;
		}

		$r = 1/$y;
		$retval = $retval + log($y) - 0.5 * $r;
		$r *= $r;
		$retval = $retval - $r * ($s3 - $r * ($s4 - $r *$s5));

		return $retval;
	}

	/**
	 * lambert
	 * 
	 * Returns the positive branch of the lambert function
	 * 
	 * @param float $x Argument to the lambert funcction
	 * @param bool $principal True to use the principal branch, false to use the secondary
	 * @return float The result of the lambert function
	 * @static
	 */
	public static function lambert($x, $principal = true) {
		// http://www.whim.org/nebula/math/lambertw.html
		
		if ($principal) {
			if ($x > 10) {
                $w = log($x) - log(log($x));
            }
			elseif ($x > -1 / M_E) {
                $w = 0;
            }
			else {
                return NAN; //Undefined below -1/e
            }
		}
		else { //Secondary
			if ($x >= -1 / M_E && $x <= -0.1) {
                $w = -2;
            }
			elseif ($x > -0.1 && $x < 0) {
                $w = log(-$x) - log(-log(-x));
            }
			else {
                return NAN; //Defined only for [-1/e, 0)
            }
		}
		
		for ($k = 1; $k < 150; ++$k) {
			$old_w = $w;
			$w = ($x * exp(-$w) + pow($w, 2)) / ($w + 1);
			
			if (abs($w - $old_w) < 0.0000001) break;
		}
		
		return $w;
	}
	
	/**
	 * lowerGamma
	 * 
	 * Returns the lower gamma function of a number.
	 * 
	 * @param float $s Upper bound of integration
	 * @param float $x Argument to the lower gamma function.
	 * @return float The lower gamma of $x
	 * @static
	 */
	public static function lowerGamma($s, $x) {
		//Adapted from jStat
		$aln = self::gammaln($s);
		$afn = self::gamma($s);
		$ap = $s;
		$sum = 1 / $s;
		$del = $sum;

		$afix = ($s >= 1 )?$s:1 / $s;
		$ITMAX = floor(log($afix) * 8.5 + $s * 0.4 + 17);

		if ($x < 0 || $s <= 0 ) {
			return NAN;
		}
		elseif ($x < $s + 1 ) {
			for ($i = 1; $i <= $ITMAX; $i++) {
				$sum += $del *= $x / ++$ap;
			}

			$endval = $sum * exp(-$x + $s * log($x) - ($aln));
		}
		else {
			$b = $x + 1 - $s;
			$c = 1 / 1.0e-30;
			$d = 1 / $b;
			$h = $d;

			for ($i = 1; $i <= $ITMAX; $i++) {
				$an = -$i * ($i - $s);
				$b += 2;
				$d = $an * $d + $b;
				$c = $b + $an / $c;
				$d = 1 / $d;
				$h *= $d * $c;
			}

			$endval = 1 - $h * exp(-$x + $s * log($x) - ($aln));
		}

		return $endval * $afn;
	}
	
	/**
	 * ilowerGamma
	 * 
	 * Returns the inverse of the lower gamma function of a number.
	 * 
	 * @param float $s Upper bound of integration
	 * @param float $x Result of the lower gamma function.
	 * @return float The argument to the lower gamma function that would return $x
	 * @static
	 */
	public static function ilowerGamma($s, $x) {
		$precision = 8;
		$guess = array(5, 20);
		$IT_MAX = 1000;
		$i = 1;

		while (round($guess[$i], $precision) != round($guess[$i - 1], $precision) && $i < $IT_MAX) {
			$f = self::lowerGamma($s, $guess[$i]);
			$f2 = self::lowerGamma($s, $guess[$i - 1]);
			$fp = ($f - $f2) / ($guess[$i] - $guess[$i - 1]);

			$guess[] = $guess[$i - 1] - $f / $fp;
			$i++;
		}

		return $guess[$i - 1];
	}
	
	/**
	 * upperGamma
	 * 
	 * Returns the upper gamma function of a number.
	 * 
	 * @param float $s Lower bound of integration
	 * @param float $x Argument to the upper gamma function
	 * @return float The upper gamma of $x
	 * @static
	 */
	public static function upperGamma($s, $x) {
		return self::gamma($s) - self::lowerGamma($s, $x);
	}

	/**
	 * beta
	 * 
	 * Returns the beta function of a pair of numbers.
	 * 
	 * @param float $a The alpha parameter
	 * @param float $b The beta parameter
	 * @return float The beta of $a and $b
	 * @static
	 */
	public static function beta($a, $b) {
		return self::gamma($a) * self::gamma($b) / self::gamma($a + $b);
	}
	
	/**
	 * regularizedIncompleteBeta
	 * 
	 * Implements the jStat method of calculating the incomplete beta.
	 * 
	 * @param float $a The alpha parameter
	 * @param float $b The beta parameter
	 * @param float $x Upper bound of integration
	 * @return float The incomplete beta of $a and $b, up to $x
	 * @static
	 */
	public static function regularizedIncompleteBeta($a, $b, $x) {
		//Again, thanks to jStat.
	
		// Factors in front of the continued fraction.
		if ($x < 0 || $x > 1) {
            return false;
        }
        
		if ($x == 0 || $x == 1) {
            $bt = 0;
        }
		else {
            $bt = exp(self::gammaln($a + $b) - self::gammaln($a) - self::gammaln($b) + $a * log($x) + $b * log(1 - $x));
        }

		if ($x < ( $a + 1 ) / ( $a + $b + 2 )) {
			// Use continued fraction directly.
			return $bt * self::betacf($x, $a, $b) / $a;
        }
		else {
			// else use continued fraction after making the symmetry transformation.
			return 1 - $bt * self::betacf(1 - $x, $b, $a) / $b;
        }
	}

	// Evaluates the continued fraction for incomplete beta function by modified Lentz's method.
	// Is a factored-out portion of the implementation of the regularizedIncompleteBeta
	private static function betacf($x, $a, $b) {
		$fpmin = 1e-30;

		// These q's will be used in factors that occur in the coefficients
		$qab = $a + $b;
		$qap = $a + 1;
		$qam = $a - 1;
		$c = 1;
		$d = 1 - $qab * $x / $qap;
        
		if (abs($d) < $fpmin) {
            $d = $fpmin;    
        }
    
		$d = 1 / $d;
		$h = $d;
        
		for ($m = 1; $m <= 100; $m++) {
			$m2 = 2 * $m;
			$aa = $m * ($b - $m) * $x / (($qam + $m2) * ($a + $m2));

			// One step (the even one) of the recurrence
			$d = 1 + $aa * $d;
            
			if (abs($d) < $fpmin ) {
                $d = $fpmin;
            }
            
			$c = 1 + $aa / $c;
            
			if (abs($c) < $fpmin ) {
                $c = $fpmin;
            }
            
			$d = 1 / $d;
			$h *= $d * $c;
			$aa = -($a + $m) * ($qab + $m) * $x / (($a + $m2) * ($qap + $m2));

			// Next step of the recurrence (the odd one)
			$d = 1 + $aa * $d;
            
			if (abs($d) < $fpmin) {
                $d = $fpmin;
            }
            
			$c = 1 + $aa / $c;
			
            if (abs($c) < $fpmin) {
                $c = $fpmin;
            }
            
			$d = 1 / $d;
			$del = $d * $c;
			$h *= $del;

			if (abs($del - 1.0) < 3e-7) {
                break;
            }
		}
        
		return $h;
	}
	
	/**
	 * iregularizedIncompleteBeta
	 *
	 * The inverse of the regularized incomplete beta function.  
	 *
	 * @param float $a The alpha parameter
	 * @param float $b The beta parameter
	 * @param float $x The incomplete beta of $a and $b, up to the upper bound of integration
	 * @return float Upper bound of integration
	 * @static
	 */
	public static function iregularizedIncompleteBeta($a, $b, $x) {
		//jStat is my hero.
		$EPS = 1e-8;
		$a1 = $a - 1;
		$b1 = $b - 1;

		$lna = $lnb = $pp = $t = $u = $err = $return = $al = $h = $w = $afac = 0;

		if ($x <= 0) {
            return 0;
        }
		else if ($x >= 1) {
            return 1;
        }

		if ($a >= 1 && $b >= 1) {
			$pp = ($x < 0.5) ? $x : 1 - $x;
			$t = pow(-2 * log($pp), 0.5);
			$return = (2.30753 + $t * 0.27061) / (1 + $t * (0.99229 + $t * 0.04481)) - $t;
			
			if ($x < 0.5) {
                $return = -$return;
            }
			
			$al = ($return * $return - 3) / 6;
			$h = 2 / (1 / (2 * $a - 1) + 1 / (2 * $b - 1));
			$w = ($return * pow($al + $h, 0.5) / $h) - (1 / (2 * $b - 1) - 1 / (2 * $a - 1)) * ($al + 5 / 6 - 2 / (3 * $h));
			$return = $a / ($a + $b * exp(2 * $w));
		} 
		else {
			$lna = log($a / ($a + $b));
			$lnb = log($b / ($a + $b));
			$t = exp($a * $lna) / $a;
			$u = exp($b * $lnb) / $b;
			$w = $t + $u;
            
			if ($x < $t / $w) {
                $return = pow($a * $w * $x, 1 / $a);
            }
			else {
                $return = 1 - pow($b * $w * (1 - $x), 1 / $b);
            }
		}

		$afac = -self::gammaln($a) - self::gammaln($b) + self::gammaln($a + $b);
        
		for ($j = 0; $j < 10; $j++) {
			if ($return === 0 || $return === 1) {
                return $return;
            }
			
			$err = self::regularizedIncompleteBeta($a, $b, $return) - $x;
			$t = exp($a1 * log($return) + $b1 * log(1 - $return) + $afac);
			$u = $err / $t;
			$return -= ($t = $u / (1 - 0.5 * min(1, $u * ($a1 / $return - $b1 / (1 - $return)))));
			
			if ($return <= 0) {
                $return = 0.5 * ($return + $t);
            }
            
			if ($return >= 1) {
                $return = 0.5 * ($return + $t + 1);
            }
            
			if (abs($t) < $EPS * $return && $j > 0) {
                break;
            }
		}
        
		return $return;
	}
}