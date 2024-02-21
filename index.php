<?php
	session_start();
	$pageTitle = 'Home';
	include 'init.php';
?>
<div class="container">
		<h1 class="text-center">All items:</h1>
	<div class="row">
		<?php
			$allItems = getAllFrom('*', 'items', 'where Approve = 1', '', 'Item_ID');
			

			//pagination goes here!
			if(isset($_GET['pageN']) && is_numeric($_GET['pageN']))
			{
			$pageN = intval($_GET['pageN']);
			}else{
			$pageN = 1;
		}

			$max = 6;
			$skip = ( $pageN  - 1 ) * $max;
			$max = $skip + $max;
			$count = 0;
			$data_count = count($allItems);


			while($skip < $max){
				if($skip == $data_count) break;
				echo '<div class="col-sm-6 col-md-3">';
					echo '<div class="thumbnail item-box">';
						echo '<span class="price-tag">' . $allItems[$skip]['Price'] . ' DH</span>';
						echo '<img class="img-responsive"  src="img.png" alt="" />';
						echo '<div class="caption">';
							echo '<h3><a href="items.php?itemid='. $allItems[$skip]['Item_ID'] .'">' . $allItems[$skip]['Name'] .'</a></h3>';
							echo '<p>' . $allItems[$skip]['Description'] . '</p>';
							echo '<div class="date">' . $allItems[$skip]['Add_Date'] . '</div>';
						echo '</div>';
					echo '</div>';
				echo '</div>';
				$skip++;
			}

			$prevN = ( $pageN > 1) ? ($pageN -1 ) : 1;
			$nextN = ( ( $pageN * 6 )+ 1 < $data_count) ? ($pageN + 1 ) : $pageN;
			echo '
					<div class="container " align="center">
							<ul id="pagination" class="pagination pagination-comp">
								<li id="prev"><a href="index.php?pageN='. $prevN .'">Previous</a></li>';
								 for($i = 1; $i < $data_count/6; $i++){
									 echo '<li id="item"><a href="index.php?pageN='.$i.'">'.$i.'</a></li>';
								 }
			echo 			'<li id="next"><a href="index.php?pageN='. $nextN .'">Next</a></li>
							</ul>
					</div>
			';

		?>
	</div>
</div>
<?php
	include $tpl . 'footer.php'; 
?>