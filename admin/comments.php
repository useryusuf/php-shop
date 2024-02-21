<?php


	session_start();

	$pageTitle = 'Comments';

	if (isset($_SESSION['Username'])) {

		include 'init.php';

		$action = isset($_GET['action']) ? $_GET['action'] : 'Manage';

		// start manage page

		if ($action == 'Manage') { // manage members page

			// select all users except admin 

			$stmt = $con->prepare("SELECT 
										comments.*, items.Name AS Item_Name, users.Username AS Member  
									FROM 
										comments
									INNER JOIN 
										items 
									ON 
										items.Item_ID = comments.item_id
									INNER JOIN 
										users 
									ON 
										users.UserID = comments.user_id
									ORDER BY 
										c_id DESC");

			// execute the statement

			$stmt->execute();

			// assign to variable 

			$comments = $stmt->fetchAll();

			if (! empty($comments)) {

			?>

			<h1 class="text-center">Manage Comments</h1>
			<div class="container">
				<div class="table-responsive">
					<table class="main-table text-center table table-bordered">
						<tr>
							<td>ID</td>
							<td>Comment</td>
							<td>Item Name</td>
							<td>User Name</td>
							<td>Added Date</td>
							<td>Control</td>
						</tr>
						<?php
							foreach($comments as $comment) {
								echo "<tr>";
									echo "<td>" . $comment['c_id'] . "</td>";
									echo "<td>" . $comment['comment'] . "</td>";
									echo "<td>" . $comment['Item_Name'] . "</td>";
									echo "<td>" . $comment['Member'] . "</td>";
									echo "<td>" . $comment['comment_date'] ."</td>";
									echo "<td>
										<a href='comments.php?action=Edit&comid=" . $comment['c_id'] . "' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
										<a href='comments.php?action=Delete&comid=" . $comment['c_id'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete </a>";
										if ($comment['status'] == 0) {
											echo "<a href='comments.php?action=Approve&comid="
													 . $comment['c_id'] . "' 
													class='btn btn-info activate'>
													<i class='fa fa-check'></i> Approve</a>";
										}
									echo "</td>";
								echo "</tr>";
							}
						?>
						<tr>
					</table>
				</div>
			</div>

			<?php } else {

				echo '<div class="container">';
					echo '<div class="nice-message m-item ">No Comments!</div>';
				echo '</div>';

			} ?>

		<?php 

		} elseif ($action == 'Edit') {

			// check if get request comid is numeric & get its integer value

			$comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;

			// select all data depend on this id

			$stmt = $con->prepare("SELECT * FROM comments WHERE c_id = ?");

			// execute query

			$stmt->execute(array($comid));

			// fetch the data

			$row = $stmt->fetch();

			// the row count

			$count = $stmt->rowCount();

			// if there's such id show the form

			if ($count > 0) { ?>

				<h1 class="text-center">Edit Comment</h1>
				<div class="container">
					<form class="form-horizontal" action="?action=Update" method="POST">
						<input type="hidden" name="comid" value="<?php echo $comid ?>" />
						<!-- start comment -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Comment</label>
							<div class="col-sm-10 col-md-6">
								<textarea class="form-control" name="comment"><?php echo $row['comment'] ?></textarea>
							</div>
						</div>
						<!-- end comment -->
						<!-- start submit -->
						<div class="form-group form-group-lg">
							<div class="col-sm-offset-2 col-sm-10">
								<input type="submit" value="Save" class="btn btn-primary btn-sm" />
							</div>
						</div>
						<!-- end submit -->
					</form>
				</div>

			<?php

			// if there's no such id show error message

			} else {

				echo "<div class='container'>";

				$theMsg = '<div class="alert alert-danger">No Such ID</div>';

				redirectHome($theMsg);

				echo "</div>";

			}

		} elseif ($action == 'Update') { // update page

			echo "<h1 class='text-center'>Update Comment</h1>";
			echo "<div class='container'>";

			if ($_SERVER['REQUEST_METHOD'] == 'POST') {

				// get variables from the form

				$comid 		= $_POST['comid'];
				$comment 	= $_POST['comment'];

				// update the database with this info

				$stmt = $con->prepare("UPDATE comments SET comment = ? WHERE c_id = ?");

				$stmt->execute(array($comment, $comid));

				// echo success message

				$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated</div>';

				redirectHome($theMsg, 'back');

			} else {

				$theMsg = '<div class="alert alert-danger">you cant browse this page directly</div>';

				redirectHome($theMsg);

			}

			echo "</div>";

		} elseif ($action == 'Delete') { // delete page

			echo "<h1 class='text-center'>Delete Comment</h1>";

			echo "<div class='container'>";

				// check if get request comid is numeric & get the integer value of it

				$comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;

				// select all data depend on this id

				$check = checkItem('c_id', 'comments', $comid);

				// if there's such id show the form

				if ($check > 0) {

					$stmt = $con->prepare("DELETE FROM comments WHERE c_id = :zid");

					$stmt->bindParam(":zid", $comid);

					$stmt->execute();

					$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Deleted</div>';

					redirectHome($theMsg, 'back');

				} else {

					$theMsg = '<div class="alert alert-danger">this id is not exist</div>';

					redirectHome($theMsg);

				}

			echo '</div>';

		} elseif ($action == 'Approve') {

			echo "<h1 class='text-center'>Approve Comment</h1>";
			echo "<div class='container'>";

				// check if get request comid is numeric & get the integer value of it

				$comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;

				// select all data depend on this id

				$check = checkItem('c_id', 'comments', $comid);

				// if there's such id show the form

				if ($check > 0) {

					$stmt = $con->prepare("UPDATE comments SET status = 1 WHERE c_id = ?");

					$stmt->execute(array($comid));

					$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Approved</div>';

					redirectHome($theMsg, 'back');

				} else {

					$theMsg = '<div class="alert alert-danger">this id is not exist</div>';

					redirectHome($theMsg);

				}

			echo '</div>';

		}

		include $tpl . 'footer.php';

	} else {

		header('Location: index.php');

		exit();
	}


?>