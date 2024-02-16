<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * @property MstrEducationProgramType $mstrEducationProgramType
 * @property MstrFaculty $mstrFaculty
 * @property AcdAllowedSk[] $acdAllowedSks
 * @property AcdCourse[] $acdCourses
 * @property AcdCourseCurriculum[] $acdCourseCurriculums
 * @property AcdCourseIdentic[] $acdCourseIdentics
 * @property AcdCurriculumEntryYear[] $acdCurriculumEntryYears
 * @property AcdDepartmentLecturer[] $acdDepartmentLecturers
 * @property AcdGpaSk[] $acdGpaSks
 * @property AcdGradeDepartment[] $acdGradeDepartments
 * @property AcdGraduationBest[] $acdGraduationBests
 * @property AcdGraduationDepartmentClass[] $acdGraduationDepartmentClasses
 * @property AcdInternship[] $acdInternships
 * @property AcdOfferedCourse[] $acdOfferedCourses
 * @property AcdPrerequisite[] $acdPrerequisites
 * @property AcdSchedRoom[] $acdSchedRooms
 * @property AcdStudent[] $acdStudents
 * @property integer $Department_Id
 * @property string $Department_Code
 * @property integer $Faculty_Id
 * @property integer $Education_Prog_Type_Id
 * @property string $Department_Name
 * @property string $Department_Name_Eng
 * @property string $Department_Acronym
 * @property string $Department_Dikti_Sk_Number
 * @property string $Department_Dikti_Sk_Date
 * @property boolean $Order_Id
 * @property string $Created_By
 * @property string $Created_Date
 * @property string $Modified_By
 * @property string $Modified_Date
 */
class MstrDepartment extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'mstr_department';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'Department_Id';

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['Department_Code', 'Faculty_Id', 'Education_Prog_Type_Id', 'Department_Name', 'Department_Name_Eng', 'Department_Acronym', 'Department_Dikti_Sk_Number', 'Department_Dikti_Sk_Date', 'Order_Id', 'Created_By', 'Created_Date', 'Modified_By', 'Modified_Date'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mstrEducationProgramType()
    {
        return $this->belongsTo('App\MstrEducationProgramType', 'Education_Prog_Type_Id', 'Education_Prog_Type_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mstrFaculty()
    {
        return $this->belongsTo('App\MstrFaculty', 'Faculty_Id', 'Faculty_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function acdAllowedSks()
    {
        return $this->hasMany('App\AcdAllowedSk', 'Department_Id', 'Department_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function acdCourses()
    {
        return $this->hasMany('App\AcdCourse', 'Department_Id', 'Department_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function acdCourseCurriculums()
    {
        return $this->hasMany('App\AcdCourseCurriculum', 'Department_Id', 'Department_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function acdCourseIdentics()
    {
        return $this->hasMany('App\AcdCourseIdentic', 'Department_Id', 'Department_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function acdCurriculumEntryYears()
    {
        return $this->hasMany('App\AcdCurriculumEntryYear', 'Department_Id', 'Department_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function acdDepartmentLecturers()
    {
        return $this->hasMany('App\AcdDepartmentLecturer', 'Department_Id', 'Department_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function acdGpaSks()
    {
        return $this->hasMany('App\AcdGpaSk', 'Department_Id', 'Department_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function acdGradeDepartments()
    {
        return $this->hasMany('App\AcdGradeDepartment', 'Department_Id', 'Department_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function acdGraduationBests()
    {
        return $this->hasMany('App\AcdGraduationBest', 'Department_Id', 'Department_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function acdGraduationDepartmentClasses()
    {
        return $this->hasMany('App\AcdGraduationDepartmentClass', 'Department_Id', 'Department_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function acdInternships()
    {
        return $this->hasMany('App\AcdInternship', 'Department_Id', 'Department_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function acdOfferedCourses()
    {
        return $this->hasMany('App\AcdOfferedCourse', 'Department_Id', 'Department_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function acdPrerequisites()
    {
        return $this->hasMany('App\AcdPrerequisite', 'Department_Id', 'Department_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function acdSchedRooms()
    {
        return $this->hasMany('App\AcdSchedRoom', 'Department_Id', 'Department_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function acdStudents()
    {
        return $this->hasMany('App\AcdStudent', 'Department_Id', 'Department_Id');
    }
}
