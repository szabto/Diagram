<?php
namespace Diagram;

set_time_limit(0);

use Diagram\Exceptions;

/**
 * undocumented class
 *
 * @package Diagram
 * @author Tamás Szabó
 **/
class Graph
{
	/**
	 * The main image's width
	 *
	 * @var width
	 **/
	protected $width = 0;

	/**
	 * The main image's height
	 *
	 * @var height
	 **/
	protected $height = 0;

	//Page paddings
	protected $paddingTop = 0;
	protected $paddingRight = 0;
	protected $paddingBottom = 0;
	protected $paddingLeft = 0;

	//Adapter 
	protected $adapter;

	//Calculated sizes
	protected $workingAreaWidth;
	protected $workingAreaHeight;

	//Image's Type
	protected $imageType;

	protected $diagramAreas = array();

	protected $drawingAreaWidth = 0;
	protected $drawingAreaHeight = 0;

	protected $drawingAreaWithAutoWidth = 0;
	protected $drawingAreaWithAutoHeight = 0;

	protected $top = 0;
	protected $left = 0;

	protected $backgroundImage = "";

	protected $margins = array(0,0,0,0);

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function __Construct(Adapters\AbstractAdapter $adapter, $width, $height, $paddingTop = null, $paddingRight = null, $paddingBottom = null, $paddingLeft = null)
	{
		$this->width = $width;
		$this->height = $height;

		if( $adapter == null )
		{
			throw new Exceptions\AdapterException("Adapter must be set in first paramter.");
		}
		else
		{
			$this->adapter = $adapter;
		}

		if( $paddingTop !== null && $paddingRight === null )
		{
			$this->paddingTop = $this->paddingRight = $this->paddingLeft = $this->paddingBottom = $paddingTop;
		}
		else if( $paddingTop !== null && $paddingRight !== null && $paddingBottom === null )
		{
			$this->paddingTop = $this->paddingBottom = $paddingTop;
			$this->paddingRight = $this->paddingLeft = $paddingRight;
		}
		else
		{
			$this->paddingTop = $paddingTop;
			$this->paddingRight = $paddingRight;
			$this->paddingLeft = $paddingLeft;
			$this->paddingBottom = $paddingBottom;
		}

		$this->top = $this->paddingTop;
		$this->left = $this->paddingLeft;

		if( ($this->paddingLeft + $this->paddingRight) >= $width || ($this->paddingTop + $this->paddingBottom) >= $height )
		{
			throw new Exceptions\ImageSizeException("Padding cannot be greater than pagesize.");
		}

		if( $this->paddingRight < 0 || $this->paddingTop < 0 || $this->paddingLeft < 0 || $this->paddingBottom < 0 )
		{
			throw new Exceptions\PaddingException("Padding must be greater than zero.");
		}

		$this->workingAreaWidth = $this->width - ($this->paddingRight + $this->paddingLeft);
		$this->workingAreaHeight = $this->height - ($this->paddingTop + $this->paddingBottom);

		$this->adapter->NewImage($width, $height);

		return $this;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function SetImageType($imageType)
	{
		if( $imageType == "png" || $imageType == "jpg" || $imageType == "jpeg" )
		{
			$this->imageType = $imageType;
		}
		else
		{
			throw new Exceptions\ImageTypeException("Image type must be instance of png or jpg or jpeg.");
		}

		$this->adapter->SetImageFormat($imageType);

		return $this;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function GetImageType()
	{
		return $this->imageType;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function GetWorkingAreaSize()
	{
		return array($this->workingAreaWidth, $this->workingAreaHeight);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function SetBackgroundImage($location)
	{
		$this->backgroundImage = $location;
		return $this;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function AddDiagramArea(DrawingArea $area)
	{
		$this->diagramAreas[] = $area;

		if( $area->getWidth() != "auto" )
		{
			if( $area->getWidth() > $this->workingAreaWidth )
				throw new Exceptions\DrawingException("Diagram Area's width cannot be greater than page width.");

			$this->drawingAreaWidth += $area->getWidth();
		}
		else
		{
			$this->drawingAreaWithAutoWidth ++;
		}

		if( $area->getHeight() != "auto" )
		{
			if( $area->getHeight() > $this->workingAreaHeight )
				throw new Exceptions\DrawingException("Diagram Area's height cannot be greater than page height.");

			$this->drawingAreaHeight += $area->getHeight();
		}
		else
		{
			$this->drawingAreaWithAutoHeight ++;
		}

		$margin = $area->GetMargin();
		$this->workingAreaHeight -= ($margin[0]+$margin[2]);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function GetDrawingAreaCount()
	{
		return count($this->diagramAreas);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function GetDrawingAreaSize()
	{
		return array($this->drawingAreaWidth, $this->drawingAreaHeight);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function GetDrawingAreaAutoHeightCount()
	{
		return $this->drawingAreaWithAutoHeight;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	private function GetMimeType()
	{
		if( $this->imageType == "png" )
			return "image/png";
		else if( $this->imageType == "jpg" || $this->imageType == "jpeg" )
			return "image/jpeg";
		else
			throw new Exceptions\ImageTypeException("Image type cannot be empty.");
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function GetRenderer($newInstance = true)
	{
		if( $newInstance )
			return new $this->adapter();
		else
			return $this->adapter;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	private function RenderAreas()
	{
		if( count($this->diagramAreas) == 0 )
			throw new Exceptions\DrawingException("You need to add at least one DrawingArea");

		foreach($this->diagramAreas as $area)
		{
			$margin = $area->GetMargin();
			$this->adapter->Copy($area->Render($this),$this->left+$margin[3], $this->top+$margin[0]);
			$this->top += $area->getHeight()+$margin[0]+$margin[2];
		}
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function Render()
	{
		$this->adapter->SetQuality(100);
		if( $this->backgroundImage != "" )
		{
			$a = new $this->adapter();
			$a->OpenImage($this->backgroundImage);
			$a->ResizeImage($this->width, $this->height);
			$this->adapter->Copy($a, 0, 0);
		}

		$this->renderAreas();

		return $this;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function Draw()
	{
		header("Content-Type:".$this->getMimeType());
		echo $this->adapter->DrawImage();
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function Save()
	{

	}
}
?>