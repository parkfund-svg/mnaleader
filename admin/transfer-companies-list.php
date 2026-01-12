<?php
require_once __DIR__ . '/../config.php';
requireLogin();

$pdo = getDBConnection();

// 업종 분류 데이터
$categories = [
    '종합건설업' => ['건축', '토목', '토건', '조경', '산업환경설비'],
    '전문건설업(대업종)' => [
        '지반조성.포장', '실내건축', '금속창호.지붕건축물조립', '도장.습식.방수.석공',
        '조경식재.시설물', '철근콘크리트', '구조물해체.비계', '상.하수도', '철도.궤도',
        '철강구조물', '수중.준설', '승강기.삭도', '기계가스설비', '가스난방'
    ],
    '전문건설업(~2021.12.31)' => [
        '실내건축', '토공', '철콘', '습식방수(미장)', '석공', '도장', '비계', '금속창호',
        '지붕판금', '승강기설치', '상하수도', '기계설비', '보링', '수중', '조경식재',
        '조경시설', '시설물', '강구조물', '포장', '철강재설치', '삭도설치', '준설', '철도궤도',
        '가스(1종)', '가스(2종, 3종)', '난방(1,2,3종)'
    ],
    '기타공사업' => [
        '전기', '정보통신', '소방', '주택건설', '문화재수리업', '산림토목',
        '숲가꾸기', '엔지니어링', '도시림', '나무병원', '대지조성', '부동산개발'
    ]
];

// 선택된 업종 가져오기
$selectedItems = $_GET['items'] ?? [];
if (is_string($selectedItems)) {
    $selectedItems = [$selectedItems];
}

// 페이지네이션
$page = max(1, intval($_GET['page'] ?? 1));
$perPage = 20;

// SQL 쿼리 구성
$where = ['1=1'];
$params = [];

if (!empty($selectedItems)) {
    // 선택된 업종/항목으로 필터링
    $placeholders = implode(',', array_fill(0, count($selectedItems), '?'));
    $where[] = "business_type IN ($placeholders)";
    foreach ($selectedItems as $item) {
        $params[] = $item;
    }
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

$pageTitle = '양도기업리스트';
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> - M&A LEADER</title>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/styles.css">
    <link rel="stylesheet" href="<?php echo ADMIN_URL; ?>/admin.css">
    <style>
        .category-section {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .category-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #1e3a8a;
            margin-bottom: 1rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #3b82f6;
        }

        .items-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 0.75rem;
        }

        .checkbox-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.6rem 0.8rem;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            background: white;
            cursor: pointer;
            transition: all 0.15s ease-in-out;
            font-size: 0.9rem;
        }

        .checkbox-item input[type="checkbox"] {
            width: 16px;
            height: 16px;
            cursor: pointer;
            accent-color: #3b82f6;
        }

        .checkbox-item:hover {
            background: #f9fafb;
            border-color: #3b82f6;
        }

        .checkbox-item input[type="checkbox"]:checked ~ label {
            font-weight: 600;
            color: #1e3a8a;
        }

        .filter-actions {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e5e7eb;
        }

        .btn-search {
            padding: 0.75rem 2rem;
            background: #1e3a8a;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.95rem;
            transition: background 0.3s;
        }

        .btn-search:hover {
            background: #3b82f6;
        }

        .btn-reset {
            padding: 0.75rem 2rem;
            background: #e5e7eb;
            color: #374151;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.95rem;
            transition: background 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-reset:hover {
            background: #d1d5db;
        }

        .results-section {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .results-header {
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .results-header h3 {
            margin: 0;
            color: #1f2937;
            font-size: 1.1rem;
        }

        .results-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
        }

        .results-table thead {
            background: #f9fafb;
        }

        .results-table th {
            padding: 0.75rem 1rem;
            text-align: left;
            font-weight: 600;
            color: #374151;
            border-bottom: 2px solid #e5e7eb;
            white-space: nowrap;
        }

        .results-table td {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #e5e7eb;
            color: #4b5563;
        }

        .results-table tbody tr {
            transition: background 0.2s;
            cursor: pointer;
        }

        .results-table tbody tr:hover {
            background: #f9fafb;
        }

        .company-link {
            color: #1e3a8a;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
        }

        .company-link:hover {
            color: #3b82f6;
        }

        .status-badge {
            display: inline-block;
            padding: 0.3rem 0.6rem;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 600;
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

        .empty-state {
            text-align: center;
            padding: 2rem;
            color: #6b7280;
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 1.5rem;
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

        @media (max-width: 768px) {
            .items-grid {
                grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            }

            .results-table {
                font-size: 0.8rem;
            }

            .results-table th,
            .results-table td {
                padding: 0.5rem 0.75rem;
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
                    <h1>🏢 양도기업리스트</h1>
                    <p>업종별 건설업체 검색</p>
                </div>
            </div>

            <!-- 필터 섹션 -->
            <form method="GET" action="">
                <?php foreach ($categories as $categoryName => $items): ?>
                    <div class="category-section">
                        <div class="category-title"><?php echo $categoryName; ?></div>
                        <div class="items-grid">
                            <?php foreach ($items as $item): ?>
                                <label class="checkbox-item">
                                    <input type="checkbox" 
                                           name="items[]" 
                                           value="<?php echo sanitize($item); ?>"
                                           <?php echo in_array($item, $selectedItems) ? 'checked' : ''; ?> />
                                    <label><?php echo $item; ?></label>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>

                <div class="category-section">
                    <div class="filter-actions">
                        <button type="submit" class="btn-search">🔍 검색하기</button>
                        <a href="<?php echo ADMIN_URL; ?>/transfer-companies-list.php" class="btn-reset">초기화</a>
                    </div>
                </div>
            </form>

            <!-- 결과 테이블 -->
            <div class="results-section">
                <div class="results-header">
                    <h3>기업 목록 (총 <?php echo number_format($totalItems); ?>개)</h3>
                </div>

                <?php if (empty($companies)): ?>
                    <div class="empty-state">
                        <p style="font-size: 1rem;">검색된 기업이 없습니다.</p>
                        <p style="font-size: 0.9rem; color: #9ca3af;">업종을 선택하여 다시 검색해주세요.</p>
                    </div>
                <?php else: ?>
                    <table class="results-table">
                        <thead>
                            <tr>
                                <th>등록번호</th>
                                <th>상태</th>
                                <th>업종</th>
                                <th>시공능력</th>
                                <th>5년실적</th>
                                <th>공제좌수</th>
                                <th>공제잔액</th>
                                <th>회사종류</th>
                                <th>지역</th>
                                <th>매도가</th>
                                <th>등록수정일</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($companies as $company): 
                                $statusClass = 'status-' . $company['status'];
                                $statusLabel = [
                                    'available' => '거래가능',
                                    'negotiating' => '협상중',
                                    'completed' => '거래완료',
                                    'cancelled' => '취소'
                                ][$company['status']] ?? $company['status'];
                            ?>
                                <tr onclick="window.location.href='<?php echo ADMIN_URL; ?>/transfer-company-detail.php?id=<?php echo $company['id']; ?>'">
                                    <td><span class="company-link"><?php echo sanitize($company['id']); ?></span></td>
                                    <td><span class="status-badge <?php echo $statusClass; ?>"><?php echo $statusLabel; ?></span></td>
                                    <td><?php echo sanitize($company['business_type']); ?></td>
                                    <td><?php echo formatPrice($company['capital_amount']); ?></td>
                                    <td><?php echo '-'; ?></td>
                                    <td><?php echo '-'; ?></td>
                                    <td><?php echo '-'; ?></td>
                                    <td><?php echo sanitize($company['company_name']); ?></td>
                                    <td><?php echo sanitize($company['location']); ?></td>
                                    <td><?php echo formatPrice($company['asking_price']); ?></td>
                                    <td><?php echo formatDate($company['created_at'], 'Y-m-d'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

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
            </div>
        </main>
    </div>
</body>
</html>
