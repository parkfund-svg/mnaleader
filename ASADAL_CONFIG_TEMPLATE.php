<?php
/**
 * 아사달 호스팅용 config.php 템플릿
 * 
 * 사용법:
 * 1. 이 파일을 config.php로 복사
 * 2. 아래 "변경 필요" 부분을 아사달 호스팅 정보로 수정
 * 3. FTP로 업로드
 */

// ============================================
// 운영 환경 설정
// ============================================
error_reporting(0); // 에러 표시 비활성화
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/logs/php_errors.log');

// 타임존 설정
date_default_timezone_set('Asia/Seoul');

// 세션 설정
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 1); // HTTPS 사용 시 1로 설정
session_start();

// ============================================
// 데이터베이스 설정 (변경 필요!)
// ============================================
// 아사달 관리자 페이지에서 확인한 정보로 변경하세요
define('DB_HOST', 'localhost'); // 또는 아사달에서 제공한 호스트
define('DB_NAME', '아사달_데이터베이스명'); // 실제 데이터베이스 이름
define('DB_USER', '아사달_데이터베이스_사용자명'); // 실제 사용자명
define('DB_PASS', '아사달_데이터베이스_비밀번호'); // 실제 비밀번호
define('DB_CHARSET', 'utf8mb4');

// ============================================
// 사이트 설정 (변경 필요!)
// ============================================
define('SITE_URL', 'https://your-domain.com'); // 실제 도메인으로 변경
define('ADMIN_URL', SITE_URL . '/admin');
define('SITE_NAME', '(주)엠앤에이리더 M&A LEADER');

// 경로 설정
define('ROOT_PATH', __DIR__);
define('INCLUDES_PATH', ROOT_PATH . '/includes');
define('ADMIN_PATH', ROOT_PATH . '/admin');
define('UPLOAD_PATH', ROOT_PATH . '/uploads');

// ============================================
// 보안 설정 (변경 필요!)
// ============================================
// PASSWORD_SALT를 랜덤 문자열로 변경하세요!
define('PASSWORD_SALT', 'MNA_LEADER_2026_SECRET_KEY_변경필요'); 
define('SESSION_TIMEOUT', 3600); // 1시간 (초 단위)

// 페이지네이션 설정
define('ITEMS_PER_PAGE', 20);
define('TRANSFER_LIST_PER_PAGE', 12);

// 파일 업로드 설정
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'xls', 'xlsx']);

// ============================================
// 이메일 설정 (선택사항)
// ============================================
// 아사달 SMTP 서버 정보를 사용할 수 있습니다
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'your-email@gmail.com');
define('SMTP_PASS', 'your-password');
define('SMTP_FROM', 'noreply@mnaleader.co.kr');
define('SMTP_FROM_NAME', 'M&A LEADER');

// ============================================
// 데이터베이스 연결 함수
// ============================================
function getDBConnection() {
    static $pdo = null;
    
    if ($pdo === null) {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . DB_CHARSET
            ];
            
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            error_log("Database Connection Error: " . $e->getMessage());
            die("데이터베이스 연결에 실패했습니다. 관리자에게 문의하세요.");
        }
    }
    
    return $pdo;
}

// ============================================
// 유틸리티 함수들
// ============================================
function sanitize($data) {
    if (is_array($data)) {
        return array_map('sanitize', $data);
    }
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

function redirect($url) {
    header("Location: " . $url);
    exit;
}

function isLoggedIn() {
    return isset($_SESSION['admin_id']) && 
           isset($_SESSION['last_activity']) && 
           (time() - $_SESSION['last_activity']) < SESSION_TIMEOUT;
}

function requireLogin() {
    if (!isLoggedIn()) {
        redirect(ADMIN_URL . '/login.php');
    }
    $_SESSION['last_activity'] = time();
}

function formatPrice($amount) {
    if ($amount === null || $amount === '') return '-';
    if ($amount >= 100000000) {
        return number_format($amount / 100000000, 1) . '억원';
    }
    return number_format($amount / 10000) . '만원';
}

function formatDate($datetime, $format = 'Y-m-d H:i') {
    if (!$datetime) return '-';
    return date($format, strtotime($datetime));
}

function logAdminActivity($action, $target_type = null, $target_id = null, $details = null) {
    if (!isset($_SESSION['admin_id'])) return;
    
    try {
        $pdo = getDBConnection();
        $stmt = $pdo->prepare("
            INSERT INTO admin_logs (admin_id, action, target_type, target_id, details, ip_address)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $_SESSION['admin_id'],
            $action,
            $target_type,
            $target_id,
            $details,
            $_SERVER['REMOTE_ADDR']
        ]);
    } catch (Exception $e) {
        error_log("Log Error: " . $e->getMessage());
    }
}

function getSetting($key, $default = '') {
    static $settings = null;
    
    if ($settings === null) {
        $pdo = getDBConnection();
        $stmt = $pdo->query("SELECT setting_key, setting_value FROM site_settings");
        $settings = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    }
    
    return isset($settings[$key]) ? $settings[$key] : $default;
}

function jsonResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}
