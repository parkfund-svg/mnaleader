<?php
require_once __DIR__ . '/../config.php';

header('Content-Type: text/plain; charset=utf-8');

try {
    $pdo = getDBConnection();

    // 기본 관리자 존재 여부 확인
    $stmt = $pdo->prepare("SELECT id, username FROM admins WHERE username = ?");
    $stmt->execute(['admin']);
    $admin = $stmt->fetch();

    if ($admin) {
        echo "이미 관리자 계정이 존재합니다: admin\n";
        echo "필요 시 phpMyAdmin에서 비밀번호를 재설정하세요.";
        exit;
    }

    // 관리자 계정 생성
    $passwordHash = password_hash('admin123!', PASSWORD_DEFAULT);
    $insert = $pdo->prepare("INSERT INTO admins (username, password, name, role, is_active, created_at) VALUES (?, ?, ?, ?, 1, NOW())");
    $insert->execute(['admin', $passwordHash, '관리자', 'super_admin']);

    echo "관리자 계정 생성 완료\n";
    echo "아이디: admin\n비밀번호: admin123!\n";
    echo "로그인 후 반드시 비밀번호를 변경하세요.";

} catch (Exception $e) {
    http_response_code(500);
    echo "오류: " . $e->getMessage();
}
