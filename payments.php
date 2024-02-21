<?php 
	session_start();
	include 'init.php';


if(isset($_SESSION['user'])):
	$user_id = $_SESSION["uid"];


	$stmt0 = $con->prepare("SELECT * FROM users WHERE UserID = ?");
		$stmt0->execute(array($user_id));

				$count = $stmt0->rowCount();

				if ($count > 0) {

				$user = $stmt0->fetch();
				}


	if (isset($_GET['itemId']) && is_numeric($_GET['itemId'])) {
				$item_id = intval($_GET['itemId']);
				
				// select all data depend on this id
		$stmt = $con->prepare("SELECT 
									items.*, 
									categories.Name AS category_name, 
									users.Username 
								FROM 
									items
								INNER JOIN 
									categories 
								ON 
									categories.ID = items.Cat_ID 
								INNER JOIN 
									users 
								ON 
									users.UserID = items.Member_ID 
								WHERE 
									Item_ID = ?
								AND 
									Approve = 1");

		$stmt->execute(array($item_id));

				$count = $stmt->rowCount();

				if ($count > 0) {

				$item = $stmt->fetch();
				}

				//shopping card here!
				?>
				<h1 class="text-center ">Payment : </h1>
				<div class="m-item container">
				
				<div class="row">
								<div class="col-75  form-pay">
									<div class="con">
									<form action="action.php" class="form-pay" method="POST">
										<div class="row">
										<div class="col-50">
											<h3>Billing Address</h3>
											<label for="fname"><i class="fa fa-user"></i> Full Name</label>
											<input type="text" id="fname" name="firstname" placeholder="<?= $user["FullName"]?>">
											<label for="email"><i class="fa fa-envelope"></i> Email</label>
											<input type="text" id="email" name="email" placeholder="<?= $user["Email"]?>">
											<label for="adr"><i class="fa fa-address-card-o"></i> Address</label>
											<input type="text" id="adr" name="address" placeholder="542 W. 15th Street">
											<label for="city"><i class="fa fa-institution"></i> City</label>
											<input type="text" id="city" name="city" placeholder="New York">
				
											<div class="row">
											<div class="col-50">
												<label for="state">State</label>
												<input type="text" id="state" name="state" placeholder="NY">
											</div>
											<div class="col-50">
												<label for="zip">Zip</label>
												<input type="text" id="zip" name="zip" placeholder="10001">
											</div>
											</div>
										</div>
				
										<div class="col-50">
											<h3>Payment</h3>
											<label for="fname">Accepted Cards</label>
											<div class="icon-container">
											<i class="fa fa-cc-visa" style="color:navy;"></i>
											<i class="fa fa-cc-amex" style="color:blue;"></i>
											<i class="fa fa-cc-mastercard" style="color:red;"></i>
											<i class="fa fa-cc-discover" style="color:orange;"></i>
											</div>
											<label for="cname">Name on Card</label>
											<input type="text" id="cname" name="cardname" placeholder="John More Doe">
											<label for="ccnum">Credit card number</label>
											<input type="text" id="ccnum" name="cardnumber" placeholder="1111-2222-3333-4444">
											<label for="expmonth">Exp Month</label>
											<input type="text" id="expmonth" name="expmonth" placeholder="September">
				
											<div class="row">
											<div class="col-50">
												<label for="expyear">Exp Year</label>
												<input type="text" id="expyear" name="expyear" placeholder="2018">
											</div>
											<div class="col-50">
												<label for="cvv">CVV</label>
												<input type="text" id="cvv" name="cvv" placeholder="352">
											</div>
											</div>
										</div>
				
										</div>
										<label>
										<input type="checkbox" checked="checked" name="sameadr"> Shipping address same as billing
										</label>
										<input type="submit" value="Continue to checkout" class="btn-payment">
									</form>
									</div>
								</div>
				

				
				
				</div>
			</div>

				
			<?php       

				include $tpl . 'footer.php';


			}
					else {
						echo '
						<div class="container" >
							<div class="m-item alert alert-danger">Not Found!</div>
						</div>';
					}
				else:
					header('Location: login.php');
					exit;
				endif;


?>



