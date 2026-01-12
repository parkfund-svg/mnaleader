<?php
require_once __DIR__ . '/../config.php';
requireLogin();

header('Content-Type: application/json');

// JSON 입력 받기
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!isset($data['id']) || !is_numeric($data['id'])) {
    jsonResponse(['success' => false, 'message' => '유효하지 않은 요청입니다.']);
}

$id = intval($data['id']);

try {
    $pdo = getDBConnection();
    
    // 삭제할 기업 정보 가져오기
    $stmt = $pdo->prepare("SELECT company_name FROM transfer_companies WHERE id = ?");
    $stmt->execute([$id]);
    $company = $stmt->fetch();
    
    if (!$company) {
        jsonResponse(['success' => false, 'message' => '존재하지 않는 데이터입니다.']);
    }
    
    // 삭제 실행
    $stmt = $pdo->prepare("DELETE FROM transfer_companies WHERE id = ?");
    $stmt->execute([$id]);
    
    // 관리자 활동 로그
    logAdminActivity('양도기업 삭제', 'transfer_company', $id, $company['company_name']);
    
    jsonResponse([
        'success' => true,
        'message' => '삭제되었습니다.'
    ]);
    
} catch (Exception $e) {
    error_log($e->getMessage());
    jsonResponse([
        'success' => false,
        'message' => '삭제 중 오류가 발생했습니다.'
    ]);
}
