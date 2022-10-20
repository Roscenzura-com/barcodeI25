<?PHP

class BarcodeI25
{
	private $widthFactor=0;
	private $widthBar=0; 
	public $width=0;
	public $heightBar=0;
	public $height=0;
	private $barColor=[];
	private $bgColor=[];
	private $gdBarColor=0;
	
	private $font='';
	
	private $chrI25=['0'=>'11221','1'=>'21112','2'=>'12112','3'=>'22111','4'=>'11212','5'=>'21211','6'=>'12211','7'=>'11122','8'=>'21121','9'=>'12121','A'=>'11','Z'=>'21']; 
   
	public function __construct($h, $widthFactor=null, $barColor=[0,0,0], $bgColor=[255, 255, 255], $font='')
	{
		$this->heightBar=$h;
		
		if (!$widthFactor) 
		{
			$widthFactor=round(($h*3.2/107), 2);
		}
		
		$this->widthFactor=$widthFactor;
		$this->barColor=$barColor;
		$this->bgColor=$bgColor;	
		
		$this->font=$font; 
	}
	
	public function getBarcode($barcode, $textBarcode='')
	{
		$this->widthBar=0;
		
		$bars = $this->getData($barcode);
		$this->width = round($this->widthBar * $this->widthFactor);
		
		
		$image = $this->createGdImageObject($this->width,  $this->heightBar);
		$this->gdBarColor = imagecolorallocate($image, $this->barColor[0], $this->barColor[1], $this->barColor[2]);
		
		// print bars
		$positionHorizontal = 0;
		foreach ($bars as $bar) {
			$barWidth = round(($bar['w'] * $this->widthFactor), 3);
		
			if ($bar['isBar'] && $barWidth > 0) {
				imagefilledrectangle($image, $positionHorizontal, 0, ($positionHorizontal + $barWidth) - 1, $this->heightBar,  $this->gdBarColor);
			}
			$positionHorizontal += $barWidth;
		}
		
		if (!$textBarcode) return $image; else return $this->addTextCode($image, $textBarcode);
	}
	
	
	public function addTextCode($imageBar, $barcode)
	{
		$hText=floor($this->heightBar/3);
		
		$this->height=$this->heightBar+$hText;
		
		$image = $this->createGdImageObject($this->width, $this->height);
		
		imagecopyresized($image, $imageBar, 0, 0, 0, 0, $this->width, $this->heightBar,  $this->width, $this->heightBar);
		
		$fontSize=floor($this->height-$this->heightBar-($this->heightBar/10));
		
		
		$box=imagettfbbox($fontSize, 0, $this->font, $barcode);
		$x = ($this->width/2)-($box[2]-$box[0])/2; 
		
		imagettftext($image, $fontSize, 0, $x, $this->heightBar+$fontSize+floor($hText/10), $this->gdBarColor, $this->font, $barcode );
		
		return $image;
	}
	
	protected function createGdImageObject($width,$height)
	{
		$image = imagecreate($width, $height);
		$colorBackground = imagecolorallocate($image, $this->bgColor[0], $this->bgColor[1], $this->bgColor[2]);
		imagecolortransparent($image, $colorBackground);
		
		return $image;
	}
	
	private function getData($code)
	{
		$chr=$this->chrI25;
		$bars = [];
		
		if ((strlen($code) % 2) != 0) {
			$code = '0' . $code;
		}
		// add start and stop codes
		$code = 'AA' . strtolower($code) . 'ZA';
		
		//$barcode = new Barcode($code);
		for ($i = 0; $i < strlen($code); $i = ($i + 2)) {
			$char_bar = $code[$i];
			$char_space = $code[$i + 1];
			
			// create a bar-space sequence
			$seq = '';
			$chrlen = strlen($chr[$char_bar]);
			for ($s = 0; $s < $chrlen; $s++) {
				$seq .= $chr[$char_bar][$s] . $chr[$char_space][$s];
			}
		
			for ($j = 0; $j < strlen($seq); ++$j) {
				if (($j % 2) == 0) {
					$t = 1; // bar
				} else {
					$t = 0; // space
				}
				$w = $seq[$j];
				
				$bars[]=['w'=>$w,'isBar'=>$t];
				
				$this->widthBar+=$w;
			}
		}
		
		return $bars;
	}

}

 
?>
