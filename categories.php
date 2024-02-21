<?php 
	session_start();
	$pageTitle = "categories";
	include 'init.php';
?>
		<?php
		if (isset($_GET['pageid']) && is_numeric($_GET['pageid'])) {
			$category_id = intval($_GET['pageid']);
			$cat_name = getCatName($category_id) ;


					$allItems = getAllFrom("*", "items", "where Cat_ID = {$category_id}", "AND Approve = 1", "Item_ID");

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

					if($data_count > 0){
									echo "
					<div class='container'>
						<h1 class='text-center'> {$cat_name[0][0]} items:</h1>
						<div class='row'>
					";

					while($skip < $max){
						if($skip == $data_count) break;
						echo '<div class="col-sm-6 col-md-3">';
							echo '<div class="thumbnail item-box">';
								echo '<span class="price-tag">' . $allItems[$skip]['Price'] . '</span>';
								echo '<img class="img-responsive" src="img.png" alt="" />';
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
										<li id="prev"><a href="categories.php?pageid='.$category_id.'&pageN='. $prevN .'">Previous</a></li>';
										for($i = 1; $i < $data_count/6; $i++){
											echo '<li id="item"><a href="categories.php?pageid='.$category_id.'&pageN='.$i.'">'.$i.'</a></li>';
										}
					echo 			'<li id="next"><a href="categories.php?pageid='.$category_id.'&pageN='. $nextN .'">Next</a></li>
									</ul>
							</div>
							</div>
							</div>';

										include $tpl . 'footer.php';


					}else{
						echo '<div class="container" ><div class="m-item alert alert-info pagination-comp">No item to show!</div></div>';
					}



		}
	  else {
			echo '<div class="container" ><div class="m-item alert alert-danger">Not Found!</div></div>';
		}
		?>
	</div>
</div>

<?php  ?>