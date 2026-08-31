<?php

# 32 ) que fait-on ici ?
# Réponse : on vérifie si le paramètre GET 'disconnect' est présent dans l'URL (ex: ?disconnect).
# Si oui, on tente de déconnecter l'utilisateur (destruction de session via deconnect()).
if (isset($_GET['disconnect'])) {
    // si déconnexion renvoie true
    if (deconnect()) {
        // redirection
        header("Location: ./");
        exit();
    }

// 33 ) à quoi pourrait servir ce bloc de code ?
# Réponse : à rendre un article visible ou invisible (masqué) côté admin, ex: ?postVisible=1&id=5.
# On vérifie d'abord que les deux paramètres GET existent ET sont bien des chaînes de chiffres (ctype_digit)
# pour éviter d'injecter autre chose qu'un entier.
}elseif(isset($_GET['postVisible'],$_GET['id'])
    &&ctype_digit($_GET['postVisible'])
    &&ctype_digit($_GET['id'])
    ){
    $postId = (int) $_GET['id'];
    $postVisible = (int) $_GET['postVisible'];

    // 34 ) que fait-on ici ?
    // Réponse : on appelle le modèle pour mettre à jour la colonne 'visible' de l'article en DB.
    // Selon le résultat (true/false), on redirige vers l'accueil admin avec un message de succès ou d'erreur.
    if (postAdminUpdateVisible($connectPDO, $postId, $postVisible)) {
        header("Location: ./?m=L'article dont l'id est $postId a été modifié");
        exit();
    } else {
        header("Location: ./?m=Problème lors de la modification de l'article!");
        exit();
    }

// 34 ) que veut on faire ici ?
# Réponse : on veut afficher (et traiter) le formulaire de création d'un nouvel article, ex: ?createPost.
}elseif(isset($_GET['createPost'])){

    // 35) si on a envoyé ... quoi ?
    // Réponse : on vérifie si le formulaire de création a été soumis en POST, c'est-à-dire si les
    // champs 'title', 'content' et 'user_id' sont bien présents dans $_POST.
    if(isset($_POST['title'],$_POST['content'],$_POST['user_id'])){
        $UserId = (int) $_POST['user_id']; // si erreur => 0
        // 36 ) que fait-on ici ?
        // Réponse : on nettoie/sécurise les champs texte envoyés par l'utilisateur : trim() enlève les
        // espaces superflus, strip_tags() retire les balises HTML, et htmlspecialchars() convertit les
        // caractères spéciaux restants en entités HTML (protection contre les injections XSS).
        $postTitle = htmlspecialchars(strip_tags(trim($_POST['title'])),ENT_QUOTES);
        $postContent = htmlspecialchars(strip_tags(trim($_POST['content'])),ENT_QUOTES);
        // ternaire ! si tableau les valeurs et clefs ne sont pas protégée contre une manipulation externe (injection etc...)
        $idCateg = (isset($_POST['category_id'])&&is_array($_POST['category_id']))? $_POST['category_id'] : [];

    if(!empty($UserId)&&!empty($postTitle)&&!empty($postContent)) {
        //  Pouvoir insérer un article AVEC ses catégories
        $insert = postAdminInsert($connectPDO, $UserId, $postTitle, $postContent, $idCateg);
        if($insert===true){
            $message = "Article inséré dans la DB";
        }
    }
    }

    // 37 )Appel des catégories pour .
    // Réponse : récupérer la liste complète des catégories existantes, afin de les proposer
    // (cases à cocher / menu) dans le formulaire de création d'article.
    $categoryChoice = getAllCategoryMenu($connectPDO);

    // 38 ) On appel qui?
    // Réponse : on récupère la liste de tous les utilisateurs, afin de pouvoir choisir
    // l'auteur de l'article dans le formulaire (menu déroulant).
    $userChoice = getAllUsers($connectPDO);

    // 39) que fait-on ici ?
    // Réponse : on inclut (affiche) la vue du formulaire de création d'article, en lui transmettant
    // les variables déjà préparées ($categoryChoice, $userChoice, $message éventuel).
    include "../view/privateView/privateInsertView.php";

// 40 ) que fait-on ici ?
# Réponse : ce bloc gère la modification d'un article existant, ex: ?updatePost=5.
}elseif(isset($_GET['updatePost'])&&ctype_digit($_GET['updatePost'])){

    // si on a envoyé le formulaire de modification
    if(isset($_POST['title'])){
        // pas de vérification des variables $_POST au niveau du contrôleur !!! -> TOUTES LES Vérification doivent se trouver dans la fonction !
        $post = postAdminUpdate($connectPDO,$_POST);
        // 41 ) quel type de retour pour avoir une erreur
        // Réponse : une chaîne de caractères (string) contenant le message d'erreur.
        if(is_string($post)){
            // affichage de l'erreur
            $message = $post;
        }
        // 42 ) quel type de retour pour avoir un succès
        // Réponse : le booléen true.
        if($post===true){
            $message = "L'article a bien été modifié<script>
            setTimeout(\"location.href = './';\", 2000);
             </script>";
        }
    }

    $idUpdatePost = (int) $_GET['updatePost'];

    # 43 ) que récupère t'on
    # Réponse : l'article correspondant à cet id (un tableau associatif avec ses infos), ou false
    # si aucun article ne correspond à cet id.
    $recupPost = postOneById($connectPDO,$idUpdatePost);

    # 44 ) que type de valeur peut-on récupérer ici, et que fait-on ensuite ?
    # Réponse : on peut récupérer soit un booléen (false, si l'article n'existe pas), soit un tableau
    # (array, si l'article existe). Si c'est un booléen, on affiche une page d'erreur 404 ;
    # sinon on affiche le formulaire de modification pré-rempli avec les données de l'article.
    if(is_bool($recupPost)){
        # récupération du menu pour l'erreur 404
        $recupMenu = getAllCategoryMenu($connectPDO);
        // création de l'erreur pour la 404
        $error = "Cet article n'existe plus";
        // appel de la vue 404
        include_once "../view/publicView/404View.php";

    // on a trouvé l'article
    }else{

    // 45) on appel les ...
    // Réponse : on récupère la liste des catégories, pour pouvoir cocher celles déjà
    // associées à l'article dans le formulaire de modification.
    $categoryChoice = getAllCategoryMenu($connectPDO);

    // 46 ) on appel les ...
    // Réponse : on récupère la liste des utilisateurs, pour pouvoir sélectionner/changer
    // l'auteur de l'article dans le formulaire.
    $userChoice = getAllUsers($connectPDO);

    // 47 ) on appel la ...
    // Réponse : on appelle (inclut) la vue de modification d'article, qui affiche le formulaire
    // pré-rempli avec les données actuelles de l'article ($recupPost).
    include "../view/privateView/privateUpdateView.php";
}

// 48 ) que fait-on ici ?
# Réponse : ce bloc gère la suppression d'un article, ex: ?deletePost=5. On vérifie que le paramètre
# existe et est numérique, on le convertit en entier, puis on appelle le modèle pour supprimer
# l'article en DB, avant de rediriger avec un message de succès ou d'erreur.
}elseif(isset($_GET['deletePost'])&&ctype_digit($_GET['deletePost'])){

    $postId = (int) $_GET['deletePost'];

    if(postAdminDeleteById($connectPDO,$postId)){
        header("Location: ./?m=L'article dont l'id est $postId a été supprimé");
        exit();
    }else{
        header("Location: ./?m=Problème lors de la modification de l'article!");
        exit();
    }


// 49) quel est cette page
# Réponse : c'est la page d'accueil de l'espace admin (cas par défaut, quand aucun des paramètres
# GET précédents n'est présent). Elle affiche TOUS les articles, y compris ceux qui sont masqués
# (visible = 0), contrairement à la page publique.
}else{
    // appel due la méthode (fonction) modèle PostModel pour afficher tous les articles SANS restrictions
    $postAll = postAdminHomepageAll($connectPDO);
    // on compte le nombre d'articles
    $postCount = count($postAll);
    // appel de la vue de l'accueil
    include "../view/privateView/privateHomepageView.php";
}