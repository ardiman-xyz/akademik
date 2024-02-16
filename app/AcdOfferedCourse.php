<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $Offered_Course_id
 * @property integer $Term_Year_Id
 * @property integer $Department_Id
 * @property integer $Class_Prog_Id
 * @property int $Course_Id
 * @property integer $Class_Id
 * @property integer $Total_Meeting
 * @property integer $Class_Capacity
 * @property string $Created_By
 * @property string $Created_Date
 * @property string $Modified_By
 * @property string $Modified_Date
 * @property MstrClass $mstrClass
 * @property MstrClassProgram $mstrClassProgram
 * @property AcdCourse $acdCourse
 * @property MstrDepartment $mstrDepartment
 * @property MstrTermYear $mstrTermYear
 * @property AcdOfferedCourseExam[] $acdOfferedCourseExams
 * @property AcdOfferedCourseLecturer[] $acdOfferedCourseLecturers
 * @property AcdOfferedCourseSched[] $acdOfferedCourseScheds
 * @property AcdStudentKhsCategory[] $acdStudentKhsCategories
 */
class AcdOfferedCourse extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'acd_offered_course';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'Offered_Course_id';

    /**
     * @var array
     */
    protected $fillable = ['Term_Year_Id', 'Department_Id', 'Class_Prog_Id', 'Course_Id', 'Class_Id', 'Total_Meeting', 'Class_Capacity', 'Created_By', 'Created_Date', 'Modified_By', 'Modified_Date'];

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
    public function mstrDepartment()
    {
        return $this->belongsTo('App\MstrDepartment', 'Department_Id', 'Department_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mstrTermYear()
    {
        return $this->belongsTo('App\MstrTermYear', 'Term_Year_Id', 'Term_Year_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function acdOfferedCourseExams()
    {
        return $this->hasMany('App\AcdOfferedCourseExam', 'Offered_Course_Id', 'Offered_Course_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function acdOfferedCourseLecturers()
    {
        return $this->hasMany('App\AcdOfferedCourseLecturer', 'Offered_Course_id', 'Offered_Course_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function acdOfferedCourseScheds()
    {
        return $this->hasMany('App\AcdOfferedCourseSched', 'Offered_Course_id', 'Offered_Course_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function acdStudentKhsCategories()
    {
        return $this->hasMany('App\AcdStudentKhsCategory', 'Offered_Course_Id', 'Offered_Course_id');
    }
}
