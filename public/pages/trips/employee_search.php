<?php
// Deprecated AJAX endpoint no longer used (employee selection inline now).
header('Content-Type: application/json');
echo json_encode(['data' => [], 'deprecated' => true]);
