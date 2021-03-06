<?php 
namespace plugins\ex;
use controllers\module as MODULE;

class iex extends module
{
	//用户列表
	function addex()
	{
		require_once D_R.'/kernel/base/tool/Excel/reader.php';


		// ExcelFile($filename, $encoding);
		$data = new \Spreadsheet_Excel_Reader();
		
		
		// Set output Encoding.
		//$data->setOutputEncoding('CP1251');
		//$data->setOutputEncoding('gb2312');
		$data->setOutputEncoding('utf-8');
		
		/***
		* if you want you can change 'iconv' to mb_convert_encoding:
		* $data->setUTFEncoder('mb');
		*
		**/
		
		/***
		* By default rows & cols indeces start with 1
		* For change initial index use:
		* $data->setRowColOffset(0);
		*
		**/
		
		
		
		/***
		*  Some function for formatting output.
		* $data->setDefaultFormat('%.2f');
		* setDefaultFormat - set format for columns with unknown formatting
		*
		* $data->setColumnFormat(4, '%.3f');
		* setColumnFormat - set format for column (apply only to number fields)
		*
		**/
		
		$data->read(D_R.'/test/base3_c_key.xls');
		
		/*
		
		
		 $data->sheets[0]['numRows'] - count rows
		 $data->sheets[0]['numCols'] - count columns
		 $data->sheets[0]['cells'][$i][$j] - data from $i-row $j-column
		
		 $data->sheets[0]['cellsInfo'][$i][$j] - extended info about cell
			
			$data->sheets[0]['cellsInfo'][$i][$j]['type'] = "date" | "number" | "unknown"
				if 'type' == "unknown" - use 'raw' value, because  cell contain value with format '0.00';
			$data->sheets[0]['cellsInfo'][$i][$j]['raw'] = value if cell without format 
			$data->sheets[0]['cellsInfo'][$i][$j]['colspan'] 
			$data->sheets[0]['cellsInfo'][$i][$j]['rowspan'] 
		*/
		
		error_reporting(E_ALL ^ E_NOTICE);
		$dataarr=array();
		
		for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++) {
			$datavalue=array();
			for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++) {
				if(strlen($data->sheets[0]['cells'][$i][$j])>0)
				{
					$datavalue[]=$data->sheets[0]['cells'][$i][$j];
					//echo "\"".$data->sheets[0]['cells'][$i][$j]."\",";
				}
			}
		//	echo "\n";
			unset($datavalue[0]);
			$dataarr[]=$datavalue;
		
		}
		
		$newdata=array();
		for ($i=0;$i<count($dataarr);$i++)
		{
			if(isset($newdata[$dataarr[$i][1]]))
			{
				$newdata[$dataarr[$i][1]].=$dataarr[$i][2].",";
			}else{
				$newdata[$dataarr[$i][1]]=$dataarr[$i][2].",";
			}
		}
		
		foreach($newdata as $key =>$value)
		{
			dbinsert("base3_c_key", array("key","allkey"), array($key,substr($value,0,-1)));
		}
	}
}