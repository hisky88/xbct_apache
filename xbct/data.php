<?php
include_once 'util.php';
include_once 'log.php';

echo "<pre>";
print_r($_REQUEST);
echo "</pre>";

//$con=mysqli_connect("127.0.0.1", "xbct", "db_passw0rd");
//mysqli_select_db($con,"xbct") or die("Cannot select DB");

//Process request
if($_REQUEST['submit']){
	//Get test session id
	?>
	<script type="text/javascript" src="browserDetect.js"></script>
	<form method="POST">
		<fieldset style="width:220px">
		<legend>Test Session Data:</legend>
		<label for="id">ID: </label><input type="text" name="id" id="id" /><br /><small>(Leave blank for the first test)</small><br/>
		<input type="hidden" name="data" value="<?php echo base64_encode($_REQUEST['data']); ?>" />
		<input type="hidden" name="referrer" value="<?php echo urlencode($_SERVER['HTTP_REFERER']); ?>" />
			
		<input type="text" name="browsername" id="browsername" /bb
		<input type="submit" name="submit" value="Submit">
		</fieldset>
	</form>
	<script type="text/javascript">
		document.getElementById("browsername").value=BrowserDetect.browser+" "+BrowserDetect.version+"/"+BrowserDetect.OS;
	</script>
	
	<?php 
}else{
	if(!$_REQUEST[id]){
		$sessId = rand_str(4);
		while(db_session_exists($sessId)){ //random str already in database
			$sessId = rand_str(4);
		}
		//print("Generated test session id: ");
		//print("<b>".$sessId."</b>");
		//print("<br /> <small>Please use this id across all browsers for testing this webpage.</small>");
		
		//Add the session-id to the database
		///mysql_query("INSERT INTO sessions (sessid, url) VALUES ('".$sessId."', '".addslashes($_POST['referrer'])."')");
		
	}else{
		$sessId = $_REQUEST[id];
		$url = db_session_url($sessId);
		if($url != "NULL"){
			///print("Found existing test session with id: ".$sessId." with url: ".urldecode($url));			
		}else{
			//print("No test session found for the ID: ".$sessId);
			//die();
		}
	}
	//Parse JSON data and save data to mysql
//	mysqli_query($con,"INSERT INTO domdata (`domid`, `testid`, `arktos`, `id`, `name`, `type`, `x0`, `y0`, `x1`, `y1`, `clickable`, `visible`, `parentid`, `children`, `xpath`, `zindex`, `contenthash`, `non_det`) VALUES ('test', 0, 0, 0, 'BODY', 0, 0, 0, 0, 0, 0, 0, 0, 'DIV 0,DIV 0,DIV 0,SCRIPT 0,SCRIPT 0,SCRIPT 0,SCRIPT 0', '/HTML/BODY', 0, '0xB2205EA7', 0)");

	$temp=$_POST['data'];
	//echo 'x';
	//print_r($_POST);
	//echo "<!-- \n ".print_r($_POST['data'],true)."\n -->";   
	$bpos=strpos($temp,'{');
	$testid=substr($temp,0,($bpos+1));
	$testid=(int)$testid;
	$temp=substr($temp,$bpos);
	
	$data = json_decode(stripslashes($temp), true);
	//echo "<!-- \n ".print_r($data,true)."\n -->";
	//$query = "INSERT INTO tests (browser,sessid, user_agent) VALUES ('".addslashes($_REQUEST['browsername'])."','".addslashes($sessId)."','".addslashes($_SERVER['HTTP_USER_AGENT'])."')";
	//mysql_query($query);
	//$testid = mysql_insert_id();
	addDomDataToDB($data, $testid);
}

?>
