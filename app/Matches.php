<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Matches extends Model
{
    public static function simulator($team1_id, $team2_id)
    {
        $home = '';
        $away = '';
        
        $squads = UserTactics::whereIn('team_id', [$team1_id, $team2_id])->orderByRaw('FIELD(team_id,'.$team1_id.','.$team2_id.')')->get();
        $teamsInfo = Team::whereIn('id', [$team1_id, $team2_id])->orderByRaw('FIELD(id,'.$team1_id.','.$team2_id.')')->get();
        // dd($teamsInfo);

        // Team Analyses
        $teamPositionArray = [];
        $defArray = [];
        $midArray = [];
        $attArray = [];
        foreach($squads as $key => $squad)
        {
            $formation = $squad->formation;
            // GK
            $teamPositionArray[$key]['gk'][] = findPlayer($squad->pos_1);
            // DEF
            $defCount = substr($formation, 0, 1);
            for($i = 2; $i <= $defCount+1; $i++) {
                $posNr = 'pos_'.$i;
                $teamPositionArray[$key]['def'][] = findPlayer($squad->$posNr);
            }
            // MID
            $midCount = substr($formation, 1, 1);
            for($i = 2+$defCount; $i <= $midCount+$defCount+1; $i++) {
                $posNr = 'pos_'.$i;
                $teamPositionArray[$key]['mid'][] = findPlayer($squad->$posNr);
            }
            // FW
            $attCount = substr($formation, 2, 1);
            for($i = 2+$midCount+$defCount; $i <= $attCount+$midCount+$defCount+1; $i++) {
                $posNr = 'pos_'.$i;
                $teamPositionArray[$key]['att'][] = findPlayer($squad->$posNr);
            }
        }
        // dd($teamPositionArray);

        // COUNT STATS

        $getAttributes = playerAttributes();
        // remove keeping
        $attributes = array_diff( $getAttributes, ['keeping'] );

        $positions = positions();
        $teamsStats = [];
        foreach($teamPositionArray as $index => $team) {
            $teamsStats[$index] = [];
            foreach($positions as $pos) {
                $teamsStats[$index][$pos] = [];
                foreach($attributes as $attr) {
                    $teamsStats[$index][$pos][$attr] = 0;
                    foreach($team[$pos] as $teamPositions) {
                        $teamsStats[$index][$pos][$attr] += $teamPositions->$attr;
                    }
                }
                // dd($teamsStats);
            }
            // dd($teamsStats);
        }
        // dd($teamsStats);

        
        // COMPARE TEAMS
        $teamsStatsSum = [];
        foreach($teamsStats as $index => $teamStats) {
            $teamsStatsSum[$index] = [];
            // dd($team1StatsSum);
            foreach($teamStats as $pos) {
                foreach($attributes as $attr) {
                    if (!isset($teamsStatsSum[$index][$attr])) $teamsStatsSum[$index][$attr] = 0;
                    $teamsStatsSum[$index][$attr] += $pos[$attr];
                    // dd($team1StatsSum);
                }
            }
        }
        // dd($teamsStatsSum);

        // WHICH TEAM HAS BETTER STATS?
        $compareTeamsStatsSums = [];
        $comparedStatsSum = 0;
        foreach($attributes as $attr) {
            if ($teamsStatsSum[0][$attr] == 0) {
                $compareTeamsStatsSums[$attr] = -1;
            }
            elseif ($teamsStatsSum[1][$attr] == 0) { 
                $compareTeamsStatsSums[$attr] = -1;
            }
            else $compareTeamsStatsSums[$attr] = $teamsStatsSum[0][$attr] / $teamsStatsSum[1][$attr];
            $comparedStatsSum += $compareTeamsStatsSums[$attr];
        }
        $averageAllStatsComparison = $comparedStatsSum / sizeOf($attributes);
        // var_dump('comparison = '.$averageAllStatsComparison);
        // var_dump($teamsInfo[0]->name);

        // chance for action
        if ( $averageAllStatsComparison > -1 ) {
            $team1_CTA = $averageAllStatsComparison*50;
            $team2_CTA = 100-$team1_CTA;
        }
        else if ( $averageAllStatsComparison <= -1 ) {
            $team1_CTA = 100;
            $team2_CTA = 100-$team1_CTA;
        }
        // var_dump($team1_CTA);
        // var_dump($team2_CTA);
        // dd($averageAllStatsComparison);
        // dd($team2_CTA);

        // TEAM 1 attack vs TEAM 2 defence

        // TEAM 2
        

        // roll team sides (home/away)
        if ( isset($team1_id) && isset($team2_id) )
        {
            $roll = rand(0, 1);
            if ($roll == 0) {
                $home = $teamsInfo[0]->name;
                $away = $teamsInfo[1]->name;
            }
            else {
                $home = $teamsInfo[1]->name;
                $away = $teamsInfo[0]->name;
            }
        }

        // ACTIONS
        // who start the FH/SH
        $ball = '';
        $ball = rand(0, 1);
        $startFH = '';
        if ($ball === 1) {
            $startFH = 'Match started by: <span class="commentary__team">' .$home. '</span>';
            $startSH = 'Match started by: <span class="commentary__team">' .$away. '</span>';
        }
        else {
            $startFH = 'Match started by: <span class="commentary__team">' .$away. '</span>';
            $startSH = 'Match started by: <span class="commentary__team">' .$home. '</span>';
        }

        // first half:
        $addedTimeFH = rand(0, 5);
        $minutes = [];
        for($i = 0; $i <= 45+$addedTimeFH; $i++){
            if ($i == 0) $minutes[$i] = ['minute' => 0, 'commentary' => $startFH];
            else {
                if (rand(0, 4) == 0) {
                    if ($i <= 45) $minutes[] = ['minute' => $i, 'commentary' => ''];
                    else $minutes[] = ['minute' => '45+'.($i-45), 'commentary' => ''];
                }
            }
        }

        // second half:
        $addedTimeSH = rand(0, 5);
        for($i = 45; $i <= 90+$addedTimeSH; $i++){
            if ($i == 46) $minutes[$i] = ['minute' => 46, 'commentary' => $startSH];
            else {
                if (rand(0, 4) == 0) {
                    if ($i <= 90) $minutes[] = ['minute' => $i, 'commentary' => ''];
                    else $minutes[] = ['minute' => '90+'.($i-90), 'commentary' => ''];
                }
            }
        }

        // dd($minutes);

        $homeGoal = 0;
        $awayGoal = 0;
        foreach($minutes as $index => $min) {
            if ($min['minute'] != 0 && $min['minute'] != 46) {
                if (rand(0, 100) <= $team1_CTA) {
                    // $minutes[$index]['commentary'] = 'Action: ' .$teamsInfo[0]->name;
                    $action = self::rollAction($squads[0]->formation);
                    $checkIfSuccess = self::tacticComparison($teamPositionArray[0], $teamPositionArray[1], $action);
                    if (rand(0, 100) <= $checkIfSuccess[$action['action']][$action['actionSide']]) {
                        $commentary = self::createComments($action, $teamsInfo[0]->name, $teamsInfo[1]->name, $checkIfSuccess, 2, $homeGoal, $awayGoal);
                        if (rand(0, 100) <= $checkIfSuccess[$action['shootType']]) {
                            $homeGoal += 1;
                            $commentary = self::createComments($action, $teamsInfo[0]->name, $teamsInfo[1]->name, $checkIfSuccess, 2, $homeGoal, $awayGoal);
                            $minutes[$index]['commentary'] = $commentary;
                            // echo '<pre>';
                            // var_dump('Head: '.$checkIfSuccess['head']);
                            // var_dump('Shoot: '.$checkIfSuccess['shoot']);
                            // var_dump('ActionChance = '. $checkIfSuccess[$action['action']][$action['actionSide']]);
                            // var_dump('ShootChance = '. $checkIfSuccess[$action['shootType']]);
                            // echo '</pre>';
                        }
                        else {
                            $commentary = self::createComments($action, $teamsInfo[0]->name, $teamsInfo[1]->name, $checkIfSuccess, 1, $homeGoal, $awayGoal);
                            $minutes[$index]['commentary'] = $commentary;
                        }
                    }
                    else {
                        $commentary = self::createComments($action, $teamsInfo[0]->name, $teamsInfo[1]->name, $checkIfSuccess, 0, $homeGoal, $awayGoal);
                        $minutes[$index]['commentary'] = $commentary;
                    }
                }
                else {
                    // $minutes[$index]['commentary'] = 'Action: ' .$teamsInfo[1]->name;
                    $action = self::rollAction($squads[1]->formation);
                    // var_dump($action);
                    $checkIfSuccess = self::tacticComparison($teamPositionArray[1], $teamPositionArray[0], $action);
                    if (rand(0, 100) <= $checkIfSuccess[$action['action']][$action['actionSide']]) {
                        $commentary = self::createComments($action, $teamsInfo[1]->name, $teamsInfo[0]->name, $checkIfSuccess, 2, $homeGoal, $awayGoal);
                        if (rand(0, 100) <= $checkIfSuccess[$action['shootType']]) {
                            $awayGoal += 1;
                            $commentary = self::createComments($action, $teamsInfo[1]->name, $teamsInfo[0]->name, $checkIfSuccess, 2, $homeGoal, $awayGoal);
                            $minutes[$index]['commentary'] = $commentary;

                            // echo '<pre>',
                            // var_dump('Head: '.$checkIfSuccess['head']);
                            // var_dump('Shoot: '.$checkIfSuccess['shoot']);
                            // var_dump('ActionChance = '. $checkIfSuccess[$action['action']][$action['actionSide']]);
                            // var_dump('ShootChance = '. $checkIfSuccess[$action['shootType']]);
                            // echo '</pre>';
                        }
                        else {
                            $commentary = self::createComments($action, $teamsInfo[1]->name, $teamsInfo[0]->name, $checkIfSuccess, 1, $homeGoal, $awayGoal);
                            $minutes[$index]['commentary'] = $commentary;
                        }
                    }
                    else {
                        $commentary = self::createComments($action, $teamsInfo[1]->name, $teamsInfo[0]->name, $checkIfSuccess, 0, $homeGoal, $awayGoal);
                        $minutes[$index]['commentary'] = $commentary;
                    }
                }
            }
        }

        // var_dump('Score: '.$homeGoal.'-'.$awayGoal);
        // var_dump($home);
        // dd($minutes);
        ksort($minutes);
        return $minutes;
    }

    public static function rollAction($formation)
    {
        $actionType = [
                [
                    'chance' => 35,
                    'action' => 'wings',
                    'actionSide' => [
                        [
                            'chance' => '50',
                            'side' => 'left'
                        ],
                        [
                            'chance' => '100',
                            'side' => 'right'
                        ],
                    ],
                    'shootType' => [
                        [
                            'chance' => '60',
                            'shoot' => 'head'
                        ],
                        [
                            'chance' => '100',
                            'shoot' => 'shoot'
                        ],
                    ]
                ],
                [
                    'chance' => 35,
                    'action' => 'short_pass',
                    'actionSide' => [
                        [
                            'chance' => '30',
                            'side' => 'left'
                        ],
                        [
                            'chance' => '30',
                            'side' => 'right'
                        ],
                        [
                            'chance' => '100',
                            'side' => 'center'
                        ],
                    ],
                    'shootType' => [
                        [
                            'chance' => '5',
                            'shoot' => 'head'
                        ],
                        [
                            'chance' => '100',
                            'shoot' => 'shoot'
                        ],
                    ]
                ],
                [
                    'chance' => 10,
                    'action' => 'freekick',
                    'actionSide' => [
                        [
                            'chance' => '30',
                            'side' => 'left'
                        ],
                        [
                            'chance' => '30',
                            'side' => 'right'
                        ],
                        [
                            'chance' => '100',
                            'side' => 'center'
                        ],
                    ],
                    'shootType' => [
                        [
                            'chance' => '35',
                            'shoot' => 'head'
                        ],
                        [
                            'chance' => '100',
                            'shoot' => 'shoot'
                        ],
                    ]
                ],
                [
                    'chance' => 15,
                    'action' => 'corner',
                    'actionSide' => [
                        [
                            'chance' => '30',
                            'side' => 'left'
                        ],
                        [
                            'chance' => '100',
                            'side' => 'right'
                        ],
                    ],
                    'shootType' => [
                        [
                            'chance' => '50',
                            'shoot' => 'head'
                        ],
                        [
                            'chance' => '100',
                            'shoot' => 'shoot'
                        ],
                    ]
                ],
                [
                    'chance' => 5,
                    'action' => 'penalty',
                    'actionSide' => [
                        [
                            'chance' => '100',
                            'side' => 'center'
                        ]
                    ],
                    'shootType' => [
                        [
                            'chance' => '100',
                            'shoot' => 'shoot'
                        ]
                    ]
                ],
                [
                    'chance' => 100,
                    'action' => 'long_pass',
                    'actionSide' => [
                        [
                            'chance' => '30',
                            'side' => 'left'
                        ],
                        [
                            'chance' => '30',
                            'side' => 'right'
                        ],
                        [
                            'chance' => '100',
                            'side' => 'center'
                        ],
                    ],
                    'shootType' => [
                        [
                            'chance' => '30',
                            'shoot' => 'head'
                        ],
                        [
                            'chance' => '100',
                            'shoot' => 'shoot'
                        ],
                    ]
                ],
            ];
        
        if ($formation == '442') $actionType[0]['chance'] = 70;
        if ($formation == '433') $actionType[0]['chance'] = -1;
        // dd($actionType);
        $action = [];

        foreach ($actionType as $type) {
            if ( rand(0, 100) <= $type['chance'] ) {
                $action['action'] = $type['action'];
                foreach ($type['actionSide'] as $side) {
                    if ( rand(0, 100) <= $side['chance'] ) {
                        $action['actionSide'] = $side['side'];
                        foreach ($type['shootType'] as $shoot) {
                            if ( rand(0, 100) <= $shoot['chance'] ) {
                                $action['shootType'] = $shoot['shoot'];
                                break;
                            }
                        }
                        break;
                    }
                }
                break;
            }
        }
        // var_dump($formation);
        return $action;
    }


    // public static function checkActionStatus($action, $attTeamPlayers, $defTeamPlayers) 
    // {
    //     // action type
    //     $action['action'];
    //     // action side
    //     $action['actionSide'];
    //     // action shoot type
    //     $action['shootType'];

    //     // attack vs defence
    //     // FL vs DC
    //     $compare = Self::comparePlayers($attTeamPlayers['att'][0], $defTeamPlayers['def'][2]);
    //     // FC

    //     // FR
    //     // var_dump($attTeamPlayers['att'][0]->speed." ".$defTeamPlayers['def'][2]->speed);
    //     // dd($compare);

    //     Self::tacticComparison($attTeamPlayers, $defTeamPlayers);
    // }

    public static function comparePlayers($player1, $player2)
    {
        $attributes = playerAttributes();

        $attrCompared = [];
        foreach($attributes as $attr) {
            $p1 =$player1->$attr;
            $p2 = $player2->$attr;
            if ($p1 == 0) $p1 = -1;
            if ($p2 == 0) $p2 = -1;
            
            $attrDivided = $p1 / $p2;
            $attrCompared[$attr] = $attrDivided;

        }

        return $attrCompared;
    }

    public static function tacticComparison($attTeamPlayers, $defTeamPlayers, $action)
    {

        
        //////////
        // GK
        ///////// 
        $gk = $defTeamPlayers['gk'][0];


        //////////
        // DEF
        ///////// 

        // check if tactic has more than 3 defenders
        if (sizeOf($defTeamPlayers['def']) > 3) {
            $dl = $defTeamPlayers['def'][0];
            $dr = $defTeamPlayers['def'][sizeOf($defTeamPlayers['def'])-1];
            $randDC = rand(1, sizeOf($defTeamPlayers['def'])-2);
        }
        else {
            $randDC = rand(0, sizeOf($defTeamPlayers['def'])-1);
        }

        // DC
        $dc = $defTeamPlayers['def'][$randDC];


        //////////
        // MID
        ///////// 

        // check if tactic has more than 3 midfields
        if (sizeOf($attTeamPlayers['mid']) > 3) {
            $ml = $attTeamPlayers['mid'][0];
            $mr = $attTeamPlayers['mid'][sizeOf($attTeamPlayers['mid'])-1];
            $randMC = rand(1, sizeOf($attTeamPlayers['mid'])-2);
        }
        else {
            $randMC = rand(0, sizeOf($attTeamPlayers['mid'])-1);
        }
        // mc
        $mc = $attTeamPlayers['mid'][$randMC];

        // WINGS
        if (isset($ml)) {
            $wingsChanceL = (
                ( ($ml['ball_control']+$ml['intelligence']+$ml['long_pass']) / 3 )
                /
                ( ( $dr['tackling']+$dr['intelligence']+$dr['heading']) / 3 )
                *
                ( ( $ml['ball_control']+$ml['intelligence']+$ml['long_pass'] ) / 3 )
                /
                ( ( $dr['tackling']+$dr['intelligence']+$dr['heading'] ) / 3 )
              )  * 0.5 * 100;
        }
        if (isset($mr)) {
            $wingsChanceR = (
                ( ($mr['ball_control']+$mr['intelligence']+$mr['long_pass']) / 3 )
                /
                ( ( $dl['tackling']+$dl['intelligence']+$dl['heading']) / 3 )
                *
                ( ( $mr['ball_control']+$mr['intelligence']+$mr['long_pass'] ) / 3 )
                /
                ( ( $dl['tackling']+$dl['intelligence']+$dl['heading'] ) / 3 )
              )  * 0.5 * 100;
        }


        //////////
        // FC
        /////////  

        // FC
        $randFW = rand(0, sizeOf($attTeamPlayers['att'])-1);
        $fc = $attTeamPlayers['att'][$randFW];

        // SHOOTING
        $shootChance = ( ($fc['shooting'] / $gk['keeping']) ) * ( ($fc['shooting'] / $gk['keeping']) ) * 0.5 * 100;
        $headChance = ( ($fc['heading'] / $gk['keeping']) ) * ( ($fc['heading'] / $gk['keeping']) ) * 0.5 * 100;
        
        if ($action['action'] == 'penalty') $shootChance += 10;
        // echo '<pre>' ,
        // var_dump('Wings: '.$wingsChanceL);
        // var_dump($randFW);
        // var_dump('FC shooting: '.$fc['shooting']);
        // var_dump('FC heading: '.$fc['heading']);
        // var_dump('GK keeping: '.$gk['keeping']);
        // var_dump('Chance for a goal shoot/head: '.$shootChance.'/'.$headChance);
        // echo '</pre>';

        // CORNER
        
        // $cornerChanceL = ( () )

        $chances = [];
        if (isset($ml)) $chances['wings']['left'] = $wingsChanceL;
        if (isset($mr)) $chances['wings']['right'] = $wingsChanceR;
        $chances['corner']['left'] = 50;
        $chances['corner']['right'] = 50;
        $chances['freekick']['right'] = 50;
        $chances['freekick']['left'] = 50;
        $chances['freekick']['center'] = 50;
        $chances['short_pass']['right'] = 50;
        $chances['short_pass']['left'] = 50;
        $chances['short_pass']['center'] = 50;
        $chances['long_pass']['right'] = 50;
        $chances['long_pass']['left'] = 50;
        $chances['long_pass']['center'] = 50;
        $chances['penalty']['center'] = 100;
        $chances['head'] = $headChance;
        $chances['shoot'] = $shootChance;
        $chances['defTeam']['gk'] = $gk;
        $chances['defTeam']['dl'] = $dl;
        $chances['defTeam']['dr'] = $dr;
        $chances['defTeam']['dc'] = $dc;
        if (isset($ml)) $chances['attTeam']['ml'] = $ml;
        if (isset($mr)) $chances['attTeam']['mr'] = $mr;
        $chances['attTeam']['mc'] = $mc;
        $chances['attTeam']['fc'] = $fc;
        // echo '<pre>' ,
        // var_dump($chances);
        // echo '</pre>';

        return $chances;
    }

    public static function createComments($action, $attTeam, $defTeam, $chances, $status, $homeGoal, $awayGoal)
    {
        // dd($action);
        // dd($homePlayers);

        // COMPARE PLAYERS
        // LEFT WING

        $commentary = '';
        $score = $homeGoal.'-'.$awayGoal;


        // DEFENDER TACKLES THE BALL
        if ($status == 0) {
            switch($action['action']) {
                case 'wings':
                    // $commentary .= 'Action by '.$attTeam.'. ';
                    switch($action['actionSide']) {
                        case 'left':
                            $commentary .= 'Action stopped by <span class="commentary__player">'.$chances['defTeam']['dr']['number'].'. '.$chances['defTeam']['dr']['name'].' '.$chances['defTeam']['dr']['surname'].'</span>.';
                            break;
                        case 'right':
                            $commentary .= 'Action stopped by <span class="commentary__player">'.$chances['defTeam']['dl']['number'].'. '.$chances['defTeam']['dl']['name'].' '.$chances['defTeam']['dl']['surname'].'</span>.';
                            break;
                    }
                    break;
                case 'short_pass':
                    // $commentary .= 'Action by: '.$attTeam.'. ';
                    switch($action['actionSide']) {
                        case 'left':
                            $commentary .= '[NOT COMPLETED] Short pass failed. <span class="commentary__team">'.$defTeam.'</span> tackle the ball';
                            break;
                        case 'right':
                            $commentary .= '[NOT COMPLETED] Short pass failed. <span class="commentary__team">'.$defTeam.'</span> tackle the ball';
                            break;
                        case 'center':
                            $commentary .= '[NOT COMPLETED] Short pass failed. <span class="commentary__team">'.$defTeam.'</span> tackle the ball';
                            break;
                    }
                    break;
                case 'freekick':
                    $commentary .= '[NOT COMPLETED] Free kick failed. <span class="commentary__team">'.$defTeam.'</span> tackle the ball';
                    break;
                case 'penalty':
                    $commentary .= '[NOT COMPLETED] Penalty failed. Ball for <span class="commentary__team">'.$defTeam.'</span>';
                    break;
                case 'corner':
                    // $commentary .= '';
                    switch($action['actionSide']) {
                        case 'left':
                            $commentary .= ' <span class="commentary__team">'.$attTeam.'</span> will start the game from the left side corner. <span class="commentary__player">'.$chances['attTeam']['mc']['number'].'. '.$chances['attTeam']['mc']['name'].' '.$chances['attTeam']['mc']['surname'].'</span> cross the ball... and defenders from <span class="commentary__team">'.$defTeam.'</span> wins the ball.';
                            break;
                        case 'right':
                            $commentary .= ' <span class="commentary__team">'.$attTeam.'</span> will start the game from the right side corner. <span class="commentary__player">'.$chances['attTeam']['mc']['number'].'. '.$chances['attTeam']['mc']['name'].' '.$chances['attTeam']['mc']['surname'].'</span> cross the ball... and defenders from <span class="commentary__team">'.$defTeam.'</span> wins the ball.';
                            break;
                    }
                    break;
                case 'long_pass':
                    // $commentary .= 'Action by '.$attTeam.'.';
                    switch($action['actionSide']) {
                        case 'left':
                            $commentary .= '[NOT COMPLETED] Long pass failed. <span class="commentary__team">'.$defTeam.'</span> tackle the ball';
                            break;
                        case 'right':
                            $commentary .= '[NOT COMPLETED] Long pass failed. <span class="commentary__team">'.$defTeam.'</span> tackle the ball';
                            break;
                        case 'center':
                            $commentary .= '[NOT COMPLETED] Long pass failed. <span class="commentary__team">'.$defTeam.'</span> tackle the ball';
                            break;
                    }
                    break;
            }
        }
        // GK KEEP THE BALL OUT OF THE NET
        if ($status == 1 || $status == 2) {
            switch($action['action']) {
                case 'wings':
                    // $commentary .= 'Action by '.$attTeam.'. ';
                    switch($action['actionSide']) {
                        case 'left':
                            $commentary .= 'Left winger <span class="commentary__player">'.$chances['attTeam']['ml']['number'].'. '.$chances['attTeam']['ml']['name'].' '.$chances['attTeam']['ml']['surname'].'</span> cross the ball.';
                            break;
                        case 'right':
                            $commentary .= 'Right winger <span class="commentary__player">'.$chances['attTeam']['mr']['number'].'. '.$chances['attTeam']['mr']['name'].' '.$chances['attTeam']['mr']['surname'].'</span> cross the ball.';
                            break;
                    }
                    break;
                case 'short_pass':
                    // $commentary .= 'Action by: '.$attTeam.'. ';
                    switch($action['actionSide']) {
                        case 'left':
                            $commentary .= '<span class="commentary__player">'.$chances['attTeam']['mc']['number'].'. '.$chances['attTeam']['mc']['name'].' '.$chances['attTeam']['mc']['surname']. '</span> pass the ball.';
                            break;
                        case 'right':
                            $commentary .= '<span class="commentary__player">'.$chances['attTeam']['mc']['number'].'. '.$chances['attTeam']['mc']['name'].' '.$chances['attTeam']['mc']['surname']. '</span> pass the ball.';
                            break;
                        case 'center':
                            $commentary .= '<span class="commentary__player">'.$chances['attTeam']['mc']['number'].'. '.$chances['attTeam']['mc']['name'].' '.$chances['attTeam']['mc']['surname']. '</span> pass the ball.';
                            break;
                    }
                    break;
                case 'freekick':
                    $commentary .= 'Free kick for <span class="commentary__player">'.$attTeam.'</span>. ';
                    break;
                case 'penalty':
                    $commentary .= 'Penalty for <span class="commentary__player">'.$attTeam.'</span>. ';
                    break;
                case 'corner':
                    // $commentary .= '';
                    switch($action['actionSide']) {
                        case 'left':
                            $commentary .= ' <span class="commentary__team">'.$attTeam.'</span> will start the game from the left side corner. <span class="commentary__player">'.$chances['attTeam']['mc']['number'].'. '.$chances['attTeam']['mc']['name'].' '.$chances['attTeam']['mc']['surname'].'</span> cross the ball to ';
                            break;
                        case 'right':
                            $commentary .= ' <span class="commentary__team">'.$attTeam.'</span> will start the game from the right side corner. <span class="commentary__player">'.$chances['attTeam']['mc']['number'].'. '.$chances['attTeam']['mc']['name'].' '.$chances['attTeam']['mc']['surname'].'</span> cross the ball to ';
                            break;
                    }
                    break;
                case 'long_pass':
                    // $commentary .= 'Action by '.$attTeam.'.';
                    switch($action['actionSide']) {
                        case 'left':
                            $commentary .= ' Long pass by: <span class="commentary__player">'.$chances['attTeam']['mc']['number'].'. '.$chances['attTeam']['mc']['name'].' '.$chances['attTeam']['mc']['surname']. '</span>. ';
                            break;
                        case 'right':
                            $commentary .= 'Long pass by: <span class="commentary__player">'.$chances['attTeam']['mc']['number'].'. '.$chances['attTeam']['mc']['name'].' '.$chances['attTeam']['mc']['surname']. '</span>. ';
                            break;
                        case 'center':
                            $commentary .= 'Long pass by: <span class="commentary__player">'.$chances['attTeam']['mc']['number'].'. '.$chances['attTeam']['mc']['name'].' '.$chances['attTeam']['mc']['surname']. '</span>. ';
                            break;
                    }
                    break;
            }
        }

        // GOAL
        if ($status == 1) {
            switch($action['shootType']) {
                case 'head':
                    $commentary .= ' <span class="commentary__player">'.$chances['attTeam']['fc']['number'].'. '.$chances['attTeam']['fc']['name'].' '.$chances['attTeam']['fc']['surname'].'</span> 
                    heads the ball. Fantastic save by <span class="commentary__player">'.$chances['defTeam']['gk']['number'].'. '.$chances['defTeam']['gk']['name'].' '.$chances['defTeam']['gk']['surname']. '</span>';
                    break;
                case 'shoot':
                    $commentary .= '  <span class="commentary__player">'.$chances['attTeam']['fc']['number'].'. '.$chances['attTeam']['fc']['name'].' '.$chances['attTeam']['fc']['surname'].'</span> 
                    shoots the ball. Fantastic save by <span class="commentary__player">'.$chances['defTeam']['gk']['number'].'. '.$chances['defTeam']['gk']['name'].' '.$chances['defTeam']['gk']['surname']. '</span>';
                    break;
            }
        }
        if ($status == 2) {
            switch($action['shootType']) {
                case 'head':
                    $commentary .= ' <span class="commentary__player">'.$chances['attTeam']['fc']['number'].'. '.$chances['attTeam']['fc']['name'].' '.$chances['attTeam']['fc']['surname'].'</span> heads the ball.... and <strong>GOAALL!</strong> '.$score;
                    break;
                case 'shoot':
                    $commentary .= ' <span class="commentary__player">'.$chances['attTeam']['fc']['number'].'. '.$chances['attTeam']['fc']['name'].' '.$chances['attTeam']['fc']['surname'].'</span> shoots the ball.... and <strong>GOAALL!</strong> '.$score;
                    break;
            }
        }

    return $commentary;
    }
}


// czarni - wisla
// 7-10

// czarni - arka
// 5-8
// 5-8
// 7-8

// czarni - lechia
// 6-11
// 11-8