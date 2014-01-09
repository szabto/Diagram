<?php
namespace Diagram\Adapters;

use Diagram\Color;

/**
 * Adapter for create images with Imagick
 *
 * @package Diagram\Adapters
 * @author Tamás Szabó
 **/
class ImagickAdapter extends AbstractAdapter
{
	var $imagick = null;
	var $imagickDraw = null;

	var $width = 0;
	var $height = 0;

	/**
	 * {@inheritdoc}
	 * @author Tamás Szabó
	 **/
	public function __Construct()
	{
		$this->imagick = new \Imagick();
		$this->imagickDraw = new \ImagickDraw();

		parent::__Construct();
	}

	/**
	 * {@inheritdoc}
	 * @author Tamás Szabó
	 **/
	public function NewImage($width, $height, Color $background = null)
	{
		if( $background == null )
			$background = new \ImagickPixel( 'white' );
		else
			$background = new \ImagickPixel( $background->Get($this) );

		$this->width = $width;
		$this->height = $height;

		$this->imagick->newImage($width, $height, $background);
	}

	/**
	 * {@inheritdoc}
	 * @author Tamás Szabó
	 **/
	public function DrawLine($x1, $y1, $x2, $y2, Color $color, $width = null)
	{
		$this->imagickDraw->clear();

		if( $width )
			$this->imagickDraw->setStrokeWidth($width);
		else
			$this->imagickDraw->setStrokeWidth(1);

		$this->imagickDraw->setStrokeColor($color->Get($this));
		$this->imagickDraw->line($x1, $y1, $x2, $y2);
		$this->imagick->drawImage($this->imagickDraw);
	}

	/**
	 * {@inheritdoc}
	 * @author Tamás Szabó
	 **/
	public function SetQuality($q)
	{
		$this->imagick->setImageCompression(\Imagick::COMPRESSION_JPEG);
   		$this->imagick->setImageCompressionQuality($q); 
	}

	/**
	 * {@inheritdoc}
	 * @author 
	 **/
	public function DrawText($text, $x, $y, $font, $fontSize, Color $color, $angle = 0)
	{
		$this->imagickDraw->clear();
		$this->imagickDraw->setStrokeWidth(1);

		$this->imagickDraw->setfillColor($color->Get($this));
		$this->imagickDraw->setFont($font);
		$this->imagickDraw->setFontSize($fontSize);

		$this->imagick->annotateImage($this->imagickDraw, $x, $y, $angle, $text);
	}

	/**
	 * {@inheritdoc}
	 * @author Tamás Szabó
	 **/
	public function DrawImage($draw=null)
	{
		return $this->imagick;
	}

	/**
	 * {@inheritdoc}
	 * @author Tamás Szabó
	 **/
	public function ResizeImage($width, $height)
	{	
		$this->width = $width;
		$this->height = $height;
		$this->imagick->resizeImage($width, $height, \Imagick::FILTER_LANCZOS, 0.9, true);
	}

	/**
	 * {@inheritdoc}
	 * @author Tamás Szabó
	 **/
	public function SetImageFormat($format)
	{
		$this->imagick->setImageFormat($format);
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
	public function DrawRectangle($x1, $y1, $x2, $y2, $color)
	{
		$this->imagickDraw->clear();

		$this->imagickDraw->setFillColor($color->Get($this));
		$this->imagickDraw->rectangle($x1, $y1, $x2, $y2);
		$this->imagick->drawImage($this->imagickDraw);
	}

	/**
	 * {@inheritdoc}
	 * @author Tamás Szabó
	 **/
	public function OpenImage($location)
	{
		$this->imagick = new \Imagick($location);
		list($width, $height) = getimagesize($location);
		$this->width = $width;
		$this->height = $height;
	}

	/**
	 * {@inheritdoc}
	 * @author Tamás Szabó
	 **/
	public function Copy(AbstractAdapter $instance, $left, $top)
	{
		$this->imagick->compositeImage($instance->DrawImage(), \Imagick::COMPOSITE_DEFAULT, $left, $top, \Imagick::CHANNEL_ALL);
	}
}
?>