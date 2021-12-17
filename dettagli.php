<?php
    require_once "dbRicky.php";
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
                $listaPiste .= '<article>';

                if ($pista['stato'] == 0) {
                    $stato = "Chiusa";
                } else {
                    $stato = "Aperta";
                }
                
                $listaPiste .= '<a id="' . $pista['numero'] . '"></a>
                                <h4>' . $pista['nome'] . '</h4>
                                <div>
                                    <p>Difficoltà: ' . $pista['difficoltà'] . '</p>
                                    <p>Stato: ' . $stato . '</p>
                                    <p class="descrizione">' . $pista['descrizione'] . '</p>
                                </div>';  
                                $listaPiste .= '</article>';                                  
            }
        } else {
            $listaPiste = "<p>Non ci sono informazioni relative alle piste.</p>";
        }
    } else {
        $listaPiste = "<p>I sistemi al momento sono non disponibili, riprova più tardi.</p>";
    }

    echo str_replace("['Dettagli Piste']",$listaPiste,$paginaHTML);
?>