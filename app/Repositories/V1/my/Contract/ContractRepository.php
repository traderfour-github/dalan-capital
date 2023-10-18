<?php

namespace App\Repositories\V1\my\Contract;

use Briofy\RepositoryLaravel\Repositories\AbstractRepository;
use App\Models\DalanCapital\V1\Contract;
use Illuminate\Database\Eloquent\Model;
use EloquentBuilder;

class ContractRepository extends AbstractRepository implements IContractRepository
{
    public function index(string $userId, array $attributes)
    {
        if (empty($attributes)) {
            return $this->model->withRelational()->whereHas('user', function ($userQuery) use ($userId) {
                return $userQuery->where('user_id', $userId);
            })->paginate();
        } else {
            return EloquentBuilder::to($this->model, $attributes)->with([
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
            ])->whereHas('user', function ($userQuery) use ($userId) {
                return $userQuery->where('user_id', $userId);
            })->paginate();
        }
    }

    public function show($uuid , $userId) : Model
    {
        return $this->model->whereHas('user', function ($userQuery) use ($userId) {
            return $userQuery->where('user_id', $userId);
        })->where('id', $uuid)->firstOrFail();
    }

    public function store(array $attributes = []) : Model
    {
        return $this->create($attributes);
    }

    public function update($id, array $attributes): Model
    {
        $model = $this->findOrFail($id);

        return $this->updateModel($model, $attributes);
    }


    public function destroy($uuid): ?bool
    {
        return $this->delete($uuid);
    }


    protected function instance(array $attributes = []): Model
    {
        return new Contract();
    }
}
