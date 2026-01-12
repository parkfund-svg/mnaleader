<?php
require_once __DIR__ . '/../config.php';
requireLogin();

$pdo = getDBConnection();
$error = '';
$success = '';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$isEdit = $id > 0;

// 수정 모드일 때 기존 데이터 로드
$company = null;
if ($isEdit) {
    $stmt = $pdo->prepare("SELECT * FROM transfer_companies WHERE id = ?");
    $stmt->execute([$id]);
    $company = $stmt->fetch();
    
    if (!$company) {
        redirect(ADMIN_URL . '/transfer-companies.php');
    }
}

// 폼 제출 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'company_name' => sanitize($_POST['company_name'] ?? ''),
        'business_type' => sanitize($_POST['business_type'] ?? ''),
        'license_type' => sanitize($_POST['license_type'] ?? ''),
        'capital_amount' => intval($_POST['capital_amount'] ?? 0),
        'established_year' => intval($_POST['established_year'] ?? 0),
        'location' => sanitize($_POST['location'] ?? ''),
        'credit_rating' => sanitize($_POST['credit_rating'] ?? ''),
        'workforce' => intval($_POST['workforce'] ?? 0),
        'recent_revenue' => intval($_POST['recent_revenue'] ?? 0),
        'asking_price' => intval($_POST['asking_price'] ?? 0),
        'has_debt' => isset($_POST['has_debt']) ? 1 : 0,
        'debt_amount' => intval($_POST['debt_amount'] ?? 0),
        'performance_summary' => sanitize($_POST['performance_summary'] ?? ''),
        'special_notes' => sanitize($_POST['special_notes'] ?? ''),
        'contact_method' => sanitize($_POST['contact_method'] ?? ''),
        'status' => sanitize($_POST['status'] ?? 'available'),
        'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
        'is_published' => isset($_POST['is_published']) ? 1 : 0,
    ];
    
    // 유효성 검사
    if (empty($data['company_name'])) {
        $error = '회사명은 필수입니다.';
    } elseif (empty($data['business_type'])) {
        $error = '업종은 필수입니다.';
    } else {
        try {
            if ($isEdit) {
                // 수정
                $sql = "UPDATE transfer_companies SET 
                        company_name = ?, business_type = ?, license_type = ?, 
                        capital_amount = ?, established_year = ?, location = ?, 
                        credit_rating = ?, workforce = ?, recent_revenue = ?, 
                        asking_price = ?, has_debt = ?, debt_amount = ?, 
                        performance_summary = ?, special_notes = ?, contact_method = ?, 
                        status = ?, is_featured = ?, is_published = ?
                        WHERE id = ?";
                $params = array_values($data);
                $params[] = $id;
                $stmt = $pdo->prepare($sql);
                $stmt->execute($params);
                
                logAdminActivity('양도기업 수정', 'transfer_company', $id, $data['company_name']);
                $success = '수정되었습니다.';
                
            } else {
                // 신규 등록
                $data['created_by'] = $_SESSION['admin_id'];
                $sql = "INSERT INTO transfer_companies (
                        company_name, business_type, license_type, capital_amount, 
                        established_year, location, credit_rating, workforce, 
                        recent_revenue, asking_price, has_debt, debt_amount, 
                        performance_summary, special_notes, contact_method, 
                        status, is_featured, is_published, created_by
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(array_values($data));
                
                $newId = $pdo->lastInsertId();
                logAdminActivity('양도기업 등록', 'transfer_company', $newId, $data['company_name']);
                
                redirect(ADMIN_URL . '/transfer-company-edit.php?id=' . $newId . '&success=1');
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
            $error = '저장 중 오류가 발생했습니다.';
        }
    }
}

// 성공 메시지
if (isset($_GET['success'])) {
    $success = '저장되었습니다.';
}

$pageTitle = $isEdit ? '양도기업 수정' : '양도기업 등록';
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
                    <h1><?php echo $isEdit ? '✏️ 양도기업 수정' : '➕ 양도기업 등록'; ?></h1>
                    <p><?php echo $isEdit ? '기존 양도기업 정보 수정' : '새로운 양도기업 정보 등록'; ?></p>
                </div>
                <a href="<?php echo ADMIN_URL; ?>/transfer-companies.php" class="btn-action btn-secondary">← 목록으로</a>
            </div>
            
            <?php if ($error): ?>
                <div style="background: #fee2e2; color: #991b1b; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div style="background: #d1fae5; color: #065f46; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-panel">
                    <!-- 기본 정보 -->
                    <div class="form-section">
                        <h3>기본 정보</h3>
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="company_name">회사명 <span style="color:red;">*</span></label>
                                <input type="text" id="company_name" name="company_name" required
                                       value="<?php echo $company ? sanitize($company['company_name']) : ''; ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="business_type">업종 <span style="color:red;">*</span></label>
                                <select id="business_type" name="business_type" required>
                                    <option value="">선택하세요</option>
                                    <option value="종합건설업" <?php echo ($company && $company['business_type'] === '종합건설업') ? 'selected' : ''; ?>>종합건설업</option>
                                    <option value="전문건설업" <?php echo ($company && $company['business_type'] === '전문건설업') ? 'selected' : ''; ?>>전문건설업</option>
                                    <option value="전기공사업" <?php echo ($company && $company['business_type'] === '전기공사업') ? 'selected' : ''; ?>>전기공사업</option>
                                    <option value="정보통신공사업" <?php echo ($company && $company['business_type'] === '정보통신공사업') ? 'selected' : ''; ?>>정보통신공사업</option>
                                    <option value="소방공사업" <?php echo ($company && $company['business_type'] === '소방공사업') ? 'selected' : ''; ?>>소방공사업</option>
                                    <option value="기계설비공사업" <?php echo ($company && $company['business_type'] === '기계설비공사업') ? 'selected' : ''; ?>>기계설비공사업</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="license_type">면허 종류</label>
                                <input type="text" id="license_type" name="license_type"
                                       value="<?php echo $company ? sanitize($company['license_type']) : ''; ?>"
                                       placeholder="예: 토목공사업, 건축공사업">
                            </div>
                            
                            <div class="form-group">
                                <label for="location">소재지</label>
                                <input type="text" id="location" name="location"
                                       value="<?php echo $company ? sanitize($company['location']) : ''; ?>"
                                       placeholder="예: 서울 강남구">
                            </div>
                            
                            <div class="form-group">
                                <label for="established_year">설립연도</label>
                                <input type="number" id="established_year" name="established_year"
                                       value="<?php echo $company ? $company['established_year'] : ''; ?>"
                                       placeholder="예: 2020" min="1900" max="2026">
                            </div>
                            
                            <div class="form-group">
                                <label for="credit_rating">신용등급</label>
                                <input type="text" id="credit_rating" name="credit_rating"
                                       value="<?php echo $company ? sanitize($company['credit_rating']) : ''; ?>"
                                       placeholder="예: A등급">
                            </div>
                        </div>
                    </div>
                    
                    <!-- 재무 정보 -->
                    <div class="form-section">
                        <h3>재무 정보</h3>
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="capital_amount">자본금 (원)</label>
                                <input type="number" id="capital_amount" name="capital_amount"
                                       value="<?php echo $company ? $company['capital_amount'] : ''; ?>"
                                       placeholder="예: 500000000">
                                <small style="color: #6b7280;">숫자만 입력 (예: 5억원 = 500000000)</small>
                            </div>
                            
                            <div class="form-group">
                                <label for="asking_price">희망 양도가 (원)</label>
                                <input type="number" id="asking_price" name="asking_price"
                                       value="<?php echo $company ? $company['asking_price'] : ''; ?>"
                                       placeholder="예: 1500000000">
                            </div>
                            
                            <div class="form-group">
                                <label for="recent_revenue">최근 매출액 (원)</label>
                                <input type="number" id="recent_revenue" name="recent_revenue"
                                       value="<?php echo $company ? $company['recent_revenue'] : ''; ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="workforce">기술인력 수 (명)</label>
                                <input type="number" id="workforce" name="workforce"
                                       value="<?php echo $company ? $company['workforce'] : ''; ?>">
                            </div>
                            
                            <div class="form-group" style="grid-column: 1 / -1;">
                                <label>
                                    <input type="checkbox" name="has_debt" value="1"
                                           <?php echo ($company && $company['has_debt']) ? 'checked' : ''; ?>>
                                    부채 있음
                                </label>
                            </div>
                            
                            <div class="form-group">
                                <label for="debt_amount">부채액 (원)</label>
                                <input type="number" id="debt_amount" name="debt_amount"
                                       value="<?php echo $company ? $company['debt_amount'] : ''; ?>">
                            </div>
                        </div>
                    </div>
                    
                    <!-- 상세 정보 -->
                    <div class="form-section">
                        <h3>상세 정보</h3>
                        <div class="form-grid" style="grid-template-columns: 1fr;">
                            <div class="form-group">
                                <label for="performance_summary">실적 요약</label>
                                <textarea id="performance_summary" name="performance_summary" rows="4"
                                          placeholder="최근 5년간 주요 실적을 간략히 작성하세요"><?php echo $company ? sanitize($company['performance_summary']) : ''; ?></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label for="special_notes">특이사항</label>
                                <textarea id="special_notes" name="special_notes" rows="4"
                                          placeholder="양도 관련 특이사항이나 참고사항"><?php echo $company ? sanitize($company['special_notes']) : ''; ?></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label for="contact_method">연락방법</label>
                                <input type="text" id="contact_method" name="contact_method"
                                       value="<?php echo $company ? sanitize($company['contact_method']) : ''; ?>"
                                       placeholder="예: 상담신청 또는 전화문의">
                            </div>
                        </div>
                    </div>
                    
                    <!-- 상태 및 옵션 -->
                    <div class="form-section">
                        <h3>상태 및 옵션</h3>
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="status">거래 상태</label>
                                <select id="status" name="status">
                                    <option value="available" <?php echo ($company && $company['status'] === 'available') ? 'selected' : ''; ?>>거래가능</option>
                                    <option value="negotiating" <?php echo ($company && $company['status'] === 'negotiating') ? 'selected' : ''; ?>>협상중</option>
                                    <option value="completed" <?php echo ($company && $company['status'] === 'completed') ? 'selected' : ''; ?>>거래완료</option>
                                    <option value="cancelled" <?php echo ($company && $company['status'] === 'cancelled') ? 'selected' : ''; ?>>취소</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" name="is_published" value="1"
                                           <?php echo (!$company || $company['is_published']) ? 'checked' : ''; ?>>
                                    공개 (체크 해제 시 비공개)
                                </label>
                            </div>
                            
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" name="is_featured" value="1"
                                           <?php echo ($company && $company['is_featured']) ? 'checked' : ''; ?>>
                                    추천 매물 (⭐ 표시)
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- 저장 버튼 -->
                    <div class="form-actions">
                        <button type="submit" class="btn-action btn-success" style="padding: 1rem 3rem;">
                            <?php echo $isEdit ? '💾 수정 저장' : '✅ 등록하기'; ?>
                        </button>
                        <a href="<?php echo ADMIN_URL; ?>/transfer-companies.php" class="btn-action btn-secondary">취소</a>
                    </div>
                </div>
            </form>
        </main>
    </div>
</body>
</html>
