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
    <div class="header">

    </div>
    <?php
        require_once( "sparqllib.php" );
        
        $data = sparql_get( 
            "http://localhost:3030/covin/sparql",
            "
            PREFIX p: <http://covin.com/ns/provinsi#>
            PREFIX d: <http://covin.com/ns/data#>
            
            SELECT ?Provinsi ?Longitude ?Latitude
            WHERE
            { 
                ?s  d:namaProvinsi ?Provinsi ;
                    d:longitude ?Longitude;
                    d:latitude ?Latitude;
            }
        " );
        if( !isset($data) )
        {
            print "<p>Error: ".sparql_errno().": ".sparql_error()."</p>";
        }
    ?>
    <div class="content">
        <div class="choice">
            <a href="/covin" class="active">Indonesia</a>
        </div>
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
        <div id = "mapid" style = "width:1200px; height:580px; margin: 0 auto;"></div>
        <script>
            // Assign JSON object from PHP to Javascript
            var json = <?php echo json_encode($data, JSON_PRETTY_PRINT); ?>;

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
                var provinsi = json[i].Provinsi;
                var longitude = json[i].Longitude;
                var latitude = json[i].Latitude;
                var marker = L.marker([latitude, longitude]);

                var popup = L.popup();
                marker.bindPopup(provinsi + '<br><a href="http://www.google.com/search?q=' + provinsi + '">Visit Province</a>').openPopup();
                marker.addTo(map);
                
            }
        </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
</body>
</html>