<?php

namespace App\Models;

use App\Utils\Database;
use PDO;

// Classe mère de tous les Models
// On centralise ici toutes les propriétés et méthodes utiles pour TOUS les Models
abstract class CoreModel {


    //une méthode définie comme abstraite doit absolument être implémentée par tous ses enfants ! 
    abstract public static function getTableName();


    public static function delete($id)
    {
        $pdo = Database::getPDO();

        $tableName = static::getTableName();

        $sql = "DELETE FROM $tableName WHERE id = :id";

        $stmt = $pdo->prepare($sql);
        return $stmt->execute([":id" => $id]);
    }

    //factorisation find
    public static function find($id)
    {
        $pdo = Database::getPDO();

        $tableName = static::getTableName();

        $sql = "SELECT * FROM $tableName WHERE id = :id";

        // prépare notre requête
        $stmt = $pdo->prepare($sql);
       
        $stmt->execute([":id" => $id]);
        $result = $stmt->fetchObject(static::class);
        //dump($result);
        return $result;
       
    }

public static function findAll()
    {
        $pdo = Database::getPDO();

        $tableName = static::getTableName();

        $sql = "SELECT * FROM $tableName ";
       
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        $results = $stmt->fetchAll(PDO::FETCH_CLASS, static::class);
        return $results;
    }
    
    /**
     * @var int
     */
    protected $id;
    /**
     * @var string
     */
    protected $created_at;
    /**
     * @var string
     */
    protected $updated_at;


    /**
     * Get the value of id
     *
     * @return  int
     */ 
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * Get the value of created_at
     *
     * @return  string
     */ 
    public function getCreatedAt() : string
    {
        return $this->created_at;
    }

    /**
     * Get the value of updated_at
     *
     * @return  string
     */ 
    public function getUpdatedAt() : string
    {
        return $this->updated_at;
    }
}
