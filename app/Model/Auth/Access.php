<?php

namespace App\Model\Auth;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laratrust\Traits\LaratrustUserTrait;
use Auth;
use DB;

/**
 * @property Role[] $roles
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 */
class Access extends Model
{
    use LaratrustUserTrait;
    use Notifiable;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = '_access';

    /**
     * @var array
     */
    protected $fillable = ['name', 'description', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany('App\Model\Auth\Role', '_role_access', 'access_id', 'role_id');
    }
}
