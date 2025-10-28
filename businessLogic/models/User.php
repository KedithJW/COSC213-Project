<?php

class User
{
    private $user_id; //PK 
    private $email; //UNIQUE
    private $f_name;
    private $l_name;
    
    private $phone;
    private $hashed_password; //CAN ONLY STORE HASHED PASS
    private $created_at;
   
    public function __construct($user_id, $email, $hashed_password, $created_at){
        $this->user_id = $user_id;
        $this->email = $email;
        $this->hashed_password = $hashed_password;
        $this->created_at = $created_at;
    }


    //Getters
    public function get_user_id(){
        return $this->user_id;
    }
    public function get_email(){
        return $this->email;
    }
 
    public function get_created_at(){
        return $this->created_at;
    } 
    
    public function get_phone(){
        return $this->phone;
    }


    public function get_full_name(){
      $first = trim(($this->f_name ?? ''));
      $last = trim(($this->l_name ?? ''));
      if($first === '' && $last === ''){
        return '';
      }else if($first === ''){
        return $last;
      }else if($last === ''){
        return $first;
      }else{
        return "$first $last";
      }
    }

    public function get_l_name(){
        return $this->trim(l_name);
    }

    public function get_f_name(){
        return $this->trim(f_name);
    }


    //Setters 

    public function set_email($email){
        $this->email = strtolower(trim($email));
    }

    public function set_phone($phone){
        $this->phone = $phone;
    }

    public function set_full_name($f_name, $l_name){
        $this->f_name = $f_name;
        $this->l_name = $l_name;
    }

}

?>