<?php

include (ROOT.'/Utils/ImageUpload.php');

class CommentsController
{    
    use ImageUpload;
    
    public function actionIndex()
    {
        //get list of comments
        $commentsList = array();
        $commentsList = Comments::getCommentsList();        
 
        //Add comment if form submited
        if (isset($_POST['submit'])){  
            //check comment for errors
            $errors = $this->actionCheckComment();            
            
            //Add comment, if there are no errors
            if (!is_array($errors)){
                $result = $this->actionAddComment(); 
                //update comment list for view
                $commentsList = Comments::getCommentsList();
            }
        }
        
        require_once (ROOT.'/views/comments/index.php');
        return true;
    }
    
    /**
     * Add new comment in db
     * @return boolean
     */
    public function actionAddComment()
    {
        $comment['name'] = '';
        $comment['email'] = '';
        $comment['text'] = '';
        $comment['image'] = '';
        
        //add comment if form submited
        if (isset($_POST['submit'])){
            $comment['name'] = $_POST['name'];
            $comment['email'] = $_POST['email'];
            $comment['text'] = $_POST['text'];
            
            //if there is an image, upload it in /upload/images/
            if ($_FILES['image']['name']){
                $comment['image'] = '/upload/images/'.$this->upload($_FILES['image']);
            }
            
            //add comment in db and get id
            $id = Comments::addComment($comment);
            
            //clear form if comment added
            if ($id){
                if (isset($_POST['submit'])){
                    unset($_POST['submit']);
                }  
            }
        }        
        return true;
    }
    
    /**
     * Check comment for errors
     * @return boolean 
     * @return array array of errors
     */
    public function actionCheckComment()
    {
        //validate fields
        $errors = false;
        
        //check name
        if(!Comments::checkName($_POST['name'])){
            $errors[] = 'Имя не должно быть короче 2 символов';
        }
        
        //check email
        if(!Comments::checkEmail($_POST['email'])){
            $errors[] = 'Неправильно введен email';
        }     
        
        //if there is an image, check it
        if (isset($_FILES['image'])){
            if ($_FILES['image']['name']){
                //get erray of errors on image 
                $imageErrors = $this->actionCheckImage($_FILES);
                
                //merge errors from form fields and errors from image
                if (is_array($imageErrors) && is_array($errors)){
                $errors = array_merge($errors, $this->actionCheckImage());
                }
                elseif (is_array($imageErrors)){
                    $errors = $imageErrors;
                }
            }
        }
        
        //if there are an errors, retern array of errors
        if(isset($errors) && is_array($errors)){
            return $errors;            
        }
        else{
            return true;           
        }
    }
    
    /**
     * Chek if image is valid
     * @return boolean
     * @return array array of errors for image
     */
    public function actionCheckImage()
    {
        //set parametrs for validation
        $extensions = array('.png', '.gif', '.jpg');   
        $maxSize = 1024000;
        //get size of image
        $size = $_FILES['image']['size'];
        //get image extension
        $extension = strtolower(strrchr($_FILES['image']['name'], '.'));
        
        //validate image extansion
        $errors = false;        
        if(!in_array($extension, $extensions)){  
            $errors[] = 'Допустимо добовлять изображения только следующих форматов: png, gif, jpg';  
        }
        
        if($size >= $maxSize){  
            $errors[] = 'Изображение должно быть не больше 1 Мб';  
        }
        
        //return errors or true if there are no errors
        if(isset($errors) && is_array($errors)){
            return $errors;            
        }
        else{
            return true;           
        }
    }
    
    /**
     * Edit comment using Id
     * @param int $commentId
     * @return boolean
     */
    public function actionEditComment($commentId)
    {        
        if($commentId){    
            //get comment from DB by id
            $commentsItem = Comments::getCommentItemById($commentId);
            
            //Update comment if form submited and check for errors
            if (isset($_POST['submit'])){  
                $errors = $this->actionCheckComment();
                
                //Update comment if there are no errors
                if (!is_array($errors)){
                    $result = $this->actionUpdateComment($commentId);
                    //get new comment fom DB by id
                    $commentsItem = Comments::getCommentItemById($commentId);
                }
            }
            
            require_once (ROOT.'/views/comments/edit.php');             
            return true;
        }
    }
    
    /**
     * Update comment in DB
     * @param int $commentId
     * @return boolean
     */
    public function actionUpdateComment($commentId)
    {        
        $comment['id'] = $commentId;
        $comment['name'] = '';
        $comment['email'] = '';
        $comment['text'] = '';
        $comment['accepted'] = 0;
        
        //update comment, if form was submit
        if (isset($_POST['submit'])){
            //get form data in one array
            $comment['name'] = $_POST['name'];
            $comment['email'] = $_POST['email'];
            $comment['text'] = $_POST['text'];
            $comment['accepted'] = (isset($_POST['accepted']) ? true : false) ;
            
            //update comment in db and clear form
            if (Comments::UpdateComment($comment)){
                if (isset($_POST['submit'])){
                    unset($_POST['submit']);
                }   
            }
        }
        return true;
    }
    
    /**
     * Delete comment from DB by id
     * @param int $commentId
     */
    public function actionDeleteComment($commentId)
    {
        //Delete comment from DB by id
        Comments::deleteComment($commentId);
        
        //Relocate admin to list of comments
        header('Location: /comments');
    }
}
