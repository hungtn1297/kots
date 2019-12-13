<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Thống kê</title>
</head>
<body>
        @include('admin/header')
        <div id="page-wrapper">
                <style>
                #chartdiv {
                    width: 100%;
                    height: 500px;
                }
                
                </style>
                
                <!-- Resources -->
                <script src="https://www.amcharts.com/lib/4/core.js"></script>
                <script src="https://www.amcharts.com/lib/4/charts.js"></script>
                <script src="https://www.amcharts.com/lib/4/themes/animated.js"></script>
                
                <!-- Chart code -->
                <script>
                am4core.ready(function() {
                
                // Themes begin
                am4core.useTheme(am4themes_animated);
                // Themes end
                
                var chart = am4core.create("chartdiv", am4charts.XYChart);
                var caseData = {!! json_encode($data) !!};
                console.log(caseData);
                var data = [];
                var value = 50;
                for(let i = 1; i <= 12; i++){
                    let date = new Date();
                    // date.setHours(0,0,0,0);
                    date.setMonth(i);
                    value = caseData[i];
                    data.push({date:date, value: value});
                }
                
                chart.data = data;
                
                // Create axes
                var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
                dateAxis.renderer.minGridDistance = 60;
                
                var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
                
                // Create series
                var series = chart.series.push(new am4charts.LineSeries());
                series.dataFields.valueY = "value";
                series.dataFields.dateX = "date";
                series.tooltipText = "{value}"
                
                series.tooltip.pointerOrientation = "vertical";
                
                chart.cursor = new am4charts.XYCursor();
                chart.cursor.snapToSeries = series;
                chart.cursor.xAxis = dateAxis;
                
                //chart.scrollbarY = new am4core.Scrollbar();
                chart.scrollbarX = new am4core.Scrollbar();
                
                }); // end am4core.ready()
                </script>
                
                <!-- HTML -->
                <div id="chartdiv"></div>
                <br>
                <p style="text-align:center; font-size: x-large">Biểu đồ thống kê số lượng sự cố trong 1 năm</p>
                <!-- /.container-fluid -->
            </div>  
            <!-- /#page-wrapper -->
        @include('admin/footer')
    <!-- Styles -->
</body>
</html>