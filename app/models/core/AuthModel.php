<?php

namespace HS\app\models\core;

use HS\app\models\items\UserItem;
use HS\config\DBAccount;
use HS\libs\database\DB;
use HS\libs\helpers\Random;
use PDO;

class AuthModel extends DB
{
    public function __construct(DBAccount|DB|PDO $account = null)
    {
        parent::__construct($account ?? DBAccount::auth);
    }

    public function GetLogin(string $nick): ?UserItem
    {
        return $this->SelectOnly('CALL user_getLogin(?)', [$nick], UserItem::class);
    }

    public function SetPassword(string $user_id, string $new_pass): bool
    {
        return $this->SelectOnly('SELECT user_SetPassword(?, ?)', [$user_id, $new_pass]) === 1;
    }

    public function SetLastAccess(string $user_id): void
    {
        $this->Run('CALL user_SetLastAccess(?)', [$user_id]);
    }

    public function ExistNick(string $nick): bool
    {
        return $this->SelectOnly('SELECT user_ExistNick(?)', [$nick]) === 1;
    }

    public function ExistEmail(string $email): bool
    {
        return $this->SelectOnly('SELECT user_ExistEmail(?)', [$email]) === 1;
    }

    public function Register(string $nick, string $pass, string $email, string $first_name, string $last_name): void
    {
        $this->Run('CALL user_Register(:uid, :nick, :pass, :email, :fname, :lname)', [
            'uid' => Random::GetTextID(),
            'nick' => $nick,
            'pass' => $pass,
            'email' => $email,
            'fname' => $first_name,
            'lname' => $last_name
        ]);
    }

    public function GetCurrentPassword(string $user_id): ?string
    {
        return $this->SelectOnly('SELECT user_GetPassword(?)', [$user_id]);
    }

    public function ChangeGeneralInformation(string $user_id, string $first_name, string $last_name, string $nick, string $email): bool
    {
        return $this->SelectOnly('SELECT user_UpdateAccountInformation(?, ?, ?, ?, ?)', [$user_id, $first_name, $last_name, $nick, $email]) == 1;
    }

    public function ChangePassword(string $user_id, string $new_pass): bool
    {
        return $this->SelectOnly('SELECT user_SetPassword(?, ?)', [$user_id, $new_pass]) == 1;
    }
}