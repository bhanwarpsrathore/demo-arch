<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait FilterByTeam {

    protected static function bootFilterByTeam() {
        static::creating(function ($model) {
            $model->team_id = getPermissionsTeamId();
        });

        self::addGlobalScope(function (Builder $builder) {
            $team_id = getPermissionsTeamId();
            $team_id && $builder->where('team_id', getPermissionsTeamId());
        });
    }
}
