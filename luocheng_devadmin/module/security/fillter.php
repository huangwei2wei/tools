<?php
/**
*@name class.fillter.php
*@abstract　过滤器,按关键字过滤
*@author 刘顺平(liushunping@tomonline-inc.com)
*@copyright www.tom.com 2004-2008
*@date 2009-6-9
*/
class fillter
{
   private $keyword_file;
   private $dict;
   public $result;

   public function __construct($file)
   {
		if(!is_file($file))
		{
			trigger_error("$file not exists!");
		}
		$this->keyword_file=$file;
	}

	public function fill($resource)
	{
		$this->dict = $this->getDict();
		$len = strlen($resource);
		for($i=0; $i<$len; ++$i)
		{
			$key=substr($resource,$i,2);
			if(array_key_exists($key,$this->dict))
			{
				$this->deal(substr($resource,$i,$this->dict[$key]['max']),$key,$af);
				$i+=$af;
			}else{
				$this->result .=substr($resource,$i,1);
			}
		}
		return $this->result;
	}

   /*
   **匹配到了关键字时的处理
   **$res 源字符串
   **$keywords　关键字数组
   */
	public function deal($res,$key,&$af)
	{
		$af=0;
		foreach($this->dict[$key]['list'] as $keyword)
		{
			if(strpos($res,$keyword) !==false)
			{
				$len=strlen($keyword);
				$af=$len-1;
				$this->result .=str_repeat("*",$len);
				return;
			}
		}
		$this->result .= substr($res,0,1);
	}

   /*
   **获取关键字列表
   */
	private function getKeyWordList()
	{
		$keywords = file_get_contents($this->keyword_file);
		return array_unique(explode("\r\n",$keywords));
	}

	public function getDict()
	{
		$keywords=$this->getKeyWordList();
		$dict=array();
		foreach($keywords as $keyword)
		{
			if(empty($keyword))
			{
				continue;
			}
			$key = substr($keyword,0,2);

			$dict[$key]['list'][]=$keyword;
			$dict[$key]['max']=max($dict[$key]['max'],strlen($keyword));
		}
		return $dict;
	}
}

//测试用demo 结果比直接用正则提高了３倍效率
$res = $text = file_get_contents("test.txt");
$t1 = microtime(true);

$fil = new fillter("banwords.txt");
$res = $fil->fill($text);
//$res = $fil->getDict();
//$keywords = explode("\r\n",file_get_contents("banwords.txt"));
//foreach($keywords as $keyword){
// if(empty($keyword)){
//   continue;
// }
// $res = preg_replace("/$keyword/","***",$res);
//}
$t2 = microtime(true);
echo $t2-$t1;
echo $text,"<br/>";
echo "<pre>";
print_r( $res);
echo "</pre>";
?>