<?php 
namespace kernel\base\base_class;

class log{
	private static $log_dir;
	private static $linux;
	public static $err;
	/**
	 * 初始化环境
	 *
	 */
	private static function init(){
		if (defined('LOG_DIR')) {
			self::$log_dir = str_replace('\\','/',D_R.LOG_DIR).'/';
		}else {
			self::$log_dir = str_replace('\\','/',D_R).'log/'; //默认
		}
		self::$linux = (DIRECTORY_SEPARATOR == "\\" ? false : true);
	}
	
	/**
	 * 登录日志写入
	 *
	 * @param string $message 要写入的日志内容
	 */
	public  static function login_log($message = "")
	{
		$dir=D_R."res/log/login/".date("Y-m",time()).'/';
		createDir($dir);
		$dir1=date("d",time()).'.log';
		
		if(file_exists($dir.$dir1))
		{
			$str=file_get_contents($dir.$dir1);
		}else{
			file_put_contents($dir.$dir1,"");
		}
		$strn=$message.'-------------'.date("Y-m-d H:i:s",time()).'<br>';
		$str.=$strn;
		
		if (is_writable($dir.$dir1))
		{
			file_put_contents($dir.$dir1,$str);
		} else {
			self::$err = $dir.$dir1.' The file is not writable'; //这个文件不可写
			return false;
		}
	}
	
	/**
	 * 写日志
	 *
	 * @param string $message 要写入的日志内容
	 */
	public static function write($message = ""){
		self::init();
		if (is_dir(self::$log_dir) && is_writable(self::$log_dir)) {
			$datedir = self::$log_dir.date('Y-m-d').'/';
			if (!is_dir($datedir)) {
			 	mkdir($datedir);
//			 	chmod($datedir,0777);
			}
			$en = (self::$linux ? "\n" : "\r\n"); //换行符
			$hour = date('H');
			$fp = fopen($datedir.$hour.'-'.($hour+1).'.txt','ab');
			fwrite($fp,$en.date('Y-m-d H:i:s')." : ".$message.'['.get_ip().']');
			fclose($fp);
			return true;
		}else {
			self::$err = "目录不存在或者不可写！";
			return false;
		}
	}
        
	/**
	 * 程序错误日志
	 *
	 * @param string $message 要写入的日志内容
	 */
	public static function error($message = ""){
		self::init();
		if (is_dir(self::$log_dir) && is_writable(self::$log_dir)) {
			$datedir = self::$log_dir.date('Y-m-d').'/';
			if (!is_dir($datedir)) {
			 	mkdir($datedir);
//			 	chmod($datedir,0777);
			}
			$en = (self::$linux ? "\n" : "\r\n"); //换行符 
			$hour = date('H');
			$fp = fopen($datedir.$hour.'-'.($hour+1).'_error.txt','ab');
			fwrite($fp,$en.date('Y-m-d H:i:s')." : ".$message.'['.get_ip().']');
			fclose($fp);
			return true;
		}else {
			self::$err = "目录不存在或者不可写！";
			return false;
		}
	}
	/**
	 * 根据日期列出当天的日志文件，如果为空则显示当天的日志文件
	 *
	 * @param string $date
	 * @return array $file_list 日志文件列表
	 */
	public static function file_list($date = ""){
		self::init();
		if (!$date) {
			$date = date('Y-m-d');
		}
		$datedir = self::$log_dir.$date.'/';
		if (is_dir($datedir)) {
			$file_list = scandir($datedir);
			foreach ($file_list as $key => $value) {
				if ($value == '.' || $value == '..' ) {
					unset($file_list[$key]);
				}/*else {
					$file_list[$key] = self::$log_dir.$date.'/'.$value;
				}*/
			}
			return $file_list;
		}else {
			return array();
		}
	}

	/**
	 * 根据文件名读取文件内容
	 *
	 * @param string $date
	 * @param string $filename
	 */
	public static function read($date = "", $filename){
		if (!$date) {
			$date = date('Y-m-d');
		}
		$datedir = self::$log_dir.$date.'/';
		if (is_dir($datedir) AND file_exists($datedir.$filename)) {
			return file($datedir.$filename);
			return file_get_contents($datedir.$filename);
		}else {
			self::$err = "读取目录不存在！";
			return array();
		}
	}
}
?>