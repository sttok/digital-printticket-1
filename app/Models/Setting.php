<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'app_name',
        'email',
        'logo',
        'favicon',
        'logo_oscuro',
        'fondo_digital',
        'map_key',        
        'currency',
        'timezone',
        'footer_copyright',
        'user_verify',
        'verify_by',
        'default_lat',
        'default_long',
        'push_notification',
        'onesignal_app_id',
        'onesignal_project_number',
        'onesignal_api_key',
        'onesignal_auth_key',
        'or_onesignal_app_id',
        'or_onesignal_project_number',
        'or_onesignal_api_key',
        'or_onesignal_auth_key',
        'mail_notification',    
        'mail_host',
        'mail_port',
        'mail_username',
        'mail_password',
        'sender_email',
        'sms_notification',
        'labsmobile_account',
        'labsmobile_token',
        'contador_sms',
        'help_center',
        'privacy_policy',
        'cookie_policy',
        'terms_services',
        'acknowledgement',  
        'primary_color',    
        'app_version',  
        'privacy_policy_organizer',
        'terms_use_organizer',
        'license_key',
        'license_name',
        'license_status',
        'org_commission_type',
        'org_commission',
        'secondary_color'
    ];

    protected $table = 'general_settng';

    
}
