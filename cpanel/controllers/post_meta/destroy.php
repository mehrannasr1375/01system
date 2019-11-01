<?php

require_once __DIR__ . "/../../../include_all.php";


if (!isset($_POST['id']))
    header('location:../../cpanel.php?action=categories');


// delete cat or tag
PostMeta::delete($_POST['id']);


header('location:../../cpanel.php?action=categories');
