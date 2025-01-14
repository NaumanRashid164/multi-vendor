<?php

namespace App\Enums;

enum ProductStatusEnum: string
{
    case Draft = "Draft";
    case Published = "Published";

    public static function labels(): array
    {
        return [
            self::Draft->value => "Draft",
            self::Published->value => "Published",
        ];
    }
    public static function colors()
    {
        return [
            "gray" => self::Draft->value,
            "success" => self::Published->value,
        ];
    }
}
