<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COVIN - Home</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body>
    <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
        <a href="home.php">COVIN</a>
    </nav>
    <div class="content">
        <h1 id="nama-provinsi">Nama Provinsi</h1>
        <br>
        <div class="card">
            <div class="card-header" style="background-color: #e6d59e;">Positif</div>
            <div class="card-body" style="background-color: #dbd1af;">
                <div id="card-positif">1000</div>
            </div>
        </div>
        <div class="card">
            <div class="card-header" style="background-color: #ace38d;">Sembuh</div>
            <div class="card-body" style="background-color: #c2e3af;">
                <div id="card-sembuh">1000</div>
            </div>
        </div>
        <div class="card">
            <div class="card-header" style="background-color: #ed9595;">Meninggal</div>
            <div class="card-body" style="background-color: #ebb5b5; ">
                <div id="card-meninggal">1000</div>
            </div>
        </div>
    </div>
    <div class="recommend">
        <h6>Provinsi lainnya</h6>
        <br>
        <a href="#" id="firstProvince">Provinsi 1</a>
        <a href="#" id="secondProvince">Provinsi 2</a>
        <a href="#" id="thirdProvince">Provinsi 3</a>
        <br>
    </div>
    <footer>&copy; COVIN Covid Information</footer>
    <script>
        // Get index parameter from URL
        var url_string = window.location.href;
        var url = new URL(url_string);
        var index = url.searchParams.get("index");

        // Create best province variable
        var bestProvinceIndex = [100,100,100];
        var bestProvinceDistance = [100,100,100];

        // Get current province's coordinate
        var lat = JSON.parse(localStorage.getItem("json"))[index].Latitude;
        var lon = JSON.parse(localStorage.getItem("json"))[index].Longitude;

        // Find the best province
        for(var i = 0; i<34; i++){
            if(i == index) continue;
            else{
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
        }

        // Update HTML elements
        document.getElementById("nama-provinsi").innerHTML = "Provinsi " + JSON.parse(localStorage.getItem("json"))[index].Provinsi;
        document.getElementById("card-positif").innerHTML = JSON.parse(localStorage.getItem("json"))[index].Positif;
        document.getElementById("card-sembuh").innerHTML = JSON.parse(localStorage.getItem("json"))[index].Sembuh;
        document.getElementById("card-meninggal").innerHTML = JSON.parse(localStorage.getItem("json"))[index].Meninggal;

        // Update Best Province
        document.getElementById("firstProvince").innerHTML= JSON.parse(localStorage.getItem("json"))[bestProvinceIndex[0]].Provinsi;
        document.getElementById("firstProvince").href='info.php?index='+bestProvinceIndex[0];
        document.getElementById("secondProvince").innerHTML= JSON.parse(localStorage.getItem("json"))[bestProvinceIndex[1]].Provinsi;
        document.getElementById("secondProvince").href='info.php?index='+bestProvinceIndex[1];
        document.getElementById("thirdProvince").innerHTML= JSON.parse(localStorage.getItem("json"))[bestProvinceIndex[2]].Provinsi;
        document.getElementById("thirdProvince").href='info.php?index='+bestProvinceIndex[2];
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
</body>
</html>
