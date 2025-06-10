<?php
header('Content-Type: application/json');

try {
    $db = new PDO('sqlite:szofer.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Build the base query
    $sql = "SELECT * FROM statistic WHERE 1=1";
    $params = [];
    
    // Add date range conditions
    if (!empty($_GET['from'])) {
        $sql .= " AND time_added >= :from";
        $params[':from'] = $_GET['from'];
    }
    
    if (!empty($_GET['to'])) {
        $sql .= " AND time_added <= :to";
        $params[':to'] = $_GET['to'];
    }
    
    // Add line filter if provided
    if (!empty($_GET['line'])) {
        $sql .= " AND vehicle_line = :line";
        $params[':line'] = $_GET['line'];
    }
    
    // Order by time
    $sql .= " ORDER BY time_added DESC";
    
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($results);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}