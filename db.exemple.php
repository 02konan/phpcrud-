<?php

function DB_connect() {
    try {
        return new PDO('mysql:host=127.0.0.1;port=3306;dbname=dbname;charset=utf8', 'user', 'pwd', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}