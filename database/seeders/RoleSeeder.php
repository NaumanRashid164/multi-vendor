<?php

namespace Database\Seeders;

use App\Enums\PermissionsEnum;
use App\Enums\RolesEnum;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $adminRole = Role::create(['name' => RolesEnum::Admin->value]);
        $vendorRole = Role::create(['name' => RolesEnum::Vendor->value]);
        $userRole = Role::create(['name' => RolesEnum::User->value]);

        $approveVendor = Permission::create(['name' => PermissionsEnum::ApproveVendors->value]);
        $buyProducts = Permission::create(['name' => PermissionsEnum::BuyProducts->value]);
        $sellProducts = Permission::create(['name' => PermissionsEnum::SellProducts->value]);

        $adminRole->syncPermissions([$approveVendor, $buyProducts, $sellProducts]);
        $vendorRole->syncPermissions([$approveVendor, $sellProducts]);
        $userRole->syncPermissions([$buyProducts]);
    }
}
