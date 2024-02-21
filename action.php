<?php
session_start();
$pageTitle = 'Login';
if (!isset($_SESSION['user'])) {
  header('Location: index.php');
}
include 'init.php';

  $item_id = intval($_SESSION['item_id']);
  $userid = $_SESSION["uid"];
  $q = $_SESSION["q"];
  
  $status = placeOrder($userid, $item_id, $q);
  if($status){


    echo '<div class="container m-item" >
        <div class="alert alert-success">Order Placed!</div>
      </div>';

      }else{
    echo '<div class="container m-item" >
              <div class="alert alert-danger">Error!</div>
            </div>';
      }



header("refresh:3;url=index.php");




