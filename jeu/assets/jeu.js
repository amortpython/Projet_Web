var map = L.map('map').setView([51.505, -0.09], 13);

L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
}).addTo(map);

var marker = L.marker([51.5, -0.09]).addTo(map);

var circle = L.circle([51.508, -0.11], {
    color: 'red',
    fillColor: '#f03',
    fillOpacity: 0.5,
    radius: 500
}).addTo(map);

var polygon = L.polygon([
    [51.509, -0.08],
    [51.503, -0.06],
    [51.51, -0.047]
]).addTo(map);

marker.bindPopup("<b>Hello world!</b><br>I am a popup.").openPopup();
circle.bindPopup("I am a circle.");
polygon.bindPopup("I am a polygon.");

var popup = L.popup()
    .setLatLng([51.513, -0.09])
    .setContent("I am a standalone popup.")
    .openOn(map);

    
function onMapClick(e) {
        alert("You clicked the map at " + e.latlng);
    }
    
    map.on('click', onMapClick);

var popup = L.popup();

function onMapClick(e) {
        popup
            .setLatLng(e.latlng)
            .setContent("You clicked the map at " + e.latlng.toString())
            .openOn(map);
    }
    
map.on('click', onMapClick);

let pos;
var circle_pos;

navigator.geolocation.getCurrentPosition(function (position) {
    pos = position
    circle_pos = L.circle([pos.coords.latitude, pos.coords.longitude], {
        color: 'blue',
        fillColor: '#f02',
        fillOpacity: 0.5,
        radius: 500
    }).addTo(map);
    
    circle_pos.bindPopup("You're here");
    map.setView([pos.coords.latitude, pos.coords.longitude], 13);
});

let points = null

let contour = null

Vue.createApp({
    data() {
        return {
            research:"",
            points:[],
            proposal:[],
            json:null,
        };
    },

    methods: {
        submit() {
            fetch("http://api-adresse.data.gouv.fr/search/?q=" + this.research) 
            .then(result => result.json())
            .then(function (result) {
                if (points != null) {map.removeLayer(points)}
                
                points = L.geoJSON(result).addTo(map);
                map.fitBounds(points.getBounds());
            })
        },
        AutoComplete() {
            let envoi = new FormData();
            envoi.append("recherche",this.research);
            fetch("/villes", 
            {method:'post',
             body:envoi})
            .then(result => result.json())
            .then(result => {this.proposal = result});
            
        },
        Selec(nom, insee) {
            let choice = new FormData();
            choice.append("insee",insee);
            fetch("/villes_geom",
            {method:'post',
            body:choice}) 
            .then(result => result.json())
            .then(result => {
                this.json = result;
                if (contour != null) {map.removeLayer(contour)}
                contour = L.geoJSON(this.json).addTo(map);
                map.fitBounds(contour.getBounds());
                this.proposal = [];
                this.research = nom ;})//+ ',' + insee;})
        },
    },
}).mount('#entete');

