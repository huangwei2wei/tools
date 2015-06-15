<?php 
namespace plugins\check_id;
use controllers\module as MODULE;
use kernel\base\base_class\string as STRING;
class icheck_id extends module
{
		function check_list($page)
			{
		     
				$info=array();
			 
				$info_arr=restoarray($this->phpmongo->find("user_base"));
		
				foreach((array)$info_arr as $key=>$value)
				{
					$user_goods=$this->phpmongo->findone("user_hero",array("user_id"=>$value["id"],));

					if($value)
					{
						$info[]=array(
		  				"user_name"=>$value["user_name"],
						"nickname"=>$user_goods["name"],			
						);
		
					}
				}

				return spage($info,15,$page);
			}

}