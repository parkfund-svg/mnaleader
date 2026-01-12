<?php
require_once __DIR__ . '/config.php';

$pdo = getDBConnection();

$id = intval($_GET['id'] ?? 0);

if ($id <= 0) {
    die('올바른 기업 ID가 아닙니다.');
}

// 기업 정보 조회 (거래가능만)
$stmt = $pdo->prepare("SELECT * FROM transfer_companies WHERE id = ? AND status = 'available'");
$stmt->execute([$id]);
$company = $stmt->fetch();

if (!$company) {
    die('기업 정보를 찾을 수 없습니다.');
}

// 조회수 증가
$pdo->prepare("UPDATE transfer_companies SET view_count = view_count + 1 WHERE id = ?")->execute([$id]);

$pageTitle = $company['company_name'] . ' - 상세정보';
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> - M&A LEADER</title>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/styles.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/subpage.css">
    <style>
        .detail-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        .detail-header {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            color: white;
            padding: 2rem;
            border-radius: 10px;
            margin-bottom: 2rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .detail-header h1 {
            margin: 0 0 0.5rem 0;
            font-size: 2rem;
        }

        .detail-header-meta {
            display: flex;
            gap: 2rem;
            flex-wrap: wrap;
            font-size: 0.95rem;
            opacity: 0.9;
        }

        .detail-section {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .section-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #1e3a8a;
            margin-bottom: 1rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #e5e7eb;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .detail-item {
            padding: 1rem;
            background: #f9fafb;
            border-radius: 6px;
            border-left: 4px solid #3b82f6;
        }

        .detail-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.5rem;
        }

        .detail-value {
            font-size: 1rem;
            font-weight: 600;
            color: #1f2937;
            word-break: break-word;
        }

        .detail-value.highlight {
            color: #1e3a8a;
            font-size: 1.2rem;
        }

        .contact-section {
            background: #f0f9ff;
            padding: 1.5rem;
            border-radius: 6px;
            border-left: 4px solid #3b82f6;
        }

        .contact-info {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .contact-item {
            flex: 1;
            min-width: 200px;
        }

        .contact-label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.25rem;
            font-size: 0.9rem;
        }

        .contact-value {
            color: #1e3a8a;
            font-weight: 600;
            font-size: 1rem;
        }

        .btn-back {
            padding: 0.75rem 1.5rem;
            background: #e5e7eb;
            color: #374151;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 1.5rem;
            transition: background 0.3s;
        }

        .btn-back:hover {
            background: #d1d5db;
        }

        .btn-inquiry {
            padding: 0.75rem 2rem;
            background: #1e3a8a;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.95rem;
            transition: background 0.3s;
            margin-top: 1rem;
        }

        .btn-inquiry:hover {
            background: #3b82f6;
        }

        @media (max-width: 768px) {
            .detail-header {
                padding: 1.5rem;
            }

            .detail-header h1 {
                font-size: 1.5rem;
            }

            .detail-header-meta {
                gap: 1rem;
                font-size: 0.85rem;
            }

            .detail-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/includes/header.php'; ?>
    
    <main class="detail-container">
        <a href="<?php echo SITE_URL; ?>/transfer-companies.php" class="btn-back">← 목록으로 돌아가기</a>

        <!-- 헤더 -->
        <div class="detail-header">
            <h1><?php echo sanitize($company['company_name']); ?></h1>
            <div class="detail-header-meta">
                <div><strong>등록번호:</strong> <?php echo sanitize($company['id']); ?></div>
                <div><strong>지역:</strong> <?php echo sanitize($company['location']); ?></div>
                <div><strong>조회수:</strong> <?php echo number_format($company['view_count']); ?></div>
            </div>
        </div>

        <!-- 기본 정보 -->
        <div class="detail-section">
            <div class="section-title">📋 기본 정보</div>
            <div class="detail-grid">
                <div class="detail-item">
                    <div class="detail-label">등록번호</div>
                    <div class="detail-value highlight"><?php echo sanitize($company['id']); ?></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">회사명</div>
                    <div class="detail-value"><?php echo sanitize($company['company_name']); ?></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">회사형태</div>
                    <div class="detail-value"><?php echo sanitize($company['company_type'] ?? '-'); ?></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">소재지</div>
                    <div class="detail-value"><?php echo sanitize($company['location']); ?></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">설립년도</div>
                    <div class="detail-value"><?php echo sanitize($company['established_year'] ?? '-'); ?></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">등록일</div>
                    <div class="detail-value"><?php echo formatDate($company['created_at'], 'Y-m-d'); ?></div>
                </div>
            </div>
        </div>

        <!-- 재무 정보 -->
        <div class="detail-section">
            <div class="section-title">💰 재무 정보</div>
            <div class="detail-grid">
                <div class="detail-item">
                    <div class="detail-label">자본금</div>
                    <div class="detail-value"><?php echo formatPrice($company['capital_amount']); ?></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">시공능력</div>
                    <div class="detail-value"><?php echo sanitize($company['construction_capability'] ?? '-'); ?></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">부채비율</div>
                    <div class="detail-value"><?php echo sanitize($company['debt_ratio'] ?? '-'); ?></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">유동비율</div>
                    <div class="detail-value"><?php echo sanitize($company['liquidity_ratio'] ?? '-'); ?></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">잉여금</div>
                    <div class="detail-value"><?php echo sanitize($company['surplus_amount'] ?? '-'); ?></div>
                </div>
            </div>
        </div>

        <!-- 공제조합 정보 -->
        <div class="detail-section">
            <div class="section-title">🏦 공제조합 정보</div>
            <div class="detail-grid">
                <div class="detail-item">
                    <div class="detail-label">공제조합 좌수</div>
                    <div class="detail-value"><?php echo sanitize($company['cooperative_shares'] ?? '-'); ?></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">공제조합 잔액</div>
                    <div class="detail-value"><?php echo formatPrice($company['cooperative_balance'] ?? 0); ?></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">협회가입여부</div>
                    <div class="detail-value"><?php echo sanitize($company['association_membership'] ?? '-'); ?></div>
                </div>
            </div>
        </div>

        <!-- 업종 정보 -->
        <div class="detail-section">
            <div class="section-title">🏗️ 업종 정보</div>
            <div class="detail-grid">
                <div class="detail-item">
                    <div class="detail-label">업종</div>
                    <div class="detail-value"><?php echo sanitize($company['business_type']); ?></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">면허종류</div>
                    <div class="detail-value"><?php echo sanitize($company['license_type']); ?></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">등록년도</div>
                    <div class="detail-value"><?php echo sanitize($company['registered_year'] ?? '-'); ?></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">5년실적</div>
                    <div class="detail-value"><?php echo sanitize($company['five_year_record'] ?? '-'); ?></div>
                </div>
            </div>
        </div>

        <!-- 거래 정보 -->
        <div class="detail-section">
            <div class="section-title">💵 거래 정보</div>
            <div class="detail-grid">
                <div class="detail-item">
                    <div class="detail-label">희망 매도가</div>
                    <div class="detail-value highlight"><?php echo formatPrice($company['asking_price']); ?></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">조회 횟수</div>
                    <div class="detail-value"><?php echo number_format($company['view_count']); ?></div>
                </div>
            </div>
        </div>

        <!-- 연락처 정보 -->
        <div class="detail-section contact-section">
            <div class="section-title">📞 정보제공 연락처</div>
            <div class="contact-info">
                <div class="contact-item">
                    <div class="contact-label">담당자명</div>
                    <div class="contact-value"><?php echo sanitize($company['contact_name'] ?? '비회원보기'); ?></div>
                </div>
                <div class="contact-item">
                    <div class="contact-label">휴대폰번호</div>
                    <div class="contact-value"><?php echo sanitize($company['contact_phone'] ?? '비회원보기'); ?></div>
                </div>
                <div class="contact-item">
                    <div class="contact-label">전화번호</div>
                    <div class="contact-value"><?php echo sanitize($company['contact_tel'] ?? '-'); ?></div>
                </div>
            </div>
            <button class="btn-inquiry" onclick="alert('상담 신청 기능은 로그인 후 이용 가능합니다.')">📱 상담하기</button>
        </div>

        <!-- 특이사항 -->
        <?php if (!empty($company['remarks'])): ?>
            <div class="detail-section">
                <div class="section-title">📝 특이사항</div>
                <div style="background: #f9fafb; padding: 1rem; border-radius: 6px; border-left: 4px solid #3b82f6; white-space: pre-wrap; line-height: 1.6;">
                    <?php echo sanitize($company['remarks']); ?>
                </div>
            </div>
        <?php endif; ?>
    </main>

    <?php include __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
