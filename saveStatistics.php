<?php
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php:://input'), true);

if(!$data || !isset($data['dataArray'])){
    throw new Exception('Invalid input data');
}

$db = new PDO('sqlite:szofer.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Prepare the insert statement
    $stmt = $db->prepare("INSERT INTO vehicles (vehicle_id, velocity) VALUES (:vehicle_id, :velocity)");

     foreach ($data['dataArray'] as $vehicle) {
        try {
            // Validate required fields
            if (empty($vehicle['vehicle_id']) || !isset($vehicle['velocity'])) {
                $errors[] = "Missing fields for vehicle: " . json_encode($vehicle);
                continue;
            }
            
            // Bind parameters and execute
            $stmt->bindParam(':vehicle_id', $vehicle['vehicle_id']);
            $stmt->bindParam(':velocity', $vehicle['velocity']);
            $stmt->execute();
            
            $inserted++;
        } catch (PDOException $e) {
            $errors[] = "Error saving vehicle {$vehicle['vehicle_id']}: " . $e->getMessage();
        }
    }
      $response = [
        'status' => 'success',
        'inserted' => $inserted,
        'errors' => $errors
    ];
    
    if ($inserted === 0 && !empty($errors)) {
        $response['status'] = 'error';
    }
    
    echo json_encode($response);
?>