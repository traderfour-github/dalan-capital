<?php

namespace App\Repositories\V1\DeskAccount;

interface IDeskAccountRepository
{
    public function index(array $attributes);

    public function show(string $uuid);

    public function store(array $attributes);

    public function update($uuid, array $attributes);

    public function destroy(string $uuid);
}
