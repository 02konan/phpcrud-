<?php
try{
    $db = new pdo('mysql:host=127.0.0.1;port=3306;dbname=crud_samuel;charset=utf8','root', 'password',
    array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

} catch(PDOException $e){
    echo $e->getMessage();
}   


// ajouter les informatiion modifier
if(isset($_GET['edit']) && !empty($_POST) ) { 
     
    $id = $_GET["edit"];
    $nom = $_POST["nom"];
    $prenom = $_POST["prenom"];
    $sexe = $_POST["sexe"];
    $tel = $_POST["tel"];  

    $sql = "UPDATE users SET nom = :nom, prenom = :prenom, tel = :tel, sexe = :sexe WHERE id = :id";
    $stmt = $db->prepare($sql);

    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':nom', $nom);
    $stmt->bindParam(':prenom', $prenom);
    $stmt->bindParam(':tel', $tel);
    $stmt->bindParam(':sexe', $sexe);
      
    try {
        $stmt->execute();
        echo 'Mise à jour réussie';
    } catch (PDOException $e) {
        echo 'Erreur de mise à jour : ' . $e->getMessage();
    }
 }
//insertion
elseif (!empty($_POST['envoyer']) ){ 

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

// ajouter
if(isset($_POST['id']) && is_numeric($_POST['id'])) {
    $id =$_POST['id'];

    $sql = "UPDATE users SET nom = :nom, prenom = :prenom, tel = :tel, sexe = :sexe WHERE id = :id";
    $stmt = $db->prepare($sql);

    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':nom', $nom);
    $stmt->bindParam(':prenom', $prenom);
    $stmt->bindParam(':tel', $tel);
    $stmt->bindParam(':sexe', $sexe);

    try {
        $stmt->execute();
        echo 'Mise à jour réussie';
    } catch (PDOException $e) {
        echo 'Erreur de mise à jour : ' . $e->getMessage();
    }
  }
//edition
$id = 0;
$nom_edit = '';
$prenom_edit = '';
$tel_edit = '';
$sexe_edit = '';

if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $id = $_GET['edit'];

     
    $stmt = $db->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    $userData = $stmt->fetch(PDO::FETCH_ASSOC);

    
    $nom_edit = $userData['nom'];
    $prenom_edit = $userData['prenom'];
    $tel_edit = $userData['tel'];
    $sexe_edit = $userData['sexe'];
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
 
    }else{
        $resul['delete'] = 'error inconnu';
    }
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
    <form action=" " method="POST">
        <input type="hidden" id="id" name="id">
        <label for="">Nom:</label> 
        <input type="text"  name="nom" >  
        <label for="">Prenom:</label> 
        <input type="text"  name="prenom" > 
        <label for="">tel:</label> 
        <input type="text"   name="tel" > 
        <label for="">sexe</label> 
        <select name="sexe" name="sexe"> 
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
                                     <a href="/fichier/index.php?editer">Editer</a> 
                                     <a href="/fichier/index.php?delete=<?= $item['id'] ?>">Supprimer</a> 
                                </td>
                            </tr>
                        <?php endforeach ?> 
                </table>
            <?php endif ?>
        </div>
    <?php endif ?>
  <script>

    function editRow(id) {
        // Récupère les valeurs des cellules de la ligne
        var nom = document.getElementById('row_' + id).getElementsByTagName('td')[1].innerText;
        var prenom = document.getElementById('row_' + id).getElementsByTagName('td')[2].innerText;
        var tel = document.getElementById('row_' + id).getElementsByTagName('td')[3].innerText;
        var sexe = document.getElementById('row_' + id).getElementsByTagName('td')[4].innerText;

        // Remplit les champs du formulaire avec ces valeurs
        document.getElementById('nom').value = nom;
        document.getElementById('prenom').value = prenom;
        document.getElementById('tel').value = tel;
        document.getElementById('sexe').value = sexe;
    }
</script>

</body>
</html>