<?php include "inc/head_software_copyright.php" ?>
    <div id="main">
<?php
require_once "db.php";
$dbWrap=new db_conn();
if($_REQUEST['sessid']){
	$xmlbrowsers=$dbWrap->getQuery("SELECT testid,user_agent from tests where sessid='".$_REQUEST['sessid']."';");
	$xmlbrowsers=simplexml_load_string($xmlbrowsers);
	$refbrowser_id=-1;
	foreach($xmlbrowsers->data->row as $row){
		if(strcmp($row->user_agent,"ref")==0){
			$refbrowser_id=$row->testid;
		}

	}
	echo "<h3>Browser report for ".$_REQUEST['url']."</h3>\n<div style='report'>";
	foreach($xmlbrowsers->data->row as $row){
		if(strcmp($row->user_agent,"ie")==0){
			$browser = "Internet Explorer";
		}else if(strcmp($row->user_agent,"gc")==0){
			$browser = "Google Chrome";
		}else{
			continue;
		}
		echo "<div class='report-item'><p>$browser</p><a href='#' onclick='window.open(\"display.php?testid=".$row->testid."&user_agent=gc&refb=".$refbrowser_id."\",\"xbct\",\"fullscreen=yes,scrollbars=no\");'><img src='screenshots/".$row->testid.".png' width='200px'/></a></div>";
	}
	echo "</div><div class='clear'></div>";
	echo "<p>Select a report to view issue list.</p>";
}else{
	$sessions=$dbWrap->getQuery("SELECT * from sessions;");
	$sessions=simplexml_load_string($sessions);
	echo "<h3>测试会话</h3><ul>";
	foreach($sessions->data->row as $row){
		echo "<li><a href='?sessid=".$row->sessid."&url=".$row->url."'>".$row->url."</a></li>";
	}
	echo "</ul>";
    echo "<h3>浏览器不支持的HTML5节点信息</h3><ul>";
	$incompability_element=$dbWrap->getQuery("SELECT * from report;");
	$incompability_element=simplexml_load_string($incompability_element);
	echo "<h4>Incompability element</h4>";
	echo "<table width='400' border='1' cellspacing='0' cellpadding='0'>";
	echo "<tr><td>Testid</td><td>Nodeid</td><td>Coords</td><td>Xpath</td><td>Element Name</td><td>Attribute Name</td><td>Attribute Value</td><td>Browser</td></tr>";
	foreach($incompability_element->data->row as $row){
		echo "<tr>";
		echo "<td>".$row->testid."</td><td>".$row->nodeid."</td><td>".$row->coords."</td><td>".$row->xpath."</td><td>".$row->element_name."</td><td>".$row->attribute_name."</td><td>".$row->attribute_value."</td><td>".$row->browser."</td>";
		echo "</tr>";
	}
	echo "</table>";
}
?>
	</div>
