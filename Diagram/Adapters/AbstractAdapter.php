<?php
namespace Diagram\Adapters;

use Diagram\Color;

/**
 * undocumented class
 *
 * @package Diagram\Adapters
 * @author Tamás Szabó
 **/
abstract class AbstractAdapter
{
	/**
	 * undocumented function
	 *
	 * @return Diagram\Adapters\AbstractAdapter
	 * @author Tamás Szabó
	 **/
	public function __Construct() {
		return $this;
	}

	/**
	 * Creates a new image with the specified parameters
	 *
	 * @param int Width of new image
	 * @param int Height of new image
	 * @param Diagram\Color Background color
	 * @return void
	 * @author Tamás Szabó
	 **/
	abstract protected function NewImage($width, $height, Color $background=null);

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Tamás Szabó
	 **/
	abstract protected function DrawRectangle($x1, $y1, $x2, $y2, $color);

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Tamás Szabó
	 **/
	abstract protected function DrawLine($x1, $y1, $x2, $y2, Color $color, $width = null);

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Tamás Szabó
	 **/
	abstract protected function DrawImage($draw);

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Tamás Szabó
	 **/
	abstract protected function ResizeImage($width, $height);

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Tamás Szabó
	 **/
	abstract protected function SetImageFormat($format);

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	abstract protected function DrawText($text, $x, $y, $font, $fontSize, Color $color, $angle = 0);

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	abstract protected function SetQuality($q);

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Tamás Szabó
	 **/
	abstract protected function GetImage();

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Tamás Szabó
	 **/
	abstract protected function OpenImage($location);

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Tamás Szabó
	 **/
	abstract protected function Copy(AbstractAdapter $instance, $left, $top);
}