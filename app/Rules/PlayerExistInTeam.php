<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use DB;

class PlayerExistInTeam implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    protected $parameters;
    public function __construct()
    {
        $this->parameters = func_get_args();
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $query = DB::table('players')->where('id', $value)->where('team_id', $this->parameters[0])->first();
        if($query) return true;
        else return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Player does not belong to the team!';
    }
}
