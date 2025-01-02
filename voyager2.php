<?php
// Fonction pour obtenir l'URL formatée avec les dates actuelles
function getHorizonsApiUrl() {
    $startTime = date('Y-M-d H:i', strtotime('today 10:00'));
    $stopTime = date('Y-M-d H:i', strtotime('today 11:00'));

    return "https://ssd.jpl.nasa.gov/api/horizons.api?format=json&COMMAND='-32'&TABLE_TYPE='VECTOR'&START_TIME='" . urlencode($startTime) . "'&STOP_TIME='" . urlencode($stopTime) . "'&STEP_SIZE='1%20h'";
}

// Obtenir l'URL
$url = getHorizonsApiUrl();

// Initialiser la session cURL
$ch = curl_init();

// Configurer les options de cURL
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

// Exécuter la requête
$response = curl_exec($ch);

// Vérifier les erreurs
if (curl_errno($ch)) {
    echo "Erreur : " . curl_error($ch);
    exit;
}

// Fermer la session cURL
curl_close($ch);

// Décoder la réponse JSON
$data = json_decode($response, true);

// Afficher les données
if ($data) {
    echo "<pre>";
    print_r($data);
    echo "</pre>";
} else {
    echo "Impossible de décoder le JSON.";
}
?>