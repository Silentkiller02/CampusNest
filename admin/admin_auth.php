<?php
// admin/admin_auth.php
session_start();
if (empty($_SESSION['admin_id'])) {
  header("Location: admin_login.php");
  exit;
}
