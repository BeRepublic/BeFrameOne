<html>
	<head>
		<title>Server Exception</title>
	</head>
	<body>
		<h3>Server Exception: <?php echo $title ?></h3>
		<p>
		<?php 
			if ( is_array($messages) ){
				echo  '<ul>';
				foreach ($messages as $k=>$v){
					echo '<li><label>'.$k.'</label>'.$v.'</li>'."\n";
				}
				echo '</ul>';
			} else {
				echo $messages;
			}
			if ( ENVIRONMENT == 'DEVEL' ){
				echo  '<ul>';
				foreach ($trace as $k=>$v){
					echo '<li><label>'.$k.'</label>';
					if ( is_array($v) ) {
						echo '<ul>';
						foreach ($v as $k1=>$v1) {
							$v1 = is_array($v1) ? implode(',', $v1) : $v1;
							echo '<li>'.$k1.': '.$v1.'</li>';
						}
						echo '</ul>';
					}else{
						echo $v;
					}
					echo '</li>'."\n";
				}
				echo '</ul>';
			}
		?>
		</p>
	</body>
</html>