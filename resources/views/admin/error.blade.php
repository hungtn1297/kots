<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Lỗi</title>
</head>
<body>
    @include('admin/header')
    <div class="page-wrapper">
        <div class="container">
            <div class="row" style="text-align: center">
                <h3>{{$error}}</h3>
                <input type="button" value="Trở về" onclick="goBack()">
            </div>
        </div>
    </div>

    @include('admin/footer')
</body>
</html>