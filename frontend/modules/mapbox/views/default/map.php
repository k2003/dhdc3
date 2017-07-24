<?php

use yii\helpers\Url;

$route_home = Url::to(['/mapbox/default/point-home']);
$tambon = $this->render('./tambon_plk_utf8.geojson');


?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset=utf-8 />
        <title>DHDC 4.0 GIS</title>
        <meta name='viewport' content='initial-scale=1,maximum-scale=1,user-scalable=no' />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src='https://api.mapbox.com/mapbox.js/v3.1.1/mapbox.js'></script>
        <link href='https://api.mapbox.com/mapbox.js/v3.1.1/mapbox.css' rel='stylesheet' />
        <style>
            body { margin:0; padding:0; }
            #map { position:absolute; top:0; bottom:0; width:100%; }
        </style>
    </head>
    <body>
        <div id='map'></div>
        <script>
            L.mapbox.accessToken = 'pk.eyJ1IjoidGVobm5uIiwiYSI6ImNpZzF4bHV4NDE0dTZ1M200YWxweHR0ZzcifQ.lpRRelYpT0ucv1NN08KUWQ';
            var map = L.mapbox.map('map').setView([16, 100], 6);
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
            var contry = L.mapbox.tileLayer('examples.map-zmy97flj');



            var baseLayers = {
                "OSM ภูมิประเทศ": osm_street.addTo(map),
                "OSM ถนน": L.tileLayer('//{s}.tile.osm.org/{z}/{x}/{y}.png'),
                "OSM ดาวเทียม": L.mapbox.tileLayer('mapbox.satellite'),
                "Google Hybrid": googleHybrid,
                "Google Street": googleStreet,
                "Google ภูมิประเทศ": googleTerrain,
                "contry": contry

            }; // base map 



            var _group1 = L.featureGroup();
            var person = L.mapbox.featureLayer().setGeoJSON(<?= $person_point ?>).addTo(_group1);
            var home = L.mapbox.featureLayer()
                    .loadURL('<?= $route_home ?>')
                    .on('ready', function () {
                        home.eachLayer(function (layer) {
                            //console.log(layer.feature.properties);
                            if (!layer.feature.properties.title && !layer.feature.properties.description) {
                                layer.bindPopup(layer.feature.properties.NAME);
                            }
                        });
                    }).addTo(_group1);
            var github = L.mapbox.featureLayer();
            var tambon = L.mapbox.featureLayer()
                    .setGeoJSON(<?= $tambon ?>)
                    .addTo(_group1);
            map.fitBounds(tambon.getBounds());
            tambon.eachLayer(function (layer) {
                //layer.bindTooltip(layer.feature.properties.TAM_NAMT);
                var fillColor = "#0000ff"
                if (layer.feature.properties.AMP_CODE <= '05') {
                    fillColor = "#00ff7f";
                } else if (layer.feature.properties.AMP_CODE >= '07') {
                    fillColor = "#ff4444";
                }
                layer.setStyle({
                    fillColor: fillColor,
                    weight: 2,
                    opacity: 1,
                    color: 'white',
                    dashArray: '3',
                    fillOpacity: 0.7,
                });
                layer.on('mouseover', function (e) {
                    var color = "#ffd700";
                    layer.setStyle({
                        fillColor: color,
                        weight: 5,
                        color: '#666',
                    });
                });
                layer.on('mouseout', function (e) {
                    layer.setStyle({
                        fillColor: fillColor,
                        weight: 2,
                        opacity: 1,
                        color: 'white',
                        dashArray: '3',
                        fillOpacity: 0.7,
                    });

                    layer.closePopup();
                });
                layer.on('click', function (e) {
                    map.fitBounds(layer.getBounds());
                    layer.bindPopup(layer.feature.properties.TAM_NAMT);
                    layer.openPopup();
                });
            });

            //wms

            var nexrad = L.tileLayer.wms("http://mesonet.agron.iastate.edu/cgi-bin/wms/nexrad/n0r.cgi", {
                layers: 'nexrad-n0r-900913',
                format: 'image/png',
                transparent: true,
                attribution: "Weather data © 2012 IEM Nexrad"
            });
            var precipitation = L.tileLayer.wms('http://nowcoast.noaa.gov/arcgis/services/nowcoast/analysis_meteohydro_sfc_qpe_time/MapServer/WmsServer', {
                format: 'image/png',
                transparent: true,
                layers: '5'
            });

            var longdo = L.tileLayer.wms('http://dt.gistda.or.th/wms/theos?version=1.3.0', {
                format: 'image/png',
                transparent: true,
                layers: '1',
                //srs:"EPSG:4326"

            });

            var traffic = L.tileLayer('https://1.traffic.maps.cit.api.here.com/maptile/2.1/flowtile/newest/normal.day/{z}/{x}/{y}/256/png8?app_id=DemoAppId01082013GAL&app_code=AJKnXv84fjrb0KIHawS0Tg').addTo(map);

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
            });

            var wms1 = L.tileLayer.wms("http://sedac.ciesin.columbia.edu/geoserver/wms", {
                format: 'image/png',
                transparent: true,
                layers: 'gpw-v3:gpw-v3-population-density_2000'
            });

            //wms

            var overlays = {
                "tambon": tambon,
                "person": person,
                "home": home,
                "github": github,
                "wms1": nexrad,
                "precipitation": precipitation,
                "longdo": longdo,
                "traffic": traffic,
                "ฝน": rain,
                "wms1":wms1


            };
            L.control.layers(baseLayers, overlays).addTo(map);
            $(function () {
                $.ajax({
                    headers: {
                        'Accept': 'application/vnd.github.v3.raw'
                    },
                    xhrFields: {
                        withCredentials: false
                    },
                    dataType: 'json',
                    url: 'https://api.github.com/repos/mapbox/mapbox.js/contents/test/manual/example.geojson',
                    success: function (geojson) {
                        // On success add fetched data to the map.
                        github.setGeoJSON(geojson);
                    }
                });
            });



        </script>
    </body>
</html>