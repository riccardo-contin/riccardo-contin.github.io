<?php
    session_start();
    require_once "php_vari/utils.php";
    require_once "php_vari/dbRicky.php";
    use DB\DBAccess;

    $paginaHTML = file_get_contents("html/comprensorio.html");
    $paginaHTML = Utils::skipNavBtn($paginaHTML);
    $paginaHTML = Utils::addScrollBtn($paginaHTML);
    $connessione = new DBAccess();
    $connessioneOK = $connessione->openDBConnection();

    $piste = ""; //dati grezzi dal DB
    $listaPiste = ""; //codice html da dare in output
    
    $impianti = "";
    $listaImpianti = "";

    if ($connessioneOK) {
        $piste = $connessione->getPisteList();
        $impianti = $connessione->getImpiantiList();
        $connessione->closeConnection();
        if ($piste != null) {
            $listaPiste = '<tbody>';
            foreach ($piste as $pista) {
                $listaPiste .= '<tr>';
                
                if ($pista['stato'] == 0) {
                    $stato = "Chiusa";
                } else {
                    $stato = "Aperta";
                }
                
                $listaPiste .= '<th scope="row"><a href="dettagli.php#' . $pista['numero'] . '">' . $pista['numero'] . '</a></th>
                                <td>' . $pista['nome'] . '</td>
                                <td>' . $pista['difficoltà'] . '</td>
                                <td>' . $pista['lunghezza'] . '</td>
                                <td>' . $pista['dislivello'] . '</td>
                                <td>' . $stato . '</td>';  
                                $listaPiste .= '</tr>';                                  
            }
            $listaPiste .= '</tbody>';
        } else {
            $listaPiste = "<p>Non ci sono informazioni relative alle piste.</p>";
        }

        if ($impianti != null) {
            $listaImpianti = '<tbody>';
            foreach ($impianti as $impianto) {
                $listaImpianti .= '<tr>';
                
                if ($impianto['stato'] == 0) {
                    $stato = "Chiuso";
                } else {
                    $stato = "Aperto";
                }
                
                $listaImpianti .= '<th scope="row">' . $impianto['numero'] . '</th>
                                <td>' . $impianto['nome'] . '</td>
                                <td>' . $impianto['tipo'] . '</td>
                                <td>' . $impianto['lunghezza'] . '</td>
                                <td>' . $impianto['dislivello'] . '</td>
                                <td>' . $stato . '</td>';  
                                $listaImpianti .= '</tr>';                                  
            }
            $listaImpianti .= '</tbody>';
        } else {
            $listaImpianti = "<p>Non ci sono informazioni relative agli impianti.</p>";
        }
    } else {
        $listaPiste = "<p>I sistemi al momento sono non disponibili, riprova più tardi.</p>";
        $listaImpianti = "<p>I sistemi al momento sono non disponibili, riprova più tardi.</p>";
    }


    $find = array("['Piste']","['Impianti']");
    $replace = array($listaPiste,$listaImpianti);
    $paginaHTML = str_replace($find, $replace, $paginaHTML);

    $curPageName = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1); 
    $paginaHTML = str_replace("[Menu]",Utils::buildNav($curPageName),$paginaHTML);
echo str_replace("['Imports']", Utils::globalImports(),$paginaHTML);
?>
