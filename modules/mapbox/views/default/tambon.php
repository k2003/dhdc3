<?php ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset=utf-8 />
        <meta name='viewport' content='initial-scale=1,maximum-scale=1,user-scalable=no' />
        <title>DHDC 3.0 GIS</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">


        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

        <link href='https://api.mapbox.com/mapbox.js/v3.1.1/mapbox.css' rel='stylesheet' />
        <script src='https://api.mapbox.com/mapbox.js/v3.1.1/mapbox.js'></script>


        <script src="<?= \Yii::getAlias('@web') ?>/js/Leaflet.Control.Custom.js"></script>        

        <style>
            body { margin:0; padding:0; }
            #map { position:absolute; top:0; bottom:0; width:100%; }
            .show-latlng{
                position:absolute;
                bottom:0;
                z-index: 10;
               
            }   
        </style>
    </head>
    <body>
        <script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-hash/v0.2.1/leaflet-hash.js'></script>
        <div id='map'>         
        </div>
           <div class="show-latlng">
                <input type="text" id="txt-latlng" style="width: 290px"/>
            </div>
        <script>
            L.mapbox.accessToken = 'pk.eyJ1IjoidGVobm5uIiwiYSI6ImNpZzF4bHV4NDE0dTZ1M200YWxweHR0ZzcifQ.lpRRelYpT0ucv1NN08KUWQ';
            var map = L.mapbox.map('map').setView([16, 100], 6);
            var hash = L.hash(map);
            //base map
            var googleHybrid = L.tileLayer('http://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}', {
                maxZoom: 20,
                subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
            });
            var googleStreet = L.tileLayer('http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
                maxZoom: 20,
                subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
            });
            var googleSat = L.tileLayer('http://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
                maxZoom: 20,
                subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
            });
            var googleTerrain = L.tileLayer('http://{s}.google.com/vt/lyrs=p&x={x}&y={y}&z={z}', {
                maxZoom: 20,
                subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
            });
            var osm_street = L.mapbox.tileLayer('mapbox.streets');




            var baseLayers = {
                "OSM ภูมิประเทศ": osm_street.addTo(map),
                "OSM ถนน": L.tileLayer('//{s}.tile.osm.org/{z}/{x}/{y}.png'),
                "OSM ดาวเทียม": L.mapbox.tileLayer('mapbox.satellite'),
                "Google Hybrid": googleHybrid,
                "Google Street": googleStreet,
                "Google ภูมิประเทศ": googleTerrain,
            }; // base map 



            var _group1 = L.featureGroup().addTo(map);

            var tambon = L.mapbox.featureLayer()
                    .setGeoJSON(<?= $tambon_pol ?>)
                    .addTo(_group1);

            map.fitBounds(tambon.getBounds());
            //wms

            //ฝน
            var base_url = 'http://rain.tvis.in.th/';
            var radars = '["NongKham","KKN"]';
            var latlng_topright = '["15.09352819610486,101.7458188486135","18.793550,105.026265"]';
            var latlng_bottomleft = '["12.38196058009694,98.97206140040996","14.116192,100.541459"]';
            var d = new Date();
            var time = d.getTime();
            console.log(time);
            radars = JSON.parse(radars);
            latlng_topright = JSON.parse(latlng_topright);
            latlng_bottomleft = JSON.parse(latlng_bottomleft);
            var rain = L.layerGroup().addTo(map);
            $.each(radars, function (key, value) {
                var top_right = latlng_topright[key].split(",");
                var bottom_left = latlng_bottomleft[key].split(",");
                console.log(base_url + "/output/" + value + ".png?" + time);
                var imageUrl = base_url + "/output/" + value + ".png?" + time,
                        imageBounds = [[top_right[0], top_right[1]], [bottom_left[0], bottom_left[1]]];
                L.imageOverlay(imageUrl, imageBounds).addTo(rain).setOpacity(0.95);
            });//จบฝน



            //จบ wms

            var overlays = {
                'ขอบเขตตำบล': tambon,
                'เรดาห์น้ำฝน': rain,
            };
            L.control.layers(baseLayers, overlays).addTo(map);
            tambon.eachLayer(function (layer) {
                var originColor = layer.feature.properties.fill;
                layer.setStyle({
                    dashArray: 3,
                });
                layer.on('mouseover', function (e) {
                    layer.setStyle({
                        weight: 5,
                    });
                });
                layer.on('mouseout', function (e) {
                    layer.setStyle({
                        fillColor: originColor,
                        weight: 2
                    });

                    layer.closePopup();
                });
                layer.on('click', function (e) {
                    map.fitBounds(layer.getBounds());
                    layer.bindPopup(layer.feature.properties.TAM_NAMT);
                    layer.openPopup();
                });
            });

            L.control.custom({
                position: 'topleft',
                content: '<button type="button" class="btn btn-default btn-circle" title="รัศมี...">' +
                        '    <i class="glyphicon glyphicon-record"></i>' +
                        '</button>' +
                        '<button type="button" class="btn btn-default btn-reload" title="reload...">' +
                        '    <i class="glyphicon glyphicon-refresh"></i>' +
                        '</button>'
                ,
                classes: 'btn-group-vertical btn-group-sm',
                style:
                        {
                            margin: '10px',
                            padding: '0px 0 0 0',
                            cursor: 'pointer'
                        },
            }).addTo(map);

            $('.btn-circle').click(function () {
                var r = prompt("ระบุรัศมี (เมตร)", 100);
                L.circle(map.getCenter(), Number(r), {color: 'yellow', 'dashArray': 4, weight: 2}).addTo(map);
            });

            $('.btn-reload').click(function () {
                location.reload();
            });


            //crosshair
            var crosshairIcon = L.icon({
                iconUrl: "<?= \Yii::getAlias('@web') ?>/images/crosshair.png",
                iconSize: [25, 25], // size of the icon
                //iconAnchor:   [10, 10], // point of the icon which will correspond to marker's location
            });
            crosshair = new L.marker(map.getCenter(), {icon: crosshairIcon, clickable: false});
            crosshair.addTo(map);

// Move the crosshair to the center of the map when the user pans
            map.on('move', function (e) {
                crosshair.setLatLng(map.getCenter());

            });

            map.on('moveend', function (e) {
                var latlng = crosshair.getLatLng();
                $('#txt-latlng').val(latlng.lat+","+latlng.lng)
            });
            $('#txt-latlng').val(map.getCenter().lat+","+map.getCenter().lng)
            $('#txt-latlng').click(function(e){
                $(this).select();
            });
        </script>
    </body>
</html>