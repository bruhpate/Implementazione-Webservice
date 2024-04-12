<?php

// Connessione al database
$servername = "localhost";
$username = "swag";
$password = "1";
$dbname = "webservice";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica della connessione
if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}

//echo $_SERVER['REQUEST_URI'];

$array = explode('/',$_SERVER['REQUEST_URI']); 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    header("Content-Type:application/json");   
    $body=file_get_contents('php://input');
    $data = json_decode($body,true);
    $nome=$data["nome"];
    $Descrizione=$data["cognome"];
    $sql = " INSERT INTO tabella (nome, cognome) VALUES ('$nome','$cognome')";
    $result = $conn->query($sql);
    http_response_code(200); 
}
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    header("Content-Type:application/json");   
    $body=file_get_contents('php://input');
    $data = json_decode($body,true);
    $IdMod=$data["IdMod"];
    $campo=$data["Campo"];
    $Agg=$data["Aggiornato"];
    try{
    $sql = "UPDATE tabella SET $campo= '$Agg' WHERE id = '$IdMod'" ;
    $result = $conn->query($sql);
    http_response_code(200);
    }
    catch(Exception $ecc){
        http_response_code(405);
    }
}
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    if (count($array) == 3 && $array[2] != '')
    {
        $nome = $array[2];
        $sql = "DELETE FROM tabella WHERE nome = '$nome'";
        $result = $conn->query($sql);   
            http_response_code(200); 

    }
    else if(count($array) == 3 && $array[2] == '')
    {
        $sql = "SELECT * FROM tabella";
        $result = $conn->query($sql);
       
        if ($result->num_rows > 0) {
            $rows = array();
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            echo json_encode($rows);
        } else {
            echo "Nessun risultato trovato nella tabella.";
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (count($array) == 3 && $array[2] != '')
    {
        $nome = $array[2];
        $sql = "SELECT * FROM tabella WHERE id = '$nome'";
        $result = $conn->query($sql);   
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            echo json_encode($row);
        } else {
            echo "Nessun risultato trovato con ID $nome";
        }
    }
    else if(count($array) == 3 && $array[2] == '')
    {
        $sql = "SELECT * FROM tabella";
        $result = $conn->query($sql);
       
        if ($result->num_rows > 0) {
            $rows = array();
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            echo json_encode($rows);
        } else {
            echo "Nessun risultato trovato nella tabella.";
        }
    }
}


$conn->close();

?>