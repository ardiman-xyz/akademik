<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $Id_Log_Error_Feeder
 * @property string $File_Name
 * @property string $Format
 * @property MstrDepartment[] $mstrDepartments
 */
class FeederLogError extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'feeder_log_error';
    public $timestamps = false;
    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'Id_Log_Error_Feeder';

    /**
     * @var array
     */
    protected $fillable = ['File_Name', 'Format'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function mstrDepartments()
    {
        return $this->belongsToMany('App\MstrDepartment', null, 'Id_Log_Error_Feeder', 'Department_Id');
    }
}
