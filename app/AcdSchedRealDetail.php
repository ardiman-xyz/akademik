<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $Sched_Real_Id
 * @property integer $Student_Id
 * @property AcdSchedReal $acdSchedReal
 * @property AcdStudent $acdStudent
 */
class AcdSchedRealDetail extends Model
{
    public $timestamps = false;

    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'acd_sched_real_detail';

    /**
     * @var array
     */
    protected $fillable = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function acdSchedReal()
    {
        return $this->belongsTo('App\AcdSchedReal', 'Sched_Real_Id', 'Sched_Real_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function acdStudent()
    {
        return $this->belongsTo('App\AcdStudent', 'Student_Id', 'Student_Id');
    }
}
