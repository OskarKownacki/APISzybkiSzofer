<?php
header('Content-Type: application/json');
$url = 'https://www.zditm.szczecin.pl/api/v1/vehicles';

try {
    $response = file_get_contents($url);
    $data = json_decode($response, true);
    
    // Filter data if q parameter exists
    if(isset($_GET['q']) && !empty($_GET['q'])) {
        $q = $_GET['q'];
        $filteredData = array_filter($data["data"], function($bus) use ($q) {
            return $bus["line_number"] == $q;
        });
        // Reindex array after filtering
        echo json_encode(array_values($filteredData));
    } else {
        // Return empty array if no filter
        echo json_encode([]);
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>