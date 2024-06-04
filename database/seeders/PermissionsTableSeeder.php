<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            [
                'id'    => 1,
                'title' => 'user_management_access',
            ],
            [
                'id'    => 2,
                'title' => 'permission_create',
            ],
            [
                'id'    => 3,
                'title' => 'permission_edit',
            ],
            [
                'id'    => 4,
                'title' => 'permission_show',
            ],
            [
                'id'    => 5,
                'title' => 'permission_delete',
            ],
            [
                'id'    => 6,
                'title' => 'permission_access',
            ],
            [
                'id'    => 7,
                'title' => 'role_create',
            ],
            [
                'id'    => 8,
                'title' => 'role_edit',
            ],
            [
                'id'    => 9,
                'title' => 'role_show',
            ],
            [
                'id'    => 10,
                'title' => 'role_delete',
            ],
            [
                'id'    => 11,
                'title' => 'role_access',
            ],
            [
                'id'    => 12,
                'title' => 'user_create',
            ],
            [
                'id'    => 13,
                'title' => 'user_edit',
            ],
            [
                'id'    => 14,
                'title' => 'user_show',
            ],
            [
                'id'    => 15,
                'title' => 'user_delete',
            ],
            [
                'id'    => 16,
                'title' => 'user_access',
            ],
            [
                'id'    => 17,
                'title' => 'audit_log_show',
            ],
            [
                'id'    => 18,
                'title' => 'audit_log_access',
            ],
            [
                'id'    => 19,
                'title' => 'cm_access',
            ],
            [
                'id'    => 20,
                'title' => 'company_create',
            ],
            [
                'id'    => 21,
                'title' => 'company_edit',
            ],
            [
                'id'    => 22,
                'title' => 'company_show',
            ],
            [
                'id'    => 23,
                'title' => 'company_delete',
            ],
            [
                'id'    => 24,
                'title' => 'company_access',
            ],
            [
                'id'    => 25,
                'title' => 'company_user_create',
            ],
            [
                'id'    => 26,
                'title' => 'company_user_edit',
            ],
            [
                'id'    => 27,
                'title' => 'company_user_show',
            ],
            [
                'id'    => 28,
                'title' => 'company_user_delete',
            ],
            [
                'id'    => 29,
                'title' => 'company_user_access',
            ],
            [
                'id'    => 30,
                'title' => 'lead_channel_create',
            ],
            [
                'id'    => 31,
                'title' => 'lead_channel_edit',
            ],
            [
                'id'    => 32,
                'title' => 'lead_channel_show',
            ],
            [
                'id'    => 33,
                'title' => 'lead_channel_delete',
            ],
            [
                'id'    => 34,
                'title' => 'lead_channel_access',
            ],
            [
                'id'    => 35,
                'title' => 'product_service_create',
            ],
            [
                'id'    => 36,
                'title' => 'product_service_edit',
            ],
            [
                'id'    => 37,
                'title' => 'product_service_show',
            ],
            [
                'id'    => 38,
                'title' => 'product_service_delete',
            ],
            [
                'id'    => 39,
                'title' => 'product_service_access',
            ],
            [
                'id'    => 40,
                'title' => 'lead_status_create',
            ],
            [
                'id'    => 41,
                'title' => 'lead_status_edit',
            ],
            [
                'id'    => 42,
                'title' => 'lead_status_show',
            ],
            [
                'id'    => 43,
                'title' => 'lead_status_delete',
            ],
            [
                'id'    => 44,
                'title' => 'lead_status_access',
            ],
            [
                'id'    => 45,
                'title' => 'main_crm_access',
            ],
            [
                'id'    => 46,
                'title' => 'lead_conversion_create',
            ],
            [
                'id'    => 47,
                'title' => 'lead_conversion_edit',
            ],
            [
                'id'    => 48,
                'title' => 'lead_conversion_show',
            ],
            [
                'id'    => 49,
                'title' => 'lead_conversion_delete',
            ],
            [
                'id'    => 50,
                'title' => 'lead_conversion_access',
            ],
            [
                'id'    => 51,
                'title' => 'lead_create',
            ],
            [
                'id'    => 52,
                'title' => 'lead_edit',
            ],
            [
                'id'    => 53,
                'title' => 'lead_show',
            ],
            [
                'id'    => 54,
                'title' => 'lead_delete',
            ],
            [
                'id'    => 55,
                'title' => 'lead_access',
            ],
            [
                'id'    => 56,
                'title' => 'profile_password_edit',
            ],
        ];

        Permission::insert($permissions);
    }
}
