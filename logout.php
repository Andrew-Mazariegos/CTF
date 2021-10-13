<?php
//logout user and invalidate cookie
if (isset($_COOKIE['cookie'])) {
    unset($_COOKIE['cookie']);
    setcookie('cookie', '', time() - 3600, '/'); // empty value and old timestamp
}
?>