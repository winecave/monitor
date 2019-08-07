<!DOCTYPE html>
<html>
  <head>
    <title>cellar monitor</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <!-- Bootstrap Scripts -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

    <!-- HighCharts Scripts -->
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/series-label.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>

    <!-- iQuery Datepicker -->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1/i18n/jquery.ui.datepicker-ja.min.js"></script>
    <script>
      window.onload = function() {
        $(function() {
          $("#loading-daily").fadeOut(10);
          $("#loading-weekly").fadeOut(10);
          $("#loading-monthly").fadeOut(10);
        });
      }
      $(function() {
        $("#datepicker-daily").datepicker({dateFormat: "yy-mm-dd"});
        $("#datepicker-weekly").datepicker({dateFormat: "yy-mm-dd"});
        $("#datepicker-monthly").datepicker({dateFormat: "yy-mm"});
      });
    </script>
    <style>
      .ml15 {margin-left: 15px;}
      .loading {
        float: left;
      }
    </style>
  </head>
  <body>
    <!---------- Daily monitor graph ---------->
    <div class="p-2 mb-2 bg-light text-white"><a href="./arch.html">Architecture</a></div>
    <div class="p-3 mb-2 bg-secondary text-white">Daily monitor</div>
    <div class="ml15" class="loading">Date: 
      <input type="text" id="datepicker-daily">
      <input type="button" id="reload-daily" value="reload">
      <img id="loading-daily" src="./load.gif"/>
    </div>
    <hr/>
    <div id="container-daily-temperature" style="min-width: 200px; height: 220px; margin: 0 auto"></div>
    <div id="highcharts-daily-temperature"></div>
    <hr/>
    <div id="container-daily-humidity" style="min-width: 200px; height: 220px; margin: 0 auto"></div>
    <div id="highcharts-daily-humidity"></div>
    <hr/>
    <div id="container-daily-pressure" style="min-width: 200px; height: 220px; margin: 0 auto"></div>
    <div id="highcharts-daily-pressure"></div>

    <!---------- Weekly monitor graph ---------->
    <div class="p-3 mb-2 bg-secondary text-white">Weekly monitor</div>
    <div class="ml15" class="loading">Date: 
      <input type="text" id="datepicker-weekly">
      <input type="button" id="reload-weekly" value="reload">
      <img id="loading-weekly" src="./load.gif"/>
    </div>
    <hr/>
    <div id="container-weekly-temperature" style="min-width: 200px; height: 220px; margin: 0 auto"></div>
    <div id="highcharts-weekly-temperature"></div>
    <hr/>
    <div id="container-weekly-humidity" style="min-width: 200px; height: 220px; margin: 0 auto"></div>
    <div id="highcharts-weekly-humidity"></div>
    <hr/>
    <div id="container-weekly-pressure" style="min-width: 200px; height: 220px; margin: 0 auto"></div>
    <div id="highcharts-weekly-pressure"></div>

    <!---------- Monthly monitor graph ---------->
    <div class="p-3 mb-2 bg-secondary text-white">Monthly monitor</div>
    <div class="ml15" class="loading">Date:
      <input type="text" id="datepicker-monthly">
      <input type="button" id="reload-monthly" value="reload">
      <img id="loading-monthly" src="./load.gif"/>
    </div>
    <hr/>
    <div id="container-monthly-temperature" style="min-width: 200px; height: 220px; margin: 0 auto"></div>
    <div id="highcharts-monthly-temperature"></div>
    <hr/>
    <div id="container-monthly-humidity" style="min-width: 200px; height: 220px; margin: 0 auto"></div>
    <div id="highcharts-monthly-humidity"></div>
    <hr/>
    <div id="container-monthly-pressure" style="min-width: 200px; height: 220px; margin: 0 auto"></div>
    <div id="highcharts-monthly-pressure"></div>

    <script>
        $(function(){
            // Ajax button click
            $('#reload-daily').on('click', function() {
                $("#loading-daily").fadeIn();
                // temperature
                $.ajax({
                    url:'./dailychart.php',
                    type:'POST',
                    data:{
                        'date_from': $('#datepicker-daily').val(),
                        'type': 'T',
                        'containerid': 'container-daily-temperature',
                        'target': 'temperature'
                    }
                })
                // Ajaxリクエストが成功した時発動
                .done( (data) => {
                    $('#highcharts-daily-temperature').html(data);
                })
                // Ajaxリクエストが失敗した時発動
                .fail( (data) => {
                    $('#container-daily-temperature').html('fail to retreve data!');
                });
                // humidity
                $.ajax({
                    url:'./dailychart.php',
                    type:'POST',
                    data:{
                        'date_from': $('#datepicker-daily').val(),
                        'type': 'H',
                        'containerid': 'container-daily-humidity',
                        'target': 'humidity'
                    }
                })
                // Ajaxリクエストが成功した時発動
                .done( (data) => {
                    $('#highcharts-daily-humidity').html(data);
                })
                // Ajaxリクエストが失敗した時発動
                .fail( (data) => {
                    $('#container-daily-humidity').html('fail to retreve data!');
                });
                // pressure
                $.ajax({
                    url:'./dailychart.php',
                    type:'POST',
                    data:{
                        'date_from': $('#datepicker-daily').val(),
                        'type': 'P',
                        'containerid': 'container-daily-pressure',
                        'target': 'pressure'
                    }
                })
                // Ajaxリクエストが成功した時発動
                .done( (data) => {
                    $('#highcharts-daily-pressure').html(data);
                })
                // Ajaxリクエストが失敗した時発動
                .fail( (data) => {
                    $('#container-daily-humidity').html('fail to retreve data!');
                });
                $("#loading-daily").fadeOut(2000);
            });
            // Ajax weekly reload button click
            $('#reload-weekly').on('click', function() {
                $("#loading-weekly").fadeIn();
                // temperature
                $.ajax({
                    url:'./weeklychart.php',
                    type:'POST',
                    data:{
                        'date_from': $('#datepicker-weekly').val(),
                        'type': 'T',
                        'containerid': 'container-weekly-temperature',
                        'target': 'temperature'
                    }
                })
                // Ajaxリクエストが成功した時発動
                .done( (data) => {
                    $('#highcharts-weekly-temperature').html(data);
                })
                // Ajaxリクエストが失敗した時発動
                .fail( (data) => {
                    $('#container-weekly-temperature').html('fail to retreve data!');
                });
                // humidity
                $.ajax({
                    url:'./weeklychart.php',
                    type:'POST',
                    data:{
                        'date_from': $('#datepicker-weekly').val(),
                        'type': 'H',
                        'containerid': 'container-weekly-humidity',
                        'target': 'humidity'
                    }
                })
                // Ajaxリクエストが成功した時発動
                .done( (data) => {
                    $('#highcharts-weekly-humidity').html(data);
                })
                // Ajaxリクエストが失敗した時発動
                .fail( (data) => {
                    $('#container-weekly-humidity').html('fail to retreve data!');
                });
                // pressure
                $.ajax({
                    url:'./weeklychart.php',
                    type:'POST',
                    data:{
                        'date_from': $('#datepicker-weekly').val(),
                        'type': 'P',
                        'containerid': 'container-weekly-pressure',
                        'target': 'pressure'
                    }
                })
                // Ajaxリクエストが成功した時発動
                .done( (data) => {
                    $('#highcharts-weekly-pressure').html(data);
                })
                // Ajaxリクエストが失敗した時発動
                .fail( (data) => {
                    $('#container-weekly-humidity').html('fail to retreve data!');
                });
                $("#loading-weekly").fadeOut(2000);
            });
            // Ajax monthly reload button click
            $('#reload-monthly').on('click', function() {
                $("#loading-monthly").fadeIn();
                // temperature
                $.ajax({
                    url:'./monthlychart.php',
                    type:'POST',
                    data:{
                        'date_from': $('#datepicker-monthly').val(),
                        'type': 'T',
                        'containerid': 'container-monthly-temperature',
                        'target': 'temperature'
                    }
                })
                // Ajaxリクエストが成功した時発動
                .done( (data) => {
                    $('#highcharts-monthly-temperature').html(data);
                })
                // Ajaxリクエストが失敗した時発動
                .fail( (data) => {
                    $('#container-monthly-temperature').html('fail to retreve data!');
                });
                // humidity
                $.ajax({
                    url:'./monthlychart.php',
                    type:'POST',
                    data:{
                        'date_from': $('#datepicker-monthly').val(),
                        'type': 'H',
                        'containerid': 'container-monthly-humidity',
                        'target': 'humidity'
                    }
                })
                // Ajaxリクエストが成功した時発動
                .done( (data) => {
                    $('#highcharts-monthly-humidity').html(data);
                })
                // Ajaxリクエストが失敗した時発動
                .fail( (data) => {
                    $('#container-monthly-humidity').html('fail to retreve data!');
                });
                // pressure
                $.ajax({
                    url:'./monthlychart.php',
                    type:'POST',
                    data:{
                        'date_from': $('#datepicker-monthly').val(),
                        'type': 'P',
                        'containerid': 'container-monthly-pressure',
                        'target': 'pressure'
                    }
                })
                // Ajaxリクエストが成功した時発動
                .done( (data) => {
                    $('#highcharts-monthly-pressure').html(data);
                })
                // Ajaxリクエストが失敗した時発動
                .fail( (data) => {
                    $('#container-monthly-humidity').html('fail to retreve data!');
                });
                $("#loading-monthly").fadeOut(4500);
            });
        });
    </script>
  </body>
</html>
