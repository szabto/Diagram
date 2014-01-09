<?php
namespace Diagram\DiagramType;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class Column extends AbstractType
{
	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function __Construct()
	{
	}

	public function Render($currX, $currY, $nextX, $nextY, $data, $area, $border=null)
	{
		$nullPoint = $data->dataAxle->getItemNumber(0) * $area->getHeight() / count($data->dataAxle->getValues());
	
		$area->GetRenderer()->DrawRectangle($currX-$data->width, $nullPoint, $currX+$data->width+(($border != null)?2*$data->border:0), $currY-(($border != null)?$data->border:0), ($border != null)?$border:$data->color);
	}
} // END  class 
?>