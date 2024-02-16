<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $Course_Id
 * @property integer $Department_Id
 * @property integer $Course_Type_Id
 * @property string $Course_Code
 * @property string $Course_Name
 * @property string $Course_Name_Eng
 * @property string $Created_By
 * @property string $Created_Date
 * @property string $Modified_By
 * @property string $Modified_Date
 * @property int $Order_Id
 * @property AcdCourseType $acdCourseType
 * @property MstrDepartment $mstrDepartment
 * @property AcdCourseCurriculum[] $acdCourseCurriculums
 * @property AcdCourseIdenticDetail[] $acdCourseIdenticDetails
 * @property AcdCourseLecturer[] $acdCourseLecturers
 * @property AcdCourseSched[] $acdCourseScheds
 * @property AcdInternship[] $acdInternships
 * @property AcdOfferedCourse[] $acdOfferedCourses
 * @property AcdPrerequisite[] $acdPrerequisites
 * @property AcdSchedReal[] $acdSchedReals
 * @property AcdStudentKr[] $acdStudentKrs
 * @property AcdThesi[] $acdTheses
 * @property AcdTranscript[] $acdTranscripts
 */
class AcdCourse extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'acd_course';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'Course_Id';

    /**
     * @var array
     */
    protected $fillable = ['Department_Id', 'Course_Type_Id', 'Course_Code', 'Course_Name', 'Course_Name_Eng', 'Created_By', 'Created_Date', 'Modified_By', 'Modified_Date', 'Order_Id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function acdCourseType()
    {
        return $this->belongsTo('App\AcdCourseType', 'Course_Type_Id', 'Course_Type_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mstrDepartment()
    {
        return $this->belongsTo('App\MstrDepartment', 'Department_Id', 'Department_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function acdCourseCurriculums()
    {
        return $this->hasMany('App\AcdCourseCurriculum', 'Course_Id', 'Course_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function acdCourseIdenticDetails()
    {
        return $this->hasMany('App\AcdCourseIdenticDetail', 'Course_Id', 'Course_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function acdCourseLecturers()
    {
        return $this->hasMany('App\AcdCourseLecturer', 'Course_Id', 'Course_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function acdCourseScheds()
    {
        return $this->hasMany('App\AcdCourseSched', 'Course_Id', 'Course_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function acdInternships()
    {
        return $this->hasMany('App\AcdInternship', 'Course_Id', 'Course_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function acdOfferedCourses()
    {
        return $this->hasMany('App\AcdOfferedCourse', 'Course_Id', 'Course_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function acdPrerequisites()
    {
        return $this->hasMany('App\AcdPrerequisite', 'Course_Id', 'Course_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function acdSchedReals()
    {
        return $this->hasMany('App\AcdSchedReal', 'Course_Id', 'Course_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function acdStudentKrs()
    {
        return $this->hasMany('App\AcdStudentKr', 'Course_Id', 'Course_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function acdTheses()
    {
        return $this->hasMany('App\AcdThesi', 'Course_Id', 'Course_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function acdTranscripts()
    {
        return $this->hasMany('App\AcdTranscript', 'Course_Id', 'Course_Id');
    }
}
