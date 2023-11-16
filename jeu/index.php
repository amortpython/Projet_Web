<?php
$server = 'localhost';
$base= 'geobase';
$user = 'root';
$password = 'root';
$link = mysqli_connect($server, $user, $password, $base);

session_start();

require 'flight/Flight.php';

// stocker une variable globale
Flight::set('db', $link);

Flight::route('/', function () {
  Flight::render('accueil');
});

Flight::route('/jeu', function () {
  Flight::render('jeu');
});

Flight::start();
?>