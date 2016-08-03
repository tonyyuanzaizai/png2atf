<?php
set_time_limit(0); 
define( 'PATH_ROOT', dirname(__FILE__).'/' );

if(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    define( 'PNG2ATF_PATH', PATH_ROOT . 'tools/win/png2atf.exe');
} 
else {
    define( 'PNG2ATF_PATH', PATH_ROOT . 'tools/mac/png2atf.exe');
}

if(count($argv) < 2){
	echo  'Usage1: php png2atf.php all';
	echo  PHP_EOL;
	echo  'Usage2: php png2atf.php android';
	echo  PHP_EOL;
	echo  'Usage3: php png2atf.php ios';
	echo  PHP_EOL;
	echo  'Usage4: php png2atf.php andios';
	echo  PHP_EOL;
	return;
}

$output_type = $argv[1];
$support_params = array('all', 'ios', 'android', 'andios');
if(in_array($output_type, $support_params)){
	//
}
else {
	echo  'Usage1: php png2atf.php all';
	echo  PHP_EOL;
	echo  'Usage2: php png2atf.php android';
	echo  PHP_EOL;
	echo  'Usage3: php png2atf.php ios';
	echo  PHP_EOL;
	echo  'Usage4: php png2atf.php andios';
	echo  PHP_EOL;
	return;
}

$fileInput = PATH_ROOT . '../assets-png/';
$fileOutput = PATH_ROOT . '../assets-atf/';
include 'file_tool.php';

$fileArr = listAllFiles($fileInput);
del_Dir($fileOutput);

for($i = 0; $i < count($fileArr); $i++){
	$oneFilePath = $fileArr[$i];
	if(strpos($oneFilePath, '.png')){
		$oneFileName = getFileInfo($oneFilePath)['basename'];
		$fileOutputFullPath = $fileOutput . substr($oneFilePath, strlen($fileInput));
		$fileOutputFullPath = substr($fileOutputFullPath, 0, -4);
		$fileOutputFullPath = $fileOutputFullPath . '.atf';
		
		
		$png_path = $oneFilePath;
		$atf_path = $fileOutputFullPath;
		$fileOutputFullDir = substr($fileOutputFullPath, 0, 0 - strlen($oneFileName));
		mkdir($fileOutputFullDir, 777, true);
		//$output_type = $argv[1];
		//$support_params = array('all', 'ios', 'android', 'andios');
		if($output_type == 'all'){
			png2atf_all($png_path, $atf_path);
		}else if($output_type == 'ios'){
			png2atf_ios($png_path, $atf_path);
		}else if($output_type == 'android'){
			png2atf_ad($png_path, $atf_path);
		}else if($output_type == 'andios'){
			png2atf_adios($png_path, $atf_path);
		}
	}
}

function png2atf_ad($png_path, $atf_path){
	$cmd = PNG2ATF_PATH.' -c e -r -e -i ' . $png_path.' -o ' . $atf_path;
	system($cmd);
}

function png2atf_ios($png_path, $atf_path){
	$cmd = PNG2ATF_PATH.' -c p -r -e -i ' . $png_path.' -o ' . $atf_path;
	system($cmd);
}
function png2atf_adios($png_path, $atf_path){
	$cmd = PNG2ATF_PATH.' -c -r -e -i ' . $png_path.' -o ' . $atf_path;
	echo $cmd;
	system($cmd);
}

function png2atf_all($png_path, $atf_path){
	$cmd = PNG2ATF_PATH.' -c -i ' . $png_path.' -o ' . $atf_path;
	system($cmd);
}
?>