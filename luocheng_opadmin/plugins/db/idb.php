<?php 
namespace plugins\db;
use controllers\module as MODULE;
use kernel\base\base_class\string as STRING;
class idb extends module
{
	function collection_list()
	{
		$collection_list=$this->phpmongo->getCollectionNames();
		$list=array();
		foreach($collection_list as $value)
		{
			$list[$value]=$this->phpmongo->find($value)->count();
		}
		
		return $list;
	}
}
		