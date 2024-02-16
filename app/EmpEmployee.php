<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $Employee_Id
 * @property string $Nik
 * @property string $Nip
 * @property string $Name
 * @property string $First_Title
 * @property string $Last_Title
 * @property string $Full_Name
 * @property string $Birth_Place
 * @property string $Birth_Date
 * @property string $Address
 * @property boolean $Gender_Id
 * @property boolean $Religion_Id
 * @property int $Identity_Type_Id
 * @property string $Identity_Number
 * @property int $Bank_Id
 * @property string $Rec_Num
 * @property string $Phone_Mobile
 * @property string $Phone_Home
 * @property boolean $Employee_Status_Id
 * @property boolean $Blood_Type_Id
 * @property boolean $Marital_Status_Id
 * @property string $Nbm
 * @property string $Nidn
 * @property string $Email_General
 * @property string $Email_Corporate
 * @property string $Role
 * @property boolean $Active_Status_Id
 * @property string $Npwp
 * @property string $Nik_Salary
 * @property string $Photos
 * @property string $Password
 * @property string $Nik_Finger_Print
 * @property int $Fingerprint_Id
 * @property string $Document_Serdos
 * @property string $Document_Serdos_Ext
 * @property int $Work_Unit_Id
 * @property integer $Department_Id
 * @property string $Employee_Role
 * @property string $Forum_Role
 * @property string $Payroll_Role
 * @property int $internal_eksternal
 * @property string $Rfid
 * @property int $Card_Accepted
 * @property string $Nbm_Document
 * @property string $Identity_Document
 * @property string $Created_By
 * @property string $Created_Date
 * @property string $Modified_By
 * @property string $Modified_Date
 * @property EmpActiveStatus $empActiveStatus
 * @property EmpBank $empBank
 * @property EmpEmployeeStatus $empEmployeeStatus
 * @property EmpIdentityType $empIdentityType
 * @property EmpWorkUnit $empWorkUnit
 * @property MstrBloodType $mstrBloodType
 * @property MstrDepartment $mstrDepartment
 * @property MstrGender $mstrGender
 * @property MstrMaritalStatus $mstrMaritalStatus
 * @property MstrReligion $mstrReligion
 * @property AcdCourseLecturer[] $acdCourseLecturers
 * @property AcdDepartmentLecturer[] $acdDepartmentLecturers
 * @property AcdFunctionalPositionTermYear[] $acdFunctionalPositionTermYears
 * @property AcdOfferedCourseLecturer[] $acdOfferedCourseLecturers
 * @property AcdSchedReal[] $acdSchedReals
 * @property AcdStudentSupervision[] $acdStudentSupervisions
 * @property AcdThesi[] $acdTheses
 * @property AcdThesi[] $acdTheses
 * @property AcdThesi[] $acdTheses
 * @property AcdThesi[] $acdTheses
 * @property AcdThesi[] $acdTheses
 * @property AcdThesi[] $acdTheses
 * @property EmpCertificateCompetence[] $empCertificateCompetences
 * @property EmpDedicationMember[] $empDedicationMembers
 * @property EmpEmployeeCuti[] $empEmployeeCutis
 * @property EmpEmployeeEducation[] $empEmployeeEducations
 * @property EmpEmployeeFamily[] $empEmployeeFamilies
 * @property EmpEmployeeFunctional[] $empEmployeeFunctionals
 * @property EmpEmployeeGolru[] $empEmployeeGolrus
 * @property EmpFellowship[] $empFellowships
 * @property EmpGeneralActivity[] $empGeneralActivities
 * @property EmpHandbookMember[] $empHandbookMembers
 * @property EmpJournalMember[] $empJournalMembers
 * @property EmpKuisionerVisiMisi[] $empKuisionerVisiMisis
 * @property EmpOrganizationProfessional[] $empOrganizationProfessionals
 * @property EmpOtherActivity[] $empOtherActivities
 * @property EmpPatentMember[] $empPatentMembers
 * @property EmpPlacement[] $empPlacements
 * @property EmpPrecence[] $empPrecences
 * @property EmpPublication[] $empPublications
 * @property EmpResearchMember[] $empResearchMembers
 * @property EmpSocialActivity[] $empSocialActivities
 * @property EmpTextbookMember[] $empTextbookMembers
 * @property EmpTraining[] $empTrainings
 * @property EmpVisitingExpert[] $empVisitingExperts
 * @property EmpWorkshop[] $empWorkshops
 */
class EmpEmployee extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'emp_employee';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'Employee_Id';

    /**
     * @var array
     */
    protected $fillable = ['Nik', 'Nip', 'Name', 'First_Title', 'Last_Title', 'Full_Name', 'Birth_Place', 'Birth_Date', 'Address', 'Gender_Id', 'Religion_Id', 'Identity_Type_Id', 'Identity_Number', 'Bank_Id', 'Rec_Num', 'Phone_Mobile', 'Phone_Home', 'Employee_Status_Id', 'Blood_Type_Id', 'Marital_Status_Id', 'Nbm', 'Nidn', 'Email_General', 'Email_Corporate', 'Role', 'Active_Status_Id', 'Npwp', 'Nik_Salary', 'Photos', 'Password', 'Nik_Finger_Print', 'Fingerprint_Id', 'Document_Serdos', 'Document_Serdos_Ext', 'Work_Unit_Id', 'Department_Id', 'Employee_Role', 'Forum_Role', 'Payroll_Role', 'internal_eksternal', 'Rfid', 'Card_Accepted', 'Nbm_Document', 'Identity_Document', 'Created_By', 'Created_Date', 'Modified_By', 'Modified_Date'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function empActiveStatus()
    {
        return $this->belongsTo('App\EmpActiveStatus', 'Active_Status_Id', 'Active_Status_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function empBank()
    {
        return $this->belongsTo('App\EmpBank', 'Bank_Id', 'Bank_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function empEmployeeStatus()
    {
        return $this->belongsTo('App\EmpEmployeeStatus', 'Employee_Status_Id', 'Employee_Status_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function empIdentityType()
    {
        return $this->belongsTo('App\EmpIdentityType', 'Identity_Type_Id', 'Identity_Type_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function empWorkUnit()
    {
        return $this->belongsTo('App\EmpWorkUnit', 'Work_Unit_Id', 'Work_Unit_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mstrBloodType()
    {
        return $this->belongsTo('App\MstrBloodType', 'Blood_Type_Id', 'Blood_Type_Id');
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
    public function mstrGender()
    {
        return $this->belongsTo('App\MstrGender', 'Gender_Id', 'Gender_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mstrMaritalStatus()
    {
        return $this->belongsTo('App\MstrMaritalStatus', 'Marital_Status_Id', 'Marital_Status_Id');
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
    public function acdCourseLecturers()
    {
        return $this->hasMany('App\AcdCourseLecturer', 'Employee_Id', 'Employee_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function acdDepartmentLecturers()
    {
        return $this->hasMany('App\AcdDepartmentLecturer', 'Employee_Id', 'Employee_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function acdFunctionalPositionTermYears()
    {
        return $this->hasMany('App\AcdFunctionalPositionTermYear', 'Employee_Id', 'Employee_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function acdOfferedCourseLecturers()
    {
        return $this->hasMany('App\AcdOfferedCourseLecturer', 'Employee_Id', 'Employee_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function acdSchedReals()
    {
        return $this->hasMany('App\AcdSchedReal', 'Employee_Id', 'Employee_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function acdStudentSupervisions()
    {
        return $this->hasMany('App\AcdStudentSupervision', 'Employee_Id', 'Employee_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function acdTheses1()
    {
        return $this->hasMany('App\AcdThesi', 'Supervisor_1', 'Employee_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function acdTheses2()
    {
        return $this->hasMany('App\AcdThesi', 'Supervisor_2', 'Employee_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function acdTheses3()
    {
        return $this->hasMany('App\AcdThesi', 'Supervisor_3', 'Employee_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function acdTheses4()
    {
        return $this->hasMany('App\AcdThesi', 'Examiner_2', 'Employee_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function acdTheses5()
    {
        return $this->hasMany('App\AcdThesi', 'Examiner_1', 'Employee_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function acdTheses6()
    {
        return $this->hasMany('App\AcdThesi', 'Examiner_3', 'Employee_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function empCertificateCompetences()
    {
        return $this->hasMany('App\EmpCertificateCompetence', 'Employee_Id', 'Employee_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function empDedicationMembers()
    {
        return $this->hasMany('App\EmpDedicationMember', 'Employee_Id', 'Employee_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function empEmployeeCutis()
    {
        return $this->hasMany('App\EmpEmployeeCuti', 'Employee_Id', 'Employee_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function empEmployeeEducations()
    {
        return $this->hasMany('App\EmpEmployeeEducation', 'Employee_Id', 'Employee_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function empEmployeeFamilies()
    {
        return $this->hasMany('App\EmpEmployeeFamily', 'Employee_Id', 'Employee_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function empEmployeeFunctionals()
    {
        return $this->hasMany('App\EmpEmployeeFunctional', 'Employee_Id', 'Employee_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function empEmployeeGolrus()
    {
        return $this->hasMany('App\EmpEmployeeGolru', 'Employee_Id', 'Employee_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function empFellowships()
    {
        return $this->hasMany('App\EmpFellowship', 'Employee_Id', 'Employee_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function empGeneralActivities()
    {
        return $this->hasMany('App\EmpGeneralActivity', 'Employee_Id', 'Employee_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function empHandbookMembers()
    {
        return $this->hasMany('App\EmpHandbookMember', 'Employee_Id', 'Employee_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function empJournalMembers()
    {
        return $this->hasMany('App\EmpJournalMember', 'Employee_Id', 'Employee_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function empKuisionerVisiMisis()
    {
        return $this->hasMany('App\EmpKuisionerVisiMisi', 'Employee_Id', 'Employee_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function empOrganizationProfessionals()
    {
        return $this->hasMany('App\EmpOrganizationProfessional', 'Employee_Id', 'Employee_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function empOtherActivities()
    {
        return $this->hasMany('App\EmpOtherActivity', 'Employee_Id', 'Employee_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function empPatentMembers()
    {
        return $this->hasMany('App\EmpPatentMember', 'Employee_Id', 'Employee_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function empPlacements()
    {
        return $this->hasMany('App\EmpPlacement', 'Employee_Id', 'Employee_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function empPrecences()
    {
        return $this->hasMany('App\EmpPrecence', 'Employee_Id', 'Employee_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function empPublications()
    {
        return $this->hasMany('App\EmpPublication', 'Employee_Id', 'Employee_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function empResearchMembers()
    {
        return $this->hasMany('App\EmpResearchMember', 'Employee_Id', 'Employee_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function empSocialActivities()
    {
        return $this->hasMany('App\EmpSocialActivity', 'Employee_Id', 'Employee_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function empTextbookMembers()
    {
        return $this->hasMany('App\EmpTextbookMember', 'Employee_Id', 'Employee_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function empTrainings()
    {
        return $this->hasMany('App\EmpTraining', 'Employee_Id', 'Employee_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function empVisitingExperts()
    {
        return $this->hasMany('App\EmpVisitingExpert', 'Employee_Id', 'Employee_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function empWorkshops()
    {
        return $this->hasMany('App\EmpWorkshop', 'Employee_Id', 'Employee_Id');
    }
}
