<?php
require_once __DIR__ . '/../config.php';
requireLogin();

$pdo = getDBConnection();

// 통계 데이터 가져오기
$stats = [
    'pending_consultations' => 0,
    'total_consultations' => 0,
    'active_transfers' => 0,
    'total_transfers' => 0,
    'pending_registrations' => 0,
    'total_registrations' => 0,
    'today_consultations' => 0
];

try {
    // 상담신청 통계
    $stmt = $pdo->query("SELECT COUNT(*) FROM consultations WHERE status = 'pending'");
    $stats['pending_consultations'] = $stmt->fetchColumn();
    
    $stmt = $pdo->query("SELECT COUNT(*) FROM consultations");
    $stats['total_consultations'] = $stmt->fetchColumn();
    
    $stmt = $pdo->query("SELECT COUNT(*) FROM consultations WHERE DATE(created_at) = CURDATE()");
    $stats['today_consultations'] = $stmt->fetchColumn();
    
    // 양도기업 통계
    $stmt = $pdo->query("SELECT COUNT(*) FROM transfer_companies WHERE status = 'available' AND is_published = 1");
    $stats['active_transfers'] = $stmt->fetchColumn();
    
    $stmt = $pdo->query("SELECT COUNT(*) FROM transfer_companies");
    $stats['total_transfers'] = $stmt->fetchColumn();
    
    // 신규등록 통계
    $stmt = $pdo->query("SELECT COUNT(*) FROM new_registrations WHERE status = 'pending'");
    $stats['pending_registrations'] = $stmt->fetchColumn();
    
    $stmt = $pdo->query("SELECT COUNT(*) FROM new_registrations");
    $stats['total_registrations'] = $stmt->fetchColumn();
    
    // 최근 상담신청
    $recentConsultations = $pdo->query("
        SELECT id, name, phone, consultation_type, status, created_at 
        FROM consultations 
        ORDER BY created_at DESC 
        LIMIT 10
    ")->fetchAll();
    
    // 최근 신규등록 신청
    $recentRegistrations = $pdo->query("
        SELECT id, applicant_name, phone, consult_type, status, created_at 
        FROM new_registrations 
        ORDER BY created_at DESC 
        LIMIT 5
    ")->fetchAll();
    
} catch (Exception $e) {
    error_log($e->getMessage());
}

$pageTitle = '대시보드';
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
                <h1>📊 대시보드</h1>
                <p>M&A LEADER 관리 현황</p>
            </div>
            
            <!-- 통계 카드 -->
            <div class="stats-grid">
                <div class="stat-card stat-primary">
                    <div class="stat-icon">📝</div>
                    <div class="stat-info">
                        <div class="stat-label">대기 중 상담</div>
                        <div class="stat-value"><?php echo number_format($stats['pending_consultations']); ?></div>
                        <div class="stat-meta">전체: <?php echo number_format($stats['total_consultations']); ?>건</div>
                    </div>
                </div>
                
                <div class="stat-card stat-success">
                    <div class="stat-icon">🏢</div>
                    <div class="stat-info">
                        <div class="stat-label">공개 양도기업</div>
                        <div class="stat-value"><?php echo number_format($stats['active_transfers']); ?></div>
                        <div class="stat-meta">전체: <?php echo number_format($stats['total_transfers']); ?>개</div>
                    </div>
                </div>
                
                <div class="stat-card stat-warning">
                    <div class="stat-icon">📋</div>
                    <div class="stat-info">
                        <div class="stat-label">신규등록 대기</div>
                        <div class="stat-value"><?php echo number_format($stats['pending_registrations']); ?></div>
                        <div class="stat-meta">전체: <?php echo number_format($stats['total_registrations']); ?>건</div>
                    </div>
                </div>
                
                <div class="stat-card stat-info">
                    <div class="stat-icon">📅</div>
                    <div class="stat-info">
                        <div class="stat-label">오늘 상담신청</div>
                        <div class="stat-value"><?php echo number_format($stats['today_consultations']); ?></div>
                        <div class="stat-meta">금일 기준</div>
                    </div>
                </div>
            </div>
            
            <!-- 최근 활동 -->
            <div class="dashboard-panels">
                <div class="panel">
                    <div class="panel-header">
                        <h3>📬 최근 상담신청</h3>
                        <a href="<?php echo ADMIN_URL; ?>/consultations.php" class="btn-small">전체보기</a>
                    </div>
                    <div class="panel-body">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>이름</th>
                                    <th>연락처</th>
                                    <th>상담구분</th>
                                    <th>상태</th>
                                    <th>신청일시</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($recentConsultations)): ?>
                                    <tr><td colspan="5" style="text-align:center; padding:40px;">상담신청 내역이 없습니다.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($recentConsultations as $consult): ?>
                                        <tr>
                                            <td><?php echo sanitize($consult['name']); ?></td>
                                            <td><?php echo sanitize($consult['phone']); ?></td>
                                            <td><?php echo sanitize($consult['consultation_type']); ?></td>
                                            <td>
                                                <span class="badge badge-<?php echo $consult['status']; ?>">
                                                    <?php 
                                                    $statusLabels = ['pending' => '대기', 'in_progress' => '진행중', 'completed' => '완료', 'cancelled' => '취소'];
                                                    echo $statusLabels[$consult['status']] ?? $consult['status'];
                                                    ?>
                                                </span>
                                            </td>
                                            <td><?php echo formatDate($consult['created_at']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="panel">
                    <div class="panel-header">
                        <h3>🏗️ 최근 신규등록 신청</h3>
                        <a href="<?php echo ADMIN_URL; ?>/registrations.php" class="btn-small">전체보기</a>
                    </div>
                    <div class="panel-body">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>신청자</th>
                                    <th>연락처</th>
                                    <th>상담구분</th>
                                    <th>상태</th>
                                    <th>신청일시</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($recentRegistrations)): ?>
                                    <tr><td colspan="5" style="text-align:center; padding:40px;">신규등록 신청 내역이 없습니다.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($recentRegistrations as $reg): ?>
                                        <tr>
                                            <td><?php echo sanitize($reg['applicant_name']); ?></td>
                                            <td><?php echo sanitize($reg['phone']); ?></td>
                                            <td><?php echo sanitize($reg['consult_type']); ?></td>
                                            <td>
                                                <span class="badge badge-<?php echo $reg['status']; ?>">
                                                    <?php 
                                                    $statusLabels = ['pending' => '대기', 'in_progress' => '진행중', 'completed' => '완료', 'cancelled' => '취소'];
                                                    echo $statusLabels[$reg['status']] ?? $reg['status'];
                                                    ?>
                                                </span>
                                            </td>
                                            <td><?php echo formatDate($reg['created_at']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
