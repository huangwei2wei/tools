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

if (! function_exists('json_encode'))
{
    require_once(D_R.'kernel/open-flash-chart-2/OFC/JSON.php');
}

require_once(D_R.'kernel/open-flash-chart-2/OFC/JSON_Format.php');

require_once(D_R.'kernel/open-flash-chart-2/OFC/OFC_Elements.php');

require_once(D_R.'kernel/open-flash-chart-2/OFC/Charts/OFC_Charts_Area.php');
require_once(D_R.'kernel/open-flash-chart-2/OFC/Charts/OFC_Charts_Bar.php');
require_once(D_R.'kernel/open-flash-chart-2/OFC/Charts/OFC_Charts_Line.php');
require_once(D_R.'kernel/open-flash-chart-2/OFC/Charts/OFC_Charts_Pie.php');
require_once(D_R.'kernel/open-flash-chart-2/OFC/Charts/OFC_Charts_Scatter.php');
require_once(D_R.'kernel/open-flash-chart-2/OFC/Charts/Area/OFC_Charts_Area_Hollow.php');
require_once(D_R.'kernel/open-flash-chart-2/OFC/Charts/Bar/OFC_Charts_Bar_Filled.php');
require_once(D_R.'kernel/open-flash-chart-2/OFC/Charts/Bar/OFC_Charts_Bar_3d.php');
require_once(D_R.'kernel/open-flash-chart-2/OFC/Charts/Bar/OFC_Charts_Bar_Glass.php');
require_once(D_R.'kernel/open-flash-chart-2/OFC/Charts/Bar/OFC_Charts_Bar_Horizontal.php');
require_once(D_R.'kernel/open-flash-chart-2/OFC/Charts/Bar/OFC_Charts_Bar_Sketch.php');
require_once(D_R.'kernel/open-flash-chart-2/OFC/Charts/Bar/OFC_Charts_Bar_Stack.php');
require_once(D_R.'kernel/open-flash-chart-2/OFC/Charts/Line/OFC_Charts_Line_Dot.php');
require_once(D_R.'kernel/open-flash-chart-2/OFC/Charts/Line/OFC_Charts_Line_Hollow.php');

class OFC_Tooltip
{
	function OFC_Tooltip(){}
	
	/**
	 * @param $shadow as boolean. Enable drop shadow.
	 */
	function set_shadow( $shadow )
	{
		$this->shadow = $shadow;
	}
	
	/**
	 * @param $stroke as integer, border width in pixels (e.g. 5 )
	 */
	function set_stroke( $stroke )
	{
		$this->stroke = $stroke;
	}
	
	/**
	 * @param $colour as string, HEX colour e.g. '#0000ff'
	 */
	function set_colour( $colour )
	{
		$this->colour = $colour;
	}
	
	/**
	 * @param $bg as string, HEX colour e.g. '#0000ff'
	 */
	function set_background_colour( $bg )
	{
		$this->background = $bg;
	}
	
	/**
	 * @param $style as string. A css style.
	 */
	function set_title_style( $style )
	{
		$this->title = $style;
	}
	
	/**
	 * @param $style as string. A css style.
	 */
    function set_body_style( $style )
	{
		$this->body = $style;
	}
	
	function set_proximity()
	{
		$this->mouse = 1;
	}
	
	function set_hover()
	{
		$this->mouse = 2;
	}
}


class OFC_Chart
{
	function OFC_Chart()
	{
		$this->title = new OFC_Elements_Title( "Many data lines" );
		$this->elements = array();
	}

	function set_title( $t )
	{
		$this->title = $t;
	}

	function set_x_axis( $x )
	{
		$this->x_axis = $x;
	}

	function set_y_axis( $y )
	{
		$this->y_axis = $y;
	}

	function add_y_axis( $y )
	{
		$this->y_axis = $y;
	}

	function set_y_axis_right( $y )
	{
		$this->y_axis_right = $y;
	}

	function add_element( $e )
	{
		$this->elements[] = $e;
	}

	function set_x_legend( $x )
	{
		$this->x_legend = $x;
	}

	function set_y_legend( $y )
	{
		$this->y_legend = $y;
	}

	function set_bg_colour( $colour )
	{
		$this->bg_colour = $colour;
	}

	function set_tooltip( $tooltip )
	{
		$this->tooltip = $tooltip;	
	}
	
	function toString()
	{
		if (function_exists('json_encode'))
		{
			return json_encode($this);
		}
		else
		{
			$json = new Services_JSON();
			return $json->encode( $this );
		}
	}

	function toPrettyString()
	{
		return json_format( $this->toString() );
	}
}

