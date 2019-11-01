<?php

unset($_SESSION['u_id']);
unset($_SESSION['u_name']);
unset($_SESSION['u_type']);
unset($_SESSION['f_name']);
unset($_SESSION['l_name']);
unset($_SESSION['avatar']);

header("Location:../index.php");
