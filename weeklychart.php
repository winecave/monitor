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
    $to = new DateTime($date_from);
    $date_to = $to->modify('+7 days')->format('Y-m-d');
    $target = $_POST['target'];
    printf("    text: 'cellar %s from %s to %s'", $target, $date_from, $date_to);
?>
    },
    xAxis: {
        type: 'datetime',
        labels: {
            overflow: 'justify'
        }
    },
    yAxis: {
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
        plotLines: [{
            color: '#EEDDCC',
            width: 1,
<?php
    if ($type === 'T') {
        echo "            value: 18";
    } elseif($type === 'H') {
        echo "            value: 70";
    }
?>
        }],
<?php
    if ($type === 'T') {
        echo "min: 0,\n";
        echo "max: 30,\n";
    } elseif($type === 'H') {
        echo "min: 0,\n";
        echo "max: 100,\n";
    } else {
        echo "min: 980,\n";
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
            pointInterval: 3600000, // one hour
<?php
    require 'database.php';
    $obj = get_weekly_data($type, $date_from, $date_to);
    printf("            pointStart: Date.UTC(%d, %d, %d, %d, 0, 0)\n",
           $obj['yyyy'],
           $obj['mm'],
           $obj['dd'],
           $obj['hh']);
?>
        }
    },
    series: [{
        showInLegend: false,
        label: {
            enabled: false
        },
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
