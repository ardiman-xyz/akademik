<?php

namespace App\Model\Auth;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laratrust\Traits\LaratrustUserTrait;
use Auth;
use DB;

/**
 * @property Access[] $accesses
 * @property User[] $users
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 */
class Role extends Model
{
    use LaratrustUserTrait;
    use Notifiable;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = '_role';

    /**
     * @var array
     */
    protected $fillable = ['name', 'description', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function accesses()
    {
        return $this->belongsToMany('App\Model\Auth\Access', '_role_access', 'role_id', 'access_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany('App\Model\Auth\User', '_role_user', 'role_id', 'user_id');
    }
}
