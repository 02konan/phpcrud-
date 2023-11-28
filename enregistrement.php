<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $servername= 'localhost';
    $user = 'root';
    $dbname= 'enregistrement';
    $password= '';

     
    $connexion = mysqli_connect($servername, $user, $password, $dbname);

     
    if (!$connexion) {
        die("La connexion a échoué : " . mysqli_connect_error());
    }

    $nom = $_POST["nom"];
    $prenom = $_POST["prenom"];
    $tel = $_POST["tel"];
    $sexe = $_POST["sexe"];

     
    $nom = mysqli_real_escape_string($connexion, $nom);
    $prenom = mysqli_real_escape_string($connexion, $prenom);
    $tel = mysqli_real_escape_string($connexion, $tel);
    $sexe = mysqli_real_escape_string($connexion, $sexe);

     
    $requete = "INSERT INTO utilisateur (nom, prenom, tel, sexe) VALUES ('$nom', '$prenom', '$tel', '$sexe')";

     
    if (mysqli_query($connexion, $requete)) {
        echo "Enregistrement réussi.";
    } else {
        echo "Erreur d'enregistrement : " . mysqli_error($connexion);
    }

     
    mysqli_close($connexion);
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
    <form action=" " method="POST">
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