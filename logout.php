<?php 
session_start(); // Elindítja a session-t, hogy hozzáférhessünk az aktuális session adatokhoz

session_unset(); // Törli az összes session változót

session_destroy(); // Megsemmisíti a session-t (a session fájl is törlésre kerül)

header("Location: login.php"); // Átirányít a login oldalra
exit; // Leállítja a script futását