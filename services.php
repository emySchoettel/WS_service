<?php 

$url = "localhost"; 
$user = "root"; 
$mdp = ""; 
$db = "clicom"; 

try
{
    $conn = mysqli_connect($url, $user, $mdp , $db );
    $conn_post = new mysqli($url, $user, $mdp , $db );
}
catch(Exception $e)
{
    var_dump($e->getMessage());
}

$method = $_SERVER['REQUEST_METHOD'];

if($method == "GET")
{
    $sql = "SELECT * from client";
    $resultat = mysqli_query($conn, $sql);
    $rows = array(); 

    if(mysqli_num_rows($resultat) > 0)
    {
        while($r = mysqli_fetch_assoc($resultat))
        {
            array_push($rows, $r);
        }
    }
    $res = safe_json_encode($rows);
    print_r($res);
}
else if($method == "POST")
{
    $ncli = $_POST['NCLI'];
    $nom = $_POST['NOM'];
    $adresse = $_POST['ADRESSE'];
    $localite = $_POST['LOCALITE'];
    $cat = $_POST['CAT'];
    $compte = $_POST['COMPTE'] ? $_POST['COMPTE'] : null;

    $sql = "INSERT INTO client VALUES ('" . $ncli . "','" . $nom . "','" . $adresse . "','" . $localite . "','" . $cat . "','" . $compte . "')";  

    if($conn_post->query($sql) === true)
    {

    }
    else
    { 
        var_dump("error" . mysqli_error($conn) );
    }
    $conn_post->close();
}
else if($method == "DELETE")
{
    $ncli = $_GET['ncli'];

    $sql = "DELETE FROM client WHERE NCLI = '" . $ncli . "'";

    if($conn_post->query($sql) === true)
    {
        
    }
    else
    {
        var_dump(msqli_error($conn));
    }
    
    $conn_post->close();
}

else if($method == "PUT")
{
    var_dump($_GET);

    $sql = "UPDATE client SET NOM = " . $_GET['nom'] . ", ADRESSE = " . $_GET['adresse'] . ", LOCALITE = " . $_GET['localite'] . ", CAT = " . $_GET['cat'] . ", COMPTE = " . $_GET['compte'] . "WHERE NCLI = " . $_GET['ncli'] ;

    if($conn_post->query($sql) === true)
    {
        
    }
    else
    {

    }
    
    $conn_post->close();
}


//Attention, l'encodage en JSON est UTF8 : json_encode();
function safe_json_encode($value, $options = 0, $depth = 512, $utfErrorFlag = false) {
    $encoded = json_encode($value, $options, $depth);
    switch (json_last_error()) 
    {
        case JSON_ERROR_NONE:
            return $encoded;

        case JSON_ERROR_DEPTH:
            return 'Maximum stack depth exceeded'; // or trigger_error() or throw new Exception()

        case JSON_ERROR_STATE_MISMATCH:
            return 'Underflow or the modes mismatch'; // or trigger_error() or throw new Exception()

        case JSON_ERROR_CTRL_CHAR:
            return 'Unexpected control character found';

        case JSON_ERROR_SYNTAX:
            return 'Syntax error, malformed JSON'; // or trigger_error() or throw new Exception()

        case JSON_ERROR_UTF8:
            $clean = utf8ize($value);
            if ($utfErrorFlag) 
            {
                return 'UTF8 encoding error'; // or trigger_error() or throw new Exception()
            }
            return safe_json_encode($clean, $options, $depth, true);

        default:
            return 'Unknown error'; // or trigger_error() or throw new Exception()
    }
}

function utf8ize($mixed) {
    if (is_array($mixed)) 
    {
        foreach ($mixed as $key => $value) 
        {
            $mixed[$key] = utf8ize($value);
        }
    } 
    else if (is_string ($mixed)) 
    {
        return utf8_encode($mixed);
    } 
    else if (is_object($mixed)) 
    {
        $a = (array)$mixed; // from object to array
        return utf8ize($a);
    }
    return $mixed;
}

function json_error_string($code)
{
    switch ($code)
    {
        case JSON_ERROR_NONE: return "No error";
        case JSON_ERROR_DEPTH: return "The maximum stack depth has been exceeded";
        case JSON_ERROR_STATE_MISMATCH: return "Invalid or malformed JSON";
        case JSON_ERROR_CTRL_CHAR: return "Control character error, possibly incorrectly encoded";
        case JSON_ERROR_SYNTAX: return "Syntax error";
        case JSON_ERROR_UTF8: return "Malformed UTF-8 characters, possibly incorrectly encoded";
        case JSON_ERROR_RECURSION: return "One or more recursive references in the value to be encoded";
        case JSON_ERROR_INF_OR_NAN: return "One or more NAN or INF values in the value to be encoded";
        case JSON_ERROR_UNSUPPORTED_TYPE: return "A value of a type that cannot be encoded was given";
        case JSON_ERROR_INVALID_PROPERTY_NAME: return "A property name that cannot be encoded was given";
        case JSON_ERROR_UTF16: return "Malformed UTF-16 characters, possibly incorrectly encoded";
    }
}
?>