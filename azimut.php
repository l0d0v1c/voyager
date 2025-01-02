<?php
// Vérifier que le paramètre SITE_COORD est passé dans l'URL
if (!isset($_GET['SITE_COORD']) || empty($_GET['SITE_COORD'])) {
    die("Erreur : Le paramètre 'SITE_COORD' est obligatoire.");
}

// Récupérer les coordonnées du site
$site_coord = $_GET['SITE_COORD'];

// Récupérer le paramètre FULL, par défaut 'NO'
$full = isset($_GET['FULL']) && $_GET['FULL'] === 'YES' ? 'YES' : 'NO';

// Définir le fuseau horaire à UTC/GMT
date_default_timezone_set('UTC');

// Calcul des heures dynamiques
$start_time = date('Y-m-d H:i:s', strtotime('-1 hour'));
$stop_time = date('Y-m-d H:i:s', strtotime('+1 hour'));

// Construire l'URL de l'API
$url = "https://ssd.jpl.nasa.gov/horizons_batch.cgi?batch=1"
    . "&COMMAND='-32'"
    . "&CENTER='coord@399'"
    . "&COORD_TYPE='GEODETIC'"
    . "&SITE_COORD=" . urlencode($site_coord)
    . "&MAKE_EPHEM='YES'"
    . "&TABLE_TYPE='OBSERVER'"
    . "&START_TIME='" . urlencode($start_time) . "'"
    . "&STOP_TIME='" . urlencode($stop_time) . "'"
    . "&STEP_SIZE='1 m'"
    . "&QUANTITIES='4'"
    . "&FULL=" . urlencode($full);

// Utiliser cURL pour effectuer la requête
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
curl_close($ch);

// Vérification de la réponse
if ($response === false) {
    die("Erreur : Impossible de récupérer les données depuis Horizons.");
}

// Traiter les données en fonction de l'option FULL
if ($full === 'NO') {
    $lines = explode("\n", $response);
    $soe_found = false;
    foreach ($lines as $line) {
        if (strpos($line, '$$SOE') !== false) {
            $soe_found = true;
            continue; // Passer à la ligne suivante après $$SOE
        }
        if ($soe_found) {
            echo "<pre>" . htmlspecialchars($line) . "</pre>";
            exit; // Arrêter après la première ligne
        }
    }
    echo "Aucune donnée trouvée après $$SOE.";
} else {
    // Afficher toute la réponse si FULL=YES
    echo "<pre>" . htmlspecialchars($response) . "</pre>";
}
?>