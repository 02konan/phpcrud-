<?php
if (isset($_POST['envoyer'])) {

    $servername = 'localhost:443';
    $user = 'root';
    $dbname= 'enregistrement';
    $password= '';
         
    try{
        $db = new pdo('mysql:host=$servername;port=3306;dbname=mysql;charset=utf8',$user, $password,
        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        
        )); 
        die(var_dump($db));

    } catch(PDOException $e){
        //die(var_dump($e));
        echo $e->getMessage();
    }    

     die();
    $nom = $_POST["nom"];
    $prenom = $_POST["prenom"];
    $tel = $_POST["tel"];
    $sexe = $_POST["sexe"];
    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="fichier.css"> 
    <title>Document</title>
</head>
<body>
<h2>formulair d'enregistrement</h2>
    <div class="formulaire">
    <form action="" method="POST">
        <label for="">Nom:</label><br>
        <input type="text" name="nom"><br>
        <label for="">Prenom:</label><br>
        <input type="text" name="prenom"><br>
        <label for="">tel:</label><br>
        <input type="text"  name="tel"><br>
        <label for="">sexe</label><br>
        <select name="sexe" name="sexe"><br>
            <option value="masculin">Masculin</option>
            <option value="feminin">Feminin</option>
        </select>
        <input type="submit" name="envoyer" value="Envoyer"> 
    </form>
</body>
</html>