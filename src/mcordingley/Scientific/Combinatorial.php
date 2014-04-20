<?php

namespace mcordingley\Scientific;

class Combinatorial {
    // Non-instantiable
    private function __construct() {}

    /**
     * factorial
     * 
     * @param numeric $x 
     * @return numeric $x!
     * @static
     */
    public static function factorial($x) {
        $sum = 1;
        
        for ($i = 1; $i <= floor($x); $i++) {
            $sum *= $i;
        }
        
        return $sum;
    }

    /**
     * permutations
     * 
     * Returns the number of ways of choosing $r objects from a collection
     * of $n objects, where the order of selection matters.
     * 
     * @param int $n The size of the collection
     * @param int $r The size of the selection
     * @return int $n pick $r
     * @static
     */
    public static function permutations($n, $r) {
        $total = 1;
        
        for ($i = ($n - $r + 1); $i <= $n; ++$i) {
            $total *= $i;
        }
        
        return $total;
    }
    
    /**
     * @alias permutations
     */
    public static function nPr($n, $r) {
        return self::permutations($n, $r);
    }

    /**
     * combinations
     * 
     * Returns the number of ways of choosing $r objects from a collection
     * of $n objects, where the order of selection does not matter.
     * 
     * @param int $n The size of the collection
     * @param int $r The size of the selection
     * @return int $n choose $r
     * @static
     */
    public static function combinations($n, $r) {
        return self::permutations($n, $r)/self::factorial($r);
    }
    
    /**
     * @alias combinations
     */
    public static function nCr($n, $r) {
        return self::combinations($n, $r);
    }
}