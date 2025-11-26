<?php
header('Content-Type: application/json');
require_once __DIR__ . '/config/Database.php';

// Only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$db = new Database();
$conn = $db->connect();
if (!$conn) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

$contentType = isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : '';

// 1) Multipart file upload (CSV)
if (strpos($contentType, 'multipart/form-data') !== false && isset($_FILES['employee_file'])) {
    $file = $_FILES['employee_file'];
    if ($file['error'] !== UPLOAD_ERR_OK) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Upload error']);
        exit;
    }

    $tmp = $file['tmp_name'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if ($ext !== 'csv') {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Only CSV uploads are supported server-side']);
        exit;
    }

    if (($handle = fopen($tmp, 'r')) === false) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to open uploaded file']);
        exit;
    }

    $inserted = 0;
    $stmt = $conn->prepare('INSERT INTO tbl_importtest (staff_id, user_id, user_name, group_app, start_date, end_date, start_time, end_time, email, co_code) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');

    $first = true;
    while (($row = fgetcsv($handle)) !== false) {
        // Skip empty rows
        if (!is_array($row) || count($row) === 0) continue;

        // Detect header row on first iteration and skip if it contains textual headers
        if ($first) {
            $first = false;
            $sample = strtolower(implode(' ', array_slice($row, 0, 6)));
            if (preg_match('/id|staff|user|name|group|email/', $sample)) {
                continue; // skip header
            }
        }

        // Expect columns in this order: staff_id, user_id, user_name, group_app, start_date, end_date, start_time, end_time, email, co_code
        if (count($row) < 10) continue;

        $staff_id = trim($row[0]);
        $user_id = trim($row[1]);
        if ($staff_id === '' && $user_id === '') continue; // require at least one identifier

        try {
            $stmt->execute([
                $staff_id,
                $user_id,
                trim($row[2]),
                trim($row[3]),
                trim($row[4]),
                trim($row[5]),
                trim($row[6]),
                trim($row[7]),
                trim($row[8]),
                trim($row[9])
            ]);
            $inserted++;
        } catch (Exception $e) {
            // skip row on error
            continue;
        }
    }

    fclose($handle);
    echo json_encode(['success' => true, 'inserted' => $inserted]);
    exit;
}

// 2) JSON payload with rows (used by client-side parsed Excel)
$raw = file_get_contents('php://input');
$data = json_decode($raw, true);
if (!is_array($data) || !isset($data['rows']) || !is_array($data['rows'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid payload']);
    exit;
}

$rows = $data['rows'];
if (count($rows) === 0) {
    echo json_encode(['success' => true, 'inserted' => 0]);
    exit;
}

try {
    $conn->beginTransaction();
    $stmt = $conn->prepare('INSERT INTO tbl_importtest (staff_id, user_id, user_name, group_app, start_date, end_date, start_time, end_time, email, co_code) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');

    $inserted = 0;
    foreach ($rows as $r) {
        $staff_id = isset($r['staff_id']) ? trim($r['staff_id']) : '';
        $user_id = isset($r['user_id']) ? trim($r['user_id']) : '';
        if ($staff_id === '' && $user_id === '') continue;

        $stmt->execute([
            $staff_id,
            $user_id,
            isset($r['user_name']) ? trim($r['user_name']) : '',
            isset($r['group_app']) ? trim($r['group_app']) : '',
            isset($r['start_date']) ? trim($r['start_date']) : '',
            isset($r['end_date']) ? trim($r['end_date']) : '',
            isset($r['start_time']) ? trim($r['start_time']) : '',
            isset($r['end_time']) ? trim($r['end_time']) : '',
            isset($r['email']) ? trim($r['email']) : '',
            isset($r['co_code']) ? trim($r['co_code']) : ''
        ]);
        $inserted++;
    }
    $conn->commit();
    echo json_encode(['success' => true, 'inserted' => $inserted]);
} catch (Exception $e) {
    $conn->rollBack();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}