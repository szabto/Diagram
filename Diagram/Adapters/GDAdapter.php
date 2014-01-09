<?php
namespace Diagram\Adapters;

use Diagram\Color;

/**
 * Adapter for create images with GD
 *
 * @package Diagram\Adapters
 * @author Tamás Szabó
 **/
class GDAdapter extends AbstractAdapter
{
	var $adapter = null;

	var $width = 0;
	var $height = 0;

	/**
	 * {@inheritdoc}
	 * @author Tamás Szabó
	 **/
	public function __Construct()
	{
		parent::__Construct();
	}

	/**
	 * {@inheritdoc}
	 * @author Tamás Szabó
	 **/
	public function NewImage($width, $height, Color $background = null)
	{
		$white = new Color("white");

		$this->adapter = imagecreatetruecolor($width, $height);
		if( $background == null )
			$background = $white->Get($this);
		else
			$background = $background->Get($this);

		$this->width = $width;
		$this->height = $height;

		imagefilledrectangle($this->adapter, 0, 0, $width, $height, $background);
	}

	/**
	 * {@inheritdoc}
	 * @author Tamás Szabó
	 **/
	public function SetQuality($q)
	{
		imageantialias($this->adapter, true);
	}

	/**
	 * {@inheritdoc}
	 * @author Tamás Szabó
	 **/
	public function DrawLine($x1, $y1, $x2, $y2, Color $color, $width = null)
	{
		// imageline($this->adapter, $x1, $y1, $x2, $y2, $color->Get($this));
		$this->imagelinethick($this->adapter, $x1, $y1, $x2, $y2, $color->Get($this), $width);
	}

	/**
	 * @return void
	 * @author php.net
	 **/
	private function imagelinethick($image, $x1, $y1, $x2, $y2, $color, $thick = 1)
	{
	    /* this way it works well only for orthogonal lines
	    imagesetthickness($image, $thick);
	    return imageline($image, $x1, $y1, $x2, $y2, $color);
	    */
	    if ($thick == 1) {
	        return imageline($image, $x1, $y1, $x2, $y2, $color);
	    }
	    $t = $thick / 2 - 0.5;
	    if ($x1 == $x2 || $y1 == $y2) {
	        return imagefilledrectangle($image, round(min($x1, $x2) - $t), round(min($y1, $y2) - $t), round(max($x1, $x2) + $t), round(max($y1, $y2) + $t), $color);
	    }
	    $k = ($y2 - $y1) / ($x2 - $x1); //y = kx + q
	    $a = $t / sqrt(1 + pow($k, 2));
	    $points = array(
	        round($x1 - (1+$k)*$a), round($y1 + (1-$k)*$a),
	        round($x1 - (1-$k)*$a), round($y1 - (1+$k)*$a),
	        round($x2 + (1+$k)*$a), round($y2 - (1-$k)*$a),
	        round($x2 + (1-$k)*$a), round($y2 + (1+$k)*$a),
	    );
	    imagefilledpolygon($image, $points, 4, $color);
	    return imagepolygon($image, $points, 4, $color);
	}

	/**
	 * {@inheritdoc}
	 * @author Tamás Szabó
	 **/
	public function DrawImage($draw=null)
	{
		imagepng($this->adapter);
	}

	/**
	 * {@inheritdoc}
	 * @author Tamás Szabó
	 **/
	public function SetImageFormat($format)
	{

	}

	/**
	 * {@inheritdoc}
	 * @author Tamás Szabó
	 **/
	public function DrawRectangle($x1, $y1, $x2, $y2, $color)
	{
		imagefilledrectangle($this->adapter, $x1, $y1, $x2, $y2, $color->Get($this));
	}

	/**
	 * {@inheritdoc}
	 * @author Tamás Szabó
	 **/
	public function DrawText($text, $x, $y, $font, $fontSize, Color $color, $angle = 0)
	{
		imagettftext($this->adapter, $fontSize-3, $angle, $x, $y, $color->Get($this), "/www/static.metnet.hu/fonts/segoeui.ttf", $text);
	}

	/**
	 * {@inheritdoc}
	 * @author Tamás Szabó
	 **/
	public function GetImage()
	{

	}

	/**
	 * {@inheritdoc}
	 * @author Tamás Szabó
	 **/
	public function Copy(AbstractAdapter $instance, $left, $top)
	{

		if( $instance->adapter !== null )
			imagecopy($this->adapter, $instance->adapter, $left, $top, 0, 0, $instance->width, $instance->height);
	}

	/**
	 * {@inheritdoc}
	 * @author Tamás Szabó
	 **/
	public function ResizeImage($width, $height)
	{
		$oldAdapter = $this->adapter;
	    $this->adapter = imagecreatetruecolor($width, $height);
	    imagecopyresampled($this->adapter, $oldAdapter, 0, 0, 0, 0, $width, $height, $this->width, $this->height);

		$this->width = $width;
		$this->height = $height;
	}

	/**
	 * {@inheritdoc}
	 * @author Tamás Szabó
	 **/
	public function OpenImage($location)
	{
		$this->adapter = imagecreatefrompng($location);
		list($width, $height) = getimagesize($location);
		$this->width = $width;
		$this->height = $height;
	}
}
?>