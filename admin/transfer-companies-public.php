<?php
require_once __DIR__ . '/../config.php';
requireLogin();

$pdo = getDBConnection();

// 검색 및 필터링
$search = $_GET['search'] ?? '';
$business_type = $_GET['business_type'] ?? [];
$status = $_GET['status'] ?? '';
$page = max(1, intval($_GET['page'] ?? 1));
$perPage = 12; // 그리드 레이아웃용

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
$offset = ($page - 1) * $perPage;

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

$pageTitle = '양도기업 리스트';
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> - M&A LEADER 관리자</title>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/styles.css">
    <link rel="stylesheet" href="<?php echo ADMIN_URL; ?>/admin.css">
    <style>
        .company-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 1.5rem;
            margin-top: 1.5rem;
        }

        .company-card {
            background: white;
            border-radius: 10px;
            border: 1px solid #e5e7eb;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            display: flex;
            flex-direction: column;
        }

        .company-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
            border-color: #3b82f6;
        }

        .card-header {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            color: white;
            padding: 1.25rem;
            border-bottom: 3px solid #1e40af;
        }

        .card-company-name {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .card-featured {
            color: #fbbf24;
            font-size: 1.1rem;
        }

        .card-business-type {
            font-size: 0.9rem;
            opacity: 0.9;
            font-weight: 500;
        }

        .card-body {
            padding: 1.25rem;
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .card-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.9rem;
            border-bottom: 1px solid #f3f4f6;
            padding-bottom: 0.5rem;
        }

        .card-row:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .card-label {
            color: #6b7280;
            font-weight: 500;
            flex: 0 0 80px;
        }

        .card-value {
            color: #1f2937;
            font-weight: 600;
            text-align: right;
            flex: 1;
        }

        .card-footer {
            padding: 1rem 1.25rem;
            background: #f9fafb;
            border-top: 1px solid #e5e7eb;
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .card-status {
            font-size: 0.85rem;
            font-weight: 600;
            padding: 0.35rem 0.75rem;
            border-radius: 9999px;
            white-space: nowrap;
        }

        .status-available {
            background: #d1fae5;
            color: #065f46;
        }

        .status-negotiating {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-completed {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-cancelled {
            background: #f3f4f6;
            color: #6b7280;
        }

        .card-views {
            font-size: 0.85rem;
            color: #9ca3af;
            margin-left: auto;
        }

        .filter-section {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .filter-form {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            align-items: flex-end;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            flex: 1;
            min-width: 180px;
        }

        .filter-group label {
            font-weight: 600;
            color: #374151;
            font-size: 0.9rem;
        }

        .filter-group input,
        .filter-group select {
            padding: 0.65rem;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 0.9rem;
        }

        .filter-group input:focus,
        .filter-group select:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .business-type-filters {
            display: flex;
            gap: 0.5rem;
            align-items: center;
            flex-wrap: wrap;
            margin-top: 0.5rem;
        }

        .checkbox-filter {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.35rem 0.6rem;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            background: white;
            cursor: pointer;
            transition: all 0.15s ease-in-out;
            font-size: 0.85rem;
        }

        .checkbox-filter input {
            width: 16px;
            height: 16px;
            cursor: pointer;
        }

        .checkbox-filter:hover {
            box-shadow: 0 2px 6px rgba(15, 23, 42, 0.06);
        }

        .checkbox-filter input:checked ~ label {
            color: #1e3a8a;
            font-weight: 600;
        }

        .checkbox-filter input:checked {
            accent-color: #3b82f6;
        }

        .btn-filter {
            padding: 0.65rem 1.5rem;
            background: #1e3a8a;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: background 0.3s;
        }

        .btn-filter:hover {
            background: #3b82f6;
        }

        .btn-reset {
            padding: 0.65rem 1.5rem;
            background: #e5e7eb;
            color: #374151;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: background 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-reset:hover {
            background: #d1d5db;
        }

        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: #6b7280;
        }

        .empty-state-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .empty-state-text {
            font-size: 1rem;
            margin-bottom: 0.5rem;
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 2rem;
            flex-wrap: wrap;
        }

        .pagination a,
        .pagination span {
            padding: 0.5rem 0.8rem;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            text-decoration: none;
            color: #374151;
            font-weight: 500;
            transition: all 0.2s;
        }

        .pagination a:hover {
            background: #f3f4f6;
            border-color: #3b82f6;
        }

        .pagination .active {
            background: #1e3a8a;
            color: white;
            border-color: #1e3a8a;
        }

        .results-info {
            color: #6b7280;
            font-size: 0.95rem;
            margin-bottom: 1rem;
        }

        @media (max-width: 768px) {
            .company-grid {
                grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            }

            .filter-form {
                flex-direction: column;
            }

            .filter-group {
                width: 100%;
            }
        }
    </style>
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
                    <h1>🏢 양도기업 리스트</h1>
                    <p>등록된 양도기업 카드 형 목록</p>
                </div>
            </div>

            <!-- 검색 및 필터 -->
            <div class="filter-section">
                <form method="GET" action="" class="filter-form">
                    <div class="filter-group" style="flex: 1; min-width: 250px;">
                        <label>회사명/지역 검색</label>
                        <input type="text" 
                               name="search" 
                               placeholder="회사명, 면허종류, 지역..." 
                               value="<?php echo sanitize($search); ?>">
                    </div>

                    <div class="filter-group" style="flex: 2;">
                        <label>건설업 종류</label>
                        <div class="business-type-filters">
                            <?php foreach ($businessTypes as $key => $label): ?>
                                <label class="checkbox-filter">
                                    <input type="checkbox" 
                                           name="business_type[]" 
                                           value="<?php echo $key; ?>" 
                                           <?php echo (is_array($business_type) && in_array($key, $business_type)) ? 'checked' : ''; ?> />
                                    <span><?php echo $label; ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="filter-group">
                        <label>거래 상태</label>
                        <select name="status">
                            <option value="">전체</option>
                            <option value="available" <?php echo $status === 'available' ? 'selected' : ''; ?>>거래가능</option>
                            <option value="negotiating" <?php echo $status === 'negotiating' ? 'selected' : ''; ?>>협상중</option>
                            <option value="completed" <?php echo $status === 'completed' ? 'selected' : ''; ?>>거래완료</option>
                            <option value="cancelled" <?php echo $status === 'cancelled' ? 'selected' : ''; ?>>취소</option>
                        </select>
                    </div>

                    <button type="submit" class="btn-filter">🔍 검색</button>
                    <a href="<?php echo ADMIN_URL; ?>/transfer-companies-public.php" class="btn-reset">초기화</a>
                </form>
            </div>

            <!-- 결과 정보 -->
            <div class="results-info">
                총 <strong><?php echo number_format($totalItems); ?></strong>개의 기업이 등록되어 있습니다.
            </div>

            <!-- 그리드 -->
            <?php if (empty($companies)): ?>
                <div class="empty-state">
                    <div class="empty-state-icon">📋</div>
                    <div class="empty-state-text">등록된 양도기업이 없습니다.</div>
                    <p style="color: #9ca3af; font-size: 0.9rem;">검색 조건을 변경하여 다시 시도해주세요.</p>
                </div>
            <?php else: ?>
                <div class="company-grid">
                    <?php foreach ($companies as $company): 
                        $statusLabel = [
                            'available' => '거래가능',
                            'negotiating' => '협상중',
                            'completed' => '거래완료',
                            'cancelled' => '취소'
                        ][$company['status']] ?? $company['status'];
                        
                        $statusClass = 'status-' . $company['status'];
                    ?>
                        <div class="company-card">
                            <div class="card-header">
                                <div class="card-company-name">
                                    <?php echo sanitize($company['company_name']); ?>
                                    <?php if ($company['is_featured']): ?>
                                        <span class="card-featured">⭐</span>
                                    <?php endif; ?>
                                </div>
                                <div class="card-business-type">
                                    <?php echo sanitize($company['business_type']); ?>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="card-row">
                                    <span class="card-label">면허</span>
                                    <span class="card-value"><?php echo sanitize($company['license_type']); ?></span>
                                </div>
                                <div class="card-row">
                                    <span class="card-label">자본금</span>
                                    <span class="card-value"><?php echo formatPrice($company['capital_amount']); ?></span>
                                </div>
                                <div class="card-row">
                                    <span class="card-label">희망가</span>
                                    <span class="card-value"><?php echo formatPrice($company['asking_price']); ?></span>
                                </div>
                                <div class="card-row">
                                    <span class="card-label">지역</span>
                                    <span class="card-value"><?php echo sanitize($company['location']); ?></span>
                                </div>
                            </div>

                            <div class="card-footer">
                                <span class="card-status <?php echo $statusClass; ?>">
                                    <?php echo $statusLabel; ?>
                                </span>
                                <span class="card-views">조회: <?php echo number_format($company['view_count']); ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- 페이지네이션 -->
                <?php if ($totalPages > 1): ?>
                    <div class="pagination">
                        <?php
                        $queryParams = $_GET;
                        for ($i = 1; $i <= $totalPages; $i++):
                            $queryParams['page'] = $i;
                            $queryString = http_build_query($queryParams);
                        ?>
                            <?php if ($i === $page): ?>
                                <span class="active"><?php echo $i; ?></span>
                            <?php else: ?>
                                <a href="?<?php echo $queryString; ?>"><?php echo $i; ?></a>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>
