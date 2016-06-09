<?php

class Comments
{
    /**
     * Get list of comments
     * @return array
     */
    public static function getCommentsList(){
        
        //get connection
        $db = Db::getConnection();
        
        $commentsList = array();  
        
        //if param for sorting was submited, then sort comments list
        if (isset($_GET['sort'])){
            $sort = $_GET['sort'];                    
        }
        //else sort by date
        else {
            $sort = 'date DESC';
        }
        
        //get list of comments
        $result = $db->query('SELECT * '
                            . 'FROM comments '
                            . 'ORDER BY '.$sort);
        
        $i=0;
        while ($row = $result->fetch()){
            $commentsList[$i]['id'] = $row['id'];
            $commentsList[$i]['name'] = $row['name'];
            $commentsList[$i]['date'] = $row['date'];
            $commentsList[$i]['email'] = $row['email'];
            $commentsList[$i]['text'] = $row['text'];
            $commentsList[$i]['image'] = $row['image'];
            $commentsList[$i]['changed_by_admin'] = $row['changed_by_admin'];
            $commentsList[$i]['accepted'] = $row['accepted'];
            $i++;            
        }        
        return $commentsList;
    }
    
    /**
     * Returns single comment item with specified id
     * @param int $id
     * @return array one comment
     */
    public static function getCommentItemById($id)
    {
        $id = intval($id);
        //if there is an id then get comment
        if($id){
            //get DB connection           
            $db = Db::getConnection();
            
            //get comment
            $result = $db->query('SELECT * FROM comments WHERE id ='.$id);
            $result->setFetchMode(PDO::FETCH_ASSOC);            
            $commentsItem = $result->fetch();
            
            return $commentsItem;
        }
    }
    
    /**
     * Get next id that will be add in table comments
     * @return int
     */
    public static function getLastCommentId()
    {
        //get connection
        $db = Db::getConnection();
        
        //get info about table of comments in DB
        $result = $db->query('SHOW TABLE STATUS LIKE "comments"');
        $result->setFetchMode(PDO::FETCH_ASSOC);        
        $row = $result->fetch();   
        
        //Get next id that will be add in comment table
        $id = $row['Auto_increment']; 

        return $id;
    }
    
    /**
     * Check length of author name
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
     * Check if email is valid
     * @param string $email
     * @return boolean
     */
    public static function checkEmail($email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)){
            return true;
        }
        else{
            return false;
        }
    }
    
    /**
     * Insert new comment in comments table 
     * @param array $comment
     * @return boolean
     */
    public static function addComment($comment)
    {
        $db = Db::getConnection();        
        
        $sql = 'INSERT INTO comments (name, email, text, image) '
                . 'VALUES (:name, :email, :text, :image)';
        
        $result = $db->prepare($sql);
        $result->bindParam(':name', $comment['name'], PDO::PARAM_STR);
        $result->bindParam(':email', $comment['email'], PDO::PARAM_STR);
        $result->bindParam(':text', $comment['text'], PDO::PARAM_STR);
        $result->bindParam(':image', $comment['image'], PDO::PARAM_STR);
        
        if ($result->execute())
        {
            return $db->lastInsertId();
        }
        
        return false;
    }
    
    /**
     * Update comment in comments table by id
     * @param array $editedComment
     * @return boolean
     */
    public static function updateComment($editedComment)
    {
        //get comment from db by id
        $comment = Comments::getCommentItemById($editedComment['id']);
       
        //get connection
        $db = Db::getConnection();        
        
        //prepare query to DB
        $sql = 'UPDATE comments '
                . 'SET '
                . 'name = :name, '
                . 'email = :email, '
                . 'text = :text, '
                . 'accepted = :accepted, '
                . 'changed_by_admin = :changed_by_admin '
                . 'WHERE id = :id';
        
        $result = $db->prepare($sql);  
        
        //Bind sql placeholders whith value of edited comment 
        $result->bindParam(':id', $editedComment['id'], PDO::PARAM_INT);
        $result->bindParam(':name', $editedComment['name'], PDO::PARAM_STR);
        $result->bindParam(':email', $editedComment['email'], PDO::PARAM_STR);
        $result->bindParam(':text', $editedComment['text'], PDO::PARAM_STR);
        
        //if comment was accepted by admin, then add mark as accepted in comments table
        $editedComment['accepted'] = (isset($editedComment['accepted']) && $editedComment['accepted'] == true);
        $result->bindParam(':accepted', $editedComment['accepted'], PDO::PARAM_BOOL);
        
        //compare comment from DB and comment from Form($_POST)
        //If comment was change, then add mark to comment
        $editedComment['changed_by_admin'] = Comments::isChangedByAdmin($comment, $editedComment);
        $result->bindParam(':changed_by_admin', $editedComment['changed_by_admin'], PDO::PARAM_BOOL);
        
        if($result->execute()){
            return true;
        }
    }    
    
    /**
     * Compare comment from db and comment from Form($_POST)
     * @param array $comment
     * @param array $editedComment
     * @return boolean
     */
    public static function isChangedByAdmin($comment, $editedComment)
    {
        //if already chenged by admin, return true
        if ($comment['changed_by_admin']) {
            return true;
        }
        //If not compare every field
        //If equal, return true, 
        //if not return false 
        return ($editedComment['name'] != $comment['name'])
        || ($editedComment['text'] != $comment['text'])
        || ($editedComment['email'] != $comment['email']);
    }
    
    /**
     * Delete comment from table by Id
     * @param int $commentId
     * @return boolean
     */
    public static function deleteComment($commentId)
    {
        $db = Db::getConnection();        
        
        $sql = 'DELETE FROM comments WHERE id = :id';        
        $result = $db->prepare($sql);          
        $result->bindParam(':id', $commentId, PDO::PARAM_INT);        
        
        if($result->execute()){
            return true;
        }
    }
}
