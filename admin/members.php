<?php

	session_start();

	$pageTitle = 'Members';

	if (isset($_SESSION['Username'])) {

		include 'init.php';

		$action = isset($_GET['action']) ? $_GET['action'] : 'Manage';

		// start manage page

		if ($action == 'Manage') { // manage members page

			$query = '';

			if (isset($_GET['page']) && $_GET['page'] == 'Pending') {

				$query = 'AND RegStatus = 0';

			}

			// select all users except admin 

			$stmt = $con->prepare("SELECT * FROM users WHERE GroupID != 1 $query ORDER BY UserID DESC");

			// execute the statement

			$stmt->execute();

			// assign to variable 

			$rows = $stmt->fetchAll();

			if (! empty($rows)) {

			?>

			<h1 class="text-center">Manage Members</h1>
			<div class="container">
				<div class="table-responsive">
					<table class="main-table manage-members text-center table table-bordered">
						<tr>
							<td>#ID</td>
							<td>Avatar</td>
							<td>Username</td>
							<td>Email</td>
							<td>Full Name</td>
							<td>Registered Date</td>
							<td>Control</td>
						</tr>
						<?php
							foreach($rows as $row) {
								echo "<tr>";
									echo "<td>" . $row['UserID'] . "</td>";
									echo "<td>";
									if (empty($row['avatar'])) {
										echo 'No Image';
									} else {
										echo "<img src='uploads/avatars/" . $row['avatar'] . "' alt='' />";
									}
									echo "</td>";

									echo "<td>" . $row['Username'] . "</td>";
									echo "<td>" . $row['Email'] . "</td>";
									echo "<td>" . $row['FullName'] . "</td>";
									echo "<td>" . $row['Date'] ."</td>";
									echo "<td>
										<a href='members.php?action=Edit&userid=" . $row['UserID'] . "' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
										<a href='members.php?action=Delete&userid=" . $row['UserID'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete </a>";
										if ($row['RegStatus'] == 0) {
											echo "<a 
													href='members.php?action=Activate&userid=" . $row['UserID'] . "' 
													class='btn btn-info activate'>
													<i class='fa fa-check'></i> Activate</a>";
										}
									echo "</td>";
								echo "</tr>";
							}
						?>
						<tr>
					</table>
				</div>
				<a href="members.php?action=Add" class="btn btn-primary">
					<i class="fa fa-plus"></i> New Member
				</a>
			</div>

			<?php } else {

				echo '<div class="container">';
					echo '<div class="nice-message">There\'s No Members To Show</div>';
					echo '<a href="members.php?action=Add" class="btn btn-primary">
							<i class="fa fa-plus"></i> New Member
						</a>';
				echo '</div>';

			} ?>

		<?php 

		} elseif ($action == 'Add') { // add page ?>

			<h1 class="text-center">Add New Member</h1>
			<div class="container">
				<form class="form-horizontal" action="?action=Insert" method="POST" enctype="multipart/form-data">
					<!-- start username -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Username</label>
						<div class="col-sm-10 col-md-6">
							<input type="text" name="username" class="form-control" autocomplete="off" required="required" placeholder="username" />
						</div>
					</div>
					<!-- end username -->
					<!-- start password -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Password</label>
						<div class="col-sm-10 col-md-6">
							<input type="password" name="password" class="password form-control" required="required" autocomplete="new-password" placeholder="password" />
							<i class="show-pass fa fa-eye fa-2x"></i>
						</div>
					</div>
					<!-- end password -->
					<!-- start email -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Email</label>
						<div class="col-sm-10 col-md-6">
							<input type="email" name="email" class="form-control" required="required" placeholder="email" />
						</div>
					</div>
					<!-- end email -->
					<!-- start full name -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Full Name</label>
						<div class="col-sm-10 col-md-6">
							<input type="text" name="full" class="form-control" required="required" placeholder="fullname" />
						</div>
					</div>
					<!-- end full name -->
					<!-- start avatar -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">User Avatar</label>
						<div class="col-sm-10 col-md-6">
							<input type="file" name="avatar" class="form-control"  />
						</div>
					</div>
					<!-- end avatar -->
					<!-- start submit -->
					<div class="form-group form-group-lg">
						<div class="col-sm-offset-2 col-sm-10">
							<input type="submit" value="Add Member" class="btn btn-primary btn-lg" />
						</div>
					</div>
					<!-- end submit -->
				</form>
			</div>

		<?php 

		} elseif ($action == 'Insert') {

			// insert member page

			if ($_SERVER['REQUEST_METHOD'] == 'POST') {

				echo "<h1 class='text-center'>Insert Member</h1>";
				echo "<div class='container'>";

				// upload variables

				if(isset($_POST['avatar'])){
					$avatarName = $_FILES['avatar']['name'];
					$avatarSize = $_FILES['avatar']['size'];
					$avatarTmp	= $_FILES['avatar']['tmp_name'];
					$avatarType = $_FILES['avatar']['type'];
	
					// list of allowed file typed to upload
	
					$avatarAllowedExtension = array("jpeg", "jpg", "png", "gif");
	
					// get avatar extension
	
					$avatarExtension = strtolower(end(explode('.', "avatarName")));
					if (!empty($avatarName) && ! in_array($avatarExtension, $avatarAllowedExtension)) {
							$formErrors[] = 'this extension is not <strong>allowed</strong>';
					}

					if ($avatarSize > 4194304) {
						$formErrors[] = 'Avatar Cant be larger than <strong>4mb</strong>';
					}
				}



				// get variables from the form

				$user 	= $_POST['username'];
				$pass 	= $_POST['password'];
				$email 	= $_POST['email'];
				$name 	= $_POST['full'];

				$hashPass = sha1($_POST['password']);

				// validate the form

				$formErrors = array();

				if (strlen($user) < 4) {
					$formErrors[] = 'username cant be less than <strong>4 characters</strong>';
				}

				if (strlen($user) > 20) {
					$formErrors[] = 'username cant be more than <strong>20 characters</strong>';
				}

				if (empty($user)) {
					$formErrors[] = 'username cant be <strong>empty</strong>';
				}

				if (empty($pass)) {
					$formErrors[] = 'password cant be <strong>empty</strong>';
				}

				if (empty($name)) {
					$formErrors[] = 'full name cant be <strong>empty</strong>';
				}

				if (empty($email)) {
					$formErrors[] = 'email cant be <strong>empty</strong>';
				}



				// loop into errors array and echo it

				foreach($formErrors as $error) {
					echo '<div class="alert alert-danger">' . $error . '</div>';
				}

				// check if there's no error proceed the update operation

				if (empty($formErrors)) {

					if(isset($_POST['avatar'])){
								$avatar = rand(0, 10000000000) . '_' . $avatarName;		
								move_uploaded_file($avatarTmp, "uploads\avatars\\" . $avatar);
					}

					// check if user exist in database

					$check = checkItem("Username", "users", $user);

					if ($check == 1) {

						$theMsg = '<div class="alert alert-danger">user already exists!</div>';

						redirectHome($theMsg, 'back');

					} else {

						// insert userinfo in database

						if(isset($_POST['avatar'])){
							$stmt = $con->prepare("INSERT INTO 
													users(Username, Password, Email, FullName, RegStatus, Date, avatar)
												VALUES(:zuser, :zpass, :zmail, :zname, 1, now(), :zavatar) ");
							$stmt->execute(array(

								'zuser' 	=> $user,
								'zpass' 	=> $hashPass,
								'zmail' 	=> $email,
								'zname' 	=> $name,
								'zavatar'	=> $avatar

							));
						}else{
							$stmt = $con->prepare("INSERT INTO 
													users(Username, Password, Email, FullName, RegStatus, Date)
												VALUES(:zuser, :zpass, :zmail, :zname, 1, now()) ");
						$stmt->execute(array(

							'zuser' 	=> $user,
							'zpass' 	=> $hashPass,
							'zmail' 	=> $email,
							'zname' 	=> $name

						));
						}

						// echo success message

						$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' record inserted</div>';

						redirectHome($theMsg, 'back');

					}

				}


			} else {

				echo "<div class='container'>";

				$theMsg = '<div class="alert alert-danger">Error</div>';

				redirectHome($theMsg);

				echo "</div>";

			}

			echo "</div>";

		} elseif ($action == 'Edit') {

			// check if get request userid is numeric & get its integer value

			$userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

			// select all data depend on this id

			$stmt = $con->prepare("SELECT * FROM users WHERE UserID = ? LIMIT 1");

			// execute query

			$stmt->execute(array($userid));

			// fetch the data

			$row = $stmt->fetch();

			// the row count

			$count = $stmt->rowCount();

			// if there's such id show the form

			if ($count > 0) { ?>

				<h1 class="text-center">Edit Member</h1>
				<div class="container">
					<form class="form-horizontal" action="?action=Update" method="POST">
						<input type="hidden" name="userid" value="<?php echo $userid ?>" />
						<!-- start username -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Username</label>
							<div class="col-sm-10 col-md-6">
								<input type="text" name="username" class="form-control" value="<?php echo $row['Username'] ?>" autocomplete="off" required="required" />
							</div>
						</div>
						<!-- end username -->
						<!-- start password -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Password</label>
							<div class="col-sm-10 col-md-6">
								<input type="hidden" name="oldpassword" value="<?php echo $row['Password'] ?>" />
								<input type="password" name="newpassword" class="form-control" autocomplete="new-password" placeholder="Leave Blank If You actionnt Want To Change" />
							</div>
						</div>
						<!-- end password -->
						<!-- start email -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Email</label>
							<div class="col-sm-10 col-md-6">
								<input type="email" name="email" value="<?php echo $row['Email'] ?>" class="form-control" required="required" />
							</div>
						</div>
						<!-- end email -->
						<!-- start full name -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Full Name</label>
							<div class="col-sm-10 col-md-6">
								<input type="text" name="full" value="<?php echo $row['FullName'] ?>" class="form-control" required="required" />
							</div>
						</div>
						<!-- end full name -->
						<!-- start submit -->
						<div class="form-group form-group-lg">
							<div class="col-sm-offset-2 col-sm-10">
								<input type="submit" value="Save" class="btn btn-primary btn-lg" />
							</div>
						</div>
						<!-- end submit -->
					</form>
				</div>

			<?php

			// if there's no such id show error message

			} else {

				echo "<div class='container'>";

				$theMsg = '<div class="alert alert-danger">no such id</div>';

				redirectHome($theMsg);

				echo "</div>";

			}

		} elseif ($action == 'Update') { // update page

			echo "<h1 class='text-center'>Update Member</h1>";
			echo "<div class='container'>";

			if ($_SERVER['REQUEST_METHOD'] == 'POST') {

				// get variables from the form

				$id 	= $_POST['userid'];
				$user 	= $_POST['username'];
				$email 	= $_POST['email'];
				$name 	= $_POST['full'];

				// password trick

				$pass = empty($_POST['newpassword']) ? $_POST['oldpassword'] : sha1($_POST['newpassword']);

				// validate the form

				$formErrors = array();

				if (strlen($user) < 4) {
					$formErrors[] = 'Username Cant Be Less Than <strong>4 Characters</strong>';
				}

				if (strlen($user) > 20) {
					$formErrors[] = 'Username Cant Be More Than <strong>20 Characters</strong>';
				}

				if (empty($user)) {
					$formErrors[] = 'Username Cant Be <strong>Empty</strong>';
				}

				if (empty($name)) {
					$formErrors[] = 'Full Name Cant Be <strong>Empty</strong>';
				}

				if (empty($email)) {
					$formErrors[] = 'Email Cant Be <strong>Empty</strong>';
				}

				// loop into errors array and echo it

				foreach($formErrors as $error) {
					echo '<div class="alert alert-danger">' . $error . '</div>';
				}

				// check if there's no error proceed the update operation

				if (empty($formErrors)) {

					$stmt2 = $con->prepare("SELECT 
												*
											FROM 
												users
											WHERE
												Username = ?
											AND 
												UserID != ?");

					$stmt2->execute(array($user, $id));

					$count = $stmt2->rowCount();

					if ($count == 1) {

						$theMsg = '<div class="alert alert-danger">  this user is exist</div>';

						redirectHome($theMsg, 'back');

					} else { 

						// update the database with this info

						$stmt = $con->prepare("UPDATE users SET Username = ?, Email = ?, FullName = ?, Password = ? WHERE UserID = ?");

						$stmt->execute(array($user, $email, $name, $pass, $id));

						// echo success message

						$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated</div>';

						redirectHome($theMsg, 'back');

					}

				}

			} else {

				$theMsg = '<div class="alert alert-danger">you cant browse this page directly</div>';

				redirectHome($theMsg);

			}

			echo "</div>";

		} elseif ($action == 'Delete') { // delete member page

			echo "<h1 class='text-center'>Delete Member</h1>";
			echo "<div class='container'>";

				// check if get request userid is numeric & get the integer value of it

				$userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

				// select all data depend on this id

				$check = checkItem('userid', 'users', $userid);

				// if there's such id show the form

				if ($check > 0) {

					$stmt = $con->prepare("DELETE FROM users WHERE UserID = :zuser");

					$stmt->bindParam(":zuser", $userid);

					$stmt->execute();

					$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Deleted</div>';

					redirectHome($theMsg, 'back');

				} else {

					$theMsg = '<div class="alert alert-danger">This ID is Not Exist</div>';

					redirectHome($theMsg);

				}

			echo '</div>';

		} elseif ($action == 'Activate') {

			echo "<h1 class='text-center'>Activate Member</h1>";
			echo "<div class='container'>";

				// check if get request userid is numeric & get the integer value of it

				$userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

				// select all data depend on this id

				$check = checkItem('userid', 'users', $userid);

				// if there's such id show the form

				if ($check > 0) {

					$stmt = $con->prepare("UPDATE users SET RegStatus = 1 WHERE UserID = ?");

					$stmt->execute(array($userid));

					$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated</div>';

					redirectHome($theMsg);

				} else {

					$theMsg = '<div class="alert alert-danger">Error</div>';

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