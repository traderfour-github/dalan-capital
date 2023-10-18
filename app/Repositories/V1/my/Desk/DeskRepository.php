<?php

namespace App\Repositories\V1\my\Desk;

use Briofy\RepositoryLaravel\Repositories\AbstractRepository;
use App\Models\DalanCapital\V1\Desk;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use EloquentBuilder ;

class DeskRepository extends AbstractRepository implements IDeskRepository
{
    public function index(string $userId, array $attributes) : LengthAwarePaginator
    {
        if (empty($attributes)) {
            return $this->model->whereHas('user', function ($userQuery) use ($userId) {
                return $userQuery->where(Desk::USER_ID, $userId);
            })->paginate();
        } else {
            return EloquentBuilder::to($this->model, $attributes)
                ->whereHas('user', function ($userQuery) use ($userId) {
                    return $userQuery->where(Desk::USER_ID, $userId);
                })->paginate();
        }
    }

    public function show($uuid , $userId) : Model
    {
        return $this->model->whereHas('user', function ($userQuery) use ($userId) {
                return $userQuery->where(Desk::USER_ID, $userId);
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


    public function destroy($uuid): ?string
    {
        return $this->delete($uuid);
    }

    protected function instance(array $attributes = []): Model
    {
        return new Desk();
    }
}
