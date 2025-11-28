<?php
// Simple import test script - posts a sample CSV to import_employee.php
$target = __DIR__ . '/../import_employee.php';
$csv = __DIR__ . '/sample_import.csv';
if (!file_exists($csv)) {
    file_put_contents($csv, "staff1,user1,User One,group,2023-10-01,2023-12-31,09:00,17:00,user1@example.com,CO1\nstaff2,user2,User Two,group,2023-10-01,2023-12-31,09:00,17:00,user2@example.com,CO1\n");
}

// Use PHP stream to POST multipart/form-data
$boundary = '----WebKitFormBoundary' . md5(time());
$eol = "\r\n";
$body = '';
$body .= "--" . $boundary . $eol;
$body .= 'Content-Disposition: form-data; name="employee_file"; filename="sample_import.csv"' . $eol;
$body .= 'Content-Type: text/csv' . $eol . $eol;
$body .= file_get_contents($csv) . $eol;
$body .= "--" . $boundary . "--" . $eol;

$opts = [
    'http' => [
        'method' => 'POST',
        'header' => "Content-Type: multipart/form-data; boundary=" . $boundary . $eol,
        'content' => $body
    ]
];
$context = stream_context_create($opts);
$url = 'http://localhost/RUT24X/backend/import_employee.php';
$result = @file_get_contents($url, false, $context);
if ($result === false) {
    echo "Request failed. Check webserver and script path.\n";
    var_dump($http_response_header);
    exit(1);
}

echo "Response:\n" . $result . "\n";