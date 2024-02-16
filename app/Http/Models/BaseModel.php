<?php


namespace App\Http\Models;

// use Fico7489\Laravel\EloquentJoin\Traits\EloquentJoinTrait;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    // use EloquentJoinTrait;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->useTableAlias = true;
    }

}
