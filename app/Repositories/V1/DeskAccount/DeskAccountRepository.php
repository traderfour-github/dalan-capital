<?php

namespace App\Repositories\V1\DeskAccount;

use App\Models\DalanCapital\V1\DeskAccount;
use Briofy\RepositoryLaravel\Repositories\AbstractRepository;
use EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class DeskAccountRepository extends AbstractRepository implements IDeskAccountRepository
{
    /**
     * @param array $attributes
     *
     * @return Collection
     */
    public function index(array $attributes)
    {
        return empty($attributes) ?
            $this->model->public()->paginate()
            :
            EloquentBuilder::to($this->model, $attributes)->public()->paginate();
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
