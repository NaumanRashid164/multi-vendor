<?php

namespace App\Enums;

enum PermissionsEnum: string
{
    case ApproveVendors = 'Approve Vendors';

    case SellProducts = 'Sell Products';
    case BuyProducts = 'Buy Products';
}
