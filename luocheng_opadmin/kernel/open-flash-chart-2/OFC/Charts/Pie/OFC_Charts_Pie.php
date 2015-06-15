<?php
/**
 * PHP Integration of Open Flash Chart
 * Copyright (C) 2008 John Glazebrook <open-flash-chart@teethgrinder.co.uk>
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 */

require_once(D_R.'kernel/open-flash-chart-2/OFC/Charts/OFC_Charts_Base.php');

class OFC_Charts_Pie_Value
{
	function OFC_Charts_Pie_Value($value,$links)
    {
    	if(is_array($value))
    	{
	    	$this->value = $value["value"];
			if(isset($value["label"])){$this->label =$value["label"];}
			$tmp = 'label-colour';
			if(isset($value["label_colour"])){$this->$tmp =$value["label_colour"];}
			$tmp = 'font-size';
			if(isset($value["font_size"])){$this->$tmp = $value["font_size"];}
			if(isset($value["text"])){$this->text = $value["text"];}
    	}else{
    		$this->value = $value;
    	}
    	
   		$tmp = 'on-click';
    	if($links){$this->$tmp=$links;}
    	
	}
}

class OFC_Charts_Pie extends OFC_Charts_Base
{
	function OFC_Charts_Pie()
    {
        parent::OFC_Charts_Base();

		$this->type             = 'pie';
		$this->colours          = array("#d01f3c","red","#C79810");
		$this->alpha			= 0.6;
		$this->border			= 2;
		$this->values			= array(2,3,new OFC_Charts_Pie_Value(6.5, 'hello (6.5)'));
		$this->tip				="#val# of #total#\n#percent# of 100%";
	}
	
	//初始化
	function initialize()
	{
		$this->colours          = array();
		$this->values			= array();
	}
	
	// boolean
	function set_animate($v=array("type"=>"fade"))
    {
		$this->animate = $v;
	}

	// real
	function set_start_angle( $angle )
    {
		$this->{'start-angle'} = $angle;
	}
	
	//设置值
	function set_Pie_Value($value,$links=false)
	{
		return new OFC_Charts_Pie_Value($value,$links);
	}
}

