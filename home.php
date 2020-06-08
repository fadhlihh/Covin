<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COVIN - Home</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css"
    integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ=="
    crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js"
    integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew=="
    crossorigin=""></script>
</head>
<body>
    <!-- <div class="header">

    </div> -->
    <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
        <a href="home.php">COVIN</a>
    </nav>
    <?php
        require_once( "sparqllib.php" );

        $data = sparql_get(
            "https://qrary-fuseki-service.herokuapp.com/covin/sparql",
            "
            PREFIX p: <http://covin.com/ns/provinsi#>
            PREFIX d: <http://covin.com/ns/data#>

            SELECT ?Provinsi ?Longitude ?Latitude ?Positif ?Sembuh ?Meninggal
            WHERE
            {
                ?s  d:namaProvinsi ?Provinsi ;
                    d:longitude ?Longitude;
                    d:latitude ?Latitude;
                    d:kasusPositif ?Positif;
                    d:kasusSembuh ?Sembuh;
                    d:kasusMeninggal ?Meninggal;
            }
        " );
        if( !isset($data) )
        {
            print "<p>Error: ".sparql_errno().": ".sparql_error()."</p>";
        }
    ?>
    <div class="content">
        <!--
            CREATE PAGE IN TABLE FORM
            <table class="table table-bordered table-hover">
            <thead class="thead-light">
            <?php
                print "<tr>";
                foreach( $data->fields() as $field )
                {
                    print "<th>$field</th>";
                }
                print "</tr>";
                print "</thead>";

                print "<tbody>";
                foreach( $data as $row )
                {
                    print "<tr>";
                    foreach( $data->fields() as $field )
                    {
                        print "<td>$row[$field]</td>";
                    }
                    print "</tr>";
                }
            print "</tbody>";
            ?>
        </table>-->

        <!-- CREATE PAGE IN MAP -->
        <div id = "mapid" style = "width:100%; height:580px;"></div>
        <script>
            // Assign JSON object from PHP to Javascript globally
            localStorage.setItem("json", JSON.stringify(<?php echo json_encode($data, JSON_PRETTY_PRINT); ?>));

            // Create map
            var map = L.map('mapid').setView([-1.609972, 118.607254], 5);
            L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
                attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
                maxZoom: 10,
                id: 'mapbox/streets-v11',
                tileSize: 512,
                zoomOffset: -1,
                accessToken: 'pk.eyJ1Ijoic2t5ZGlhIiwiYSI6ImNrYXpjaWo3djA3b24ydXI4NWw4bnZ4ZXEifQ.iuJaVfxn1-IwRpSgXxpmpQ'
            }).addTo(map);

            // Create marker for every province
            for(var i = 0 ; i < 34 ; i++){
                var provinsi = JSON.parse(localStorage.getItem("json"))[i].Provinsi;
                var longitude = JSON.parse(localStorage.getItem("json"))[i].Longitude;
                var latitude = JSON.parse(localStorage.getItem("json"))[i].Latitude;
                var marker = L.marker([latitude, longitude]);
                marker.bindPopup(provinsi + '<br><a href="info.php?index=' + i + '">Details</a>' + '<br><a href="http://www.google.com/search?q=' + provinsi + '">Search at Google</a>').openPopup();
                marker.addTo(map);
            }
        </script>
    </div>
    <div class="recommend">
        <h6>Provinsi di sekitar anda</h6>
        <br>
        <a href="info.php?index=32" id="firstProvince">DKI Jakarta</a>
        <a href="info.php?index=20" id="secondProvince">Lampung</a>
        <a href="info.php?index=27" id="thirdProvince">Sumatera Selatan</a>
        <br>
    </div>
    <footer>&copy; COVIN Covid Information</footer>
    <script>

          // Get current province's coordinate
          window.navigator.geolocation.getCurrentPosition(function(position) {
            var  lat = position.coords.latitude;
            var  lon = position.coords.longitude;
            // Create best province variable
            var bestProvinceIndex = [100,100,100];
            var bestProvinceDistance = [100,100,100];
            // Find the best province
            for(var i = 0; i<34; i++){
                    var currLat = JSON.parse(localStorage.getItem("json"))[i].Latitude;
                    var currLon = JSON.parse(localStorage.getItem("json"))[i].Longitude;
                    var distance = Math.sqrt(Math.pow(lat-currLat,2) + Math.pow(lon-currLon,2));
                    for(var j=0; j<bestProvinceIndex.length; j++){
                        if(distance < bestProvinceDistance[j]){
                            bestProvinceIndex[j] = i;
                            bestProvinceDistance[j] = distance;
                            break;
                        }
                    }
            }
            // Update Best Province
            document.getElementById("firstProvince").innerHTML= JSON.parse(localStorage.getItem("json"))[bestProvinceIndex[0]].Provinsi;
            document.getElementById("firstProvince").href='info.php?index='+bestProvinceIndex[0];
            document.getElementById("secondProvince").innerHTML= JSON.parse(localStorage.getItem("json"))[bestProvinceIndex[1]].Provinsi;
            document.getElementById("secondProvince").href='info.php?index='+bestProvinceIndex[1];
            document.getElementById("thirdProvince").innerHTML= JSON.parse(localStorage.getItem("json"))[bestProvinceIndex[2]].Provinsi;
            document.getElementById("thirdProvince").href='info.php?index='+bestProvinceIndex[2];
        },function(){
          alert("Geolocation is not available");
          var  lat = -6.200000;
          var  lon = 106.816666;
          // Create best province variable
          var bestProvinceIndex = [100,100,100];
          var bestProvinceDistance = [100,100,100];
          // Find the best province
          for(var i = 0; i<34; i++){
                  var currLat = JSON.parse(localStorage.getItem("json"))[i].Latitude;
                  var currLon = JSON.parse(localStorage.getItem("json"))[i].Longitude;
                  var distance = Math.sqrt(Math.pow(lat-currLat,2) + Math.pow(lon-currLon,2));
                  for(var j=0; j<bestProvinceIndex.length; j++){
                      if(distance < bestProvinceDistance[j]){
                          bestProvinceIndex[j] = i;
                          bestProvinceDistance[j] = distance;
                          break;
                      }
                  }
          }
          // Update Best Province
          document.getElementById("firstProvince").innerHTML= JSON.parse(localStorage.getItem("json"))[bestProvinceIndex[0]].Provinsi;
          document.getElementById("firstProvince").href='info.php?index='+bestProvinceIndex[0];
          document.getElementById("secondProvince").innerHTML= JSON.parse(localStorage.getItem("json"))[bestProvinceIndex[1]].Provinsi;
          document.getElementById("secondProvince").href='info.php?index='+bestProvinceIndex[1];
          document.getElementById("thirdProvince").innerHTML= JSON.parse(localStorage.getItem("json"))[bestProvinceIndex[2]].Provinsi;
          document.getElementById("thirdProvince").href='info.php?index='+bestProvinceIndex[2];
        })
      </script>
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
</body>
</html>
