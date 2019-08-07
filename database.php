<?php

function getConnection() {
  $dsn = 'pgsql:dbname=winecave host=localhost port=5432';
  $user = 'winecaveadmin';
  $password = 'fbdc1234';

  try {
    $dbh = new PDO($dsn, $user, $password);
    //$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    //$dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
  } catch (PDOException $e) {
    print('Error:'.$e->getMessage());
    die();
  }

  return $dbh;
}

function get_daily_data($type, $date_from, $date_to = 'now') {

    $dbh = getConnection();

    $stmt = $dbh->prepare('select value, time from sensorvalues where 
                               type=? and 
                               time between ? and ? order by time');
    $stmt->bindValue(1, $type);
    $stmt->bindValue(2, $date_from);
    $stmt->bindValue(3, $date_to);
    $stmt->execute();

    $yyyy = 9999;
    $mm   = 99;
    $dd   = 99;
    $hh   = 99;
    $i    = 99;
    $s    = 99;
    foreach($stmt as $rec) {
        $values[] = $rec['value'];
        if ($yyyy == 9999) {
            $date = getdate(DateTime::createFromFormat('Y-m-d H:i:s', $rec['time'])->format('U'));
            $yyyy = $date['year'];
            $mm   = $date['mon'] - 1;
            $dd   = $date['mday'];
            $hh   = $date['hours'];
            $i    = $date['minutes'];
            $s    = $date['seconds'];
        }
    }
    return array(
        'values' => $values,
        'yyyy'   => $yyyy,
        'mm'     => $mm,
        'dd'     => $dd,
        'hh'     => $hh,
        'i'      => $i,
        's'      => $s
    );
}

function get_weekly_data($type, $date_from, $date_to) {

    $dbh = getConnection();

    $stmt = $dbh->prepare('select yyyy, mm, dd, hh, avg(value) as value from sensorvalues
                           where
                               type=? and
                               time between ? and ?
                           group by yyyy, mm, dd, hh
                           order by yyyy, mm, dd, hh');
    $stmt->bindValue(1, $type);
    $stmt->bindValue(2, $date_from);
    $stmt->bindValue(3, $date_to);
    $stmt->execute();

    $yyyy = 9999;
    $mm   = 99;
    $dd   = 99;
    $hh   = 99;
    foreach($stmt as $rec) {
        $values[] = round($rec['value'], 2);
        if ($yyyy == 9999) {
            $yyyy = $rec['yyyy'];
            $mm   = $rec['mm'] - 1;
            $dd   = $rec['dd'];
            $hh   = $rec['hh'];
        }
    }
    return array(
        'values' => $values,
        'yyyy'   => $yyyy,
        'mm'     => $mm,
        'dd'     => $dd,
        'hh'     => $hh
    );
}


function get_monthly_data($type, $yyyy, $mm) {
    $dbh = getConnection();

    $stmt = $dbh->prepare('select yyyy, mm, dd, avg(value) as value from sensorvalues
                           where
                               type=? and
                               yyyy>=? and
                               (mm>=? or
                               mm in (1, 2, 3, 4, 5, 6))
                           group by yyyy, mm, dd
                           order by yyyy, mm, dd');
    $stmt->bindValue(1, $type);
    $stmt->bindValue(2, $yyyy);
    $stmt->bindValue(3, $mm);
    $stmt->execute();

    $yyyy = 9999;
    $mm   = 99;
    $dd   = 99;
    foreach($stmt as $rec) {
        $values[] = round($rec['value'], 2);
        if ($yyyy == 9999) {
            $yyyy = $rec['yyyy'];
            $mm   = $rec['mm'] - 1;
            $dd   = $rec['dd'];
        }
    }
    return array(
        'values' => $values,
        'yyyy'   => $yyyy,
        'mm'     => $mm,
        'dd'     => $dd
    );
}
?>
