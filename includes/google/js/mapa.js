var map;
var directionsDisplay;
var directionsService = new google.maps.DirectionsService();

function initialize() {
	directionsDisplay = new google.maps.DirectionsRenderer();
	var latlng = new google.maps.LatLng(-18.8800397, -47.05878999999999);
    var options = {
        zoom: 10,
		center: latlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    map = new google.maps.Map(document.getElementById("mapa"), options);
	directionsDisplay.setMap(map);
	directionsDisplay.setPanel(document.getElementById("trajeto-texto"));
	if (navigator.geolocation) {
		navigator.geolocation.getCurrentPosition(function (position) {
			pontoPadrao = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
			map.setCenter(pontoPadrao);
			var geocoder = new google.maps.Geocoder();
			geocoder.geocode({
				"location": new google.maps.LatLng(position.coords.latitude, position.coords.longitude)
            },
            function(results, status) {
				if (status == google.maps.GeocoderStatus.OK) {
					$("#txtEnderecoPartida").val(results[0].formatted_address);
				}
				function callback(results, status) {
				  if (status == google.maps.places.PlacesServiceStatus.OK) {
					for (var i = 0; i < results.length; i++) {
					  var place = results[i];
					  createMarker(results[i]);
					}
				  }
				}
				function createMarker(place) {
				  var placeLoc = place.geometry.location;
				  var marker = new google.maps.Marker({
					map: map,
					position: place.geometry.location
				  });
				}
			    navigator.geolocation.getCurrentPosition(function (position) {
				  pontoPadrao = new google.maps.LatLng(position.coords.latitude, position.coords.longitude); // Com a latitude e longitude que retornam do Geolocation, criamos um LatLng
				  var geocoder = new google.maps.Geocoder();

				  var enderecoPadrao = "";
				  geocoder.geocode({
					 "location": new google.maps.LatLng(position.coords.latitude, position.coords.longitude)
				  },
				  function(results, status) {
					 if (status == google.maps.GeocoderStatus.OK) {
						  var marker = new google.maps.Marker({
							map: map,
							position: pontoPadrao,
						  });
						  google.maps.event.addListener(marker, 'click', function() {
							infowindow.setContent("<div Style='height:100px;width:300px'>Minha Localização Atual<br><br>"+results[0].formatted_address+"</div>");
							infowindow.open(map, this);
						  });
					 }
				  });
			   });
            });
		});
	}
}
initialize();

$(document).ready(function(){
	event.preventDefault();
	var enderecoPartida = $("#txtEnderecoPartida").val();
	var enderecoChegada = $("#txtEnderecoChegada").val();
	var request = {
		origin: enderecoPartida,
		destination: enderecoChegada,
		travelMode: google.maps.TravelMode.DRIVING
	};
	directionsService.route(request, function(result, status) {
	if (status == google.maps.DirectionsStatus.OK) {
			directionsDisplay.setDirections(result);
		}
	});

	var i = 0;
	setInterval(function(){
		if(i<enderecoLoc.length){
			geraGeocoder(enderecoLoc[i],prestadorLoc[i]);
		}
		i = i +1;
	}, 1);
});

function geraGeocoder(enderecoLoc,prestadorLoc){
	geocoder = new google.maps.Geocoder();
	geocoder.geocode({'address':enderecoLoc}, function(results, status ){
		if( status = google.maps.GeocoderStatus.OK){
			latlng = results[0].geometry.location;
			var markerInicio = new google.maps.Marker({
				map: map,
				position: latlng,
				title: 'nome prestador'
			});
			var infowindow = new google.maps.InfoWindow();
			google.maps.event.addListener(markerInicio, 'click', function() {
				$(".mostra-dados-mapa").parent().parent().parent().parent().hide();
				infowindow.setContent("<div class='mostra-dados-mapa' Style='height:100px;width:300px'>"+prestadorLoc+"<br><br>"+enderecoLoc+"</div>");
				infowindow.open(map,markerInicio);
			});
		}
	});
}