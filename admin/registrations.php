<?php
require_once __DIR__ . '/../config.php';
requireLogin();

$pageTitle = '신규등록 신청 관리';
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> - M&A LEADER 관리자</title>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/styles.css">
    <link rel="stylesheet" href="<?php echo ADMIN_URL; ?>/admin.css">
</head>
<body class="admin-body">
    <?php include __DIR__ . '/admin-header.php'; ?>
    <div class="admin-container">
        <aside class="admin-sidebar">
            <?php include __DIR__ . '/admin-sidebar.php'; ?>
        </aside>
        <main class="admin-content">
            <div class="admin-page-header">
                <h1>📋 신규등록 신청 관리</h1>
                <p>이 페이지는 준비 중입니다.</p>
            </div>
            <div class="panel">
                <div class="panel-body" style="padding: 2rem; text-align: center; color: #6b7280;">
                    상세 관리 UI는 다음 단계에서 구현됩니다. 좌측 메뉴의 "양도기업 관리"는 사용 가능합니다.
                </div>
            </div>
        </main>
    </div>
</body>
</html>
