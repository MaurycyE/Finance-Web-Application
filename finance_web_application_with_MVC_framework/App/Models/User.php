<?php

namespace App\Models;

use PDO;
use \App\Token;
use \App\Config;
use \App\Mail;
use \Core\View;

class User extends \Core\Model {

    public $errors = [];

    public function __construct($data = []) {

        foreach($data as $key => $value) {

            if($key == "email") {
                $key = "user_email";
            }
            $this->$key = $value;
        };

    }

    public function save() {

        $this->validate();

        if(empty($this->errors)) {

            $password_hash = password_hash($this->password, PASSWORD_DEFAULT);

            $sql = 'INSERT INTO users (user_name, user_email, user_password)
            VALUES (:name, :email, :password_hash)';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':name', $this->user_name, PDO::PARAM_STR);
            $stmt->bindValue(':email', $this->user_email, PDO::PARAM_STR);
            $stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);

            return $stmt->execute();
        }

        return false;
    }

    public function validate() {

        if($this->user_name == '') {
            $this->errors[] = 'Nazwa jest wymagana';
        }

        if(filter_var($this->user_email, FILTER_VALIDATE_EMAIL) === false) {
            $this->errors[] = 'Nieprawidłowy email';
        }

        if(static::emailExists($this->user_email, $this->id_users ?? null)) {
            $this->errors[] = 'Email już zajęty';
        }

        if(strlen($this->password)<6) {
            $this->errors[] = 'Hasło musi mieć conajmniej 6 znaków';
        }

        if(preg_match('/.*[a-z]+.*/i', $this->password) ==0 ) {
            $this->errors[] = 'Hasło powinno mieć przynajmniej jedną literę';
        }

        if(preg_match('/.*\d+.*/i', $this->password) == 0) {
            $this->errors[] = 'Hasło powinno mieć przynajmniej jedną cyfrę';
        }
    }

    public static function emailExists($email, $ignore_id = null) {

        $user = static::findByEmail($email);

        if($user) {

            if($user->id_users != $ignore_id) {

                return true;
            }
        }

        return false;
    }

    public static function findByEmail($email) {

        $sql = 'SELECT *FROM users WHERE user_email = :email';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        return $stmt->fetch();
    }

    public static function authenticate($email, $password) {

        $user = static::findByEmail($email);

        if($user) {
            if(password_verify($password, $user->user_password)){
                return $user;
            }
        }

        return false;
    }

    public function writeDefaultCategoriesToNewUser($tableColumn, $tableWithDefaultValues, $targetTable)
    {
        $db = static::getDB();
        $userId = $this->findUserId();

        $resultOfQuery = $db->query("SELECT $tableColumn FROM $tableWithDefaultValues");
        $rowFromDatabase = $resultOfQuery->fetchAll();

        foreach ($rowFromDatabase as $defaultCategory) {
            $db->query("INSERT INTO $targetTable VALUES (NULL, '$userId', 
        '$defaultCategory[0]')");
        }
    }

    protected function findUserId() {

        $db = static::getDB();

        $resultOfQuery = $db->query("SELECT id_users FROM users WHERE user_email='$this->user_email'");
        $rowFromDatabase = $resultOfQuery->fetch();
        $userId = $rowFromDatabase['id_users'];

        return $userId;
    }

    public static function findByID($id) {

        $sql = 'SELECT * FROM users WHERE id_users = :id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        return $stmt->fetch();
    }


    public function rememberLogin() {

        $token = new Token();
        $hashed_token = $token->getHash();
        $this->remember_token = $token->getValue();

        $this->expiry_timestamp = time() +60*60*24*30; //30 days from now

        $sql = 'INSERT INTO remembered_logins (token_hash, user_id, expires_at)
        VALUES (:token_hash, :user_id, :expires_at)';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':token_hash', $hashed_token, PDO::PARAM_STR);
        $stmt->bindValue(':user_id', $this->id_users, PDO::PARAM_INT);
        $stmt->bindValue(':expires_at', date('Y-m-d H:i:s', $this->expiry_timestamp), PDO::PARAM_STR);

        return $stmt->execute();
    }

    public static function sendPasswordReset($email) {

        $user = static::findByEmail($email);

        if($user) {

            if($user->startPasswordReset()) {

                $user->sendPasswordResetEmail();
            }
        }
    }

    protected function startPasswordReset() {

        $token = new Token();
        $hashed_token = $token->getHash();
        $this->password_reset_token = $token->getValue();

        $expiry_timestamp = time() +60 * 60 * 2; // 2 hours from now

        $sql = 'UPDATE users SET password_reset_hash = :token_hash, password_reset_expiry = :expires_at
        WHERE id_users = :id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':token_hash', $hashed_token, PDO::PARAM_STR);
        $stmt->bindValue(':expires_at', date('Y-m-d H:i:s', $expiry_timestamp), PDO::PARAM_STR);
        $stmt->bindValue(':id', $this->id_users, PDO::PARAM_INT);

        return $stmt->execute();
    }

    protected function sendPasswordResetEmail() {

        $url = 'http://' . $_SERVER['HTTP_HOST'] . 
        Config::PATH_TO_MAIN_FOLDER . '?password/reset/' . $this->password_reset_token;

        $text = View::getTemplate('PasswordRestore/reset_email_plain_text.txt', ['url' => $url]);
        $html = View::getTemplate('PasswordRestore/reset_email.html', ['url' => $url]);

        Mail::send($this->user_email, 'Resetowanie hasła', $text, $html);
    }

    public static function findByPasswordReset($token) {

        $token = new Token($token);
        $hashed_token = $token->getHash();

        $sql = 'SELECT * FROM users WHERE password_reset_hash = :token_hash';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':token_hash', $hashed_token, PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        $user = $stmt->fetch();

        if($user) {

            if(strtotime($user->password_reset_expiry) > time()) {

                return $user;
            }
        }
    }

    public function resetPassword($password) {

        $this->password = $password;

        $this->validate();

        if(empty($this->errors)) {

            $password_hash = password_hash($this->password, PASSWORD_DEFAULT);

            $sql = 'UPDATE users SET user_password = :password_hash, password_reset_hash = NULL,
            password_reset_expiry = NULL WHERE id_users = :id';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':id', $this->id_users, PDO::PARAM_INT);
            $stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);

            return $stmt->execute();
        }

        return false;
    }

}