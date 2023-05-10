<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="#">
    <title>萬年曆作業</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css"
        integrity="sha512-NhSC1YmyruXifcj/KFRWoC561YpHpc5Jtzgvbuzx5VozKpWvQ+4nXhPdFgmx8xqexRcpAglTj9sIBWINXa8x5w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="calendar.css" media="all">

    
    <style>
        /*請在這裹撰寫你的CSS*/
        body{animation-name: akey; 
            animation-duration: 60s;
            animation-iteration-count: infinite; 
        }
        @keyframes akey{
    0% {background-image: url('./images/<?=rand(4,8);?>.jpg');}
    25% {background-image: url('./images/<?=rand(4,8);?>.jpg');}
    50% {background-image: url('./images/<?=rand(4,8);?>.jpg');}
    75% {background-image: url('./images/<?=rand(4,8);?>.jpg');}
    100% {background-image: url('./images/<?=rand(4,8);?>.jpg');}        
}
    </style>
</head>

<body>
    <!-- 萬年歷 -->
    <?php
    #判斷從_GET[]所取得的資料
    $month = $_GET['month'] ?? date('n'); #判斷月
    $year = $_GET['year'] ?? date('Y'); #判斷年
    
    #取得相關資料
    
    $first_day = strtotime("$year-$month-1"); #當月的第一天秒數
    $days = date("t", $first_day); #取得最後一天，天數
    $final_day = strtotime("$year-$month-$days"); #當月的最後一天秒數
    $first_week = date('w', $first_day); #第一週星期幾 六
    $final_week = date('w', $final_day); #最後一週星期幾 日
    $month_days = ($days + $first_week); #當月總共要算幾天36
    $month_week = ceil($month_days / 7); #當月有幾週 6
    $today = date('Y-n-j'); #取得今天日期
    
    #判斷年月有沒有超過
    if ($month == 12) {
        $nextmonth = 1;
        $nextyear = $year + 1;
    } else {
        $nextmonth = $month + 1;
        $nextyear = $year;
    }
    if ($month == 1) {
        $prevmonth = 12;
        $prevyear = $year - 1;
    } else {
        $prevmonth = $month - 1;
        $prevyear = $year;
    }

    // 為了處理上個月的天數和下個月的天數
    $prevmonth_first_day = strtotime("$prevyear-$prevmonth-1"); #上個月的第一天秒數
    $prevmonth_days = date("t", $prevmonth_first_day); #取得上個月最後一天，天數
    $nextdays = 1;      #下個月從1號開始算

    #建立月份陣列相關資料
    $data = [];
    for ($i = 0; $i < $month_week; $i++) {

        for ($j = 0; $j < 7; $j++) {
            #判斷要寫入空白還是天數
            if ($j < $first_week && $i == 0) {
                $prevdays = ($prevmonth_days - $first_week + 1) + $j ;
                $data[] = $prevyear . '-' . $prevmonth . '-' . $prevdays;
            } else {
                if ($i == ($month_week - 1) && $final_week < $j) {
                    
                    $data[] = $nextyear . '-' . $nextmonth . '-' . $nextdays++;
                } else {
                    $data[] = $year . '-' . $month . '-' . ($i * 7) + $j - $first_week + 1;

                }
            }
        }
    }
    // <!--假日資料庫 -->
        
    $holiday = [
        '2023-1-1' => '元旦',
        '2023-1-2' => '補假',
        '2023-1-20' => '彈性放假日',
        '2023-1-21' => '除夕',
        '2023-1-22' => '春節',
        '2023-1-23' => '春節',
        '2023-1-24' => '春節',
        '2023-1-25' => '補假',
        '2023-1-26' => '補假',
        '2023-1-27' => '彈性放假日',
        '2023-2-27' => '彈性放假日',
        '2023-2-28' => '和平記念日',
        '2023-4-3' => '彈性放假日',
        '2023-4-4' => '國際兒童節',
        '2023-4-5' => '清明節',
        '2023-5-1' => '勞動節',
        '2023-6-22' => '端午節',
        '2023-6-23' => '彈性放假日',
        '2023-9-29' => '中秋節',
        '2023-10-9' => '彈性放假日',
        '2023-10-10' => '國慶日'
    ];
    $mon=['January','February','March','April','May','June',
          'July','August','September','October','November','December']
    ?>

<!-- #flexbox -->
<div class="years">
    <?= $year ?>年
</div>
<!-- 上一月，這一月，下一月 -->

<div class="a-month">
    <a href="index.php?year=<?= $prevyear; ?>&month=<?= $prevmonth; ?>" class="left"></a>
    <a href="index.php?year=<?= $year; ?>&month=<?= $month; ?>"><?= $mon[$month-1]; ?></a>
    <a href="index.php?year=<?= $nextyear; ?>&month=<?= $nextmonth; ?>"class="right"></a>
</div>

<div class="contianer">
    <div class="box tittle">星期日</div>
    <div class="box tittle">星期一</div>
    <div class="box tittle">星期二</div>
    <div class="box tittle">星期三</div>
    <div class="box tittle">星期四</div>
    <div class="box tittle">星期五</div>
    <div class="box tittle">星期六</div>

    <?php

    #使用新的data格式判斷日期
    // 需要使用explode()取出$data[]日期和判斷空白
    for ($i = 0; $i < count($data); $i++) {
        echo "<div class='box  ";
        $d = explode('-', $data[$i])[2]; #取$i日期
        $m = explode('-',$data[$i])[1];#取月份
        
        if ($today == $data[$i]) { #判斷今天  
            echo "today-day'>";
            if($m != $month){echo"<span class='notmonthday-font'>$d</span>";    #控制非本月字型
            }else{
            echo "<span class='day-font'>$d</span>"; #設定天數字型
            }
            if (isset($holiday[$data[$i]])) { #剛好國定假日在今天，取值
                $days = $holiday[$data[$i]];
                echo "<span class='holiday-font'>$days</span>";
            }
            if($m != $month){echo"<span class='notmonthday-font'>$d</span>";    #控制非本月字型
            }else{
            echo "<br><span class='day-font'>Today</span>";
            }
            echo "</div>";
           
        } else {
            if ($i % 7 == 0 or $i % 7 == 6) { #判斷6日
                if (isset($holiday[$data[$i]])) { #判斷國定假日
                    $days = $holiday[$data[$i]]; #將國定假日值取出
                    echo "holiday-day'>";
                    if($m != $month){echo"<span class='notmonthday-font'>$d</span>";    #控制非本月字型
                    }else{
                    echo "<span class='day-font'>$d</span>"; #設定天數字型
                    }
                    echo "<span class='holiday-font'>$days</span>";
                    echo "</div>";

                } else { #不是國定假日的6日
                    echo "holiday'>";
                    if($m != $month){echo"<span class='notmonthday-font'>$d</span>";    #控制非本月字型
                    }else{
                    echo "<span class='day-font'>$d</span>";
                    }
                    echo "</div>";
                }
            } else {
                if (isset($holiday[$data[$i]])) { #平日的國定假日
                    $days = $holiday[$data[$i]];
                    echo "holiday-day'>";
                    if($m != $month){echo"<span class='notmonthday-font'>$d</span>";    #控制非本月字型
                    }else{
                    echo "<span class='day-font'>$d</span>";
                    }
                    echo "<br>";
                    echo "<span class='holiday-font'>$days</span>";
                    echo "</div>";

                } else { #不是國定假日的平日
                    echo "'>";
                    $days = (isset($holiday[$data[$i]])) ? $holiday[$data[$i]] : "";
                    if($m != $month){echo"<span class='notmonthday-font'>$d</span>";    #控制非本月字型
                    }else{
                    echo "<span class='day-font'>$d</span>";
                    }
                    echo "</div>";
                }
            }
        }
    }
    ?>

</div>

</body>

</html>