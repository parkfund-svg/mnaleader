<?php
require_once __DIR__ . '/../config.php';

// 로그아웃 처리
if (isset($_SESSION['admin_id'])) {
    logAdminActivity('로그아웃', 'admin', $_SESSION['admin_id']);
}

// 세션 파괴
session_destroy();

// 로그인 페이지로 리다이렉트
redirect(ADMIN_URL . '/login.php?logout=1');
