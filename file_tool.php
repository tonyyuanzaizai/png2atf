<?php

function compressPng($cmd, $value) {
    if(empty($cmd)){
    	$cmd = '../tool/pngquant/pngquant.exe';
    }
    
    $cmd = $cmd . ' --nofs --speed 1 160 --ext mot.png --force ' . $value;
    
    system($cmd);
    //ɾ���ļ����������ļ�
    $newfilename = $value;
    unlink($newfilename);
    $oldfilename = str_replace(".png", "mot.png", $value);//$value;
    rename($oldfilename,$newfilename);
}

function getFileContentsArr($txt_old_file){
	$idx = 0;
	$file = fopen($txt_old_file, "r") or exit("Unable to open file:".$txt_old_file);
	//Output a line of the file until the end is reached
	while(!feof($file)){
		$str = fgets($file);
		$str = trim($str);
		
		$txt_old_file_array[$idx] = $str;
		
		$idx++;	
	}
	
	return $txt_old_file_array;
}

/**
 * �ļ��и���
 */
function recurse_copy($src,$dst) {  // ԭĿ¼�����Ƶ���Ŀ¼
    $dir = opendir($src);
    @mkdir($dst);
    while(false !== ( $file = readdir($dir)) ) {
        if (( $file != '.' ) && ( $file != '..' )) {
            if ( is_dir($src . '/' . $file) ) {
	            if(!file_exists($dst . '/' . $file)){
					mkdir($dst . '/' . $file, 0777, true);
				}
                recurse_copy($src . '/' . $file,$dst . '/' . $file);
            }
            else {
                copy($src . '/' . $file,$dst . '/' . $file);
            }
        }
    }
    closedir($dir);
}

/**
 * ɾ���ļ���
 */
function del_Dir($dir) {
  //��ɾ��Ŀ¼�µ��ļ���
  $dh=opendir($dir);
  while ($file=readdir($dh)) {
    if($file!="." && $file!="..") {
      $fullpath=$dir."/".$file;
      if(!is_dir($fullpath)) {
          unlink($fullpath);
      } else {
          del_Dir($fullpath);
      }
    }
  }
 
  closedir($dh);
  //ɾ����ǰ�ļ��У�
  if(rmdir($dir)) {
    return true;
  } else {
    return false;
  }
}
/**
 * ɾ���ļ���
 */
function del_DirContents($dir) {
  //��ɾ��Ŀ¼�µ��ļ���
  $dh=opendir($dir);
  while ($file=readdir($dh)) {
    if($file!="." && $file!="..") {
      $fullpath=$dir."/".$file;
      if(!is_dir($fullpath)) {
          unlink($fullpath);
      }
    }
  }
 
  closedir($dh);
}
/**
 * ɨ���ض���׺���ļ�
 */
function scan_Files($dir, $suffix) {
    $arrfiles = array();
    if (is_dir($dir)) {
        if ($handle = opendir($dir)) {
            chdir($dir);
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != "..") {
                    if (is_file($file) && strpos($file, '.' . $suffix) > 0) {
                        $arrfiles[] = $file;
                    }
                }
            }
        }
        closedir($handle);
    }
    return $arrfiles;
}


function write_File($file, $content_arr) {
	file_put_contents($file, "");
	for ($i = 0; $i < count($content_arr); $i++) {  
		file_put_contents($file, $content_arr[$i] . "\n", FILE_APPEND);
	}
}

function append_File($file, $content_arr) {
	for ($i = 0; $i < count($content_arr); $i++) {  
		file_put_contents($file, $content_arr[$i] . "\n", FILE_APPEND);
	}
}

function getFileName($file) {
	$idx = strpos($file, '.');
	return substr($file, 0, $idx);
}

/**
 * /usr/admin/config/test.xml
 *
 * /usr/admin/config
 * test.xml
 * xml
 * test
 *
 * $path = "/home/httpd/html/index.php";
 * $file = basename($path);         // $file is set to "index.php"
 * $file = basename($path, ".php"); // $file is set to "index"
 **/
function getFileInfo($file) {
	$fileInfo = array();
	$innerInfo = pathinfo($file);
	
	$fileInfo['dirname']  = $innerInfo['dirname'];
	$fileInfo['basename'] = $innerInfo['basename'];
	$fileInfo['extension'] = $innerInfo['extension'];
	$fileInfo['filename'] = $innerInfo['filename'];
	
	$fileInfo['modifytime'] = filemtime($file);
	$fileInfo['createtime'] = filectime($file);
	

	return $fileInfo;
}

/**
 * ɨ�����е��ļ�
 */
function listAllFiles($dir) {
    global $files;
	if(!isset($files)){
		$files = array();
	}
    
    $dir_list = scandir($dir);  
    foreach($dir_list as $file) {  
        if( $file != ".." && $file != "." ) {  
            if(is_dir($dir . "/" . $file)) {  
                listAllFiles($dir . "/" . $file);  
            }
            else {
				$files[] = $dir . "/" . $file;                  
            }  
        }  
    }  

    return $files;  
}  

/***
define( 'PATH_ROOT', dirname(__FILE__).'\\' );

$input_path = PATH_ROOT . 'input\\';

$arr = listAllFiles($input_path);
echo $arr[0];
print_r(getFileInfo($arr[0]));


define( 'PATH_ROOT', dirname(__FILE__).'\\' );

$arr1 = getFileContentsArr(PATH_ROOT . 'keys.txt');
$arr2 = getFileContentsArr(PATH_ROOT . 'en_v.txt');

echo count($arr1);
echo ' ';
echo count($arr2);

for($i = 0; $i < count($arr1); $i++){
	$arr[] = $arr1[$i] . ' = ' . $arr2[$i];
}

write_File('en_us.txt', $arr);
***/
?>