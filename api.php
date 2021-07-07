<?
    $data = file("https://www.hltv.org/matches");
    $i = 0;
    $team = 1;
    foreach ($data as $line_num => $line) 
    {
        if(strpos($line,"\"upcomingMatch \""))
        {
            if($match['time'] != null && $match['time'] != 'LIVE' && $match['team1'] != null && $match['team2'] != null && $match['bo'] != null)
            {
                $matches[$i] = $match;
                $matches[$i]['day'] = $day;
                $i++;
            }
            unset($match);
            $team = 1;
        }
        if(strpos($line,"upcomingMatchesSection"))
        {
            preg_match('/matchDayHeadline">.*</',$line,$m);
            $day = str_replace(array("matchDayHeadline\">","<"),"",$m[0]);
        }
        if(strpos($line,"matchTime"))
        {
            preg_match('/data-unix=".*">/',$line,$m);
            $match['time'] = (int)str_replace(array("data-unix=\"","\">"),"",$m[0])/1000;
        }
        
        if(strpos($line,"matchRating"))
        {
            str_replace("\"fa fa-star\"","",$line,$match['star']);
            if($match['star'] == null)
                $match['star'] == 0;
        }
        
        if(strpos($line,"matchMeta"))
        {
            preg_match('/>.*</',$line,$m);
            $match['bo'] = str_replace(array("<",">"),"",$m[0]);
        }
        
        if(strpos($line,"matchTeamName text-ellipsis"))
        {
            preg_match('/>.*</',$line,$m);
            $match['team'.$team] = str_replace(array("<",">"),"",$m[0]);
            $team ++;
        }
    }

    $today = date('l');
    $message = "今日CSGO比赛日报:\n";
    $today_start= strftime( "%B:%d", time());
    foreach ($matches as $key => $m) 
    {
        if($matches[$key]['star'] > 0)
        {
            $star = '';
            for($i = 0;$i < $matches[$key]['star'];$i++)
            {
                    $star .="★";
            }
            if(strftime( "%B:%d", $matches[$key]['time']) == $today_start)
            {
                $buffer = strftime( "今天 %H:%M ", $matches[$key]['time'])."\n[".$matches[$key]['bo']."]  ".$matches[$key]['team1']." VS ".$matches[$key]['team2']."\n"."HLTV指数:".$star."\n";
                $message.= $buffer;
            }
        }
    }
	/*
    @NoifyToGroup("5984557",$message);
    @NoifyToGroup("739902016",$message);
    @NoifyToGroup("1104064935",$message);
	*/