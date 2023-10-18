<?php

namespace App\Repositories\V1\my\Desk\Account;

use App\Models\DalanCapital\V1\DeskAccount;
use Briofy\RepositoryLaravel\Repositories\AbstractRepository;
use EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class AccountRepository extends AbstractRepository implements IAccountRepository
{
    /**
     * @param string $user_id
     * @param array $attributes
     *
     * @return LengthAwarePaginator
     */
    public function index(string $user_id, array $attributes) : LengthAwarePaginator
    {
        $withUser = $this->model->whereHas('desk', function ($q) use ($user_id) {
            $q->where('user_id', $user_id);
            //@TODO: Fix query error   #message: "SQLSTATE[42S22]: Column not found: 1054 Unknown column 'desk_accounts.desk_id' in 'where clause' (Connection: mysql, SQL: select * from `desks` where `desk_accounts`.`desk_id` = `desks`.`id` and `user_id` = 1edec0f7-09c7-6056-a54a-9e589352d784 and `desks`.`deleted_at` is null)"
        });
        return empty($attributes) ? $withUser->paginate() : EloquentBuilder::to($withUser, $attributes)->paginate();
    }

    /**
     * @param string $uuid
     *
     * @return Model
     */
    public function show(string $uuid) : Model
    {
        return $this->findOrFail($uuid);
    }

    /**
     * @param array $attributes
     *
     * @return Model
     */
    public function store(array $attributes = []) : Model
    {
        return $this->create($attributes);
    }

    /**
     * @param string $uuid
     * @param array  $attributes
     *
     * @return Model
     */
    public function update($uuid, array $attributes) : Model
    {
        $model = $this->findOrFail($uuid);
        return $this->updateModel($model, $attributes);
    }

    /**
     * @param string $uuid
     *
     * @return bool|null
     */
    public function destroy(string $uuid) : ?bool
    {
        return $this->delete($uuid);
    }

    /**
     * @param array $attributes
     *
     * @return Model
     */
    protected function instance(array $attributes = []) : Model
    {
        return new DeskAccount();
    }
}
