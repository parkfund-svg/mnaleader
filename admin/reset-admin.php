<?php
require_once __DIR__ . '/../config.php';

header('Content-Type: text/plain; charset=utf-8');

try {
    $pdo = getDBConnection();

    $username = 'admin';
    $newPassword = 'admin123!';
    $hash = password_hash($newPassword, PASSWORD_DEFAULT);

    // 존재 여부 확인
    $stmt = $pdo->prepare("SELECT id FROM admins WHERE username = ?");
    $stmt->execute([$username]);
    $row = $stmt->fetch();

    if ($row) {
        $upd = $pdo->prepare("UPDATE admins SET password = ?, is_active = 1, role = COALESCE(role, 'super_admin') WHERE id = ?");
        $upd->execute([$hash, $row['id']]);
        echo "관리자 비밀번호 재설정 완료\n";
        echo "아이디: admin\n비밀번호: admin123!\n";
    } else {
        $ins = $pdo->prepare("INSERT INTO admins (username, password, name, role, is_active, created_at) VALUES (?, ?, ?, ?, 1, NOW())");
        $ins->execute([$username, $hash, '관리자', 'super_admin']);
        echo "관리자 계정 생성 완료\n";
        echo "아이디: admin\n비밀번호: admin123!\n";
    }

    echo "로그인 후 반드시 비밀번호를 변경하세요.";

} catch (Exception $e) {
    http_response_code(500);
    echo "오류: " . $e->getMessage();
}
