<?php
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
/*
Tout le code doit se faire dans ce fichier PHP

Réalisez un formulaire HTML contenant :
- firstname
- lastname
- email
- pwd
- pwdConfirm
*/
try {
    $pdo = new PDO('pgsql:host=172.18.0.2;port=5432;dbname=devdb', 'devuser', 'devpass');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Requête de création de la table si elle n'existe pas
    $sql = <<<SQL
    CREATE TABLE IF NOT EXISTS "users" (
        id SERIAL PRIMARY KEY,
        firstname VARCHAR(30),
        lastname VARCHAR(30),
        email VARCHAR(50),
        password TEXT
    );
    SQL;

    $pdo->exec($sql);

} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}?>


<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Formulaire</title>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    </head>
    <body style="width: 100%">
        <div style="border: 1px solid black; align-content: center; padding: 10px; width: 60%">
        <form action="" method="POST">
            <label for='firstname'>Firstname : </label>
            <input type="text" name="firstname" id="firstname" placeholder="Jean" value="<?= htmlspecialchars($_POST['firstname'] ?? '') ?>"><br><br>

            
            <label for="lastname">Lastname : </label>
            <input type="text" name="lastname" id="lastname" placeholder="Dupont" value="<?= htmlspecialchars($_POST['lastname'] ?? '') ?>"><br><br>


            <label for="email">E-mail : </label>
            <input type="email" name="email" id="email" placeholder="jean.dupont@exemple.com" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"><br><br>


            <label for="pwd">Password : </label>
            <input type="password" name="pwd" id="pwd" placeholder="Password" required><br>
            <small>Your password must contain at least 8 character, including a capital letter, a small letter, a number and a special character (ex: ! @ # $ % ^ & *)</small><br>

            <label for="cpwd">Password Confirm : </label>
            <input type="password" name="cpwd" id="cpwd" placeholder="Confirm your password" required><br><br>

            <button type="submit" name="submit"> Submit</button>
        </form>
        </div>
        <script>
            function swal(title, text){
                Swal.fire({
                    icon: 'error',
                    title: title,
                    text: text
                })
            }
        </script>
    </body>
</html>
<?php
/*
Créer une table "user" dans la base de données, regardez le .env à la racine et faites un build de docker
si vous n'arrivez pas à les récupérer pour qu'il les prenne en compte

Lors de la validation du formulaire vous devez :
- Nettoyer les valeurs, exemple trim sur l'email et lowercase (5 points)
- Attention au mot de passe (3 points)
- Attention à l'unicité de l'email (4 points)
- Vérifier les champs sachant que le prénom et le nom sont facultatifs
- Insérer en BDD avec PDO et des requêtes préparées si tout est OK (4 points)
- Sinon afficher les erreurs et remettre les valeurs pertinantes dans les inputs (4 points)
*/

if ($_SERVER ["REQUEST_METHOD"] == "POST"  && isset($_POST['submit'])) {
    $firstname=isset($_POST["firstname"]) && trim($_POST["firstname"])!=="" ? trim($_POST["firstname"]) : null;
    $lastname=isset($_POST["lastname"]) && trim($_POST["lastname"])!=="" ? trim($_POST["lastname"]) : null;
    $email=strtolower(trim($_POST["email"]));
    $password=$_POST["pwd"];
    $cpassword=$_POST["cpwd"];


if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo '<script>Swal.fire("Invalid E-mail", "Enter a valid E-mail", "error");</script>';
    exit;
}

if ($password === $cpassword) {
    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $password)) {
        echo '<script>
            Swal.fire("Invalid Password", "Your password must contain at least 8 characters, including a capital letter, a small letter, a number and a special character", "error");
        </script>';
        exit;
    } else {
        $passwordhash = password_hash($password, PASSWORD_DEFAULT);

    }
} else {
    echo '<script>Swal.fire("Invalid Password", "The password are note same", "error");</script>';
    exit;
}

try {
    $pdo = new PDO('pgsql:host=172.18.0.2;port=5432;dbname=devdb', 'devuser', 'devpass');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$emailPresent="select id from \"user\" where email=:email";
$stmt=$pdo->prepare($emailPresent);
$stmt ->execute([
    ':email' => $email
]);

if($stmt->fetch()){
    echo '<script>Swal.fire("Erreur", "Cet email est déjà utilisé", "error");</script>';
    exit;
} 
// else {
//     $html="
// <h1>Verify and sign in</h1>
// <h2>Hello, </h2>
// <p>Thank you for signing up. Please click the link below to verify your email: </p>
// <p><a href=">
//     Verify My Email
// </a></p>
// <p>If you did not request this, please ignore this email.</p>
// <p>Thanks</p>
// ";
// $mail = new PHPMailer(true);
// try {
//     $mail->isSMTP();
//     $mail->Host = 'smtp.gmail.com';
//     $mail->SMTPAuth = true;
//     $mail -> Port = 587;

//     $mail->setFrom('pouvelle@gmail.com');
//     $mail -> addAddress($email);
//     $mail ->Subject = 'Verification mail';

//     $mail -> isHTML(true);
//     $mail->Body=$html;

//     $mail->send();
//     echo"Mail has been successfully sended";
// }
// catch(Exception $e) {
//     echo"erreur : " . $mail->ErrorInfo;
//     exit;
// }
// }

if (isset($_POST['submit'])) {
    if ($firstname !== null && $lastname !== null) {
        $sql="insert into \"user\" (firstname, lastname, email, password) values (:firstname,:lastname,:email,:password)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
        ':firstname' => $firstname,
        ':lastname' => $lastname,
        ':email' => $email,
        ':password' => $passwordhash
        ]);
    } else if ($firstname !== null) {
        $sql="insert into \"user\" (firstname, email, password) values (:firstname,:email,:password)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
        ':firstname' => $firstname,
        ':email' => $email,
        ':password' => $passwordhash
        ]);
    } else if ($lastname !== null) {
        $sql="insert into \"user\" (lastname, email, password) values (:lastname,:email,:password)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
        ':lastname' => $lastname,
        ':email' => $email,
        ':password' => $passwordhash
        ]);
    } else {
        $sql="insert into \"user\" (email, password) values (:email, :password)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
        ':email' => $email,
        ':password' => $passwordhash
        ]);
    }

}
}
catch (PDOException $e){
    echo "Erreur de connexion : " . $e->getMessage();
    exit;
}
}
/*
Le design je m'en fiche mais pas la sécurité

Bonus de 3 points si vous arrivez à envoyer un mail via un compte SMTP de votre choix
pour valider l'adresse email en bdd

Pour le : 22 Octobre 2025 - 8h
M'envoyer un lien par mail de votre repo sur y.skrzypczyk@gmail.com
Objet du mail : TP1 - 2IW3 - Nom Prénom
Si vous ne savez pas mettre votre code sur un repo envoyez moi une archive
*/