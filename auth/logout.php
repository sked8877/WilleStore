<?php
session_start();
session_destroy();
header("Location: /ws/index.php");
exit;