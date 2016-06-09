<?php

class Admin
{
    /**
     * Login user into Session
     * @param int $user_id
     */
    public static function login($user_id)
    {
        $_SESSION['user'] = $user_id;
    }
    
    /**
     * Check if user already Logged In
     * @return int
     */
    public static function checkLogged()
    {
        //If there is a session, return user id
        if (isset($_SESSION['user'])){
            return $_SESSION['user'];
        }
        
        //Relocate user to the login page
        header("Location: /login");
    }
    
    /**
     * Check if user have admin privilege
     * @return boolean
     */
    public static function isAdmin()
    {
        //If there is a session user id = 1, return user id
        if (isset($_SESSION['user']) && ($_SESSION['user'] == 1)){
            return true;
        }        
        return false;
    }
    
    /**
     * Check if there is a user in DB 
     * with specified username and password 
     * @param string $name
     * @param string $password
     * @return boolean
     */
    public static function checkUserData($name, $password)
    {
        $db = Db::getConnection();        
        
        $sql = 'SELECT * FROM users WHERE name = :name and password = :password';
        
        $result = $db->prepare($sql);
        $result->bindParam(':name', $name, PDO::PARAM_STR);
        $result->bindParam(':password', $password, PDO::PARAM_STR);
        $result->execute();
        
        //If user id was find, return user id
        $user = $result->fetch();
        if($user){
            return $user['id'];
        }
        
        return false;
    }
    
    /**
     * Check if username is valid 
     * @param string $name
     * @return boolean
     */
    public static function checkName($name)
    {
        if (strlen($name)>=2){
            return true;
        }
        else{
            return false;
        }
    }
    
    /**
     * Check if password is valid 
     * @param string $password
     * @return boolean
     */
    public static function checkPassword($password)
    {
        if (strlen($password)>=3){
            return true;
        }
        else{
            return false;
        }
    }
}

