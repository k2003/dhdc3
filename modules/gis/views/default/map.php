<?php

use yii\helpers\Url;
use yii\bootstrap\Modal;

$web = \Yii::getAlias('@web');
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset=utf-8 />
        <meta name='viewport' content='initial-scale=1,maximum-scale=1,user-scalable=no' />
        <title>DHDC 3.0 GIS</title>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

        <link href='//api.mapbox.com/mapbox.js/v3.1.1/mapbox.css' rel='stylesheet' />
        <script src='//api.mapbox.com/mapbox.js/v3.1.1/mapbox.js'></script>

        <script src='//api.mapbox.com/mapbox.js/plugins/leaflet-draw/v0.4.10/leaflet.draw.js'></script>
        <link href='//api.mapbox.com/mapbox.js/plugins/leaflet-draw/v0.4.10/leaflet.draw.css' rel='stylesheet' />

        <script src='//api.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v1.0.0/leaflet.markercluster.js'></script>
        <link href='//api.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v1.0.0/MarkerCluster.css' rel='stylesheet' />
        <link href='//api.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v1.0.0/MarkerCluster.Default.css' rel='stylesheet' />

        <script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-locatecontrol/v0.43.0/L.Control.Locate.min.js'></script>
        <link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-locatecontrol/v0.43.0/L.Control.Locate.mapbox.css' rel='stylesheet' />
        <!--[if lt IE 9]>
          <link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-locatecontrol/v0.43.0/L.Control.Locate.ie.css' rel='stylesheet' />
        <![endif]-->
        <link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-locatecontrol/v0.43.0/css/font-awesome.min.css' rel='stylesheet' />

        <script src="<?= $web ?>/js/Leaflet.Control.Custom.js"></script>        

        <style>
            body { margin:0; padding:0; }
            #map { position:absolute; top:0; bottom:0; width:100%; }
            .show-latlng{
                position:absolute;
                bottom:0;
                z-index: 10;

            }
            .leaflet-control-draw-measure {
                background-image: url(<?= $web ?>/images/measure-control.png);
            }
            .point-label {  white-space: nowrap;background:null;}
        </style>
    </head>
    <body>
        <script src='//api.mapbox.com/mapbox.js/plugins/leaflet-hash/v0.2.1/leaflet-hash.js'></script>
        <link rel="stylesheet" href="<?= $web ?>/lib/map/ruler/leaflet-ruler.css" />
        <script src="<?= $web ?>/lib/map/ruler/leaflet-ruler.js"></script>

        <!-- search-->
        <link rel="stylesheet" type="text/css" href="<?= $web ?>/lib/map/leaflet-search/dist/leaflet-search.min.css"/>
        <script src="<?= $web ?>/lib/map/leaflet-search/dist/leaflet-search.min.js"></script>

        <script src='https://npmcdn.com/@turf/turf/turf.min.js'></script>   
        <div id='map'>         
        </div>
        <div class="show-latlng">
            <input type="text" id="txt-latlng" style="width: 290px"/>
        </div>
        <script>
            L.mapbox.accessToken = 'pk.eyJ1IjoidGVobm5uIiwiYSI6ImNpZzF4bHV4NDE0dTZ1M200YWxweHR0ZzcifQ.lpRRelYpT0ucv1NN08KUWQ';
            var map = L.mapbox.map('map').setView([16, 100], 6);
            var hash = L.hash(map);
            L.control.locate().addTo(map);

            var clusterHome = new L.MarkerClusterGroup().addTo(map);
            //base map
            var googleHybrid = L.tileLayer('http://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}&hl=th', {
                maxZoom: 20,
                subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
            });
            var googleStreet = L.tileLayer('http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}&hl=th', {
                maxZoom: 20,
                subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
            });
            var googleSat = L.tileLayer('http://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}&hl=th', {
                maxZoom: 20,
                subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
            });
            var googleTerrain = L.tileLayer('http://{s}.google.com/vt/lyrs=p&x={x}&y={y}&z={z}&hl=th', {
                maxZoom: 20,
                subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
            });
            var osm_street = L.mapbox.tileLayer('mapbox.streets');




            var baseLayers = {
                "OSM ภูมิประเทศ": osm_street,
                "OSM ถนน": L.tileLayer('//{s}.tile.osm.org/{z}/{x}/{y}.png'),
                "OSM ดาวเทียม": L.mapbox.tileLayer('mapbox.satellite'),
                "Google Hybrid": googleHybrid,
                "Google Street": googleStreet.addTo(map),
                "Google ภูมิประเทศ": googleTerrain,
            }; // base map 

            //crosshair
            var crosshairIcon = L.icon({
                iconUrl: "<?=$web?>/images/crosshair.png",
                iconSize: [25, 25], // size of the icon
                //iconAnchor:   [10, 10], // point of the icon which will correspond to marker's location
            });
            crosshair = new L.marker(map.getCenter(), {icon: crosshairIcon, clickable: false});
            crosshair.addTo(map);




            var villGroup = L.featureGroup();
            var tambonGroup = L.featureGroup().addTo(map);
            var hospitalGroup = L.featureGroup().addTo(map);

            var tambon = L.mapbox.featureLayer()
                    .setGeoJSON(<?= $tambon_pol ?>);
            tambon.eachLayer(function (layer) {
                var json = layer.feature;
                var feature = L.mapbox.featureLayer(json);
                feature.bindTooltip(json.properties.title, {permanent: 'true'});
                feature.setStyle({weight:1,fillOpacity:0,dashArray:4});
                feature.addTo(tambonGroup);
            });

            map.fitBounds(tambon.getBounds());
<?php
$json_home_route = Url::to(['point-home']);
$json_vill_route = Url::to(['point-vill']);
$json_hosp_route = Url::to(['point-hosp']);
?>
            var home = L.mapbox.featureLayer().loadURL('<?= $json_home_route ?>');
            var labelHomeLayer = L.featureGroup().addTo(map);
            home.on('ready', function () {
                home.addTo(clusterHome);

                $('.btn-circle').click(function () {
                    var r = prompt("ระบุรัศมี (เมตร)", 100);
                    var circleRadius = L.circle(map.getCenter(), Number(r), {color: 'yellow', 'dashArray': 4, weight: 2}).addTo(map);
                    circleRadius.on('click', function (e) {
                        var layer = e.target;
                        //console.log(layer);
                        var latlng = layer.getLatLng();
                        var circleJson = turf.circle([latlng.lng, latlng.lat], Number(r) / 1000, 100, 'kilometers', {});

                        var circleCollection = turf.featureCollection([circleJson]);

                        //var resGeojson = turf.within(,circleCollection);

                        var homeGeojson = [];
                        home.eachLayer(function (layer) {
                            if (layer.feature.geometry.coordinates[1] != null & layer.feature.geometry.coordinates[0] != null) {
                                homeGeojson.push(layer.feature);
                            }
                        });
                        var homeCollection = turf.featureCollection(homeGeojson);

                        var resGeojson = turf.within(homeCollection, circleCollection);
                        var countHome = resGeojson.features.length;
                        var list = "";
                        //labelHomeLayer.remove();
                        resGeojson.features.forEach(function (data) {
                            list += "บ้านเลขที่ " + data.properties.title + "<br>";

                            var latLng = [data.geometry.coordinates[1], data.geometry.coordinates[0]];
                            var lbHtml = '<span style="background-color:#FFF8DC;">';
                            lbHtml += data.properties.title;
                            lbHtml += '<span>';
                            L.marker(latLng, {icon: L.divIcon({className: 'point-label', html: lbHtml})}).addTo(labelHomeLayer);

                        });
                        //alert("<b>พื้นที่นี้มี  <u>" + countHome + "</u> หลังคาเรือน</b>" + list)
                        $('#modal').modal('show').find('#modalContent').html("<h4>ทั้งหมด " + countHome + " หลัง</h4><br>" + list);

                    })
                });
            })

            var villages = L.mapbox.featureLayer().loadURL('<?= $json_vill_route ?>');
            villages.on('ready', function () {
                villages.eachLayer(function (layer) {
                    var latLng = [layer.feature.geometry.coordinates[1], layer.feature.geometry.coordinates[0]];
                    var tambon_code = layer.feature.properties.DOLACODE.substring(0, 6) * 1;
                    var marker_vill = L.marker(latLng, {
                        icon: L.mapbox.marker.icon({
                            'marker-symbol': 'circle-stroked',
                            'marker-color': tambon_code % 2 == 0 ? '#7CFC00' : '#87CEFA',
                            'marker-size': 'large'
                        }),
                    });
                    var title = "หมู่ที่ " + layer.feature.properties.VILL_NO;
                    title += " บ." + layer.feature.properties.MUBAN;
                    title += "<br>ต." + layer.feature.properties.TAMBOL;

                    var tips = "หมู่ที่ " + layer.feature.properties.VILL_NO;
                    tips += " บ." + layer.feature.properties.MUBAN;

                    //marker_vill.bindPopup(title);
                    marker_vill.bindTooltip(tips, {permanent: 'true'});
                    marker_vill.addTo(villGroup);
                });

            });

            var hospital = L.mapbox.featureLayer();
            hospital.loadURL('<?= $json_hosp_route ?>');
            hospital.on('ready', function (e) {
                var json = e.target.getGeoJSON();
                json.forEach(function (feature) {
                    var pointHosp = L.mapbox.featureLayer();
                    pointHosp.bindTooltip(feature.properties.title);
                    pointHosp.setGeoJSON(feature);
                    pointHosp.addTo(hospitalGroup);

                })
            });

            //wms

            //ฝน
            var base_url = 'http://rain.tvis.in.th/';
            var radars = '["NongKham","KKN"]';
            var latlng_topright = '["15.09352819610486,101.7458188486135","18.793550,105.026265"]';
            var latlng_bottomleft = '["12.38196058009694,98.97206140040996","14.116192,100.541459"]';
            var d = new Date();
            var time = d.getTime();
            //console.log(time);
            radars = JSON.parse(radars);
            latlng_topright = JSON.parse(latlng_topright);
            latlng_bottomleft = JSON.parse(latlng_bottomleft);
            var rain = L.layerGroup().addTo(map);
            $.each(radars, function (key, value) {
                var top_right = latlng_topright[key].split(",");
                var bottom_left = latlng_bottomleft[key].split(",");
                //console.log(base_url + "/output/" + value + ".png?" + time);
                var imageUrl = base_url + "/output/" + value + ".png?" + time,
                        imageBounds = [[top_right[0], top_right[1]], [bottom_left[0], bottom_left[1]]];
                L.imageOverlay(imageUrl, imageBounds).addTo(rain).setOpacity(0.95);
            });//จบฝน



            //จบ wms

            var overlays = {
                'โรงพยาบาล': hospitalGroup,
                'หลังคาเรือน': clusterHome,
                'ขอบเขตตำบล': tambonGroup,
                'หมู่บ้าน': villGroup,
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
            L.control.ruler({position: 'topleft'}).addTo(map);

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



            $('.btn-reload').click(function () {
                location.reload();
            });




            map.on('move', function (e) {
                crosshair.setLatLng(map.getCenter());

            });

            map.on('moveend', function (e) {
                var latlng = crosshair.getLatLng();
                $('#txt-latlng').val(latlng.lat + "," + latlng.lng)
            });
            $('#txt-latlng').val(map.getCenter().lat + "," + map.getCenter().lng)
            $('#txt-latlng').click(function (e) {
                $(this).select();
            });

            // search control

            var searchControl = new L.Control.Search({layer: clusterHome});
            map.addControl(searchControl);
            searchControl.on('search:locationfound', function (data) {
                //console.log(data)
                var latLngs = [data.latlng];
                var pointFoundBounds = L.latLngBounds(latLngs);
                map.fitBounds(pointFoundBounds);
                data.layer.openPopup();
            });
        </script>

        <?php
        Modal::begin([
            'header' => 'บ้านที่อยู่ในรัศมี',
            'size' => 'modal-md',
            'id' => 'modal',
        ]);
        echo "<div id='modalContent'>Loading...</div>";
        Modal::end();
        ?>
    </body>
</html>