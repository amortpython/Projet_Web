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

Flight::route('GET /login', function () {
  if (isset($_SESSION['user'])){
    $user = $_SESSION['user'];
  }
  else{$user=null;}
  Flight::render('login', ['user' => $user]);
});

Flight::route('POST /login', function () {
  $_SESSION['user'] = $_POST['user'];
  Flight::render('login', ['user' => $_POST['user']]);
});

Flight::route('GET /logout', function () {
    $_SESSION['user'] = [];
    $user = null;
  Flight::render('login', ['user' => $user]);
});

Flight::route('/departements', function () {
  // récupérer la variable
  $link = Flight::get('db');
  $results_region = mysqli_query($link, "SELECT insee, nom FROM regions");
  foreach ($results_region as $reg) {
    // $result est un tableau associatif
    $region[$reg["insee"]] = $reg["nom"];

  if (isset($_POST['region'])){
    $reg = $_POST['region'];
    $sql = "SELECT insee, nom FROM departements WHERE region_insee=?";
    // requete preparee
    $requete = mysqli_prepare($link, $sql);
    
    // lie des donnees à la requete (dans l’ordre)
    mysqli_stmt_bind_param($requete, "i", $reg);

    // execution
    mysqli_stmt_execute($requete);

    // recuperation des resultats
    $results = mysqli_stmt_get_result($requete);
    }
  else{
    $results = mysqli_query($link, "SELECT insee, nom FROM departements");
    }
  }
  foreach ($results as $result) {
    // $result est un tableau associatif
    $departement[$result["insee"]] = $result["nom"];} 
     
  Flight::render('departements', ['departement' => $departement, 'region' => $region]);
});

Flight::route('GET /nombre', function () {
  Flight::render('nombre');
});

Flight::route('GET /tweetbox', function () {
  Flight::render('tweetbox');
});

Flight::route('GET /map', function () {
  Flight::render('leaflet');
});

Flight::route('POST /villes', function() {
  $link = Flight::get('db');
  
  $tab_com=[];
  $tab_surf = [];
  if (isset($_POST['recherche']) and $_POST['recherche'] != null){
    $recherche = $_POST['recherche'];
    $communes = mysqli_query($link, "SELECT insee, nom FROM communes WHERE nom LIKE '$recherche%' LIMIT 10");

    foreach ($communes as $com) {
      // $result est un tableau associatif
      $tab_com[] = $com;
    }
  }

  if (isset($_POST['code_insee']) and $_POST['code_insee'] != null){
    $code_insee = $_POST['code_insee'];
    $surface = mysqli_query($link, "SELECT ST_AsGeoJSON(geometry) AS geo FROM communes WHERE insee='$code_insee'");

    foreach ($surface as $surf) {
      // $result est un tableau associatif
      $tab_surf[] = json_decode($surf['geo']);
    }
  }

  
  Flight::json(['communes' => $tab_com, 'geom' => $tab_surf]);
});


Flight::start();
?>