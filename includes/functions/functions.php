<?php

	/*
	** function to get all records from any database table
	*/
	function getAllFrom($field, $table, $where = NULL, $and = NULL, $orderfield="", $ordering = "DESC") {
		global $con;
		$getAll = $con->prepare("SELECT $field FROM $table $where $and ORDER BY $orderfield $ordering");
		$getAll->execute();
		$all = $getAll->fetchAll();
		return $all;
	}



	/*
	** check if user is not activated
	** function to check the regstatus of the user
	*/
	function checkUserStatus($user) {
		global $con;
		$stmtx = $con->prepare("SELECT 
									Username, RegStatus 
								FROM 
									users 
								WHERE 
									Username = ? 
								AND 
									RegStatus = 0");

		$stmtx->execute(array($user));
		$status = $stmtx->rowCount();
		return $status;
	}

	/*
	** checkItem => function to check item in database
	*/

	function checkItem($select, $from, $value) {
		global $con;
		$statement = $con->prepare("SELECT $select FROM $from WHERE $select = ?");
		$statement->execute(array($value));
		$count = $statement->rowCount();
		return $count;
	}





	/*getTitle => get page title in case the page has the variable $pagetitle and echo defult title for other pages*/
	function getTitle() {
		global $pageTitle;
		if (isset($pageTitle)) {
			echo $pageTitle;
		} else {
			echo 'Default';
		}
	}

	/*redirectHome => redection*/
	function redirectHome($theMsg, $url = null, $seconds = 3) {
		if ($url === null) {
			$url = 'index.php';
			$link = 'Home';
		} else {
			if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '') {
				$url = $_SERVER['HTTP_REFERER'];
				$link = 'previous page';
			} else {
				$url = 'index.php';
				$link = 'Home';
			}
		}
		// echo $theMsg;
		echo "<div class='alert alert-info'>$theMsg <br><br> you will be redirected to $link .</div>";
		header("refresh:$seconds;url=$url");
		exit();
	}

	/*countItems => function to count number of items rows*/
	function countItems($item, $table) {
		global $con;
		$stmt2 = $con->prepare("SELECT COUNT($item) FROM $table");
		$stmt2->execute();
		return $stmt2->fetchColumn();

	}

	/* getLatest=> function to get latest items from database [ users, items, comments ]*/

	function getLatest($select, $table, $order, $limit = 5) {
		global $con;
		$getStmt = $con->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit");
		$getStmt->execute();
		$rows = $getStmt->fetchAll();
		return $rows;

	}

	/*getCatName => function to get name of a category by id*/

		function getCatName($id) {
		global $con;
		$getStmt = $con->prepare("SELECT Name FROM categories WHERE ID = $id");
		$getStmt->execute();
		$rows = $getStmt->fetchAll();
		return $rows;

	}

function placeOrder($UserID, $ItemID, $Qauntity){
		global $con;
		$statement = $con->prepare("INSERT INTO `orders`(`UserID`, `Order_Date`) VALUES (?,CURDATE())");
		$statement->execute(array($UserID));
		$count = $statement->rowCount();
		if($count > 0) {

			$st = $con->query("SELECT * FROM orders");
			$rows = $st->fetchAll();
			$last_k = array_key_last($rows);
			$order_id = intval($rows[$last_k]["OrderID"]);
			$st = $con->prepare("SELECT Price FROM items WHERE Item_ID = ?");
			$st->execute([$ItemID]);
			$res = $st->fetch();
			$UnitPrice = floatval($res["Price"]);

			$st2 = $con->prepare("INSERT INTO `order_items`(`OrderID`, `ItemID`, `Quantity`, `UnitPrice`) VALUES (?,?,?,?)");
			$status = $st2->execute(array($order_id,$ItemID,$Qauntity,$UnitPrice));
			return $status;
		}

		
}