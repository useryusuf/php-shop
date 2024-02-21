<?php 
	session_start();
	include 'init.php';

if (isset($_GET['itemId']) && is_numeric($_GET['itemId'])) {
			$item_id = intval($_GET['itemId']);
			$q = isset($_GET['qan']) ?  intval($_GET['qan']) : 1;
			$_SESSION["q"] = $q;
			$_SESSION["item_id"] = $item_id;
			// select all data depend on this id
	$stmt = $con->prepare("SELECT 
								*
							FROM 
								items
							
							WHERE 
								Item_ID = ?
							");

	$stmt->execute(array($item_id));

	$count = $stmt->rowCount();

	if ($count > 0) {

	$item = $stmt->fetch();

  }

$products_count = 1;
//shopping card here!
?>


<div class="container m-item mb-item">
	<h1 class="text-center">Shopping card</h1>
	<div class="col-25">
		<h4>Product:
			<span class="price" style="color:black">
			<i class="fa fa-shopping-cart"></i>
			<b id="num"><?=$q?></b>
			</span>
		</h4>
		<hr class="custom-hr">
		<p><a href="items.php?itemid=<?=$item_id?>"><?php echo $item["Name"]?></a> <span class="price">$<span id="product-price"><?php echo $item["Price"]?></span></span></p>
		<!-- <hr class="custom-hr">
		<p><a href="#">Product 2</a> <span class="price">$<span id="product-price">5</span></span></p>
		<hr class="custom-hr">
		<p><a href="#">Product 3</a> <span class="price">$<span id="product-price">8</span></span></p>
		<hr class="custom-hr">
		<p><a href="#">Product 4</a> <span class="price">$<span id="product-price">2</span></span></p> -->
		<hr class="custom-hr">
		<hr>
		<p>Total <span class="price" style="color:black">$<b id="total-price"><?php echo $item["Price"]?></b></span></p>
		 <div class="buttons m-item">
            <input class="btn btn-default" type="submit" value="Update" name="update">
            <a class="btn btn-success" href="payments.php?itemId=<?=$item_id?>">Order</a>
      </div>
		</div>
	</div>
</div>
	
<?php      
			include $tpl . 'load.php';

}

		else {
			echo '
      <div class="container" >
        <div class="m-item alert alert-danger">Not Found!</div>
      </div>';
		}



?>