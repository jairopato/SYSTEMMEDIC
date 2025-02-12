var Latant=  null;
var Lngant = null;
var Latitud =  null;
var Longitud = null;
var Latitud =  null;
var Longitud = null;
var Lonbus = null;
var Latbus = null;
var VelBus = null;

var horabus = null;
var sumat = null;
var sumamas = 3;


//-------------------------------------------------------------
$(document).ready(function(){

        });
//--------------------------------------------------------------------------------------------

var customLabel = {
   restaurant: {
       label: 'R'
   },
   bar: {
       label: 'B'
   }
};

function initMap() {

//---------------------------------------------------------------------



if (navigator.geolocation)
{
//-------------------- //Pedimos los datos de geolocalizacion al navegador del USUARIO

navigator.geolocation.getCurrentPosition(
 //Si el navegador entrega los datos de geolocalizacion los imprimimos
 function (position) {
   Latitud = position.coords.latitude;
   Longitud = position.coords.longitude;



     var coord = {lat: Latitud ,lng: Longitud};
     var map = new google.maps.Map(document.getElementById('map-container-google-2'),{
       zoom: 16,
       center: coord
     });
     
     var marker = new google.maps.Marker({
       position: coord,
       map: map,
      // icon: 'http://maps.google.com/mapfiles/ms/icons/blue-dot.png',
      icon: 'jpg/user001ico.png',
      size: new google.maps.Size(32, 32), //tamaño de la imagen
      origin: new google.maps.Point(0,0), //origen de la iamgen
      anchor: new google.maps.Point(32, 32),
      title: 'Estás Aquí', 
      visible:true,
       labelContent: "Estás Aquí",
       labelAnchor: new google.maps.Point(50, 70),
       labelClass: "labels" // the CSS class for the label
     });
   

//-------------------------------------------------------------------------------------

   var infoWindow = new google.maps.InfoWindow;

//--------------------------COLOCA LA RUTAS DEL BUS----------------------------------*/

        var point;
        var flightPlanCoordinates=new Array();
        downloadUrl('http://localhost:3000/php/xmlRutas.php', function(data) {
            var xml = data.responseXML;
    
            var markers = xml.documentElement.getElementsByTagName("markeruta");
            for (var i = 0; i < markers.length; i++) {
                point = new google.maps.LatLng(
                  markers[i].getAttribute("latitud"),
                  markers[i].getAttribute("longitud"));
                flightPlanCoordinates[i]=point;
              }
            
            var flightPath = new google.maps.Polyline({
             path: flightPlanCoordinates,
             geodesic: true,
             map: map,
             strokeColor: "#FF0000",
             strokeOpacity: 0.5,
             strokeWeight: 6,
             clickable: false
            });
    
        });



//-------------------------------------Coloca la posición de las Paradas -----------------------------------------------


   setInterval(function(){

//----------------------------------------------------------------
       downloadUrl('http://localhost:3000/php/xmlBus.php', function(data) {
        var xml = data.responseXML;
        var markers = xml.documentElement.getElementsByTagName("marker");
        Array.prototype.forEach.call(markers, function(markerElem) {
            
             Latbus = markerElem.getAttribute('latitud');
             Lonbus =  markerElem.getAttribute('longitud');
             VelBus = markerElem.getAttribute('velocidad');
        });

    });
//-----------------------------------------------------COLOCA LAS PARADAS-----------
var imageparada = {
    url: 'jpg/parada002.png', //ruta de la imagen
    size: new google.maps.Size(32, 32), //tamaño de la imagen
    origin: new google.maps.Point(0,0), //origen de la iamgen
    //el ancla de la imagen, el punto donde esta marcando, en nuestro caso el centro inferior.
    anchor: new google.maps.Point(13, 32) 
  };

   downloadUrl('http://localhost:3000/php/xml.php', function(data) {
    
     
       var xml = data.responseXML;
       var markers = xml.documentElement.getElementsByTagName('marker');
       Array.prototype.forEach.call(markers, function(markerElem) {
           var idmapa = markerElem.getAttribute('idmapa');
           var lugar = markerElem.getAttribute('lugar');
           var descripcion = markerElem.getAttribute('descripcion');
          
           var point = new google.maps.LatLng(
               parseFloat(markerElem.getAttribute('lat')),
               parseFloat(markerElem.getAttribute('lng')));

//------------Formulas para sacar distancia entre 2 coordenadas----------
//La fórmula de Haversine
Number.prototype.toRad = function() {
    return this * Math.PI / 180;
 }


 //var lat2 = -1.66659; 
 //var lon2 = -78.6525; 
 var lat2 = parseFloat(Latbus); 
 var lon2 = parseFloat(Lonbus);
 var lat1 = parseFloat(markerElem.getAttribute('lat')); 
 var lon1 = parseFloat(markerElem.getAttribute('lng')); 
 
 var R = 6371; // km 
 var x1 = lat2-lat1;
 var dLat = x1.toRad();  
 var x2 = lon2-lon1;
 var dLon = x2.toRad();  
 var a = Math.sin(dLat/2) * Math.sin(dLat/2) + 
                 Math.cos(lat1.toRad()) * Math.cos(lat2.toRad()) * 
                 Math.sin(dLon/2) * Math.sin(dLon/2);  
 var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)); 
 var d = R * c; 
 //var t = Math.round(d / parseFloat(VelBus),2) + 3;
 //var t = Math.round((d / 5)*20, 2);
 var t = Math.round((d/VelBus)*60); 
 //var t = Math.round(d / parseFloat(VelBus),2) ;
 //var t = Math.round((d / 5)*20, 2);
 //sumat = sumamas ;
 sumat = sumamas;

//--------------------------------------
           const contentString =
               '<div id="content">' +
               '<div id="siteNotice">' +
               "</div>" +
               '<center>'+
               '<h1 id="firstHeading" class="firstHeading">LLega en ' + sumamas +  ' min</h1>' +
               '</center>'+
               '<br>'+
               '<div id="bodyContent">' +
               '<br>'+
               "<p><b> " + descripcion + "</p>" +
               "</p>" +
               "</div>" +
               "</div>";

           var marker = new google.maps.Marker({
               map: map,
               position: point,
               icon: imageparada,
           });
           marker.addListener('click', function() {
               infoWindow.setContent(contentString);
               infoWindow.open(map, marker);
           });
           sumamas = sumamas + 3;
       });
       sumamas = 3;
   });
//sumamas = 3;
//--------------------------------------------------------COLOCA EL BUS 1-------------
     var image = {
       url: 'jpg/bus003.png', //ruta de la imagen
       size: new google.maps.Size(32, 32), //tamaño de la imagen
       origin: new google.maps.Point(0,0), //origen de la iamgen
       //el ancla de la imagen, el punto donde esta marcando, en nuestro caso el centro inferior.
       anchor: new google.maps.Point(32, 32) 
     };

       downloadUrl('http://localhost:3000/php/xmlBus.php', function(data) {

       var xml = data.responseXML;
       var markers = xml.documentElement.getElementsByTagName('marker');
       Array.prototype.forEach.call(markers, function(markerElem) {
           var Idlocalizacion = markerElem.getAttribute('Idlocalizacion');
           var IdBus = markerElem.getAttribute('IdBus');
           var velocidad = markerElem.getAttribute('velocidad');
           Latant = markerElem.getAttribute('latitud');
           Lngant = markerElem.getAttribute('longitud');
           
           var point = new google.maps.LatLng(
               parseFloat(markerElem.getAttribute('latitud')),
               parseFloat(markerElem.getAttribute('longitud')));

            var point2 = new google.maps.LatLng(
            parseFloat(Latant),
            parseFloat(Lngant));


           const contentString =
               '<div id="content">' +
               '<div id="siteNotice">' +
               "</div>" +
               '<center>'+
               '<h1 id="firstHeading" class="firstHeading">'+ velocidad +  '</h1>' +
               '</center>'+
               '<br>'+
               '<div id="bodyContent">' +
               '<br>'+
               "<p><b>Linea: " + IdBus + "</p>" +
               "</p>" +
               "</div>" +
               "</div>";

            var marker = new google.maps.Marker({
            map: map,
            visible:true,
            icon: image,
            position: point,  
           });

           marker.addListener('click', function() {
               infoWindow.setContent(contentString);
               infoWindow.open(map, marker);
           });
           
           setTimeout(function(){
          marker.setMap(null);
           }, 2000);

       });
   });
//---------------------------------
//--------------------------------------------------------COLOCA EL BUS 2-------------

       downloadUrl('http://localhost:3000/php/xmlBus2.php', function(data) {

       var xml = data.responseXML;
       var markers = xml.documentElement.getElementsByTagName('marker');
       Array.prototype.forEach.call(markers, function(markerElem) {
           var Idlocalizacion = markerElem.getAttribute('Idlocalizacion');
           var IdBus = markerElem.getAttribute('IdBus');
           var velocidad = markerElem.getAttribute('velocidad');
           Latant = markerElem.getAttribute('latitud');
           Lngant = markerElem.getAttribute('longitud');
           
           var point = new google.maps.LatLng(
               parseFloat(markerElem.getAttribute('latitud')),
               parseFloat(markerElem.getAttribute('longitud')));

            var point2 = new google.maps.LatLng(
            parseFloat(Latant),
            parseFloat(Lngant));


           const contentString =
               '<div id="content">' +
               '<div id="siteNotice">' +
               "</div>" +
               '<center>'+
               '<h1 id="firstHeading" class="firstHeading">'+ velocidad +  '</h1>' +
               '</center>'+
               '<br>'+
               '<div id="bodyContent">' +
               '<br>'+
               "<p><b>Linea: " + IdBus + "</p>" +
               "</p>" +
               "</div>" +
               "</div>";

            var marker = new google.maps.Marker({
            map: map,
            visible:true,
           // icon: 'http://maps.google.com/mapfiles/dir_0.png',
            icon: image,
            position: point,
               
           });

           marker.addListener('click', function() {
               infoWindow.setContent(contentString);
               infoWindow.open(map, marker);
           });
           
           setTimeout(function(){
          marker.setMap(null);
           }, 2000);

       });
   });

//--------------------------------
}, 2000);

// Una matriz con las coordenadas de los límites de Bucaramanga, extraídas manualmente de la base de datos GADM
 },
 //Si no los entrega manda un alerta de error
 function () {
     window.alert("nav no permitido");
 }

);

}
else
{
window.alert("Navegador no soportado");
}

//------------------------------------------------------------------------------  
  
}


// fin de initmap-----------------------------------------------------  
function downloadUrl(url, callback) {
   var request = window.ActiveXObject ?
       new ActiveXObject('Microsoft.XMLHTTP') :
       new XMLHttpRequest;
   request.onreadystatechange = function() {
       if (request.readyState == 4) {
           request.onreadystatechange = doNothing;
           callback(request, request.status);
       }
   };
   request.open('GET', url, true);
   request.send(null);
}

function doNothing() {}



//--------------------- Fin de JS

