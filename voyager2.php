<?php
// Obtenir la date actuelle
$today = date('Y-M-d');

// Construire les paramètres dynamiques de l'URL
$startTime = urlencode("$today 10:00");
$stopTime = urlencode("$today 11:00");

// Construire l'URL de l'API
$url = "https://ssd.jpl.nasa.gov/api/horizons.api?format=json&COMMAND='-32'&TABLE_TYPE='VECTOR'&START_TIME='$startTime'&STOP_TIME='$stopTime'&STEP_SIZE='1%20h'";

// Initialiser cURL
$ch = curl_init();

// Configurer cURL
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

// Exécuter la requête et récupérer le JSON brut
$response = curl_exec($ch);

// Gérer les erreurs éventuelles
if (curl_errno($ch)) {
    echo "Erreur de cURL : " . curl_error($ch);
    curl_close($ch);
    exit;
}

// Fermer la session cURL
curl_close($ch);

// Afficher directement le JSON brut
header('Content-Type: application/json');
echo $response;
?>