<?php
namespace kernel\base\base_class;
/**
 * 条件类
 */
class conditions{
	var $mem;
	var $mongo;
	var $phpmongo;
	var $res;
	var $fairyism;
	var $error;
	
	/**
	 * 构造函数
	 */
	function __construct()
	{
		global $mem,$mongo;
		$this->mongo=$mongo;
		$this->phpmongo=$mongo->mongo;
		$this->mem=$mem;
		$this->getclassfun();
	}
	/**
	 * 析构函数
	 */
	function __destruct()
	{
		unset($this->mem);
	}

	/**
	 * 描述：取得mem数据处理大全
	 * 返回值 1：
	 */
	function getclassfun()
	{
	}


	/**
	 * 通用条件函数
	 *
	 * @param int $user_id 用户)ID
	 * @param int $condition 条件
	 * @param int $goods_user_id 物品ID	主要是传递作用目标物品)
	 * @param bool $state 是否执行
	 * @return BOOL
	 */
	function c_d($user_id,$condition,$goods_user_id=0,$state=false)
	{
		if(!$condition){return true;}
		$o=0;
		$i=0;
		$conditions_info=$condition;

		$conditions_info_arr=arrayed($conditions_info);

		$user_info=$this->mongo->user_info($user_id);

		$equip=$this->mongo->getequip($user_id);
		foreach((array)$conditions_info_arr as $key=>$value)
		{
			switch($value[0])
			{
				case 2://一级用户数据
					if($user_info[$key]>=$value[1])
					{
						$o++;
					}
					break;

				case 3://二级用户属性 力量 敏捷等
					$use_potential=arrayed($user_info["use_potential"]);
					if($use_potential[$key]>=$value[1])
					{
						$o++;
					}
					break;

				case 4://二级装备数据
					if($equip[$key]==$value[1])
					{
						$o++;
					}
					break;
					
				case 5://奇门遁甲
//					if($equip[$key]>=$value[1])
//					{
//						$o++;
//					}
					break;
					
				case 6://忍术
//					if($equip[$key]>=$value[1])
//					{
//						$o++;
//					}
					break;

				Default://默认 0、1  整形、浮点型
					$property=arrayed($user_info['property']);
					if($property[$key][1]>=$value[1])
					{
						$o++;
					}
			}
		}
		if($o==count($conditions_info_arr))
		{
			if($state)
			{
				return $this->xconditions($user_id,$c_id,$goods_user_id);
			}else{
				return true;
			}
		}else{
			return false;
		}
	}

	/**
	 * 执行条件
	 *
	 * @param int $user_id 用户)D
	 * @param int $c_id 条件ID
	 * @param int $goods_user_id 物品ID
	 * @return bool 
	 */
	function xconditions($user_id,$c_id,$goods_user_id=0)
	{
		$o=0;
		$i=0;
		require(D_R."res/data/base_condition.php");
		$conditions_=$base_condition[$c_id];
		$conditions_action=$conditions_['action'];
		$conditions_action_arr=arrayed($conditions_action);
		if(!$conditions_action_arr){return false;}
		if($goods_user_id)
		{
			$conditions_['object']=$goods_user_id;
		}

		if($conditions_['object']=='user')
		{
			$user_info=$this->mongo->user_info($user_id);
		}elseif($conditions_['object']){
			$goods_property_arr=arrayed($this->mongo->getgoods($user_id,$conditions_['object'],'property'));
		}


		foreach($conditions_action_arr as $key=>$value)
		{
			switch($value[0])
			{
				case"2"://一级用户数据
					if($conditions_['object']=='user')
					{
						$user_info[$key]=$this->c_d_f($user_info[$key],$value[1]);
					}

					break;

				Default://默认 0、1  整形、浮点型
					$property=arrayed($user_info['property']);

					if($conditions_['object']=='user')
					{
						$property[$key][1]=$this->c_d_f($property[$key][1],$value[1]);
						$user_info['property']=stred($property);

					}elseif($conditions_['object']){
						$goods_property_arr[$key][1]=$this->c_d_f($goods_property_arr[$key][1],$value[1]);
						$goods_property_str=stred($goods_property_arr);
					}
			}
		}

		if($conditions_['object']=='user')
		{
			if($user_info)
			{
				$this->mongo->up_user_info($user_info,$user_id);
			}
		}elseif($conditions_['object']){
			if($goods_property_str)
			{
				$this->mongo->upgoods($goods_property_str,$user_id,$conditions_['object'],'property');
			}
		}
		return true;
	}

	/**
	 * 数值运算
	 *
	 * @param int $int $int原始值
	 * @param stirng $str $str包含运算符的值
	 * @return int
	 */
	function c_d_f($int,$str)
	{
		$s=substr($str,0,1);
		if($s=='+')
		{
			return $int+substr($str,1,strlen($str));
		}else	if($s=='-'){
			return $int-substr($str,1,strlen($str));
		}else	if($s=='*'){
			return $int*substr($str,1,strlen($str));
		}else	if($s=='/'){
			return $int/substr($str,1,strlen($str));
		}else{
			return $int+substr($str,1,strlen($str));
		}
	}
}

?>