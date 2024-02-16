<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $Faculty_Id
 * @property string $Faculty_Code
 * @property string $Faculty_Name
 * @property string $Faculty_Name_Eng
 * @property string $Faculty_Acronym
 * @property int $Dean_Id
 * @property int $Dean_1_Id
 * @property int $Dean_2_Id
 * @property int $Dean_3_Id
 * @property int $Secertary_Id
 * @property boolean $Order_Id
 * @property string $Created_By
 * @property string $Created_Date
 * @property string $Modified_By
 * @property string $Modified_Date
 * @property AcdFunctionalPositionTermYear[] $acdFunctionalPositionTermYears
 * @property MstrDepartment[] $mstrDepartments
 */
class MstrFaculty extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'mstr_faculty';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'Faculty_Id';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['Faculty_Code', 'Faculty_Name', 'Faculty_Name_Eng', 'Faculty_Acronym', 'Dean_Id', 'Dean_1_Id', 'Dean_2_Id', 'Dean_3_Id', 'Secertary_Id', 'Order_Id', 'Created_By', 'Created_Date', 'Modified_By', 'Modified_Date'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function acdFunctionalPositionTermYears()
    {
        return $this->hasMany('App\AcdFunctionalPositionTermYear', 'Faculty_Id', 'Faculty_Id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function mstrDepartments()
    {
        return $this->hasMany('App\MstrDepartment', 'Faculty_Id', 'Faculty_Id');
    }
}
