<?php

namespace Y2thek\PhpMvcframeworkCore\db;

use Y2thek\PhpMvcframeworkCore\Model;
use Y2thek\PhpMvcframeworkCore\Application;


abstract class DbModel extends Model
{
     abstract public static function tableName(): string;


     abstract public function attributes() : array;

     abstract public static function primaryKey() : string;

     public function save(){
          try {
               $tableName = $this->tableName();

               $attributes = $this->attributes();
     
               $params = array_map(fn($attr) => ":$attr",$attributes);
     
               $statement = self::prepare("
               INSERT INTO $tableName (". implode(',',$attributes) .")
               VALUES (". implode(',',$params) .") 
               ");
     
               foreach($attributes as $attribute){
                    $statement->bindValue(":$attribute",$this->$attribute);
               }
     
               $statement->execute();
     
               return true;
          } catch (\Throwable $e) {
               echo '<pre>';
               var_dump($e);
               echo '</pre>';
               exit;
          }
       
     }

     public static function prepare($sql){

          return Application::$app->db->pdo->prepare($sql);
     }

     public static function findOne($where){ // ['email' => 'y2k@gmail.com','firstname' => 'ye Yint']
         
          $tableName = static::tableName();

          $attributes = array_keys($where);

          //SELECT * FROM $tableName WHERE email = :email AND firstname = :firstname

          $sql = implode("AND ",array_map(fn($attr) => "$attr = :$attr",$attributes));

          try {
               $statement = self::prepare("SELECT * FROM $tableName WHERE $sql");
               foreach ($where as $key => $value) {
                    $statement->bindValue(":$key",$value);
               }
               $statement->execute();
               
               $data = $statement->fetchObject(static::class);  //return false or object

               return $data === false ? null : $data;

          } catch (\Throwable $e) {
               echo '<pre>';
               var_dump($e);
               echo '</pre>';
               exit;
          }

          

     }


}