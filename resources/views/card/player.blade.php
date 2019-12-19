<div class="row">
    <div class="col-md-12">
        <table class="" cellspacing="0" width="100%">
            <tbody>
                <tr>
                    <td>Age</td>
                    <td>{{ age($player->age) }}</td>
                </tr>
                <tr>
                    <td>Height</td>
                    <td>{{ $player->height }}</td>
                </tr>
                <tr>
                    <td>Weight</td>
                    <td>{{ $player->weight }}</td>
                </tr>
                <tr>
                    <td>Foot</td>
                    <td>{{ $player->foot }}</td>
                </tr>
                <!-- temporary countBalls -->
                <!-- <tr>
                    <td>Balls</td>
                    <td class="count-balls"></td>
                </tr> -->
            </tbody>
        </table>
    </div>
    <div class="col-md-12">
        <table class="" cellspacing="0" width="100%">
            <tbody>
                <tr>
                    <td>Speed</td>
                    <td>{!! balls($player->speed) !!}</td>
                    <td>{{ $player->speed }}</td>
                </tr>
                <tr>
                    <td>Stamina</td>
                    <td>{!! balls($player->stamina) !!}</td>
                    <td>{{ $player->stamina }}</td>
                </tr>
                <tr>
                    <td>Intelligence</td>
                    <td>{!! balls($player->intelligence) !!}</td>
                    <td>{{ $player->intelligence }}</td>
                </tr>
                <tr>
                    <td>Short pass</td>
                    <td>{!! balls($player->short_pass) !!}</td>
                    <td>{{ $player->short_pass }}</td>
                </tr>
                <tr>
                    <td>Long pass</td>
                    <td>{!! balls($player->long_pass) !!}</td>
                    <td>{{ $player->long_pass }}</td>
                </tr>
                <tr>
                    <td>Ball control</td>
                    <td>{!! balls($player->ball_control) !!}</td>
                    <td>{{ $player->ball_control }}</td>
                </tr>
                <tr>
                    <td>Heading</td>
                    <td>{!! balls($player->heading) !!}</td>
                    <td>{{ $player->heading }}</td>
                </tr>
                <tr>
                    <td>Shooting</td>
                    <td>{!! balls($player->shooting) !!}</td>
                    <td>{{ $player->shooting }}</td>
                </tr>
                <tr>
                    <td>Tackling</td>
                    <td>{!! balls($player->tackling) !!}</td>
                    <td>{{ $player->tackling }}</td>
                </tr>
                <tr>
                    <td>Set plays</td>
                    <td>{!! balls($player->set_plays) !!}</td>
                    <td>{{ $player->set_plays }}</td>
                </tr>
                <tr>
                    <td>Keeping</td>
                    <td>{!! balls($player->keeping) !!}</td>
                    <td>{{ $player->keeping }}</td>
                </tr>
                <tr>
                    <td>Experience</td>
                    <td>{!! balls($player->experience) !!}</td>
                    <td>{{ $player->experience }}</td>
                </tr>
                <tr>
                    <td>Form</td>
                    <td>{!! balls($player->form) !!}</td>
                    <td>{{ $player->form }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>