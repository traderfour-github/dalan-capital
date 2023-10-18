<?php

namespace App\Models\DalanCapital\V1;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contract extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'user_id','team_id', 'team_trader_id', 'desk_id', 'desk_account_id', 'number', 'title',
        'description', 'share', 'start_balance', 'current_balance', 'currency', 'profits',
        'harvestable', 'harvested', 'scale_up_amount', 'scale_up_times', 'scaled_up_at',
        'target', 'is_public', 'status', 'synced_at'
    ];

    protected $casts = [
        'share' => 'double',
        'start_balance' => 'double',
        'current_balance' => 'double',
        'profits' => 'double',
        'harvestable' => 'double',
        'harvested' => 'double',
        'scale_up_amount' => 'double',
        'scale_up_times' => 'integer',
        'scaled_up_at' => 'datetime',
        'is_public' => 'boolean',
        'synced_at' => 'datetime'
    ];


    public function teams() : BelongsTo
    {
        return $this->belongsTo(Team::class, 'team_id' , 'id');
    }

    public function desks() : BelongsTo
    {
        return $this->belongsTo(Desk::class , 'desk_id' , 'id');
    }

    public function teamTraders() : BelongsTo
    {
        return $this->belongsTo(TeamTrader::class , 'team_trader_id' , 'id');
    }

    public function deskAccounts() : BelongsTo
    {
        return $this->belongsTo(DeskAccount::class , 'desk_account_id' , 'id');
    }


    public static function withRelational(){
        return self::with([
            'desks' => function($desk){
                return $desk->select(['id','title']);
            },
            'teams' => function($team){
                return $team->select(['id','title']);
            },
            'teamTraders'=> function($teamTraders){
                return $teamTraders->select(['id','team_id','content'])->with([
                    'team' => function($team){
                        return $team ;
                    }
                ]);
            },
            'deskAccounts' => function($deskAccounts){
                return $deskAccounts
                    ->select(['id','title','desk_id','trading_account_id','risk_management_id','money_management_id'])->with([
                    'desk' => function($desk){
                        return $desk ;
                    }
                ]);
            },
        ]);
    }
}
