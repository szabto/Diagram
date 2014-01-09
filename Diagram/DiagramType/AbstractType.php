<?php
namespace Diagram\DiagramType;

/**
 * undocumented class
 *
 * @package default
 * @author Tamás Szabó
 **/
abstract class AbstractType
{
	abstract protected function __Construct();

	abstract protected function Render($currX, $currY, $nextX, $nextY, $data, $area, $border=null);

} // END abstract class DiagramType