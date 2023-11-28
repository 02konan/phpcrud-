<?php
try{
    $db = new pdo('mysql:host=127.0.0.1;port=3306;dbname=crud_samuel;charset=utf8','root', 'password',
    array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

} catch(PDOException $e){
    echo $e->getMessage();
}   

//insertion
if (isset($_POST['envoyer'])) { 

    $nom = $_POST["nom"];
    $prenom = $_POST["prenom"];
    $tel = $_POST["tel"];
    $sexe = $_POST["sexe"];
    
    $requete = "INSERT INTO users (nom, prenom, tel, sexe) VALUES (:nom, :prenom, :tel, :sexe)";

    //Prepare our statement.
    $statement = $db->prepare($requete);

    //Bind our values to our parameters (we called them :make and :model).
    $statement->bindValue(':nom', $nom);
    $statement->bindValue(':prenom', $prenom);
    $statement->bindValue(':tel', $tel);
    $statement->bindValue(':sexe', $sexe);

    //Execute the statement and insert our values.
    $inserted = $statement->execute();
    if($inserted){
        $resul = "bravo";
    }
    else{
        $resul['insert'] = "echec";
    }
}

//selection
$data = $db->query("SELECT * from users")->FetchAll();

if (empty($data)) {
    $resul['select'] = 'aucune data';
}

//suppression
if(isset($_GET['delete'])){
    $id = 0;
   
    if(is_numeric($_GET['delete'])){
        $id = $_GET['delete'];

         //Prepare our statement.
        $statement = $db->prepare("DELETE FROM users  WHERE id =:id");
    
          //Bind our values to our parameters (we called them :make and :model).
        $statement->bindValue(':id', $id , PDO::PARAM_INT);        

        $delete = $statement->execute();

       if($delete){
        header('Location: /');
       }
    }else{
        $resul['delete'] = 'error inconnu';
    }
}

//edit
if(isset($_GET['editer'])){
    $editer['nom'] = "toure";
    $editer['prenom'] = "simplice";
    $editer['tel'] = "04555866";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="fichier.css"> 
    <title>Document</title>
</head>
<body>
<h2>formulair d'enregistrement</h2>

    <div class="return">
        <?php if(isset($resul['insert'])) :?> 
 
            <p><?= $resul['insert'] ?></p>

        <?php endif ?>
    </div>

    <div class="formulaire">
    <form action="" method="post">
        <label for="">Nom:</label><br>
        <input type="text" name="nom" value="<?= isset($editer['nom']) ? $editer['nom'] : '' ?>"  > <br>
        <label for="">Prenom:</label><br>
        <input type="text" name="prenom" value="<?= isset($editer['prenom']) ? $editer['prenom'] : '' ?>"><br>
        <label for="">tel:</label><br>
        <input type="text"  name="tel" value="<?= isset($editer['tel']) ? $editer['tel'] : '' ?>"><br>
        <label for="">sexe</label><br>
        <select name="sexe" name="sexe"><br>
            <option value="masculin">Masculin</option>
            <option value="feminin">Feminin</option>
        </select>
        <input type="submit" name="envoyer" value="Envoyer"> 
    </form>
    <?php if (isset($data)) :?> 
        <div class="resultat">
            <h2>Resultat des enregistrement</h2>
            <?php if (empty($data)) :?> 
                <?php if(isset($resul['select'])) :?> 
                    <p><?= $resul['select'] ?></p>
                <?php endif ?>
            <?php else :?>
                <table>
                    <tr>
                        <th>Id</th>
                        <th>Nom</th>
                        <th>prenom</th>
                        <th>Tel</th>
                        <th>Sexe</th>
                        <th>action</th>
                    </tr>
                        <?php foreach ($data as $item) :?> 
                            <tr>
                                <td><?= $item['id'] ?></td>
                                <td><?= $item['nom'] ?></td>
                                <td><?= $item['prenom'] ?></td>
                                <td><?= $item['tel'] ?></td>
                                <td><?= $item['sexe'] ?></td>
                                <td>
                                    <a href="#">Editer</a>
                                    <a href="/?delete=<?= $item['id'] ?>">Supprimer</a>
                                </td>
                            </tr>
                        <?php endforeach ?>
                </table>
            <?php endif ?>
        </div>
    <?php endif ?>
</body>
</html>