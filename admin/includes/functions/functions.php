<?php

/* getAllFrm => function to get all records with specified conds */
	function getAllFrom($field, $table, $where = NULL, $and = NULL, $orderfield, $ordering = "DESC") {
		global $con;
		$getAll = $con->prepare("SELECT $field FROM $table $where $and ORDER BY $orderfield $ordering");
		$getAll->execute();
		$all = $getAll->fetchAll();
		return $all;
	}




	function getTitle() {
		global $pageTitle;
		if (isset($pageTitle)) {
			echo $pageTitle;
		} else {
			echo 'Default';
		}
	}


/*redirectHome => function for redirection*/
	function redirectHome($theMsg, $url = null, $seconds = 2) {
		if ($url === null) {
			$url = 'index.php';
			$link = 'Homepage';
		} else {
			if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '') {
				$url = $_SERVER['HTTP_REFERER'];
				$link = 'Previous Page';
			} else {
				$url = 'index.php';
				$link = 'Homepage';
			}
		}
		echo $theMsg;
		echo "<div class='alert alert-info'>You Will Be Redirected to $link After $seconds Seconds.</div>";
		header("refresh:$seconds;url=$url");
		exit();
	}

	/*check items => function to check item in database [ function accept parameters ]*/
	function checkItem($select, $from, $value) {
		global $con;
		$statement = $con->prepare("SELECT $select FROM $from WHERE $select = ?");
		$statement->execute(array($value));
		$count = $statement->rowCount();
		return $count;
	}

	/*count number of items => function to count number of items rows*/
	function countItems($item, $table) {
		global $con;
		$stmt2 = $con->prepare("SELECT COUNT($item) FROM $table");
		$stmt2->execute();
		return $stmt2->fetchColumn();
	}

	/* get latest records =>function to get latest items from database [ users, items, comments ]*/
	function getLatest($select, $table, $order, $limit = 5) {
		global $con;
		$getStmt = $con->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit");
		$getStmt->execute();
		$rows = $getStmt->fetchAll();
		return $rows;
	}