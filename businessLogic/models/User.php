<?php

declare(strict_types=1);
final class User
{
    private readonly int $user_id; //PK 
    private string $email; //UNIQUE
    private ?string $f_name = null;
    private ?string $l_name = null;
    
    private ?string $phone = null;
    private readonly string $hashed_password; //CAN ONLY STORE HASHED PASS
    private readonly DateTimeImmutable $created_at;
   
    public function __construct(int $user_id, string $email, string $hashed_password, DateTimeImmutable $created_at){
        $this->user_id = $user_id;
        $this->email = strtolower(trim($email));
        $this->hashed_password = $hashed_password;
        $this->created_at = $created_at;
    }


    //Getters
    public function getUserId(): int{
        return $this->user_id;
    }
    public function getEmail(): string{
        return $this->email;
    }
 
    public function getCreatedAt(): DateTimeImmutable {
        return $this->created_at;
    } 
    
    public function getPhone(): ?string{
        return $this->phone;
    }


    public function getFullName(): string{
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

    public function getLastName():string{
        return trim($this->l_name ?? '');
    }

    public function getFirstName(): string{
        return trim($this->f_name ?? '');
    }


    //Setters 

    public function setEmail(string $email): void{
        $this->email = strtolower(trim($email));
    }

    public function setPhone(?string $phone): void{
        $this->phone = $phone !== null ? trim($phone) : null;
    }

    public function setFullName(string $f_name, string $l_name): void{
        $this->f_name = trim($f_name);
        $this->l_name = trim($l_name);
    }

}

?>