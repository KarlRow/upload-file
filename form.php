<?php
$errors = [];

// Je vérifie si le formulaire est soumis comme d'habitude
if ($_SERVER['REQUEST_METHOD'] === "POST") {
     // Securité en php
    // chemin vers un dossier sur le serveur qui va recevoir les fichiers uploadés (attention ce dossier doit être accessible en écriture)
    $uploadDir = 'public/uploads/';
    // le nom de fichier sur le serveur est ici généré à partir du nom de fichier sur le poste du client (mais d'autre stratégies de nommage sont possibles)
    $extension = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
    // Je récupère l'extension du fichier
    $uploadFile = $uploadDir . uniqid() . '.' . $extension;
    // Je récupère l'extension du fichier
    $authorizedExtensions = ['jpg', 'png', 'gif', 'webp'];
    // Le poids max géré par PHP par défaut est de 1M
    $maxFileSize = 1000000;

    // Je sécurise et effectue mes tests

    /****** Si l'extension est autorisée *************/
    if (!in_array($extension, $authorizedExtensions)) {
        $errors[] = 'Veuillez sélectionner une image de type Jpg, png, gif ou webp !<br>';
    }
    /****** On vérifie si l'image existe et si le poids est autorisé en octets *************/
    if (file_exists($_FILES['avatar']['tmp_name']) && filesize($_FILES['avatar']['tmp_name']) > $maxFileSize) {
        $errors[] = "Votre fichier doit faire moins de 1M !";
    }

     /****** Si je n'ai pas d"erreur alors j'upload *************/
   /**
        TON SCRIPT D'UPLOAD
 */
    //On vérifie que les données ont bien été saisies dans le formulaire
    if (!isset($_POST["name"]) || trim($_POST["name"]) === '') {
        $errors[] = "<br>Le nom n'a pas été rempli";
    }
    if (!isset($_POST["surname"]) || trim($_POST["surname"]) === '') {
        $errors[] = "<br>Le prénom n'a pas été rempli<br>";
    }
    //Si pas d'erreur, on upload le fichier dans le dossier de destination
    if (empty($errors)) {
        if (move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadFile)) {
            echo "Le fichier a été téléchargé avec succès";
        } else {
            $errors[] = "Erreur lors de l'upload du fichier.";
        }
    }

    foreach ($errors as $error) {
        echo $error;
    }
}
?>
    <html>
    <div>
        <p>Affichage de l'image:
            <br>
        
<?php
        //On vérifie que l'image existe et dans ce cas on affiche
        if (isset($uploadFile) && file_exists($uploadFile)) {
            echo '<img src="' . $uploadFile . '" alt="image homer">';
        }
?>
    </p>
<?php
        //On vérifie que les données saisies existent et dans ce cas on affiche le nom et prénom
        if (isset($_POST["name"]) && isset($_POST["surname"])) {
            echo 'Nom : ' . htmlspecialchars($_POST["name"]) . '<br>';
            echo 'Prénom : ' . htmlspecialchars($_POST["surname"]) . '<br>';
}
?>
        <form action="" method="POST" enctype="multipart/form-data">
            <label for="imageUpload">Upload a profile image</label>
            <br>
            <input type="file" name="avatar" id="imageUpload" />
            <br>
            <label for="name">Nom</label>
            <input type="text" name="name" placeholder="Nom">
            <br>
            <label for="surname">Prénom</label>
            <input type="text" name="surname" placeholder="Prénom">
            <button name="send">Send</button>
        </form>
    </div>
    </html>
