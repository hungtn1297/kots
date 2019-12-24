@extends('admin/master')
@section('content')
@section('title')
    Thống kê
@endsection
        <div id="page-wrapper">
                <style>
                #chartdiv {
                    width: 100%;
                    height: 500px;
                }

                #chartdiv2{
                    width: 100%;
                    height: 500px;
                }
                
                </style>
                
                <!-- Resources -->
                <script src="https://www.amcharts.com/lib/4/core.js"></script>
                <script src="https://www.amcharts.com/lib/4/charts.js"></script>
                <script src="https://www.amcharts.com/lib/4/themes/animated.js"></script>
                
                <!-- Chart code -->
                <!-- Chart code -->
                <script>
                    var totalCaseArr = {!! json_encode($totalCase) !!};
                    var successCaseArr = {!! json_encode($successCase) !!};
                    var failCaseArr = {!! json_encode($failCase) !!};
                    var barData = {!! json_encode($barData) !!};
                    am4core.ready(function() {
                    
                    // Themes begin
                    am4core.useTheme(am4themes_animated);
                    // Themes end
                    
                    // Create chart instance
                    var chart = am4core.create("chartdiv", am4charts.XYChart);
                    
                    // Increase contrast by taking evey second color
                    chart.colors.step = 2;
                    
                    // Add data
                    chart.data = generateChartData();
                    
                    // Create axes
                    var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
                    dateAxis.renderer.minGridDistance = 50;
                    
                    // Create series
                    function createAxisAndSeries(field, name, opposite, bullet) {
                    var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
                    
                    var series = chart.series.push(new am4charts.LineSeries());
                    series.dataFields.valueY = field;
                    series.dataFields.dateX = "date";
                    series.strokeWidth = 2;
                    series.yAxis = valueAxis;
                    series.name = name;
                    series.tooltipText = "{name}: [bold]{valueY}[/]";
                    series.tensionX = 0.8;
                    
                    var interfaceColors = new am4core.InterfaceColorSet();
                    
                    switch(bullet) {
                        case "triangle":
                        var bullet = series.bullets.push(new am4charts.Bullet());
                        bullet.width = 12;
                        bullet.height = 12;
                        bullet.horizontalCenter = "middle";
                        bullet.verticalCenter = "middle";
                        
                        var triangle = bullet.createChild(am4core.Triangle);
                        triangle.stroke = interfaceColors.getFor("background");
                        triangle.strokeWidth = 2;
                        triangle.direction = "top";
                        triangle.width = 12;
                        triangle.height = 12;
                        break;
                        case "rectangle":
                        var bullet = series.bullets.push(new am4charts.Bullet());
                        bullet.width = 10;
                        bullet.height = 10;
                        bullet.horizontalCenter = "middle";
                        bullet.verticalCenter = "middle";
                        
                        var rectangle = bullet.createChild(am4core.Rectangle);
                        rectangle.stroke = interfaceColors.getFor("background");
                        rectangle.strokeWidth = 2;
                        rectangle.width = 10;
                        rectangle.height = 10;
                        break;
                        default:
                        var bullet = series.bullets.push(new am4charts.CircleBullet());
                        bullet.circle.stroke = interfaceColors.getFor("background");
                        bullet.circle.strokeWidth = 2;
                        break;
                    }
                    
                    valueAxis.renderer.line.strokeOpacity = 1;
                    valueAxis.renderer.line.strokeWidth = 2;
                    valueAxis.renderer.line.stroke = series.stroke;
                    valueAxis.renderer.labels.template.fill = series.stroke;
                    valueAxis.renderer.opposite = opposite;
                    valueAxis.renderer.grid.template.disabled = true;
                    }
                    
                    createAxisAndSeries("totalCase", "Tất cả sự cố", false, "circle");
                    // createAxisAndSeries("successCase", "Sự cố thành công", true, "triangle");
                    // createAxisAndSeries("failCase", "Sự cố thất bại", true, "rectangle");
                    
                    // Add legend
                    chart.legend = new am4charts.Legend();
                    
                    // Add cursor
                    chart.cursor = new am4charts.XYCursor();
                    
                    // generate some random data, quite different range
                    function generateChartData() {
                    var chartData = [];
                    var firstDate = new Date();
                    firstDate.setDate(firstDate.getDate() - 100);
                    firstDate.setHours(0, 0, 0, 0);
                    
                    var totalCase = 0;
                    var successCase = 0;
                    var failCase = 0;
                    
                    for (var i = 1; i <= 12; i++) {
                        // we create date objects here. In your data, you can have date strings
                        // and then set format of your dates using chart.dataDateFormat property,
                        // however when possible, use date objects, as this will speed up chart rendering.
                        var newDate = new Date(firstDate);
                        newDate.setMonth(i);
                    
                        totalCase = totalCaseArr[i];
                        successCase = successCaseArr[i];
                        failCase =  failCaseArr[i];

                        chartData.push({
                        date: newDate,
                        totalCase: totalCase,
                        successCase: successCase,
                        failCase: failCase
                        });
                    }
                    return chartData;
                    }

                    var chart2 = am4core.create("chartdiv2", am4charts.XYChart);

                    // Add data
                    chart2.data = barData;
                    // Create axes
                    var categoryAxis = chart2.yAxes.push(new am4charts.CategoryAxis());
                    categoryAxis.dataFields.category = "district";
                    categoryAxis.numberFormatter.numberFormat = "#";
                    categoryAxis.renderer.inversed = true;
                    categoryAxis.renderer.grid.template.location = 0;
                    categoryAxis.renderer.cellStartLocation = 0.1;
                    categoryAxis.renderer.cellEndLocation = 0.9;

                    var  valueAxis = chart2.xAxes.push(new am4charts.ValueAxis()); 
                    valueAxis.renderer.opposite = true;

                    // Create series
                    function createSeries(field, name) {
                    var series = chart2.series.push(new am4charts.ColumnSeries());
                    series.dataFields.valueX = field;
                    series.dataFields.categoryY = "district";
                    series.name = name;
                    series.columns.template.tooltipText = "{name}: [bold]{valueX}[/]";
                    series.columns.template.height = am4core.percent(100);
                    series.sequencedInterpolation = true;

                    var valueLabel = series.bullets.push(new am4charts.LabelBullet());
                    valueLabel.label.text = "{valueX}";
                    valueLabel.label.horizontalCenter = "left";
                    valueLabel.label.dx = 10;
                    valueLabel.label.hideOversized = false;
                    valueLabel.label.truncate = false;

                    var categoryLabel = series.bullets.push(new am4charts.LabelBullet());
                    categoryLabel.label.text = "{name}";
                    categoryLabel.label.horizontalCenter = "right";
                    categoryLabel.label.dx = -10;
                    categoryLabel.label.fill = am4core.color("#fff");
                    categoryLabel.label.hideOversized = false;
                    categoryLabel.label.truncate = false;
                    }

                    createSeries("all", "Tất cả");
                    createSeries("success", "Thành công");
                    createSeries("fail", "Thất bại");
                    
                    }); // end am4core.ready()
                    </script>
                
                <!-- HTML -->
                <div id="chartdiv"></div>
                <br>
                <p style="text-align:center; font-size: x-large">Biểu đồ thống kê số lượng sự cố trong 1 năm</p>
                <hr>
                <div style="text-align: right;">
                    <form action="{{url('admin/analytic')}}" method="get">
                        <b>Từ ngày</b>: <input  type="date" name="startDate" id="startDate" style="margin-right: 3%;">
                        <b>Đến ngày</b>: <input  type="date" name="endDate" id="endDate" style="margin-right: 3%;">
                        <input type="submit" value="Xem" class="btn btn-info" style="margin-right: 3%;">
                    </form>
                </div>
                <div id="chartdiv2"></div>
                <br>
                <p style="text-align:center; font-size: x-large">Các quận xảy ra nhiều sự cố nhất từ ngày <b id="startDateText"></b> đến ngày <b id="endDateText"></b> </p>
                <!-- /.container-fluid -->
            </div>  
            <!-- /#page-wrapper -->
    <script>

        startDateVar = {!! json_encode($startDate) !!};
        endDateVar = {!! json_encode($endDate) !!};

        console.log(startDateVar);
        console.log(endDateVar);
        if(startDateVar != 0 && endDateVar != 0){
            var startDate = new Date(startDateVar);
            var endDate = new Date(endDateVar);
        }else{
            var endDate = new Date();
            var startDate = new Date(endDate.getFullYear(), endDate.getMonth(), endDate.getDate() - 7);
        }
        console.log(startDate);
        console.log(endDate);
        document.getElementById('startDate').valueAsDate = startDate;
        document.getElementById('endDate').valueAsDate = endDate;
        document.getElementById('startDateText').innerText = changeFormat(document.getElementById('startDate').value);
        document.getElementById('endDateText').innerText = changeFormat(document.getElementById('endDate').value);

        function changeFormat(date){
            var date = date.split('-');
            return date[2] + '/' + date[1] + '/' + date[0];
        }
    </script>
@stop