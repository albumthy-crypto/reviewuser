<?php
header('Content-Type: application/json');
require_once __DIR__ . '/config/Database.php';
$db = new Database();
$conn = $db->connect();
if (!$conn) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

// Try to include Composer autoloader for PhpSpreadsheet (optional)
$composerAutoload = __DIR__ . '/../vendor/autoload.php';
if (file_exists($composerAutoload)) {
    require_once $composerAutoload;
}

$contentType = isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : '';

// 1) Multipart file upload (CSV)
if (strpos($contentType, 'multipart/form-data') !== false && isset($_FILES['employee_file'])) {
    $file = $_FILES['employee_file'];

    // Basic upload errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Upload error', 'code' => 'UPLOAD_ERROR']);
        exit;
    }

    // Ensure file was uploaded via HTTP POST
    if (!is_uploaded_file($file['tmp_name'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid upload', 'code' => 'INVALID_UPLOAD']);
        exit;
    }

    // Size check (10 MB)
    $maxBytes = 10 * 1024 * 1024;
    if ($file['size'] > $maxBytes) {
        http_response_code(413);
        echo json_encode(['success' => false, 'message' => 'File too large (max 10 MB)', 'code' => 'FILE_TOO_LARGE']);
        exit;
    }

    $tmp = $file['tmp_name'];
    $origName = $file['name'];
    $ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));

    $allowedExt = ['csv', 'xls', 'xlsx'];
    if (!in_array($ext, $allowedExt, true)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Unsupported file type', 'code' => 'UNSUPPORTED_TYPE']);
        exit;
    }

    $rows = [];
    // CSV path
    if ($ext === 'csv') {
        if (($handle = fopen($tmp, 'r')) === false) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to open uploaded CSV', 'code' => 'OPEN_FAILED']);
            exit;
        }
        while (($row = fgetcsv($handle)) !== false) {
            // normalize empty rows
            $allEmpty = true; foreach ($row as $c) { if (trim($c) !== '') { $allEmpty = false; break; } }
            if ($allEmpty) continue;
            $rows[] = $row;
        }
        fclose($handle);
    } else {
        // Excel path - requires PhpSpreadsheet
        if (!class_exists('PhpOffice\PhpSpreadsheet\IOFactory')) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Server is missing PhpSpreadsheet. Run: composer require phpoffice/phpspreadsheet',
                'code' => 'MISSING_DEP'
            ]);
            exit;
        }

        try {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($tmp);
            $sheet = $spreadsheet->getActiveSheet();
            foreach ($sheet->getRowIterator() as $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);
                $dataRow = [];
                foreach ($cellIterator as $cell) {
                    $dataRow[] = trim((string)$cell->getValue());
                }
                // skip empty rows
                $allEmpty = true; foreach ($dataRow as $c) { if ($c !== '') { $allEmpty = false; break; } }
                if ($allEmpty) continue;
                $rows[] = $dataRow;
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to parse Excel file', 'code' => 'PARSE_FAILED']);
            exit;
        }
    }

    // basic header detection: skip first row if it looks like headers
    if (count($rows) && is_array($rows[0])) {
        $first0 = strtolower(isset($rows[0][0]) ? $rows[0][0] : '');
        if (preg_match('/name|staff|user|email|id/', $first0)) {
            array_shift($rows);
        }
    }

    $inserted = 0;
    foreach ($rows as $row) {
        if (!is_array($row)) continue;
        if (count($row) < 10) continue; // must have at least 10 columns
        try {
            $stmt = $conn->prepare("
                        INSERT INTO tbl_importtest 
                        (Periodid, staff_id, user_id, user_name, group_app, start_date, end_date, start_time, end_time, email, co_code)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                    ");

            $stmt->execute([
                        $reviewPeriod,             // <- numeric period ID
                        trim($row[0]),
                        trim($row[1]),
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
            // skip problematic row
            error_log('Import row failed: ' . $e->getMessage());
            continue;
        }
    }

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
    $stmt = $conn->prepare("
    INSERT INTO tbl_importtest 
    (Periodid, staff_id, user_id, user_name, group_app, start_date, end_date, start_time, end_time, email, co_code)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");


    $inserted = 0;
    foreach ($rows as $r) {
        $staff_id = isset($r['staff_id']) ? trim($r['staff_id']) : '';
        $user_id = isset($r['user_id']) ? trim($r['user_id']) : '';
        if ($staff_id === '' && $user_id === '') continue;

        $stmt->execute([
            $reviewPeriod,            // <- numeric period ID
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