<?php

/**
 * Faett_Piwik_Chart
 *
 * NOTICE OF LICENSE
 * 
 * Faett_Piwik is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * Faett_Piwik is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with Faett_Piwik.  If not, see <http://www.gnu.org/licenses/>.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Faett_Piwik to newer
 * versions in the future. If you wish to customize Faett_Piwik for your
 * needs please refer to http://www.faett.net for more information.
 *
 * @category    Faett
 * @package     Faett_Piwik
 * @copyright   Copyright (c) 2009 <tw@faett.net> Tim Wagner
 * @license     <http://www.gnu.org/licenses/> 
 * 			    GNU General Public License (GPL 3)
 */

require_once 'phplot/phplot.php';

/**
 * @category   	Faett
 * @package    	Faett_Piwik
 * @copyright  	Copyright (c) 2009 <tw@faett.net> Tim Wagner
 * @license    	<http://www.gnu.org/licenses/> 
 * 				GNU General Public License (GPL 3)
 * @author      Tim Wagner <tw@faett.net>
 */
class Faett_Piwik_Chart extends PHPlot_TrueColor
{
	
	/**
	 * Constructor to initialize the chart with
	 * default values.
	 * 
	 * @param integer $which_width The chart's with
	 * @param integer $which_height The chart's hight
	 * @param string $which_output_file The output file to render the graph to
	 * @param string $which_input_file The input file with the data to render
	 * @return void
     * @see lib/phplot/PHPlot_TrueColor#__construct($which_width = 600, $which_height = 400, $which_output_file = NULL, $which_input_file = NULL)
	 */
	public function __construct(
		$which_width = 587, 
		$which_height = 300, 
		$which_output_file = NULL, 
		$which_input_file = NULL) {
		// pass the values to the parent class
		parent::__construct(
			$which_width, 
			$which_height, 
			$which_output_file, 
			$which_input_file
		);
		// set the default values
		$this->SetMarginsPixels(30, 30, 30, 30);
		$this->SetIsInline(true);
		$this->SetPlotType('area');
		$this->SetPrecisionY(0);
		$this->SetTickColor('#f4f4f4');
		$this->SetXTickLength(10);
		$this->SetBackgroundColor('#f4f4f4');
		$this->SetDrawXGrid(true);
		$this->SetDataColors('#f4d4b2');
		$this->SetLineWidths(2);
		$this->SetDrawPlotAreaBackground(true);
		$this->SetDataType('data-data');
		$this->SetPlotBorderType('left');
		$this->SetDrawDashedGrid(false);
		$this->SetTextColor('#999999');
		// set the callback for rendering the lines, because
		// by default only the areas will be rendered
		$this->SetCallback('draw_graph', array($this, 'RenderLines'), '#db4814');		
	}
	
	/**
	 * The values to render the chart for.
	 * 
	 * @param array $which_dv The data to render the chart for
	 * @return void
     * @see lib/phplot/PHPlot_TrueColor#SetDataValues($which_dv)
	 */
	public function SetDataValues($which_dv)
	{
		// calculate the maximum value
		$maxvalue = 0;
		for ($i = 0; $i < sizeof($which_dv); $i++) {
			$comp = (integer) $which_dv[$i][2];
			if ($comp > $maxvalue) {
				$maxvalue = $comp;
			}
		}
		// initialize the array for the Y labels
		$yLabels = array();
    	// calculate the maximum Y value
        if ($maxvalue > 10) {
        	$p = pow(10, $this->_getPow($maxvalue));
            $maxy = (ceil($maxvalue / $p)) * $p;
            $yLabels = range(0, $maxy, $p);
        } else {
            $maxy = ceil($maxvalue + 1);
            $yLabels = range(0, $maxy, 1);
        }
		// set the plot area
		$this->SetPlotAreaWorld(NULL, NULL, NULL, $maxy);
        // calculate the incrementation value for the Y axis
        if (sizeof($yLabels) - 1) {
        	$deltaY = $maxy / (sizeof($yLabels) - 1);
        } else {
        	$deltaY = $maxy;
        }
        // set the incrementation value for the Y axis
		$this->SetYTickIncrement($deltaY);
        // setting skip step
        if (count($which_dv) > 8 && count($which_dv) < 15) {
            $c = 1;
        } else if (count($which_dv) >= 15) {
            $c = 2;
        } else {
            $c = 0;
        }
        // skipping some X axis labels for good reading
        $i = 0;
        foreach ($which_dv as $k => $d) {
            if ($i == $c) {
                $which_dv[$k] = $d;
                $i = 0;
            } else {
                $which_dv[$k] = array('', $d[1], $d[2]);
                $i++;
            }
        }
        // set the size of ticks for the X axis
		$this->SetNumXTicks(sizeof($which_dv) - 1);
		// pass the data values
		parent::SetDataValues($which_dv);
	}

	/**
	 * Callback method for rendering the lines
	 * not the areas only.
	 * 
	 * @param resource $img The image resource
	 * @param string $lineColor The color for the line to render
	 */
	public function RenderLines($img, $lineColor)
	{
		$this->SetDataColors($lineColor);
		$this->DrawLines(True);
	}

	/**
	 * Returns the exponential value based
	 * to ten for the passed number.
	 * 
	 * @param integer $number The value to calulate the exponential value for
	 * @return integer The calculated value
	 */
    protected function _getPow($number)
    {
        $pow = 0;
        while ($number >= 10) {
            $number = $number / 10;
            $pow++;
        }
        return $pow;
    }
}