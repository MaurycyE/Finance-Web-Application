<?php

namespace App\Models;

use PDO;

class User extends \Core\Model {

    public $errors = [];

    public function __construct($data = []) {

        foreach($data as $key => $value) {
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

            $stmt->bindValue(':name', $this->username, PDO::PARAM_STR);
            $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
            $stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);

            return $stmt->execute();
        }

        return false;
    }

    public function validate() {

        if($this->username == '') {
            $this->errors[] = 'Name is required';
        }

        if(filter_var($this->email, FILTER_VALIDATE_EMAIL) === false) {
            $this->errors[] = 'Invalid email';
        }

        if(static::emailExists($this->email)) {
            $this->errors[] = 'email already taken';
        }

        if(strlen($this->password)<6) {
            $this->errors[] = 'Please enter at least 6 characters for the password';
        }

        if(preg_match('/.*[a-z]+.*/i', $this->password) ==0 ) {
            $this->errors[] = 'Password needs at least one letter';
        }

        if(preg_match('/.*\d+.*/i', $this->password) == 0) {
            $this->errors[] = 'Password needs at least one number';
        }
    }

    public static function emailExists($email) {

        return static::findByEmail($email) !== false;
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

        $resultOfQuery = $db->query("SELECT id_users FROM users WHERE user_email='$this->email'");
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


}