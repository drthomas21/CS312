<!DOCTYPE html>
<?php
	require_once(dirname(__FILE__).'/protected/includes.php');
	
	$arrInputtedGraph = array();
	$arrNodes = array();
	$arrEdges = array();
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
					if(!array_key_exists($value, $arrNodes)) {
						$arrNodes[$value] = new Node($value);
					}
				}
				
				$edge1 = $line[0].$line[1];
				$edge2 = $line[1].$line[0];
				//Add indirect edge
				if($_POST['type'] == 'indirect' && !array_key_exists($edge1,$arrEdges) && !array_key_exists($edge2,$arrEdges)) {
					$Edge = new IndirectEdge($edge1);
					$Edge->addNode($arrNodes[$line[0]]);
					$Edge->addNode($arrNodes[$line[1]]);
					$arrEdges[$edge1] = $Edge;
				}
				
				//Add direct edge
				if($_POST['type'] == 'direct' && !array_key_exists($edge1,$arrEdges)) {
					$Edge = new DirectEdge($edge1);
					$Edge->addNode($arrNodes[$line[0]]);
					$Edge->addNode($arrNodes[$line[1]]);
					$arrEdges[$edge1] = $Edge;
				}
			}
		}
	}
	
	//Fill Matrix
	if(!empty($arrEdges) && !empty($arrNodes)) {
		$tempArrNodes = array_keys($arrNodes);
		$tempArrEdges = array_keys($arrEdges);
		for($r = 0; $r <= count($tempArrNodes); $r++) {
			$arrMatrix[$r] = array();
			if($r == 0) {
				for($c = 0; $c <= count($tempArrNodes); $c++) {
					if($c == 0) {
						$arrMatrix[$r][$c] = '&nbsp;';
					} else {
						$arrMatrix[$r][$c] = $tempArrNodes[($c-1)];
					}
				}
			} else {				
				for($c = 0; $c <= count($tempArrNodes); $c++) {
					if($c == 0) {
						$arrMatrix[$r][$c] = $tempArrNodes[($r-1)];
					} else {
						$edge = $tempArrNodes[$r-1].$tempArrNodes[$c-1];
						if(in_array($edge,$tempArrEdges)) {
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
		
		<!-- jQuery -->
		<script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
		
		<!-- AngularJS -->
		<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.0/angular.min.js"></script>
		<script src="//cdnjs.cloudflare.com/ajax/libs/angular-ui-bootstrap/0.10.0/ui-bootstrap-tpls.min.js"></script>
		
		<title>CS312: Adjacency List and Matrix</title>
	</head>
	<body style="padding-bottom: 40px">
		<nav class="navbar navbar-default" role="navigation">
			<div class="container-fluid">
		    <!-- Brand and toggle get grouped for better mobile display -->
		    <div class="navbar-header">
				<a class="navbar-brand" href="/">Adjacency List and Matrix</a>
				<ul class="nav navbar-nav">
					<li><a href="https://github.com/drthomas21/CS312">Fork On GitHub</a></li>
				</ul>
			</div><!-- /.container-fluid -->
		</nav>
		<div class="container-fluid">
			<h1 class="page-header">CS312: Adjacency List and Matrix</h1>
			<?php if(!empty($arrMatrix)): ?>
			<section class="col-lg-12" id="matrix">
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
			<section class="col-lg-3" id="nodes">
				<h2>Nodes</h2>
				<ul>
				<?php foreach($arrNodes as $Node): ?>
					<li><?php echo $Node->getName(); ?></li>
				<?php endforeach; ?>
				</ul>
			</section>
			<?php endif; ?>
			
			<?php if($_POST['type'] == 'direct' && !empty($arrEdges)): ?>
			<section class="col-lg-3" id="direct-cut">
				<h2>Cuts <small>Direct</small></h2>
				<ul>
				<?php foreach($arrEdges as $Edge): ?>
					<li><?php echo $Edge->getName(); ?></li>
				<?php endforeach; ?>
				</ul>
			</section>
			<section class="col-lg-3" id="direct-edge">
				<h2>Edges <small>Direct</small></h2>
				<?php 
					$arrList = array();
					foreach($arrNodes as $Node) {
						$arrList[$Node->getName()] = array();
						$arrNodes = $Node->getNeighborNodes();
						if(!empty($arrNodes)) {
							foreach($arrNodes as $Item) {
								$arrList[$Node->getName()][] = $Item->getName();
							}
						}
					}
				?>
				<ul>
				<?php foreach($arrList as $letter => $array): ?>
					<li><strong><?php echo $letter; ?>: </strong> <?php echo implode(', ',$array); ?></li>
				<?php endforeach; ?>
				</ul>
			</section>
			<?php endif; ?>
			
			<?php if($_POST['type'] == 'indirect' && !empty($arrEdges)): ?>
			<section class="col-lg-3" id="indirect-cut">
				<h2>Cuts <small>Indirect</small></h2>
				<ul>
				<?php foreach($arrEdges as $Edge): ?>
					<li><?php echo $Edge->getName(); ?></li>
				<?php endforeach; ?>
				</ul>
			</section>
			<section class="col-lg-3" id="indirect-edge">
				<h2>Edges <small>Indirect</small></h2>
				<?php 
					$arrList = array();
					foreach($arrNodes as $Node) {
						$arrList[$Node->getName()] = array();
						$arrNodes = $Node->getNeighborNodes();
						if(!empty($arrNodes)) {
							foreach($arrNodes as $Item) {
								$arrList[$Node->getName()][] = $Item->getName();
							}
						}
					}
				?>
				<ul>
				<?php foreach($arrList as $letter => $array): ?>
					<li><strong><?php echo $letter; ?>: </strong> <?php echo implode(', ',$array); ?></li>
				<?php endforeach; ?>
				</ul>
			</section>
			<?php endif; ?>
		
			<section class="col-lg-12">	
				<form method="POST">
					<?php if(!empty($arrError)): ?>
						<div class="alert alert-danger" role="alert"><?php echo implode('<br />',$arrError); ?></div>
					<?php endif; ?>
					
					<div class="form-group">
						<label class="col-lg-12">Graph Type</label>
						<label for="type-direct" class="col-lg-1 text-right">Direct</label><input type="radio" name="type" id="type-direct" value="direct" class="col-lg-1 text-left" <?php echo $_POST['type'] == 'direct' ? "checked" : ""; ?>/>
						<label for="type-indirect" class="col-lg-1 text-right">Indirect</label><input type="radio" name="type" id="type-indirect" value="indirect" class="col-lg-1 text-left" <?php echo $_POST['type'] == 'indirect' ? "checked" : ""; ?>/>
					</div>
					<div class="form-group">
						<label for="graph" class="col-lg-12">Input Sets</label>
						<textarea class="col-lg-12" name="graph" id="graph" style="height: 150px"><?php print_r($_POST['graph']); ?></textarea>					
					</div>
					<div class="col-lg-12">&nbsp;</div>
					<div class="col-lg-12">
						<input type="submit" class="btn btn-primary pull-right" />
						<span class="help-block">Sample:<br />A B<br />C D</span> 
					</div>
				</form>
			</section>

			<section class="col-lg-12">
				<ul class="nav nav-tabs" role="tablist" id="tabs">
					<li class="active"><a href="#changelog" role="tab" data-toggle="tab">Changelog</a></li>
					<li><a href="#about" role="tab" data-toggle="tab">About Adjacency</a></li>
					<li><a href="#terms" role="tab" data-toggle="tab">Terms</a></li>
				</ul>

				<div class="tab-content">
					<div class="tab-pane active" id="changelog">
						<ul>
							<li>
								<strong>2014-10-27</strong>
								<ul>
									<li>Added Node and Edge classes</li>
								</ul>
							</li>
							<li>
								<strong>2014-10-22</strong>
								<ul>
									<li>Added tabset</li>
									<li>Added Cut and Edge Columns</li>
								</ul>
							</li>
						</ul>
					</div>
					<div class="tab-pane" id="about">
						<h2>Adjacency List representation of graphs</h2>
						<ul>
							<li>Array of nodes</li>
							<li>Array of edges</li>
							<li>each edge points to its endpoints</li>
							<li>each node points to edges incident on it</li>
						</ul>
						<h2>Info: Directed Graphs</h2>
						<ul>
							<li>AB is not the same as BA</li>
						</ul>
						<h2>Info Indirected Graphs</h2>
						<ul>
							<li>AB is the same as BA</li>
						</ul>
					</div>
					<div class="tab-pane" id="terms">
						<p>Last updated: October 22, 2014</p>

			                        <p>Please read these Terms of Service ("Terms", "Terms of Service") carefully before using the http://adjacency.superlunchvote.com/ website (the "Service") operated by Adjancency List And Matrix ("us", "we", or "our").</p>

                        			<p>Your access to and use of the Service is conditioned on your acceptance of and compliance with these Terms. These Terms apply to all visitors, users and others who access or use the Service.</p>

			                        <p>By accessing or using the Service you agree to be bound by these Terms. If you disagree with any part of the terms then you may not access the Service.</p>


			                        <p><strong>Links To Other Web Sites</strong></p>

			                        <p>Our Service may contain links to third-party web sites or services that are not owned or controlled by Adjancency List And Matrix.</p>

			                        <p>Adjancency List And Matrix has no control over, and assumes no responsibility for, the content, privacy policies, or practices of any third party web sites or services. You further acknowledge and agree that Adjancency List And Matrix shall not be responsible or liable, directly or indirectly, for any damage or loss caused or alleged to be caused by or in connection with use of or reliance on any such content, goods or services available on or through any such web sites or services.</p>

			                        <p>We strongly advise you to read the terms and conditions and privacy policies of any third-party web sites or services that you visit.</p>


                        			<p><strong>Governing Law</strong></p>

			                        <p>These Terms shall be governed and construed in accordance with the laws of California, United States, without regard to its conflict of law provisions.</p>

			                        <p>Our failure to enforce any right or provision of these Terms will not be considered a waiver of those rights. If any provision of these Terms is held to be invalid or unenforceable by a court, the remaining provisions of these Terms will remain in effect. These Terms constitute the entire agreement between us regarding our Service, and supersede and replace any prior agreements we might have between us regarding the Service.</p>

			                        <p><strong>Changes</strong></p>

			                        <p>We reserve the right, at our sole discretion, to modify or replace these Terms at any time. If a revision is material we will try to provide at least 30 days notice prior to any new terms taking effect. What constitutes a material change will be determined at our sole discretion.</p>

			                        <p>By continuing to access or use our Service after those revisions become effective, you agree to be bound by the revised terms. If you do not agree to the new terms, please stop using the Service.</p>

			                        <p style="font-size: 85%; color: #999;">With permission from TermsFeed.com</p>

			                        <p><strong>Contact Us</strong></p>

			                        <p>If you have any questions about these Terms, please contact us.</p>
					</div>
				</div>
			</section>
		</div>		
	</body>
</html>
