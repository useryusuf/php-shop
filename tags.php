<?php 
	session_start();
	include 'init.php';
?>



		<?php
		if (isset($_GET['name']) && !empty($_GET['name'])) {
			$tag = $_GET['name'];
			$tagItems = getAllFrom("*", "items", "where tags like '%$tag%'", "AND Approve = 1", "Item_ID");
			if(!empty($tagItems)){
			echo "<h1 class='text-center'>" . $tag. " Items:</h1>
						<div class='container '>
							<div class='row '>";
			foreach ($tagItems as $item) {
				echo '<div class="col-sm-6 col-md-3 mb-item">';
					echo '<div class="thumbnail item-box">';
						echo '<span class="price-tag">' . $item['Price'] . '</span>';
						echo '<img class="img-responsive" src="img.png" alt="" />';
						echo '<div class="caption">';
							echo '<h3><a href="items.php?itemid='. $item['Item_ID'] .'">' . $item['Name'] .'</a></h3>';
							echo '<p>' . $item['Description'] . '</p>';
							echo '<div class="date">' . $item['Add_Date'] . '</div>';
						echo '</div>';
					echo '</div>';
				echo '</div>';
			}
			echo "
						</div>
					</div>
			";
			include $tpl . 'footer.php';
			}else{
			echo 
				'<div class="container">
					<div class="alert alert-danger m-item">invalid tag name</div>
				</div>';
			}
		
		} else {
						'<div class="container">
					<div class="alert alert-danger m-item">Error</div>
				</div>';
		}
		?>
	</div>
</div>

