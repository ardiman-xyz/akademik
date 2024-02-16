<?php

namespace App\Model\Auth;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laratrust\Traits\LaratrustUserTrait;
use Auth;
use DB;

/**
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $create_at
 * @property string $update_at
 * @property Role[] $roles
 */
class Accesskeuangan extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = '_accesskeuangan';

    /**
     * @var array
     */
    protected $fillable = ['name', 'description', 'create_at', 'update_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany('App\Model\Auth\Role', '_role_accesskeuangan', 'accesskeuangan_id', 'role_id');
    }
}
