<?php
namespace kernel\base\tool;
# 
# PHPZip v2.0 by alacner (alacner@gmail.com) 2005-12-31
# QQ:964142  MSN:alacn@msn.com
# Makes zip archive
#
# Based on "Zip file creation class", uses zLib
#
# More Info "readme.txt"
#
/**
 * PHPZip压缩类
 *
 */
class phpzip
{

	var $dirInfo = array("0","0"); 
	var $rootDir = '';
	/**
	 * 压缩文件
	 *
	 * @param string $dir 压缩后保存目录
	 * @param string $zipfilename 要压缩的文件
	 */
	function Zip($dir, $zipfilename)
	{
    	if (@function_exists('gzcompress'))
		{	@set_time_limit("0");
			if (is_array($dir)) 
			{
				$fd = fopen ($dir, "r");
				$fileValue = fread ($fd, filesize ($filename));
				fclose ($fd);
				if (is_array($dir)) $filename = basename($dir);
				$this -> addFile($fileValue, "$filename");
			}
			else 
			{
			$this->dirTree($dir,$dir);	
			}
			$out = $this -> filezip();
            		$fp = fopen($zipfilename, "w");
			fwrite($fp, $out, strlen($out));
			fclose($fp);
			}
	}//end func.
	
	
//recursion get dir tree..
function dirTree($directory,$rootDir)
{
	global $_SERVER,$dirInfo,$rootDir;
	//echo "<ul>\n";
	$fileDir=$rootDir;
	if(!file_exists($file)){
		createDir($directory);
	}
	
	$myDir=dir($directory);
    while($file=$myDir->read())
	{
		if(is_dir("$directory/$file") and $file!="." and $file!="..")
		{
			if($file==".svn")
			{
				continue;
			}
			$dirInfo[0]++;
			$rootDir .="$file/";
			$this -> addFile('', "$rootDir");
			
			$this->dirTree("$directory/$file",$rootDir);
		}else{
			if($file!="." and $file!="..")
			{
				if($file==".xml"){continue;}
				
				$dirInfo[1]++;
				$fd = fopen ("$directory/$file", "r");
				if(filesize ("$directory/$file")==0)
				{
					continue;
				}
				$fileValue = fread ($fd, filesize ("$directory/$file"));
				fclose ($fd);
				
				$this -> addFile($fileValue, "$fileDir$file");
			}
		}
	}
	$myDir->close();
}


//////////

    var $datasec      = array();
    var $ctrl_dir     = array();
    var $eof_ctrl_dir = "\x50\x4b\x05\x06\x00\x00\x00\x00";
    var $old_offset   = 0;

    /**
     * Converts an Unix timestamp to a four byte DOS date and time format (date
     * in high two bytes, time in low two bytes allowing magnitude comparison).
     *
     * @param  integer  the current Unix timestamp
     *
     * @return integer  the current date in a four byte DOS format
     *
     * @access private
     */
    function unix2DosTime($unixtime = 0) {
        $timearray = ($unixtime == 0) ? getdate() : getdate($unixtime);

        if ($timearray['year'] < 1980) {
        	$timearray['year']    = 1980;
        	$timearray['mon']     = 1;
        	$timearray['mday']    = 1;
        	$timearray['hours']   = 0;
        	$timearray['minutes'] = 0;
        	$timearray['seconds'] = 0;
        } // end if

        return (($timearray['year'] - 1980) << 25) | ($timearray['mon'] << 21) | ($timearray['mday'] << 16) |
                ($timearray['hours'] << 11) | ($timearray['minutes'] << 5) | ($timearray['seconds'] >> 1);
    } // end of the 'unix2DosTime()' method


    /**
     * Adds "file" to archive
     *
     * @param  string   file contents
     * @param  string   name of the file in the archive (may contains the path)
     * @param  integer  the current timestamp
     *
     * @access public
     */
    function addFile($data, $name, $time = 0)
    {
        $name     = str_replace('\\', '/', $name);

        $dtime    = dechex($this->unix2DosTime($time));
        $hexdtime = '\x' . $dtime[6] . $dtime[7]
                  . '\x' . $dtime[4] . $dtime[5]
                  . '\x' . $dtime[2] . $dtime[3]
                  . '\x' . $dtime[0] . $dtime[1];
        eval('$hexdtime = "' . $hexdtime . '";');

        $fr   = "\x50\x4b\x03\x04";
        $fr   .= "\x14\x00";            // ver needed to extract
        $fr   .= "\x00\x00";            // gen purpose bit flag
        $fr   .= "\x08\x00";            // compression method
        $fr   .= $hexdtime;             // last mod time and date

        // "local file header" segment
        $unc_len = strlen($data);
        $crc     = crc32($data);
        $zdata   = gzcompress($data);
        $c_len   = strlen($zdata);
        $zdata   = substr(substr($zdata, 0, strlen($zdata) - 4), 2); // fix crc bug
        $fr      .= pack('V', $crc);             // crc32
        $fr      .= pack('V', $c_len);           // compressed filesize
        $fr      .= pack('V', $unc_len);         // uncompressed filesize
        $fr      .= pack('v', strlen($name));    // length of filename
        $fr      .= pack('v', 0);                // extra field length
        $fr      .= $name;

        // "file data" segment
        $fr .= $zdata;

        // "data descriptor" segment (optional but necessary if archive is not
        // served as file)
        $fr .= pack('V', $crc);                 // crc32
        $fr .= pack('V', $c_len);               // compressed filesize
        $fr .= pack('V', $unc_len);             // uncompressed filesize

        // add this entry to array
        $this -> datasec[] = $fr;
        $new_offset        = strlen(implode('', $this->datasec));

        // now add to central directory record
        $cdrec = "\x50\x4b\x01\x02";
        $cdrec .= "\x00\x00";                // version made by
        $cdrec .= "\x14\x00";                // version needed to extract
        $cdrec .= "\x00\x00";                // gen purpose bit flag
        $cdrec .= "\x08\x00";                // compression method
        $cdrec .= $hexdtime;                 // last mod time & date
        $cdrec .= pack('V', $crc);           // crc32
        $cdrec .= pack('V', $c_len);         // compressed filesize
        $cdrec .= pack('V', $unc_len);       // uncompressed filesize
        $cdrec .= pack('v', strlen($name) ); // length of filename
        $cdrec .= pack('v', 0 );             // extra field length
        $cdrec .= pack('v', 0 );             // file comment length
        $cdrec .= pack('v', 0 );             // disk number start
        $cdrec .= pack('v', 0 );             // internal file attributes
        $cdrec .= pack('V', 32 );            // external file attributes - 'archive' bit set

        $cdrec .= pack('V', $this -> old_offset ); // relative offset of local header
        $this -> old_offset = $new_offset;

        $cdrec .= $name;

        // optional extra field, file comment goes here
        // save to central directory
        $this -> ctrl_dir[] = $cdrec;
    } // end of the 'addFile()' method


    /**
     * Dumps out file
     *
     * @return  string  the zipped file
     *
     * @access public
     */
    function filezip()
    {
        $data    = implode('', $this -> datasec);
        $ctrldir = implode('', $this -> ctrl_dir);

        return
            $data .
            $ctrldir .
            $this -> eof_ctrl_dir .
            pack('v', sizeof($this -> ctrl_dir)) .  // total # of entries "on this disk"
            pack('v', sizeof($this -> ctrl_dir)) .  // total # of entries overall
            pack('V', strlen($ctrldir)) .           // size of central dir
            pack('V', strlen($data)) .              // offset to start of central dir
            "\x00\x00";                             // .zip file comment length
    } // end of the 'filezip()' method
} // end of the 'PHPZip' class
?>