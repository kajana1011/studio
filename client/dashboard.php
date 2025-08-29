<?php
 session_start();
 echo "Welcome to the Client Dashboard, " . htmlspecialchars($_SESSION['username']) . "!";