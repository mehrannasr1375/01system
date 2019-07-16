<?php

date_default_timezone_set('Asia/Tehran');

function convertDate($time)
{
    $weekDays=array("شنبه","یکشنبه","دوشنبه","سه شنبه","چهارشنبه","پنجشنبه","جمعه");
    $months=array("فروردین","اردیبهشت","خرداد","تیر","مرداد","شهریور","مهر","آبان","آذر","دی","بهمن","اسفند");

    $dayNumber=date("d",$time);    //day
    $monthNumber=date("m",$time);  //month
    $year=date("Y",$time);         //year
    $weekDayNumber=date("w",$time);
    $hour=date("G",$time);
    $minute=date("i",$time);
    $second=date("s",$time);

    switch ($monthNumber)
    {
        case 1:
            ($dayNumber < 20) ? ($monthNumber = 10) : ($monthNumber = 11);
            ($dayNumber < 20) ? ($dayNumber += 10) : ($dayNumber -= 19);
            break;
        case 2:
            ($dayNumber < 19) ? ($monthNumber = 11) : ($monthNumber = 12);
            ($dayNumber < 19) ? ($dayNumber += 12) : ($dayNumber -= 18);
            break;
        case 3:
            ($dayNumber < 21) ? ($monthNumber = 12) : ($monthNumber = 1);
            ($dayNumber < 21) ? ($dayNumber += 10) : ($dayNumber -= 20);
            break;
        case 4:
            ($dayNumber < 21) ? ($monthNumber = 1) : ($monthNumber = 2);
            ($dayNumber < 21) ? ($dayNumber += 11) : ($dayNumber -= 20);
            break;
        case 5:
        case 6:
            ($dayNumber < 22) ? ($monthNumber -= 3) : ($monthNumber -= 2);
            ($dayNumber < 22) ? ($dayNumber += 10) : ($dayNumber -= 21);
            break;
        case 7:
        case 8:
        case 9:
            ($dayNumber < 23) ? ($monthNumber -= 3) : ($monthNumber -= 2);
            ($dayNumber < 23) ? ($dayNumber += 9) : ($dayNumber -= 22);
            break;
        case 10:
            ($dayNumber < 23) ? ($monthNumber = 7) : ($monthNumber = 8);
            ($dayNumber < 23) ? ($dayNumber += 8) : ($dayNumber -= 22);
            break;
        case 11:
        case 12:
            ($dayNumber < 22) ? ($monthNumber -= 3) : ($monthNumber -= 2);
            ($dayNumber < 22) ? ($dayNumber += 9) : ($dayNumber -= 21);
            break;
    }
    $newDate['day']=$dayNumber;
    $newDate['month_num']=$monthNumber;
    $newDate['month_name']=$months[$monthNumber-1];
    if(($monthNumber<3) or (($monthNumber==3) and ($dayNumber<21))){
        $newDate['year']=$year-622;
    }
    else
        $newDate['year']=$year-621;
    if($weekDayNumber==6)
        $newDate['weekday_num']=0;
    else
        $newDate['weekday_num']=$weekDayNumber+1;
    $newDate['weekday_name']=$weekDays[$newDate['weekday_num']];
    $newDate['hour']=$hour;
    $newDate['minute']=$minute;
    $newDate['second']=$second;
    return $newDate;
}
