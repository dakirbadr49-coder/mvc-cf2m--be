<?php
# 50 ) recupere id+title de toutes les categories, triees par id
# query() car aucune valeur externe -> pas besoin de requete preparee
# fetchAll : renvoie toutes les lignes (pour construire le menu)
function getAllCategoryMenu(PDO $db): array {
    $sql ="SELECT id, title FROM category ORDER BY id ASC";
    try{
        $query=$db->query($sql);
    }catch(Exception $e){
        die($e->getMessage());
    }
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

# 51 ) recupere toutes les colonnes d'une categorie via son id
# prepare()/execute() car $id vient de l'exterieur -> protection injection SQL
# fetch() renvoie une seule ligne (ou false si l'id n'existe pas)
function recupCategoryById(PDO $db,int $id):array|bool{
    $recup = "SELECT * FROM category where id=?";
    $prepare = $db -> prepare($recup);
    try{
        $prepare->execute([$id]);
    }catch(Exception $e){
        die($e->getMessage());
    }
    $bp = $prepare->fetch(PDO::FETCH_ASSOC);
    $prepare->closeCursor();
    return $bp;
}

