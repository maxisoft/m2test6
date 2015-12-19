<?php

namespace website\tests\units;

//require_once __DIR__ . '/../../Coordinates.php';
	use \atoum;

	class Coordinates extends atoum{

	    public function testXY ( ) {
	
	        $x = 7.0;
	        $y = 42.0;
	
	        $this
	            ->if($coordinates = new \website\Coordinates($x, $y))
	            ->then
	                ->float($coordinates->getX())
	                    ->isEqualTo($x)
	                ->float($coordinates->getY())
	                    ->isEqualTo($y)
	            ;
	    }
	    
	    public function testXY2 ( ) {
	    	$x = 7.0;
	        $y = 42.0;
			$coordinates = new \website\Coordinates($x, $y);
	        $this
	        	->float($coordinates->getX())
	            	->isEqualTo($x)
	            ->float($coordinates->getY())
		        	->isEqualTo($y)
	            ;

	    }
	
	    public function testCast ( ) {
			// tester que les valeurs sont bien castées (si on passe des entiers) 
	    }
	}

