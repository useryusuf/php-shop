<?php
	session_start();
	$pageTitle = 'Show Items';
	include 'init.php';

	$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

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

	$stmt->execute(array($itemid));

	$count = $stmt->rowCount();

	if ($count > 0) {

	$item = $stmt->fetch();
?>
<h1 class="text-center"><?php echo $item['Name'] ?></h1>
<div class="container">
	<div class="row">
		<div class="col-md-3">
			<img class="img-responsive img-thumbnail center-block" src="img.png" alt="" />
			<!-- <a href="#" class="btn m-item ms-item btn-danger center-block ">Buy Now!</a> -->
		</div>
		<div class="col-md-9 item-info">
			<h2><?php echo $item['Name'] ?></h2>
			<p><?php echo $item['Description'] ?></p>
			<ul class="list-unstyled">
				<li>
					<i class="fa fa-calendar fa-fw"></i>
					<span>Added Date</span> : <?php echo $item['Add_Date'] ?>
				</li>
				<li>
					<i class="fa fa-money fa-fw"></i>
					<span>Price</span> : <?php echo $item['Price'] ?> DH
				</li>
				<li>
					<i class="fa fa-building fa-fw"></i>
					<span>Made In</span> : <?php echo $item['Country_Made'] ?>
				</li>
				<li>
					<i class="fa fa-tags fa-fw"></i>
					<span>Category</span> : <a href="categories.php?pageid=<?php echo $item['Cat_ID'] ?>"><?php echo $item['category_name'] ?></a>
				</li>
				<li>
					<i class="fa fa-user fa-fw"></i>
					<?php $username =  $item['Username']; ?>
					<span>Added By</span> : <a href="#"><?php echo $username ?></a>
				</li>
				<li class="tags-items">
					<i class="fa fa-user fa-fw"></i>
					<span>Tags</span> : 
					<?php 
						$allTags = explode(",", $item['tags']);
						foreach ($allTags as $tag) {
							$tag = str_replace(' ', '', $tag);
							$lowertag = strtolower($tag);
							if (! empty($tag)) {
								echo "<a href='tags.php?name={$lowertag}'>" . $tag . '</a>';
							}
						}
					?>
				</li>
				<li class="tags-items">
					<i class="fa fa-money fa-fw"></i>
					<span>Quantity</span> : <input class="col-4" type="number" name="qan" style="padding-left: 7.5%;padding-right: 7%;padding-block: 0.3%;" value="1" min="1" max="23" placeholder="Quantity" required="">
				</li>
				<li></li>
				<a  href="card.php?itemId=<?php echo $item["Item_ID"]?>&qan=1" class="target-btn btn btn-danger center-block">Buy Now!</a>
			</ul>
		</div>
	</div>
	<hr class="custom-hr">
	<?php if (isset($_SESSION['user'])) { ?>
	<!-- start add comment -->
	<div class="row">
		<div class="col-md-offset-3">
			<div class="add-comment">
				<h3>Add Your Comment</h3>
				<form action="<?php echo $_SERVER['PHP_SELF'] . '?itemid=' . $item['Item_ID'] ?>" method="POST">
					<textarea name="comment" required></textarea>
					<input class="btn btn-primary" type="submit" value="Add Comment">
				</form>
				<?php 
					if ($_SERVER['REQUEST_METHOD'] == 'POST') {

						$comment 	= htmlspecialchars($_POST['comment']);
						$itemid 	= $item['Item_ID'];
						$userid 	= $_SESSION['uid'];

						if (! empty($comment)) {

							$stmt = $con->prepare("INSERT INTO 
								comments(comment, status, comment_date, item_id, user_id)
								VALUES(:zcomment, 0, NOW(), :zitemid, :zuserid)");

							$stmt->execute(array(

								'zcomment' => $comment,
								'zitemid' => $itemid,
								'zuserid' => $userid

							));

							if ($stmt) {

								echo '<div class="m-item alert alert-success">comment added!</div>';

							}

						} else {

							echo '<div class="m-item alert alert-danger">you must add comment!</div>';

						}

					}
				?>
			</div>
		</div>
	</div>
	<!-- end add comment -->
	<?php } else {
		echo '<a href="login.php">Login</a> or <a href="login.php">Register</a> to add comment';
	} ?>
	<hr class="custom-hr">
		<?php

			// select all users except admin 
			$stmt = $con->prepare("SELECT 
										comments.*, users.Username AS Member  
									FROM 
										comments
									INNER JOIN 
										users 
									ON 
										users.UserID = comments.user_id
									WHERE 
										item_id = ?
									AND 
										status = 1
									ORDER BY 
										c_id DESC");


			$stmt->execute(array($item['Item_ID']));
			$comments = $stmt->fetchAll();

		?>
	
	<?php foreach ($comments as $comment) { ?>
		<div class="comment-box">
			<div class="row">
				<div class="col-sm-2 text-center">
					<img class="img-responsive img-thumbnail img-circle center-block" src="img.png" alt="" />
					<?php echo $comment['Member'] ?>
				</div>
				<div class="col-sm-10">
					<p class="lead"><?php echo $comment['comment'] ?></p>
				</div>
			</div>
		</div>
		<hr class="custom-hr">
	<?php } 

	echo "</div>";
	include $tpl . 'footer.php';
	}else {
		echo '<div class="container">';
			echo '<div class="m-item alert alert-danger">There\'s no such id or  item is pending</div>';
		echo '</div>';
	}
	
?>