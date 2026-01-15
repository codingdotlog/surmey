<?php
namespace App\Models;

use Core\{Model, Database};

final class Personal extends Model
{
    /**
     * Check the row is exists: true otherwise false via specific column and value
     */
    public static function exists($phone)
    {
        return Database::get()->select("id")
            ->from("personals")
            ->where("phone1", "=", $phone)
            ->or_where("phone2", "=", $phone)
            ->first();
    }

    public static function existsByEmail(string $email)
    {
        return Database::get()->select("id")
            ->from("personals")
            ->where("email", "=", $email)
            ->first();
    }
}
