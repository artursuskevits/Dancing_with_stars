<?php
$kasuatajd ='root';
$servernimi = 'localhost';
$password = '';
$aandmebaas= 'artursuskevits';
$yhendus = new mysqli($servernimi,$kasuatajd,$password,$aandmebaas);
$yhendus ->set_charset('UTF8');