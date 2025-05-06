<?php
 if (isset($_SESSION['id'])) {
   session_destroy();
   header("location:index.php?page=login");
 }else {
   header("location:index.php?page=login");
 }
 
