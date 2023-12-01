<?php
try {
    $db = new PDO('mysql:host=127.0.0.1;port=3306;dbname=crud_samuel;charset=utf8', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

} catch (PDOException $e) {
    echo $e->getMessage();
}

// ajouter les informations modifiées
if (isset($_GET['edit']) && !empty($_POST)) {

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

// insertion
elseif (!empty($_POST['envoyer'])) {

    $nom = $_POST["nom"];
    $prenom = $_POST["prenom"];
    $tel = $_POST["tel"];
    $sexe = $_POST["sexe"];

    if (empty($nom) || empty($prenom) || empty($tel) || empty($sexe)) {
        $resul['insert'] = "Veuillez remplir tous les champs.";
    } elseif (!is_numeric($tel)) {
        $resul['insert'] = "Le numéro de téléphone doit être un nombre.";
    } else {
        $requete = "INSERT INTO users (nom, prenom, tel, sexe) VALUES (:nom, :prenom, :tel, :sexe)";

        // Prepare our statement.
        $statement = $db->prepare($requete);

        // Bind our values to our parameters.
        $statement->bindValue(':nom', $nom);
        $statement->bindValue(':prenom', $prenom);
        $statement->bindValue(':tel', $tel);
        $statement->bindValue(':sexe', $sexe);

        // Execute the statement and insert our values.
        $inserted = $statement->execute();
        if ($inserted) {
            $resul = "bravo";
        } else {
            $resul['insert'] = "echec";
        }
    }
}

// édition

if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $id = $_GET['edit'];

    if (is_numeric($id)) {

        $stmt = $db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $userData = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($userData) {
            $nom_edit = $userData['nom'];
            $prenom_edit = $userData['prenom'];
            $tel_edit = $userData['tel'];
            $sexe_edit = $userData['sexe'];
        } else {
            die('Aucun utilisateur trouvé avec cet ID');
        }
    }
}

// sélection
$data = $db->query("SELECT * FROM users")->fetchAll();

if (empty($data)) {
    $resul['select'] = 'aucune data';
}

// suppression
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = $_GET['delete'];

    // Prepare our statement.
    $statement = $db->prepare("DELETE FROM users  WHERE id =:id");

    // Bind our values to our parameters.
    $statement->bindValue(':id', $id, PDO::PARAM_INT);

    $delete = $statement->execute();
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
    <h2>formulaire d'enregistrement</h2>
    <div class="return">
        <?php if (isset($resul['insert'])) : ?>

            <p><?= $resul['insert'] ?></p>

        <?php endif ?>
    </div>

    <div class="formulaire">
        <form action="" method="POST">
            <label for="">Nom:</label>
            <input type="text" name="nom" value="<?= $nom_edit ?? "" ?>">
            <label for="">Prenom:</label>
            <input type="text" name="prenom" value="<?= $prenom_edit ?? "" ?>">
            <label for="">Mot de passe:</label>
            <input type="password" name="pasword">
            <label for="">telephone:</label>
            <input type="text" name="tel" value="<?= $tel_edit ?? "" ?>">
            <label for="">sexe</label>
            <select name="sexe" name="sexe">
                <option value="masculin"<?= "" ?? (($sexe_edit === 'masculin') ? 'selected' : '') ?>>Masculin</option>
                <option value="feminin"<?= "" ?? (($sexe_edit === 'feminin') ? 'selected' : '') ?>>Feminin</option>
            </select>
            <input type="submit" name="envoyer" value="Envoyer">
            <a href="http://localhost/fichier/index.php">initialiser</a>
        </form>
        <?php if (isset($data)) : ?>
            <div class="resultat">
                <h2>Resultat des enregistrement</h2>
                <?php if (empty($data)) : ?>
                    <?php if (isset($resul['select'])) : ?>
                        <p><?= $resul['select'] ?></p>
                    <?php endif ?>
                <?php else : ?>
                    <table>

                        <tr>
                            <th>Id</th>
                            <th>Nom</th>
                            <th>prenom</th>
                            <th>Tel</th>
                            <th>Sexe</th>
                            <th>action</th>
                        </tr>
                        <?php foreach ($data as $item) : ?>
                            <tr>
                                <td><?= $item['id'] ?></td>
                                <td><?= $item['nom'] ?></td>
                                <td><?= $item['prenom'] ?></td>
                                <td><?= $item['tel'] ?></td>
                                <td><?= $item['sexe'] ?></td>
                                <td>
                                    <a href="http://localhost/fichier/index.php?delete=<?= $item['id']; ?>">Supprimer</a>
                                    <a href="http://localhost/fichier/index.php?edit=<?= $item['id']; ?>">Modifier</a>
                                </td>
                            </tr>
                        <?php endforeach ?>
                        <colgroup>
                    </table>
                <?php endif ?>
            </div>
        <?php endif ?>
</body>

</html>
