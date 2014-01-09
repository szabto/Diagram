<?php
namespace Diagram;

/**
 * undocumented class
 *
 * @package Diagram
 * @author Tamás Szabó
 **/
class Axle
{
	const POSITION_RIGHT = 0;
	const POSITION_LEFT = 1;
	const POSITION_BOTTOM = 2;

	const TYPE_MASTER = 0;
	const TYPE_DATA = 1;

	const COLUMN_SHOW_NONE = 0;
	const COLUMN_SHOW_SMALL = 1;
	const COLUMN_SHOW_BIG = 2;
	const COLUMN_SHOW_BOTH = 3;

	const TEXT_SHOW_NONE = 0;
	const TEXT_SHOW_SMALL = 1;
	const TEXT_SHOW_BIG = 2;
	const TEXT_SHOW_BOTH = 3;

	protected $position = -1;

	protected $textColor;
	protected $smallStepColor;

	protected $showStep = 3;
	protected $showText = 3;

	protected $values = array();
	protected $specials = array();

	protected $smallStep = 1;

	protected $bigStep = 0;
	protected $bigStepColor;
	protected $bigStepWidth = 1;

	protected $name = "";
	protected $type = 0;

	/**
	 * undocumented function
	 *
	 * @return Diagram\Axle
	 * @author Tamás Szabó
	 **/
	public function __Construct($axle_position = 0, $axle_type = 0, $minVal = null, $maxVal = null)
	{
		if( $axle_position == self::POSITION_RIGHT || $axle_position == self::POSITION_LEFT || $axle_position == self::POSITION_BOTTOM )
		{
			$this->position = $axle_position;
		}
		else
		{
			throw new Exceptions\AxleException("Invalid axle position.");
		}

		if( $axle_type == self::TYPE_MASTER || $axle_type == self::TYPE_DATA )
		{
			$this->type = $axle_type;
		}
		else
		{
			throw new Exceptions\AxleException("Invalid axle type.");
		}

		if( $minVal != null )
		{
			if( is_array($minVal) )
			{
				$this->addValues($minVal);
			}
			else if($maxVal != null && !is_array($minVal) && !is_array($maxVal) )
			{
				$this->addValueRange($minVal, $maxVal);
			}
			else
			{
				$this->addValue($minVal);
			}
		}

		$this->name = rand();

		return $this;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function setName($name)
	{
		$this->name = $name;
		return $this;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getName()
	{
		return $this->name;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getPosition()
	{
		return $this->position;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Tamás Szabó
	 **/
	public function setStepVisibility($visibleType)
	{
		//feltétel a csekkolásra
		$this->showStep = $visibleType;
		return $this;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Tamás Szabó
	 **/
	public function getStepVisibility()
	{
		return $this->showStep;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Tamás Szabó
	 **/
	public function setTextVisibility($visibleType)
	{
		//feltétel a csekkolásra
		$this->showText = $visibleType;
		return $this;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Tamás Szabó
	 **/
	public function getTextVisibility()
	{
		return $this->showText;
	}


	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getType()
	{
		return $this->type;
	}

	/**
	 * undocumented function
	 *
	 * @return Diagram\Axle
	 * @author Tamás Szabó
	 **/
	public function setColor(Color $color)
	{
		$this->smallstepColor = $this->bigStepColor = $this->textColor = $color;
		return $this;
	}

	/**
	 * If color were set with Diagram\Axle::setColor() we can get all colors with this.
	 *
	 * @return Diagram\Color All color
	 * @author Tamás Szabó
	 **/
	public function getColor()
	{
		return $this->smallstepColor;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Tamás Szabó
	 **/
	public function setBigStep($step, Color $color = null, $width = 1)
	{
		$this->bigStep = $step;
		$this->bigStepColor = ($color)?$color : $this->getColor();
		$this->bigStepWidth = $width;
		return $this;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Tamás Szabó
	 **/
	public function getBigStep()
	{
		if( $this->bigStep === 0 )
			return false;

		return array(
			"step" => $this->bigStep,
			"color" => $this->bigStepColor,
			"width" => $this->bigStepWidth
		);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Tamás Szabó
	 **/
	public function getItemNumber($item)
	{
		for( $i=0; $i<count($this->values);$i++ )
		{
			$val = $this->values[$i];
			if( $val == $item )
				return $i;
		}

		$smaller = null;
		$bigger = null;

		$diffR = abs(abs($this->values[0]) - abs($this->values[1]));

		for( $i=count($this->values)-1; $i>0; $i-- )
		{
			$val = $this->values[$i];
		    if ($val >= $item)
		    {				
	    		$diff = abs((abs($item) - abs($val)))/$diffR;
	    		// var_dump($val);  
	    		// var_dump($val2);  
	    		// var_dump($item);  
	    		// var_dump($diff);
	    		// var_dump($i);
	    		// var_dump($val);
	    		return $i + $diff;
		    }
		}

		return $item;
	}

	/**
	 * undocumented function
	 *
	 * @return Diagram\Axle
	 * @author Tamás Szabó
	 **/
	public function setSmallStepColor(Color $color)
	{
		$this->smallstepColor = $color;
		return $this;
	}

	/**
	 * undocumented function
	 *
	 * @return Diagram\Color Small Step's Color
	 * @author Tamás Szabó
	 **/
	public function getSmallStepColor()
	{
		return $this->smallstepColor;
	}

	/**
	 * undocumented function
	 *
	 * @return Diagram\Color Big Step's color
	 * @author Tamás Szabó
	 **/
	public function getBigStepColor()
	{
		return $this->bigStepColor;
	}

	/**
	 * undocumented function
	 *
	 * @return Diagram\Axle
	 * @author Tamás Szabó
	 **/
	public function setTextColor(Color $color)
	{
		$this->textColor = $color;
		return $this;
	}

	/**
	 * undocumented function
	 *
	 * @return Diagram\Color Text's color
	 * @author Tamás Szabó
	 **/
	public function getTextColor()
	{
		return $this->textColor;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Tamás Szabó
	 **/
	public function addSpecialValue($value, Color $color, $width = 1)
	{
		$this->specials[$value] = array(
			"color" => $color,
			"width" => $width
		);
		return $this;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Tamás Szabó
	 **/
	public function setStep($step)
	{
		$this->smallStep = $step;
		return $this;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Tamás Szabó
	 **/
	public function getSpecialValue($value)
	{
		if( isset($this->specials[$value]) )
		{
			return $this->specials[$value];
		}
		else
		{
			return null;
		}
	}

	/**
	 * undocumented function
	 *
	 * @return Diagram\Axle
	 * @author Tamás Szabó
	 **/
	public function addValues($values)
	{
		if( is_array($values) )
		{
			$this->values = $values;
		}
		return $this;
	}

	/**
	 * undocumented function
	 *
	 * @return Diagram\Axle
	 * @author Tamás Szabó
	 **/
	public function addValue($value)
	{
		$this->values[] = $value;
		return $this;
	}

	/**
	 * undocumented function
	 *
	 * @return Diagram\Axle
	 * @author Tamás Szabó
	 **/
	public function addValueRange($minVal, $maxVal)
	{
		if( $minVal > $maxVal )
		{
			for( $i=$minVal; $i>=$maxVal; $i-=$this->smallStep )
			{
				$this->addValue($i);
			}
		}
		else
		{
			for( $i=$minVal; $i<$maxVal; $i+=$this->smallStep )
			{
				$this->addValue($i);
			}
		}
		return $this;
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
}
?>