<?php

namespace App\Model\Auth;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laratrust\Traits\LaratrustUserTrait;
use Auth;
use DB;
use App\Model\Auth\Role;
/**
 * @property Role[] $roles
 * @property int $id
 * @property string $name
 * @property string $email
 * @property int $Faculty_Id
 * @property string $password
 * @property string $remember_token
 * @property string $created_at
 * @property string $updated_at
 */
class User extends Authenticatable
{
    use LaratrustUserTrait;
    use Notifiable;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = '_user';

    /**
     * @var array
     */
    protected $fillable = ['name', 'email', 'Faculty_Id', 'Department_Id', 'password', 'remember_token', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    public function roles()
    {
        return $this->belongsToMany('App\Model\Auth\Role', '_role_user', 'user_id', 'role_id');
    }

    public function akses()
    {
        $akses = [];
        $roles = $this->roles()->where('app','Akademik')->get();
        $i = 0;
        foreach($roles as $role){
            $accesses = Role::find($role->id)->accesses()->get();
            foreach($accesses as $access){
                $akses[$i] = $access->name;
                $i++;
            }
        }
        return $akses;
    }

    public function getSessionAttribute()
    {
      function acak($panjang)
      {
          $karakter= 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz123456789';
          $string = '';
          for ($i = 0; $i < $panjang; $i++) {
        $pos = rand(0, strlen($karakter)-1);
        $string .= $karakter{$pos};
          }
          return $string;
      }
      //cara memanggilnya
      $hasil_1= acak(125);
      $timezone = +7;
      $Timestamp =  gmdate("Y-m-d H:i:s", time() + 3600*($timezone+date("I")));

      $data = DB::table('_token')->where('username', Auth::user()->email)->count();
      if($data == 0){
              DB::table('_token')
              ->insert(
                ['username'=>Auth::user()->email,'token'=>$hasil_1,'Timestamp_time'=>$Timestamp]);
            }else{
              DB::table('_token')
              ->where('username',Auth::user()->email)
              ->update(
                ['token'=>$hasil_1,'Timestamp_time'=>$Timestamp]);
            }
      return $hasil_1;
    }
}
