<?php
	$arrGraph = array();

	if(!empty($_POST['graph'])) {
		$_POST['graph'] = preg_replace("/[^A-Za-z0-9\r\n\s]/","",$_POST);
		$lines = explode("\r\n",$_POST['graph']);
		if(!empty($lines)) {
			foreach($lines as $key => $line) {
				$line = str_replace(' ','|',$line);
				$parts = explode();
			}
		}
	}
?>
