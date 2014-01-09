<?php
namespace Diagram;

/**
 * undocumented class
 *
 * @package default
 * @author Tamás Szabó
 **/
class DiagramData
{
	var $width = 1;
	var $color;
	protected $type;

	protected $values = array();

	var $masterAxle;
	var $dataAxle;
	var $border = 0;
	var $borderColor;

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Tamás Szabó
	 **/
	public function __Construct($type = null, Color $color = null, $width = 1)
	{
		$this->type = $type;
		$this->color = $color;
		$this->width = $width;
		return $this;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Tamás Szabó
	 **/
	public function setBorder($width, Color $color)
	{
		$this->border = $width;
		$this->borderColor = $color;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Tamás Szabó
	 **/
	public function setMasterAxle(Axle $axle)
	{
		$this->masterAxle = $axle;
		return $this;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Tamás Szabó
	 **/
	public function setDataAxle(Axle $axle)
	{
		$this->dataAxle = $axle;
		return $this;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Tamás Szabó
	 **/
	public function addValue($master, $data)
	{
		if( !$this->masterAxle instanceof Axle )
			throw new Exceptions\DataException("Master Axle must be set before adding data.");

		if( !$this->dataAxle instanceof Axle )
			throw new Exceptions\DataException("Data Axle must be set before adding data.");

		$curr = count($this->values);
		$this->values[$curr] = array();
		$this->values[$curr][$this->masterAxle->getName()] = $master;
		$this->values[$curr][$this->dataAxle->getName()] = $data;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Tamás Szabó
	 **/
	public function Render(DrawingArea $area)
	{
		$renderer = $area->GetRenderer();

		for($i=0;$i<count($this->values);$i++)
		{
			$value = $this->values[$i];

			$masterDistance = $area->getWidth() / count($this->masterAxle->getValues());

			$dataDistance = $area->getHeight() / count($this->dataAxle->getValues());
			if( isset($this->values[$i+1]) )
			{
				$nextVal = $this->values[$i+1];
				if( $this->border != 0 )
				{
					$this->type->Render(($this->masterAxle->getItemNumber($value[$this->masterAxle->getName()])) * $masterDistance-$this->border, ($this->dataAxle->getItemNumber($value[$this->dataAxle->getName()])) * $dataDistance, ($this->masterAxle->getItemNumber($nextVal[$this->masterAxle->getName()])) * $masterDistance+$this->border, (($this->dataAxle->getItemNumber($nextVal[$this->dataAxle->getName()])) * $dataDistance) + $this->border, $this, $area, $this->borderColor);
				}
				$this->type->Render(($this->masterAxle->getItemNumber($value[$this->masterAxle->getName()])) * $masterDistance,($this->dataAxle->getItemNumber($value[$this->dataAxle->getName()])) * $dataDistance,($this->masterAxle->getItemNumber($nextVal[$this->masterAxle->getName()])) * $masterDistance,($this->dataAxle->getItemNumber($nextVal[$this->dataAxle->getName()])) * $dataDistance, $this, $area);
			}
		}
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Tamás Szabó
	 **/
	public function getValues()
	{
		return $this->values;
	}

} // END class 
?>