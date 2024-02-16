<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $Student_Id
 * @property string $Student_Code
 * @property string $Nim
 * @property integer $Register_Id
 * @property string $Register_Number
 * @property string $Full_Name
 * @property string $First_Title
 * @property string $Last_Title
 * @property boolean $Gender_Id
 * @property integer $Department_Id
 * @property integer $Class_Prog_Id
 * @property integer $Concentration_Id
 * @property integer $Class_Id
 * @property string $Birth_Place
 * @property string $Birth_Date
 * @property integer $Birth_Place_Id
 * @property integer $Birth_Country_Id
 * @property boolean $Citizenship_Id
 * @property integer $Entry_Period_Id
 * @property integer $Entry_Period_Type_Id
 * @property integer $Entry_Year_Id
 * @property boolean $Entry_Term_Id
 * @property integer $Register_Status_Id
 * @property boolean $Religion_Id
 * @property boolean $Marital_Status_Id
 * @property boolean $Job_Id
 * @property boolean $Blood_Id
 * @property boolean $High_School_Major_Id
 * @property integer $Nisn
 * @property string $Nik
 * @property boolean $Status_Id
 * @property string $Registration_Date
 * @property integer $Registration_Officer_Id
 * @property boolean $Source_Fund_Id
 * @property boolean $Read_Quran
 * @property float $Transport
 * @property string $Photo
 * @property boolean $Photo_Status
 * @property string $Student_Password
 * @property string $Parent_Password
 * @property integer $Hobby_Id
 * @property integer $Residence_Type_Id
 * @property integer $Transport_Type_Id
 * @property int $Kebutuhan_Khusus
 * @property string $Kk_Name
 * @property float $Recieve_Kps
 * @property string $Kps_Number
 * @property string $Completion_Date
 * @property string $Out_Date
 * @property string $Phone_Home
 * @property string $Phone_Mobile
 * @property string $Email_Corporate
 * @property string $Email_General
 * @property string $Rfid
 * @property string $Created_By
 * @property string $Created_Date
 * @property string $Modified_By
 * @property string $Modified_Date
 * @property MstrCountry $mstrCountry
 * @property MstrBloodType $mstrBloodType
 * @property MstrCitizenship $mstrCitizenship
 * @property MstrClass $mstrClass
 * @property MstrClassProgram $mstrClassProgram
 * @property MstrConcentration $mstrConcentration
 * @property MstrDepartment $mstrDepartment
 * @property MstrEntryPeriodType $mstrEntryPeriodType
 * @property MstrRegisterStatus $mstrRegisterStatus
 * @property MstrReligion $mstrReligion
 * @property AcdEducationHistory[] $acdEducationHistories
 * @property AcdGraduationBest[] $acdGraduationBests
 * @property AcdGraduationReg[] $acdGraduationRegs
 * @property AcdGraduationRegTemp[] $acdGraduationRegTemps
 * @property AcdInternship[] $acdInternships
 * @property AcdOfferedCourseExamMember[] $acdOfferedCourseExamMembers
 * @property AcdSchedReal[] $acdSchedReals
 * @property AcdStudentAddress[] $acdStudentAddresses
 * @property AcdStudentKr[] $acdStudentKrs
 * @property AcdStudentParent[] $acdStudentParents
 * @property AcdStudentSupervision[] $acdStudentSupervisions
 * @property AcdStudentVacation[] $acdStudentVacations
 * @property AcdThesi[] $acdTheses
 * @property AcdTranscript[] $acdTranscripts
 * @property AcdTranscriptFinal[] $acdTranscriptFinals
 * @property FncCostKrsPersonal[] $fncCostKrsPersonals
 * @property FncStudentCostKrsPersonal[] $fncStudentCostKrsPersonals
 */
class AcdStudent extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'acd_student';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'Student_Id';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['Student_Code', 'Nim', 'Register_Id', 'Register_Number', 'Full_Name', 'First_Title', 'Last_Title', 'Gender_Id', 'Department_Id', 'Class_Prog_Id', 'Concentration_Id', 'Class_Id', 'Birth_Place', 'Birth_Date', 'Birth_Place_Id', 'Birth_Country_Id', 'Citizenship_Id', 'Entry_Period_Id', 'Entry_Period_Type_Id', 'Entry_Year_Id', 'Entry_Term_Id', 'Register_Status_Id', 'Religion_Id', 'Marital_Status_Id', 'Job_Id', 'Blood_Id', 'High_School_Major_Id', 'Nisn', 'Nik', 'Status_Id', 'Registration_Date', 'Registration_Officer_Id', 'Source_Fund_Id', 'Read_Quran', 'Transport', 'Photo', 'Photo_Status', 'Student_Password', 'Parent_Password', 'Hobby_Id', 'Residence_Type_Id', 'Transport_Type_Id', 'Kebutuhan_Khusus', 'Kk_Name', 'Recieve_Kps', 'Kps_Number', 'Completion_Date', 'Out_Date', 'Phone_Home', 'Phone_Mobile', 'Email_Corporate', 'Email_General', 'Rfid', 'Created_By', 'Created_Date', 'Modified_By', 'Modified_Date'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mstrCountry()
    {
        return $this->belongsTo('App\MstrCountry', 'Birth_Country_Id', 'Country_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mstrBloodType()
    {
        return $this->belongsTo('App\MstrBloodType', 'Blood_Id', 'Blood_Type_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mstrCitizenship()
    {
        return $this->belongsTo('App\MstrCitizenship', 'Citizenship_Id', 'Citizenship_Id');
    }

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
    public function mstrConcentration()
    {
        return $this->belongsTo('App\MstrConcentration', 'Concentration_Id', 'Concentration_Id');
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
    public function mstrEntryPeriodType()
    {
        return $this->belongsTo('App\MstrEntryPeriodType', 'Entry_Period_Type_Id', 'Entry_Period_Type_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mstrRegisterStatus()
    {
        return $this->belongsTo('App\MstrRegisterStatus', 'Register_Status_Id', 'Register_Status_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mstrReligion()
    {
        return $this->belongsTo('App\MstrReligion', 'Religion_Id', 'Religion_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function acdEducationHistories()
    {
        return $this->hasMany('App\AcdEducationHistory', 'Student_Id', 'Student_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function acdGraduationBests()
    {
        return $this->hasMany('App\AcdGraduationBest', 'Student_Id', 'Student_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function acdGraduationRegs()
    {
        return $this->hasMany('App\AcdGraduationReg', 'Student_Id', 'Student_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function acdGraduationRegTemps()
    {
        return $this->hasMany('App\AcdGraduationRegTemp', 'Student_Id', 'Student_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function acdInternships()
    {
        return $this->hasMany('App\AcdInternship', 'Student_Id', 'Student_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function acdOfferedCourseExamMembers()
    {
        return $this->hasMany('App\AcdOfferedCourseExamMember', 'Student_Id', 'Student_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function acdSchedReals()
    {
        return $this->belongsToMany('App\AcdSchedReal', 'acd_sched_real_detail', 'Student_Id', 'Sched_Real_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function acdStudentAddresses()
    {
        return $this->hasMany('App\AcdStudentAddress', 'Student_Id', 'Student_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function acdStudentKrs()
    {
        return $this->hasMany('App\AcdStudentKr', 'Student_Id', 'Student_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function acdStudentParents()
    {
        return $this->hasMany('App\AcdStudentParent', 'Student_Id', 'Student_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function acdStudentSupervisions()
    {
        return $this->hasMany('App\AcdStudentSupervision', 'Student_Id', 'Student_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function acdStudentVacations()
    {
        return $this->hasMany('App\AcdStudentVacation', 'Student_Id', 'Student_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function acdTheses()
    {
        return $this->hasMany('App\AcdThesi', 'Student_Id', 'Student_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function acdTranscripts()
    {
        return $this->hasMany('App\AcdTranscript', 'Student_Id', 'Student_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function acdTranscriptFinals()
    {
        return $this->hasMany('App\AcdTranscriptFinal', 'Student_Id', 'Student_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function fncCostKrsPersonals()
    {
        return $this->hasMany('App\FncCostKrsPersonal', 'Student_Id', 'Student_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function fncStudentCostKrsPersonals()
    {
        return $this->hasMany('App\FncStudentCostKrsPersonal', 'Student_Id', 'Student_Id');
    }
}
