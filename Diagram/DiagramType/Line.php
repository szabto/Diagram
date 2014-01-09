<?php
namespace Diagram\DiagramType;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class Line extends AbstractType
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
		$area->GetRenderer()->DrawLine($currX, $currY, $nextX, $nextY, $data->color, $data->width);
	}

} // END  class 
?>