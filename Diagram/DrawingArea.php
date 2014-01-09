<?php
namespace Diagram;

/**
 * undocumented class
 *
 * @package Diagram
 * @author Tamás Szabó
 **/
class DrawingArea
{
	//Image's Width
	protected $width = 0;

	//Image's height
	protected $height = 0;

	//Page paddings
	protected $marginTop = 0;
	protected $marginRight = 0;
	protected $marginBottom = 0;
	protected $marginLeft = 0;

	//Background color
	protected $backgroundColor;

	protected $top = 0;
	protected $right = 0;

	protected $pageSize = array();
	protected $areaCount = 1;
	protected $areaSize = array();
	protected $autoHeights = 0;

	protected $axis = array();

	protected $datas = array();

	protected $renderer;

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Tamás Szabó
	 **/
	public function __Construct($width = "auto", $height ="auto", $backColor = null)
	{
		$this->width = $width;
		$this->height = $height;

		if( $backColor != null )
			$this->backgroundColor = $backColor;
		else
			$this->backgroundColor = new Color("red");
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Tamás Szabó
	 **/
	private function CalculateSize()
	{
		if( $this->width == "auto" )
			$this->width = $this->pageSize[0] - ($this->marginLeft + $this->marginRight);

		if( $this->height == "auto" )
		{
			$this->height = ($this->pageSize[1] - $this->areaSize[1]) / $this->autoHeights;
		}
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Tamás Szabó
	 **/
	public function addData(DiagramData $data)
	{
		$this->datas[] = $data;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Tamás Szabó
	 **/
	public function getWidth()
	{
		return $this->width;	
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Tamás Szabó
	 **/
	public function getHeight()
	{
		return $this->height;	
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Tamás Szabó
	 **/
	public function SetMargin($top=null, $right=null, $bottom=null, $left=null)
	{
		if( $top !== null && $right === null )
		{
			$this->marginTop = $this->marginRight = $this->marginLeft = $this->marginBottom = $top;
		}
		else if( $top !== null && $right !== null && $bottom === null )
		{
			$this->marginTop = $this->marginBottom = $top;
			$this->marginRight = $this->marginLeft = $right;
		}
		else
		{
			$this->marginTop = $top;
			$this->marginRight = $right;
			$this->marginLeft = $left;
			$this->marginBottom = $bottom;
		}
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Tamás Szabó
	 **/
	public function GetMargin()
	{
		return array($this->marginTop, $this->marginRight, $this->marginBottom, $this->marginLeft);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Tamás Szabó
	 **/
	public function AddAxle( Axle $axle )
	{
		$this->axis[] = $axle;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Tamás Szabó
	 **/
	public function GetAxis()
	{
		return $this->axis;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Tamás Szabó
	 **/
	private function HasAxis()
	{
		return count($this->axis);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Tamás Szabó
	 **/
	public function GetRenderer($newInstance = false)
	{
		if( $newInstance )
			return new $this->renderer;
		else
			return $this->renderer;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Tamás Szabó
	 **/
	public function Render( Graph $diagramPage )
	{
		$this->renderer = $diagramPage->GetRenderer();
		$this->pageSize = $diagramPage->getWorkingAreaSize();

		$this->areaCount = $diagramPage->GetDrawingAreaCount();
		$this->areaSize = $diagramPage->GetDrawingAreaSize();
		$this->autoHeights = $diagramPage->GetDrawingAreaAutoHeightCount();

		$this->CalculateSize();

		$this->renderer->NewImage($this->width, $this->height, $this->backgroundColor);
		$this->renderer->SetImageFormat($diagramPage->GetImageType());
		$this->renderer->SetQuality(100);

		if( $axisCount = $this->HasAxis() )
		{
			for( $i=0; $i < $axisCount; $i++ )
			{
				$axle = $this->axis[$i];
				$axleVals = $axle->getValues();

				if( $axle->getPosition() == Axle::POSITION_LEFT || $axle->getPosition() == Axle::POSITION_RIGHT )
				{
					if( count($axleVals) )
					{
						$distance = $this->height / count($axleVals);
						$bigStep = $axle->getBigStep();

						for( $a = 0; $a < count($axleVals); $a++ )
						{
							// if( $a == 0 )
							// 	continue;

							$val = $axleVals[$a];
							$special = $axle->getSpecialValue($val);
							if( $axle->getStepVisibility() != Axle::COLUMN_SHOW_NONE )
							{
								if( $special )
								{
									$this->renderer->DrawLine(0, floor($a * $distance),$this->width, floor($a * $distance), $special["color"], $special["width"]);
								}
								else if( $bigStep && $val % $bigStep["step"] === 0 && ($axle->getStepVisibility() == Axle::COLUMN_SHOW_BOTH || $axle->getStepVisibility() == Axle::COLUMN_SHOW_BIG) )
								{
									$this->renderer->DrawLine(0, floor($a * $distance), $this->width, floor($a * $distance), $bigStep["color"], $bigStep["width"]);
								}
								else if( $axle->getStepVisibility() == Axle::COLUMN_SHOW_BOTH || $axle->getStepVisibility() == Axle::COLUMN_SHOW_SMALL )
								{
									$this->renderer->DrawLine(0, floor($a * $distance), $this->width, floor($a * $distance), $axle->getColor(), 1);
								}
							}

							if( $axle->getTextVisibility() != Axle::TEXT_SHOW_NONE )
							{
								if( $axle->getPosition() != Axle::POSITION_RIGHT )
								{
									if( $bigStep && $val % $bigStep["step"] == 0 && ($axle->getTextVisibility() == Axle::TEXT_SHOW_BOTH || $axle->getTextVisibility() == Axle::TEXT_SHOW_BIG) )
									{
										$this->renderer->DrawText($val, 0, floor($a * $distance)+4, "AvantGarde-Book", 12, $axle->getTextColor());
									}
									else if( $axle->getTextVisibility() == Axle::TEXT_SHOW_BOTH || $axle->getTextVisibility() == Axle::TEXT_SHOW_SMALL )
									{
										$this->renderer->DrawText($val, 0, floor($a * $distance)+4, "AvantGarde-Book", 12, $axle->getTextColor());
									}
								}
								else
								{
									if( $bigStep && $val % $bigStep["step"] == 0 && ($axle->getTextVisibility() == Axle::TEXT_SHOW_BOTH || $axle->getTextVisibility() == Axle::TEXT_SHOW_BIG) )
									{
										$this->renderer->DrawText($val, $this->width-10, floor($a * $distance)+4, "AvantGarde-Book", 12, $axle->getTextColor());
									}
									else if( $axle->getTextVisibility() == Axle::TEXT_SHOW_BOTH || $axle->getTextVisibility() == Axle::TEXT_SHOW_SMALL )
									{
										$this->renderer->DrawText($val, $this->width-10, floor($a * $distance)+4, "AvantGarde-Book", 12, $axle->getTextColor());
									}
								}
							}
						}
					}
				}
				else
				{
					if( count($axleVals) )
					{
						$distance = $this->width / count($axleVals);
						$bigStep = $axle->getBigStep();

						for( $a = 0; $a < count($axleVals); $a++ )
						{
							if( $a == 0 )
								continue;

							$val = $axleVals[$a];
							$special = $axle->getSpecialValue($val);
							if( $axle->getStepVisibility() != Axle::COLUMN_SHOW_NONE )
							{
								if( $bigStep && $val % $bigStep["step"] == 0 && ($axle->getStepVisibility() == Axle::COLUMN_SHOW_BOTH || $axle->getStepVisibility() == Axle::COLUMN_SHOW_BIG) )
								{
									$this->renderer->DrawLine(floor($a * $distance), 0, floor($a * $distance), $this->height, $bigStep["color"], $bigStep["width"]);
								}
								else if( $axle->getStepVisibility() == Axle::COLUMN_SHOW_BOTH || $axle->getStepVisibility() == Axle::COLUMN_SHOW_SMALL )
								{
									if( $special )
									{
										$this->renderer->DrawLine(floor($a * $distance), 0, floor($a * $distance), $this->height, $special["color"], $special["width"]);
									}
									else
									{
										$this->renderer->DrawLine(floor($a * $distance), 0, floor($a * $distance), $this->height, $axle->getColor(), 1);
									}
								}
							}

							if( $axle->getTextVisibility() != Axle::TEXT_SHOW_NONE )
							{
								if( $bigStep && $val % $bigStep["step"] == 0 && ($axle->getTextVisibility() == Axle::TEXT_SHOW_BOTH || $axle->getTextVisibility() == Axle::TEXT_SHOW_BIG) )
								{
									$this->renderer->DrawText($val, floor($a * $distance)-5, $this->height - 10, "AvantGarde-Book", 12, $axle->getTextColor());
								}
								else if( $axle->getTextVisibility() == Axle::TEXT_SHOW_BOTH || $axle->getTextVisibility() == Axle::TEXT_SHOW_SMALL )
								{
									$this->renderer->DrawText($val, floor($a * $distance)-5, $this->height - 10, "AvantGarde-Book", 12, $axle->getTextColor());
								}
							}
						}
					}
				}
			}

			if( $dataCount = count($this->datas) )
			{
				for( $i=0; $i<$dataCount; $i++ )
				{
					$data = $this->datas[$i];

					$data->Render($this);
				}
			}
		}
		
		return $this->renderer;
	}
}
?>