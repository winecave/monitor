<script>
<?php
printf("Highcharts.chart('%s', {\n", $_POST['containerid']);
?>
    credits: {
        enabled: false
    },
    chart: {
        type: 'spline',
        scrollablePlotArea: {
            minWidth: 600,
            scrollPositionX: 1
        }
    },
    title: {
<?php
    $date_from = $_POST['date_from'];
    $target = $_POST['target'];
    printf("    text: 'cellar %s on %s'", $target, $date_from);
?>
    },
    xAxis: {
        type: 'datetime',
        labels: {
            overflow: 'justify'
        }
    },
    yAxis: {
        plotLines: [{
            color: '#d8bfd8',
            width: 1,
<?php
    $type = $_POST['type'];
    if ($type === 'T') {
        echo "            value: 18";
    } elseif($type === 'H') {
        echo "            value: 70";
    }
?>
        }],
        title: {
<?php
    $type = $_POST['type'];
    if ($type === 'T') {
        echo "            text: 'temperature(℃)'\n";
        $tooltip = "℃";
    } elseif($type === 'H') {
        echo "            text: 'humidity(%)'\n";
        $tooltip = "%";
    } else {
        echo "            text: 'pressure(hPa)'\n";
        $tooltip = "hPa";
    }
?>
        },
<?php
    if ($type === 'T') {
        echo "max: 30,\n";
    } elseif($type === 'H') {
        echo "max: 100,\n";
    }
?>
        minorGridLineWidth: 0,
        gridLineWidth: 0,
        alternateGridColor: null
    },
    tooltip: {
<?php
    echo "        valueSuffix: '".$tooltip."'";
?>
    },
    plotOptions: {
        spline: {
            lineWidth: 4,
            states: {
                hover: {
                    lineWidth: 5
                }
            },
            marker: {
                enabled: false
            },
            pointInterval: 60000, // one hour
<?php
    require 'database.php';
    $obj = get_daily_data($type, $date_from." 00:00:00", $date_from." 23:59:59");
    printf("            pointStart: Date.UTC(%d, %d, %d, %d, %d, %d)\n",
           $obj['yyyy'],
           $obj['mm'],
           $obj['dd'],
           $obj['hh'],
           $obj['i'],
           $obj['s']);
?>
        }
    },
    series: [{
        label: {enabled: false},
        showInLegend: false,
<?php
    if ($type === 'T') {
        echo "        name: 'temperature',\n";
    } elseif ($type === 'H') {
        echo "        name: 'humidity',\n";
    }
?>
        data: [
<?php
    foreach($obj['values'] as $dat) {
        echo $dat.",";
    }
?>
        ],
<?php
    if ($type === 'T') {
        echo "        color: '#BB88FA'\n";
    } elseif ($type === 'H') {
        echo "        color: '#AA08F4'\n";
    } else {
        echo "        color: '#da70d6'\n";
    }
?>
    }]
});
</script>
