<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

require_once dirname(__DIR__) . '/includes/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false]);
    exit;
}

$email = trim((string)($_POST['email'] ?? ''));
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['ok' => false, 'message' => 'Email tidak valid']);
    exit;
}

$pdo = db();
try {
    $pdo->prepare('INSERT INTO newsletter_subscribers (email) VALUES (?)')->execute([$email]);
} catch (PDOException $e) {
    if ($e->getCode() === '23000' || strpos($e->getMessage(), 'Duplicate') !== false) {
        echo json_encode(['ok' => true, 'message' => 'Email Anda sudah terdaftar. Terima kasih!']);
        exit;
    }
    echo json_encode(['ok' => false, 'message' => 'Gagal menyimpan']);
    exit;
}

echo json_encode(['ok' => true, 'message' => 'Terima kasih! Anda berhasil berlangganan.']);
