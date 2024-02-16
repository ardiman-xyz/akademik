<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $Sched_Real_Id
 * @property integer $Term_Year_Id
 * @property integer $Class_Prog_Id
 * @property integer $Class_Id
 * @property int $Course_Id
 * @property integer $Meeting_Order
 * @property integer $Room_Id
 * @property string $Date
 * @property string $Time_Start
 * @property string $Time_End
 * @property string $Token
 * @property string $Course_Content
 * @property string $Closed_By
 * @property integer $Max_Minutes
 * @property string $Description
 * @property string $Created_By
 * @property string $Created_Date
 * @property string $Modified_By
 * @property string $Modified_Date
 * @property MstrClass $mstrClass
 * @property MstrClassProgram $mstrClassProgram
 * @property AcdCourse $acdCourse
 * @property MstrRoom $mstrRoom
 * @property MstrTermYear $mstrTermYear
 * @property AcdStudent[] $acdStudents
 * @property EmpEmployee[] $empEmployees
 */
class AcdSchedReal extends Model
{
    public $timestamps = false;
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'acd_sched_real';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'Sched_Real_Id';

    /**
     * @var array
     */
    protected $fillable = ['Term_Year_Id', 'Class_Prog_Id', 'Class_Id', 'Course_Id', 'Meeting_Order', 'Room_Id', 'Date', 'Time_Start', 'Time_End', 'Token', 'Course_Content', 'Closed_By', 'Max_Minutes', 'Description', 'Created_By', 'Created_Date', 'Modified_By', 'Modified_Date'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mstrClass()
    {
        return $this->belongsTo('App\MstrClass', 'Class_Id', 'Class_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mstrClassProgram()
    {
        return $this->belongsTo('App\MstrClassProgram', 'Class_Prog_Id', 'Class_Prog_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function acdCourse()
    {
        return $this->belongsTo('App\AcdCourse', 'Course_Id', 'Course_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mstrRoom()
    {
        return $this->belongsTo('App\MstrRoom', 'Room_Id', 'Room_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mstrTermYear()
    {
        return $this->belongsTo('App\MstrTermYear', 'Term_Year_Id', 'Term_Year_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function acdStudents()
    {
        return $this->belongsToMany('App\AcdStudent', 'acd_sched_real_detail', 'Sched_Real_Id', 'Student_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function empEmployees()
    {
        return $this->belongsToMany('App\EmpEmployee', 'acd_sched_real_employee', 'Sched_Real_Id', 'Employee_Id');
    }
}
