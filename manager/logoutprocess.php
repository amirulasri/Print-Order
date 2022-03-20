<?php
if (isset($_COOKIE['managerusercookie'])) {
    unset($_COOKIE['managerusercookie']);
    setcookie('managerusercookie', null, -1, '/');
    header('location: login');
} else {
    echo "FAILED COOKIE";
    die(header('location: login'));
}