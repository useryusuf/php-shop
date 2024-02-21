<?php
	session_start();
	$pageTitle = 'Login';
	if (isset($_SESSION['user'])) {
		header('Location: index.php');
	}
	include 'init.php';

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

		if (isset($_POST['login'])) {

			$user = $_POST['username'];
			$pass = $_POST['password'];
			$hashedPass = sha1($pass);

			// check if the user exist in database
			$stmt = $con->prepare("SELECT 
										UserID, Username, Password 
									FROM 
										users 
									WHERE 
										Username = ? 
									AND 
										Password = ?");

			$stmt->execute(array($user, $hashedPass));

			$get = $stmt->fetch();

			$count = $stmt->rowCount();

			// count > 0 ===> the database contain record about this username

			if ($count > 0) {

				$_SESSION['user'] = $user; 
				$_SESSION['uid'] = $get['UserID']; 
				header('Location: index.php');
				exit();
			}

		} else {

			$formErrors = array();

			$username 	= $_POST['username'];
			$password 	= $_POST['password'];
			$password2 	= $_POST['password2'];
			$email 		= $_POST['email'];

			if (isset($username)) {

				$filterdUser = filter_var($username, FILTER_SANITIZE_STRING);

				if (strlen($filterdUser) < 4) {

					$formErrors[] = 'username must be larger than 4 characters';

				}

			}

			if (isset($password) && isset($password2)) {

				if (empty($password)) {

					$formErrors[] = 'password cant be empty';

				}

				if (sha1($password) !== sha1($password2)) {

					$formErrors[] = 'password doesn\'t match';

				}

			}

			if (isset($email)) {

				$filterdEmail = filter_var($email, FILTER_SANITIZE_EMAIL);

				if (filter_var($filterdEmail, FILTER_VALIDATE_EMAIL) != true) {

					$formErrors[] = 'email is not valid';

				}

			}

			// check if there's no error proceed the user add

			if (empty($formErrors)) {

				// check if user exist in database

				$check = checkItem("Username", "users", $username);

				if ($check == 1) {

					$formErrors[] = 'this user already exists';

				} else {

					// insert userinfo in database

					$stmt = $con->prepare("INSERT INTO 
											users(Username, Password, Email, RegStatus, Date)
										VALUES(:zuser, :zpass, :zmail, 0, now())");
					$stmt->execute(array(

						'zuser' => $username,
						'zpass' => sha1($password),
						'zmail' => $email

					));

					// echo success message
					$succesMsg = 'registered!';

				}

			}

		}

	}

?>

<div class="container login-page">
	<h1 class="text-center">
		<span class="selected" data-class="login">Login</span> | 
		<span data-class="signup">Signup</span>
	</h1>
	<!-- start login form -->
	<form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
		<div class="input-container">
			<input 
				class="form-control" 
				type="text" 
				name="username" 
				autocomplete="off"
				placeholder="username" 
				required />
		</div>
		<div class="input-container">
			<input 
				class="form-control" 
				type="password" 
				name="password" 
				autocomplete="new-password"
				placeholder="password" 
				required />
		</div>
		<input class="btn btn-primary btn-block" name="login" type="submit" value="Login" />
	</form>
	<!-- end login form -->
	<!-- start signup form -->
	<form class="signup" 
	action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
		<div class="input-container">
			<input 
				pattern=".{4,}"
				title="Username Must Be Between 4 Chars"
				class="form-control" 
				type="text" 
				name="username" 
				autocomplete="off"
				placeholder="username" 
				required />
		</div>
		<div class="input-container">
			<input 
				minlength="4"
				class="form-control" 
				type="password" 
				name="password" 
				autocomplete="new-password"
				placeholder="complex password" 
				required />
		</div>
		<div class="input-container">
			<input 
				minlength="4"
				class="form-control" 
				type="password" 
				name="password2" 
				autocomplete="new-password"
				placeholder="password" 
				required />
		</div>
		<div class="input-container">
			<input 
				class="form-control" 
				type="email" 
				name="email" 
				placeholder="valid email" />
		</div>
		<input class="btn btn-success btn-block" name="signup" type="submit" value="Signup" />
	</form>
	<!-- end signup form -->
	<div class="the-errors text-center">
		<?php 

			if (!empty($formErrors)) {

				foreach ($formErrors as $error) {

					echo '<div class="msg error">' . $error . '</div>';

				}

			}

			if (isset($succesMsg)) {

				echo '<div class="msg success">' . $succesMsg . '</div>';

			}

		?>
	</div>
</div>

<?php 
	include $tpl . 'footer.php';
?>