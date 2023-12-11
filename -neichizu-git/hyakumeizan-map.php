<!DOCTYPE html>
<html>
<head>
  <title>leaflet-map-csv</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta charset="utf-8">
  <!-- Load Leaflet code library - see updates at http://leafletjs.com/download.html -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"/>
  <link rel="stylesheet" href="./css/style.css"/>
  <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
  <!-- Load jQuery and PapaParse to read data from a CSV file -->
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/papaparse@5.3.0/papaparse.min.js"></script>

  <!-- Plugin Icon.Pulse -->
  <!-- <link rel="stylesheet" href="./css/L.Icon.Pulse.css" >
  <script src="./js/L.Icon.Pulse.js" ></script> -->
  <!-- plugin -->
  <link rel="stylesheet" href="./css/L.VisualClick.css" />
  <script src="./js/L.VisualClick.js"></script>
  <!-- plugin -->

  <!-- Position the map with Cascading Style Sheet (CSS) -->
  <style>
    body { margin:0; padding:0; }
    #map { position: absolute; top:0; bottom:0; right:0; left:0; }
  </style>

</head>
<body>

  <!-- Insert HTML division tag to layout the map -->
  <div id="map"></div>

  <!-- Insert Javascript (.js) code to create the map -->
  <script>

  // Set up initial map center and zoom level
  var map = L.map('map', {
    center: [35,139], // EDIT latitude, longitude to re-center map
    zoom: 5.5,  // EDIT from 1 to 18 -- decrease to zoom out, increase to zoom in
    scrollWheelZoom: true,
    tap: true ,
    zoomControl: false,
  });
    //スケールコントロールを最大幅200px、右下、m単位で地図に追加
    L.control.scale({ maxWidth: 200, position: 'bottomright', imperial: false }).addTo(map);

  var osm = L.tileLayer('http://tile.openstreetmap.jp/{z}/{x}/{y}.png', {
    attribution: "&copy; <a href='http://www.openstreetmap.org/copyright' target='_blank'>OpenStreetMap</a>"
  }) // EDIT - insert or remove ".addTo(map)" before last semicolon to display by default
 
  var gmap = L.tileLayer('https://mt1.google.com/vt/lyrs=r&x={x}&y={y}&z={z}',
        {attribution: "<a href='https://developers.google.com/maps/documentation' target='_blank'>Google Map</a>"
  }); // EDIT - insert or remove ".addTo(map)" before last semicolon to display by default
   //地理院地図の標準地図タイル
   var gsi =L.tileLayer('https://cyberjapandata.gsi.go.jp/xyz/std/{z}/{x}/{y}.png', 
        {attribution: "<a href='https://maps.gsi.go.jp/development/ichiran.html' target='_blank'>地理院タイル</a>"});
  //地理院地図の淡色地図タイル
  var gsipale = L.tileLayer('http://cyberjapandata.gsi.go.jp/xyz/pale/{z}/{x}/{y}.png',
    {attribution: "<a href='http://portal.cyberjapan.jp/help/termsofuse.html' target='_blank'>地理院タイル</a>"});
  //地理院地図航空写真のタイル  
  var gsikouku =L.tileLayer('https://cyberjapandata.gsi.go.jp/xyz/seamlessphoto/{z}/{x}/{y}.jpg');    
  // see more basemap options at https://leaflet-extras.github.io/leaflet-providers/preview/
  var baseMaps = {
        "オープンストリートマップ"  : osm,
        "地理院地図": gsi,
        "GoogleMap": gmap,
        "空中写真": gsikouku,
      };
  
//   //現在地のアイコン  
//   var marker;
//       map.on("locationfound", function(location) {
//     if (!marker){
//         var pulsingIcon = L.icon.pulse({
//             iconSize:[20,20],color:'#99ffcc',fillColor:'#99ffcc',heartbeat: 2
//         });
//         L.marker(location.latlng, {icon:pulsingIcon}).addTo(map);
//   }
//   });

// map.on('locationerror', function(e) {
//     alert('現在地が取得できませんでした。');
// });
// map.locate({
//     watch: false,
//     locate: true,
//     setView: true,
//     enableHighAccuracy: true
// });  


  var MountainIcon = L.icon({
    iconUrl:'./img/mountain.png',
    iconSize:[50,50],
    iconAnchor:[26,50],
    popupAnchor:[0,-50]
	});
  // Read markers data from data.csv
  $.get('./csv/hyakumeizan.csv', function(csvString) {
    // Use PapaParse to convert string to array of objects
    var data = Papa.parse(csvString, {header: true, dynamicTyping: false}).data;
    // For each row in data, create a marker and add it to the map
    // For each row, columns `Latitude`, `Longitude`, and `Title` are required
    for (var i in data) {
      var row = data[i];
      var marker = L.marker([row.緯度, row.経度],{icon: MountainIcon},{
        opacity: 1}).bindPopup('<div class="popup-image_wrapper"><img class="popup-image" src='+'"'+row.画像+'"'+'></div>'+"<br>"+'<span class="m-name_text">'+row.name+'</span>'+'<br>'+'<span class="m-content_text">'+row.エリア+'</span>'+'<br>'+'<span class="m-content_text">'+row.height+'</span>'+'<br>'+'<a href='+'"'+row.googlemaplink+'"'+'target="_blank">'+'<img class="g_map_logo" src="./img/g_map_logo.jpg" alt="Googlemap">'+'</a>'+'<br>'+'<a href='+'"'+row.YAMAP+'"'+'target="_blank">'+'<img class="yamap-logo" src="./img/yamap-logo.png" alt="YAMAP">'+'</a>');
      marker.addTo(map);
    }
  });
  //'<br>'+row.住所

map.attributionControl.setPrefix(
  '<span style="color:#3a5b52;">NatureMap'
);
L.control.layers(baseMaps).addTo(map);
      gsi.addTo(map); 
</script>
</body>
</html>
