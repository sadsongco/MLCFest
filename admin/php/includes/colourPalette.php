<?php
class ColorPalette{
	public $color;
	
	public function __construct($color){
		$this->color = $color;
	}
	public function color_mod($hex, $diff) {
		$rgb = str_split(trim($hex, '# '), 2);
		 
		foreach ($rgb as &$hex) {
		$dec = hexdec($hex);
		if ($diff >= 0) {
		$dec += $diff;
		}
		else {
		$dec -= abs($diff);	
		}
		$dec = max(0, min(255, $dec));
		$hex = str_pad(dechex($dec), 2, '0', STR_PAD_LEFT);
	}
	return '#'.implode($rgb);
	}
	public function createPalette($colorCount=4){
		$colorPalette = array();
		for($i=1; $i<=$colorCount; $i++){
			if($i == 1){
				$color = $this->color;
				$colorVariation = -(($i*4) * 8);
			}
			if($i == 2){
				$color = $newColor;
				$colorVariation = -($i * 8);
			}
			if($i == 3){
				$color = $newColor;
				$colorVariation = -($i * 8);
			}
			if($i == 4){
				$color = $newColor;
				$colorVariation = -($i * 8);
			}
			
			$newColor = $this->color_mod($color, $colorVariation);
            $alpha = "aa";

			array_push($colorPalette, $newColor.$alpha);
		}
		return $colorPalette;
	}
}