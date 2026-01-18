<?php

namespace Mini\Core;

// Modele de base : c'est la classe mère dont vont hériter TOUS les models
// Cette classe n'est pas destinée à être instancié, mais seulement à être héritée
class Model
{
    // Ici on veut éviter de répéter les propriétés présentes dans tous les Models
    // On factorise dans la classe "parent" de tous les Models => donc ici meme CoreModel
    // Les propriétés doivent être en protected car on veut pouvoir les utiliser dans les classe enfant (avant ça, elles etaient en private)

    protected $id;
    protected $created_at;
    protected $updated_at;

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Get the value of updated_at
     */ 
    public function getUpdated_at()
    {
        return $this->updated_at;
    }

    /**
     * Set the value of updated_at
     *
     * @return  self
     */ 
    public function setUpdated_at($updated_at)
    {
        $this->updated_at = $updated_at;
    }

    /**
     * Get the value of created_at
     */ 
    public function getCreated_at()
    {
        return $this->created_at;
    }

    /**
     * Set the value of created_at
     *
     * @return  self
     */ 
    public function setCreated_at($created_at)
    {
        $this->created_at = $created_at;
    }
}