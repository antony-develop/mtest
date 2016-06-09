<?php

class AdminController
{
    /**
     * Login user, start session
     * @return boolean
     */
    public function actionLogin() 
    {
        $name = '';
        $password = '';
        
        //if form submited, then login
        if (isset($_POST['submit'])){
            $name = $_POST['name'];
            $password = $_POST['password'];
        
        
            //validate fields
            $errors = false;

            if(!Admin::checkName($name)){
                $errors[] = 'Имя не должно быть короче 2 символов';
            }

            if(!Admin::checkPassword($password)){
                $errors[] = 'Пароль не должен быть короче 3 символов';
            }
        
            //Check if usrer exists
            $user_id = Admin::checkUserData($name, $password);

            if($user_id == false){
                //if user data wrong, then show an error
                $errors[] = 'Неправильные данные для входа на сайт'; 
            }
            else{
                //If user data exists in db, then remember the user
                Admin::login($user_id);

                //relocate user to the comments section
                header("location: /comments/");
            }        
        }
        
        require_once(ROOT . '/views/admin/login.php');        
        return true;
    }
    
    
    /**
     * Logout user, close session
     * relocate to list of comments
     */
    public function actionLogout()
    {
        unset($_SESSION['user']);
        header("Location: /comments");
    }
    
}

