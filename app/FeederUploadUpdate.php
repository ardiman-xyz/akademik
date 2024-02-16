<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $Id_File_Feeder_Update
 * @property string $File_Name
 * @property MstrDepartment[] $mstrDepartments
 */
class FeederUploadUpdate extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'feeder_upload_update';
    public $timestamps = false;
    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'Id_File_Feeder_Update';

    /**
     * @var array
     */
    protected $fillable = ['File_Name'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function mstrDepartments()
    {
        return $this->belongsToMany('App\MstrDepartment', null, 'Id_File_Feeder_Update', 'Department_Id');
    }
}
