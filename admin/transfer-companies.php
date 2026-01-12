<?php
require_once __DIR__ . '/../config.php';
requireLogin();

$pdo = getDBConnection();

// 검색 및 필터링
$search = $_GET['search'] ?? '';
$business_type = $_GET['business_type'] ?? [];
$status = $_GET['status'] ?? '';
$page = max(1, intval($_GET['page'] ?? 1));
$perPage = 20;
$offset = ($page - 1) * $perPage;

// WHERE 조건 구성
$where = ['1=1'];
$params = [];

if ($search) {
    $where[] = "(company_name LIKE ? OR license_type LIKE ? OR location LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if (!empty($business_type)) {
    if (!is_array($business_type)) {
        $business_type = [$business_type];
    }
    $placeholders = implode(',', array_fill(0, count($business_type), '?'));
    $where[] = "business_type IN ($placeholders)";
    foreach ($business_type as $bt) {
        $params[] = $bt;
    }
}

if ($status) {
    $where[] = "status = ?";
    $params[] = $status;
}

$whereClause = implode(' AND ', $where);

// 전체 개수
$countStmt = $pdo->prepare("SELECT COUNT(*) FROM transfer_companies WHERE $whereClause");
$countStmt->execute($params);
$totalItems = $countStmt->fetchColumn();
$totalPages = ceil($totalItems / $perPage);

// 데이터 조회
$sql = "SELECT * FROM transfer_companies WHERE $whereClause ORDER BY created_at DESC LIMIT $perPage OFFSET $offset";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$companies = $stmt->fetchAll();

// 업종 목록
$businessTypes = [
    '종합건설업' => '종합건설업',
    '전문건설업' => '전문건설업',
    '전기공사업' => '전기공사업',
    '정보통신공사업' => '정보통신공사업',
    '소방공사업' => '소방공사업',
    '기계설비공사업' => '기계설비공사업'
];

$pageTitle = '양도기업 관리';
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
                <div>
                    <h1>🏢 양도기업 관리</h1>
                    <p>등록된 양도기업 목록 및 관리</p>
                </div>
                <a href="<?php echo ADMIN_URL; ?>/transfer-company-edit.php" class="btn-action btn-primary">➕ 새 양도기업 등록</a>
            </div>
            
            <!-- 검색 및 필터 -->
            <div class="panel" style="margin-bottom: 1.5rem;">
                <div class="panel-body" style="padding: 1.5rem;">
                    <form method="GET" action="" style="display: flex; gap: 1rem; flex-wrap: wrap;">
                        <div style="flex: 1; min-width: 200px;">
                            <input type="text" 
                                   name="search" 
                                   placeholder="회사명, 면허종류, 지역 검색..." 
                                   value="<?php echo sanitize($search); ?>"
                                   style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 5px;">
                        </div>
                        
                        <div style="display:flex; gap:0.5rem; align-items:center; flex-wrap:wrap;">
                            <span style="font-size:0.9rem; color:#374151; margin-right:0.25rem;">업종:</span>
                            <?php foreach ($businessTypes as $key => $label): ?>
                                <label style="display:inline-flex; align-items:center; gap:0.35rem; padding:0.25rem 0.5rem; border:1px solid #e5e7eb; border-radius:6px; background:#fff;">
                                    <input type="checkbox" name="business_type[]" value="<?php echo $key; ?>" <?php echo (is_array($business_type) && in_array($key, $business_type)) ? 'checked' : ''; ?> />
                                    <span style="font-size:0.9rem; color:#111827;"><?php echo $label; ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                        
                        <select name="status" style="padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 5px;">
                            <option value="">전체 상태</option>
                            <option value="available" <?php echo $status === 'available' ? 'selected' : ''; ?>>거래가능</option>
                            <option value="negotiating" <?php echo $status === 'negotiating' ? 'selected' : ''; ?>>협상중</option>
                            <option value="completed" <?php echo $status === 'completed' ? 'selected' : ''; ?>>거래완료</option>
                            <option value="cancelled" <?php echo $status === 'cancelled' ? 'selected' : ''; ?>>취소</option>
                        </select>
                        
                        <button type="submit" class="btn-action btn-primary">🔍 검색</button>
                        <a href="<?php echo ADMIN_URL; ?>/transfer-companies.php" class="btn-action btn-secondary">초기화</a>
                    </form>
                </div>
            </div>
            
            <!-- 결과 -->
            <div class="panel">
                <div class="panel-header">
                    <h3>총 <?php echo number_format($totalItems); ?>개 기업</h3>
                </div>
                <div class="panel-body">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>회사명</th>
                                <th>업종</th>
                                <th>면허</th>
                                <th>자본금</th>
                                <th>희망가</th>
                                <th>지역</th>
                                <th>상태</th>
                                <th>조회수</th>
                                <th>등록일</th>
                                <th>관리</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($companies)): ?>
                                <tr><td colspan="11" style="text-align:center; padding:60px 20px; color:#9ca3af;">등록된 양도기업이 없습니다.</td></tr>
                            <?php else: ?>
                                <?php foreach ($companies as $company): ?>
                                    <tr>
                                        <td><?php echo $company['id']; ?></td>
                                        <td>
                                            <strong><?php echo sanitize($company['company_name']); ?></strong>
                                            <?php if ($company['is_featured']): ?>
                                                <span style="color: #f59e0b;">⭐</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo sanitize($company['business_type']); ?></td>
                                        <td><?php echo sanitize($company['license_type']); ?></td>
                                        <td><?php echo formatPrice($company['capital_amount']); ?></td>
                                        <td><?php echo formatPrice($company['asking_price']); ?></td>
                                        <td><?php echo sanitize($company['location']); ?></td>
                                        <td>
                                            <span class="badge badge-<?php echo $company['status']; ?>">
                                                <?php 
                                                $statusLabels = [
                                                    'available' => '거래가능',
                                                    'negotiating' => '협상중',
                                                    'completed' => '거래완료',
                                                    'cancelled' => '취소'
                                                ];
                                                echo $statusLabels[$company['status']] ?? $company['status'];
                                                ?>
                                            </span>
                                        </td>
                                        <td><?php echo number_format($company['view_count']); ?></td>
                                        <td><?php echo formatDate($company['created_at'], 'Y-m-d'); ?></td>
                                        <td>
                                            <a href="<?php echo ADMIN_URL; ?>/transfer-company-edit.php?id=<?php echo $company['id']; ?>" 
                                               class="btn-action btn-primary" 
                                               style="font-size: 0.8rem; padding: 0.4rem 0.8rem;">수정</a>
                                            <button onclick="deleteCompany(<?php echo $company['id']; ?>)" 
                                                    class="btn-action btn-danger" 
                                                    style="font-size: 0.8rem; padding: 0.4rem 0.8rem;">삭제</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- 페이지네이션 -->
            <?php if ($totalPages > 1): ?>
                <div style="margin-top: 2rem; display: flex; justify-content: center; gap: 0.5rem;">
                    <?php
                    $queryParams = $_GET;
                    for ($i = 1; $i <= $totalPages; $i++):
                        $queryParams['page'] = $i;
                        $queryString = http_build_query($queryParams);
                    ?>
                        <a href="?<?php echo $queryString; ?>" 
                           class="btn-action <?php echo $i === $page ? 'btn-primary' : 'btn-secondary'; ?>"
                           style="padding: 0.5rem 1rem;">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>
        </main>
    </div>
    
    <script>
        function deleteCompany(id) {
            if (!confirm('정말 이 양도기업 정보를 삭제하시겠습니까?\n이 작업은 되돌릴 수 없습니다.')) {
                return;
            }
            
            fetch('<?php echo ADMIN_URL; ?>/transfer-company-delete.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ id: id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('삭제되었습니다.');
                    location.reload();
                } else {
                    alert('삭제 실패: ' + (data.message || '오류가 발생했습니다.'));
                }
            })
            .catch(error => {
                alert('삭제 중 오류가 발생했습니다.');
                console.error(error);
            });
        }
    </script>
</body>
</html>
