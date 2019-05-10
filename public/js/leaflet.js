



var markers = [];

// var latlng = L.latLng([latitude,longitude]);
// var mymap = L.map('mapid').setView([46.85, 2.3518], 13);

// import du fond de carte
function display_map(mymap)
{

  L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
    maxZoom: 18,
    // fournisseur de mosaiquemapbox.streets
    id: 'mapbox.streets',
    accessToken: 'pk.eyJ1IjoicGFyc2VyaWFuanVseSIsImEiOiJjanVpbmx0MXEwOHhjM3lsbG8ycmFhZHlmIn0.onqkeNx7j1WWu-WPbg9WIw'

  }).addTo(mymap);
}

// ajouter une fenetre contextuelle à un click sur un marqueur

function display_marker(mymap, latitude, longitude, title, place)
{


    marker = new L.Marker([latitude,longitude])
    marker.addTo(mymap)

    marker.setLatLng([latitude,longitude])
    // marker.setContent('<p>This is a popup.</p>')
  // marker.openOn(mymap)
    marker.bindPopup('<strong>'+title+'</strong>' + '<br/>' + place)
    marker.openPopup();
    markers.push(marker); // les markers stockés dans un tableau
    console.log(markers);

}

function get_markers(p = null)
{
  //     // Si la variable est null alors afficher tout les evenements sinon afficher en fonction du clique choisi
  projectType = p === null ? 'allProject' : p.target.id;// recupere id du bouton un evenement se produit

  fetch(`http://localhost/sites/Hypnos/public/index.php/project/jsongps?option=${projectType}`)
  .then(function(response){
    return response.json();
  })
  .then(function(myJson) {
    console.log(JSON.stringify(myJson)); // myJson array ou sont stockes mes evenements
    //display_marker(mymap,latitude,longitude);

    // supprimer les markers
    for(m of markers){
      mymap.removeLayer(m)
    }

    // recuperation de tout mes evenements
    for( let p of myJson) {
      display_marker(mymap, p.latitude, p.longitude, p.title, p.place);
    }
    console.log(markers);
  });
}


// TEST marqueur sur paris
//var marker = L.marker([48.836533, 2.334451]).addTo(mymap);
//marker.bindPopup("<p>You are here</p>").openPopup();
//}




//initmap ();
