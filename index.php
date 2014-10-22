<!DOCTYPE html>
<?php
	$arrInputtedGraph = array();
	$arrNodes = array();
	$arrEdges = array("directed"=>array(),"indirected"=>array());
	$arrMatrix = array();
	$arrError = array();
	
	function dumpGraph(array $arGraph) {
		foreach($arGraph as $row => $arLine) {
			echo implode(' ',$arLine)."<br />";
		}
	}

	//Load graph
	if(!empty($_POST['graph'])) {
		$_POST['graph'] = preg_replace("/[\r\n]+/","|",$_POST['graph']);
		$_POST['graph'] = preg_replace("/[^A-Za-z0-9\r\n\s\|]/","",$_POST['graph']);
		$_POST['graph'] = preg_replace("/\s+/"," ",$_POST['graph']);

		$lines = explode("|",$_POST['graph']);
		if(!empty($lines)) {
			foreach($lines as $row => $line) {
				$arrInputtedGraph[$row] = array();
				$arrParts = explode(' ',$line);
				if($arrParts) {
					foreach($arrParts as $column => $value) {
						if($value != '') {
							$arrInputtedGraph[$row][$column] = $value;
						}
					}
				}
			}
		}
	}
	
	//Fill Lists
	if(!empty($arrInputtedGraph)) {
		$maxColumn = 0;
		foreach($arrInputtedGraph as $row => $line) {
			$maxColumn = count($arrInputtedGraph[$row]) > $maxColumn ? count($arrInputtedGraph[$row]) : $maxColumn;
		}
		
		if($maxColumn != 2) {
			$arrError[] = "Invalid sets inputted";
			$_POST['graph'] = '';		
		} else {
			foreach($arrInputtedGraph as $row => $line) {
				foreach($line as $column => $value) {
					if(!in_array($value,$arrNodes)) {
						$arrNodes[] = $value;
					}
				}
				
				$edge1 = $line[0].$line[1];
				$edge2 = $line[1].$line[0];
				//Add indirect edge
				if(!in_array($edge1,$arrEdges['indirected']) && !in_array($edge2,$arrEdges['indirected'])) {
					$arrEdges['indirected'][] = $edge1;
				}
				
				//Add direct edge
				if(!in_array($edge1,$arrEdges['directed'])) {
					$arrEdges['directed'][] = $edge1;
				}
			}
		}
	}
	
	//Fill Matrix
	if(!empty($arrEdges['directed']) && !empty($arrNodes)) {
		for($r = 0; $r <= count($arrNodes); $r++) {
			$arrMatrix[$r] = array();
			if($r == 0) {
				for($c = 0; $c <= count($arrNodes); $c++) {
					if($c == 0) {
						$arrMatrix[$r][$c] = '&nbsp;';
					} else {
						$arrMatrix[$r][$c] = $arrNodes[($c-1)];
					}
				}
			} else {				
				for($c = 0; $c <= count($arrNodes); $c++) {
					if($c == 0) {
						$arrMatrix[$r][$c] = $arrNodes[($r-1)];
					} else {
						$edge = $arrNodes[$r-1].$arrNodes[$c-1];
						if(in_array($edge,$arrEdges['directed'])) {
							$arrMatrix[$r][$c] = 1;
						} else {
							$arrMatrix[$r][$c] = 0;
						}
					}
					
				}
			}
			
		}
	}
?>
<html>
	<head>
		<meta name="description" content="This is a simple test of the Adjacency List and Matrix" />
		<meta property="og:description" content="This is a simple test of the Adjacency List and Matrix" />
		<meta property="og:name" content="CS312: Adjacency List and Matrix" />
		<link rel="canoncial" href="http://adjacency.superlunchvote.com/" />
		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
		
		<!-- Optional theme -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">
		
		<!-- Latest compiled and minified JavaScript -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
		<script>
		  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
		
		  ga('create', 'UA-29594513-10', 'auto');
		  ga('send', 'pageview');
		
		</script>
		
		<title>CS312: Adjacency List and Matrix</title>
	</head>
	<body>
		<nav class="navbar navbar-default" role="navigation">
			<div class="container-fluid">
		    <!-- Brand and toggle get grouped for better mobile display -->
		    <div class="navbar-header">
				<a class="navbar-brand" href="/">Adjacency List and Matrix</a>
				<ul class="nav navbar-nav">
					<li><a href="/terms.php">Term of Use</a></li>
					<li><a href="https://github.com/drthomas21/CS312">Fork On GitHub</a></li>
				</ul>
			</div><!-- /.container-fluid -->
		</nav>
		<div class="container">
			<h1>CS312: Adjacency List and Matrix</h1>
			<?php if(!empty($arrMatrix)): ?>
			<section class="col-xs-12" id="matrix">
				<h2>Matrix</h2>
				<table class='table table-bordered'>
					<?php foreach($arrMatrix as $count => $row): ?>
					<tr>
						<?php if($count == 0): ?>
							<?php foreach($row as $value): ?>
								<th><?php echo $value; ?></th>
							<?php endforeach; ?>
						<?php else: ?>
							<?php foreach($row as $value): ?>
								<td><?php echo $value; ?></td>
							<?php endforeach; ?>
						<?php endif; ?>						
					</tr>
					<?php endforeach; ?>
				</table>
				
			</section>
			<?php endif; ?>
			
			<?php if(!empty($arrNodes)): ?>
			<section class="col-xs-4" id="nodes">
				<h2>Nodes</h2>
				<ul>
				<?php foreach($arrNodes as $value): ?>
					<li><?php echo $value; ?></li>
				<?php endforeach; ?>
				</ul>
			</section>
			<?php endif; ?>
			
			<?php if(!empty($arrEdges['directed'])): ?>
			<section class="col-xs-4" id="direct-edge">
				<h2>Directed Edges</h2>
				<ul>
				<?php foreach($arrEdges['directed'] as $value): ?>
					<li><?php echo $value; ?></li>
				<?php endforeach; ?>
				</ul>
			</section>
			<?php endif; ?>
			
			<?php if(!empty($arrEdges['indirected'])): ?>
			<section class="col-xs-4" id="direct-edge">
				<h2>Indirected Edges</h2>
				<ul>
				<?php foreach($arrEdges['indirected'] as $value): ?>
					<li><?php echo $value; ?></li>
				<?php endforeach; ?>
				</ul>
			</section>
			<?php endif; ?>
			
			<form method="POST">
				<?php if(!empty($arrError)): ?>
					<div class="alert alert-danger" role="alert"><?php echo implode('<br />',$arrError); ?></div>
				<?php endif; ?>
				
				<div class="col-xs-12">
					<label for="graph" class="col-xs-12">Input Sets</label>
					<textarea class="col-xs-12" name="graph" id="graph" style="height: 150px"><?php print_r($_POST['graph']); ?></textarea>					
				</div>
				<div class="col-xs-12">&nbsp;</div>
				<div class="col-xs-12">
					<input type="submit" class="btn btn-primary pull-right" />
					<span class="help-block">Sample:<br />A B<br />C D</span> 
				</div>
			</form>
		</div>		
	</body>
</html>
