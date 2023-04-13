<?php 

class Users {
    private string $username;
    private string $password;
    private string $email;
    private string $avatar;
    private int $recoveryCode;
    private string $role = "User";

    private function __construct($username, $password, $email, $avatar)
    {
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
        $this->avatar = $avatar;
    }

}