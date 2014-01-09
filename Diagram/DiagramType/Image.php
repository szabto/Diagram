<?php
namespace Diagram\DiagramType;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class Image extends AbstractType
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

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function Render($currX, $currY, $nextX, $nextY, $data, $area, $border=null)
	{
		$renderer = $area->GetRenderer(true);
		$renderer->OpenImage("images/graph/icons/061.png");
	
		$area->GetRenderer()->Copy($renderer, $currX-($renderer->width/2), $currY-($renderer->height/2));
	}
} // END  class 
?>