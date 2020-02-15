<?php
/*
TODO:
1. check with sir if this is the right way to do it... improtant!!!
2. what if the user wants to re-register, so change uname & email check mechanisms
3. username & password check (for login);
*/
require "dbConn.php";

Class User{
    public $user_id;
    public $user_uname;
    public $user_pass;
    public $user_fname;
    public $user_lname;
    public $user_email;
    public $user_status; //registered,suspended or not

    function __construct($uname, $fname, $lname, $email){
        $this->user_id = null; //value is not set until registered
        $this->user_uname = $uname;
        $this->user_fname = $fname;
        $this->user_lname = $lname;
        $this->user_email = $email;
        $this->user_status = "unregistered"; //sets the intial value as unregistered
    }

    function __construct($param, $value){ 
        //finds user and sets values
        //param values could be id, username, email

        $sql = "SELECT *, pass AS NULL FROM users WHERE $param = $value";
        $result = $dbConn->query($sql);
        
        if($result->num_rows > 0){
            $row = $result->fetch_assoc();
            $row = $row[0];

            $this->user_id = $row['id']; 
            $this->user_uname = $row['uname'];
            $this->user_fname = $row['fname'];
            $this->user_lname = $row['lname'];
            $this->user_email = $row['email'];
            $this->user_status = $row['status'];
        }
    }

    function register(){
        $sql = "INSERT INTO users(uname,fname, lname, email, status) VALUES($this->user_uname, $this->user_fname, $this->user_lname, $this->user_email,'registered')";

        if($dbConn->query($sql)){
            $this->user_id = $result->insert_id;
            $this->user_status = "registered"; //registered since registration successful
            return true;
        }else{
            return false;
        }
    }

    function unregister(){ //unregister a user, similar to deleting a user
        if($this->user_id===null){
            return false; //user not registered atleast once, user record does't exist in Database
        }else{
            $this->user_status = "unregistered";
            $this->update();
        }
    }

    function suspend(){ //suspend a user
        if($this->user_id===null){
            return false; //user not registered atleast once, user record does't exist in Database
        }else{
            $this->user_status = "suspended";
            $this->update();
        }
    }

    function update(){
        if($this->user_id===null){
            return false; //user not registered atleast once, user record does't exist in Database
        }else{
            if($this->count('uname', $this->user_uname)>1){
                return false; //another record exists with the same username apart from this record
            }else if($this->count('email', $this->user_email)>1){
                return false; //another record exists with the same email apart from this record
            }else{
                $sql = "UPDATE users SET uname=$this->user_uname, fname=$this->user_fname, lname=$this->user_lname, email=$this->user_email, status=$this->user_status WHERE id $this->user_id";
            
                if($result->query($sql))
                    return true;
                else
                    return false;
            }            
        }        
    }

    function count($param, $value){ //returns the parameter parsed 
        $sql = "SELECT id FROM users WHERE $param = $value";
        $result = $dbConn->query($sql);
        
        return $result->num_rows
    }

}