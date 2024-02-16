<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $Id_File_Feeder
 * @property string $File_Name
 * @property MstrDepartment[] $mstrDepartments
 */
class FeederUpload extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'feeder_upload';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'Id_File_Feeder';

    /**
     * Indicates if the IDs are auto-incrementing.
     * 
     * @var bool
     */
    public $incrementing = false;
    public $timestamps = false;
    /**
     * @var array
     */
    protected $fillable = ['File_Name'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function mstrDepartments()
    {
        return $this->belongsToMany('App\MstrDepartment', null, 'Id_File_Feeder', 'Department_Id');
    }
}
