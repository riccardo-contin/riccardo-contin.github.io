<?php
session_start();
    require_once "dbRicky.php";
    require_once "utils.php";
    use DB\DBAccess;

    $paginaHTML = file_get_contents("dettagli.html");

    $connessione = new DBAccess();
    $connessioneOK = $connessione->openDBConnection();

    $piste = ""; //dati grezzi dal DB
    $listaPiste = ""; //codice html da dare in output

    if ($connessioneOK) {
        $piste = $connessione->getPisteList();
        $connessione->closeConnection();
        if ($piste != null) {
            $listaPiste = '';
            foreach ($piste as $pista) {
                $listaPiste .= '<div class="pista">';

                if ($pista['stato'] == 0) {
                    $stato = "Chiusa";
                } else {
                    $stato = "Aperta";
                }
                
                $listaPiste .= '<a id="' . $pista['numero'] . '"></a>
                                <h4>' . $pista['numero'] . ' - ' . $pista['nome'] . '</h4>
                                <div class="dati">
                                    <p>Difficoltà: ' . $pista['difficoltà'] . '</p>
                                    <p>Stato: ' . $stato . '</p>
                                </div>
                                <div class="descrizione">
                                    <p>' . $pista['descrizione'] . '</p>
                                </div>';  
                                $listaPiste .= '</div>';                                  
            }
        } else {
            $listaPiste = "<p>Non ci sono informazioni relative alle piste.</p>";
        }
    } else {
        $listaPiste = "<p>I sistemi al momento sono non disponibili, riprova più tardi.</p>";
    }
    $find = array("['Dettagli Piste']","['LinkLogin']","['LinkDashboard']");
    $replaceDashboard = Utils::checkPriv()?"<a class='right' href='dashboard.php'>Dasboard Admin</a>":"";
    $replaceLogin = isset($_SESSION['Privilegi'])?"<a class='right' href='logout.php'>Logout</a>":"<a class='right' href='login.php'>Login</a>";
    $replace = array($listaPiste,$replaceDashboard,$replaceLogin);
    echo str_replace($find,$replace,$paginaHTML);
?>