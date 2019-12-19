<?php
    use Carbon\Carbon;
    use App\Players;
    use App\UserTactics;
    use App\Team;

    function balls($count)
    {
        $html = '';
        for($i = 1; $i <= number_format($count); $i++) {
            $html .= '<balls-component></balls-component>';
        }
        return $html;
    }

    function age($arg)
    {
        $age = Carbon::parse($arg)->age;
        return $age;
    }

    function findPlayer($id)
    {
        $playerData = Players::find($id);
        
        return $playerData;
    }

    function playerAttributes() 
    {
        $attributes = ['speed', 'stamina', 'intelligence', 'short_pass', 'long_pass', 'ball_control', 
        'heading', 'shooting', 'tackling', 'set_plays', 'keeping', 'experience', 'form'];

        return $attributes;
    }

    function positions() 
    {
        $positions = ['gk', 'def', 'mid', 'att'];

        return $positions;
    }