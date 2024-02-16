<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $url_wsdl
 * @property string $user_wsdl
 * @property string $pass_wsdl
 * @property boolean $activate_synchronization
 * @property int $default_term_year
 * @property int $realization_term_year
 * @property string $separator_export
 */
class FeederConf extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'feeder_conf';

    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['url_wsdl', 'user_wsdl', 'pass_wsdl', 'activate_synchronization', 'default_term_year', 'realization_term_year', 'separator_export'];

}
