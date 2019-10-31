<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Danh sách sự cố</title>
</head>
<body>
    @include('admin/header')
    <div id="page-wrapper">
            <div class="container-fluid">
                <div id="googleMap" style="width:100%;height:450px;"></div>

                <script>
                    // var client = new google.loader.ClientLocation();
                    // var lat = client.latitude;
                    // var long = client.longitude;   
                    // alert(lat);      
                    function myMap() {     
                        var mapProp= {
                        center:new google.maps.LatLng(51.508742,-0.120850),
                        zoom:5,
                        };
                        var map = new google.maps.Map(document.getElementById("googleMap"),mapProp);
                        }
                </script>
            </div>
            Latitude: <script type="text/javascript">document.write(google.loader.ClientLocation.latitude);</script><br />
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->

        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDRJl0JFqHhM8jQ24VrJnzJE8HarKJ1qF0&callback=myMap"></script>
    @include('admin/footer')
</body>

</html>


