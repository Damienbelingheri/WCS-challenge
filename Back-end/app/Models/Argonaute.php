<?php

namespace App\Models;

use App\Utils\Database;


class Argonaute extends CoreModel
{



    /**
     * @var string
     */
    protected $name;

    public  static function getTableName()
    {
        return 'argonautes';
    }


    /**
     * insert argonaute
     *
     * @return void
     */
    public function insert()
    {
        $pdo = Database::getPDO();
        $sql = "
            INSERT INTO `argonautes` (name,created_at)
            VALUES (:name, NOW()) 
        ";
        //on envoie notre requête au serveur MySQL, sans l'exécuter
        $stmt = $pdo->prepare($sql);
        //dump($stmt);
        $insertedRows = $stmt->execute([
            ":name" => $this->name
        ]);
        // Si au moins une ligne ajoutée
        if ($insertedRows > 0) {
            // Alors on récupère l'id auto-incrémenté généré par MySQL
            $this->id = $pdo->lastInsertId();
            return $this->id;
            // On retourne VRAI car l'ajout a parfaitement fonctionné
            return true;
            // => l'interpréteur PHP sort de cette fonction car on a retourné une donnée
        }
        // Si on arrive ici, c'est que quelque chose n'a pas bien fonctionné => FAUX
        return false;
    }


    /**
     * Get the value of name
     *
     * @return  string
     */ 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @param  string  $name
     *
     * @return  self
     */ 
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }
}
