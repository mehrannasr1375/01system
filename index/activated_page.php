<?php
    // activate user
    if ( isset($_GET['username']) && isset($_GET['code']) )
    {
        $username = htmlspecialchars(trim($_GET['username']), ENT_QUOTES);
        $code     = htmlspecialchars(trim($_GET['code']), ENT_QUOTES);

        if ( User::activateUser($username, $code) === true )
            echo ("<br><br><p class='alert alert-success px-5 py-5'>حساب کاربری شما با موفقیت فعال گردید. از هم اکنون می توانید از سایت بهره ببرید!</span>");
        else
            echo ("<br><br><p class='alert alert-danger px-5 py-5'>متاسفانه حساب کاربری شما قابل فعالسازی نمی باشد!</span>");

    }
?>

