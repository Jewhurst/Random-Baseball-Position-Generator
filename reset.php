<?php
require 'header.php';
session_destroy();
redirect(1,"index.php");
?>