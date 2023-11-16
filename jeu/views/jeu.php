<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>L'aventure !!!</title>
        <meta name="description" content="La description">
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
        crossorigin=""/>
        <link rel="stylesheet" href="assets/jeu.css"/>
        <!-- Make sure you put this AFTER Leaflet's CSS -->
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>
        <script src="https://cdn.jsdelivr.net/npm/vue"></script>
    </head>
    <body>
        <h1>Map.php Made with Leaflet</h1>
        <div id="entete">
            <form @submit.prevent="submit">
                <fieldset>
                    <input type='text' v-model="research" @input="AutoComplete">
                    <button>Search</button> 
                </fieldset>
                <ul id="villes" v-if="research != ''">
                    <li v-for="prop in proposal" @click='Selec(prop.nom,prop.insee)'>{{prop.nom}} <!--, {{prop.insee}}--></li>
                </ul>
                
            </form>
        </div>
        
        <div id="map"></div>
        
        <script src="assets/jeu.js"></script>
    </body>