<?php
include_once "register.html";
require_once "dbRicky.php";
use DB\DBAccess;
$paginaHTML = file_get_contents("comprensorio.html");

$connessione = new DBAccess();
$connessioneOK = $connessione->openDBConnection();

if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['ripetiPassword'])) {
    if($_POST['password'] == $_POST['ripetiPassword']){

        function valida($data){
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            $data = strip_tags($data);
            return $data;
        }
     
        //validazione credenziali
        $username = valida($_POST['username']);
        $pass = valida($_POST['password']);
     
        
        if (!$connessione->isUsernameTaken($username)) {
            //se non c'é nessun utente con questo usrname
            $hash = password_hash($pass, PASSWORD_DEFAULT);
            $query = "INSERT INTO `Utenti` (`Username`, `Password`, `Privilegi`) VALUES ('$username', '$hash', '0');";
            $result = $connessione->execQuery($query);
            if (!$result) {
                //se si verifica un errore
                echo "<p>Si é verificato un errore, riprovare piú tardi</p>";
            }else{
                //tutto bene
                //non credo vada bene inserire in questo modo il feedback
                echo "<p>Registrato con successo</p>";
                header("Location: login.php");
            }
        }else{
            //username giá utilizzato
            echo "<p>Username giá in uso</p>";
        }
    }
}else{
    echo "form non riempito";

}
$connessione->closeConnection();
?>