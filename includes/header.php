<?php
if (!defined('ROOT_PATH')) {
    require_once __DIR__ . '/../config.php';
}
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!-- 헤더 -->
<header class="header">
    <div class="container">
        <div class="header-top">
            <div class="logo">
                <a href="<?php echo SITE_URL; ?>/index.php">
                    <img src="<?php echo SITE_URL; ?>/images/logo.png" alt="(주)엠앤에이리더 로고">
                    <span>M&A LEADER</span>
                </a>
            </div>
            <div class="header-contact">
                <div class="contact-info">
                    <img src="<?php echo SITE_URL; ?>/images/icon-tel.png" alt="전화" onerror="this.style.display='none'">
                    <span class="tel"><?php echo getSetting('company_phone', '02-588-2844'); ?></span>
                </div>
            </div>
        </div>
        <nav class="nav">
            <ul>
                <li class="has-submenu">
                    <a href="<?php echo SITE_URL; ?>/greeting.php" class="main-menu">회사소개</a>
                    <ul class="submenu">
                        <li><a href="<?php echo SITE_URL; ?>/greeting.php">인사말</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/business-info.php">업무내용안내</a></li>
                    </ul>
                </li>
                <li class="has-submenu">
                    <a href="<?php echo SITE_URL; ?>/transfer-guide.php" class="main-menu">양도양수</a>
                    <ul class="submenu">
                        <li><a href="<?php echo SITE_URL; ?>/transfer-list.php">양도기업리스트</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/transfer-guide.php">양도양수안내</a></li>
                    </ul>
                </li>
                <li class="has-submenu">
                    <a href="<?php echo SITE_URL; ?>/new-registration-process.php" class="main-menu">신규건설업신청</a>
                    <ul class="submenu">
                        <li><a href="<?php echo SITE_URL; ?>/new-registration-process.php">신규등록절차</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/new-registration-docs.php">신규건설업신청구비서류</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/new-registration-apply.php">건설업신규등록신청</a></li>
                    </ul>
                </li>
                <li class="has-submenu">
                    <a href="<?php echo SITE_URL; ?>/corporation-types.php" class="main-menu">건설관련정보</a>
                    <ul class="submenu">
                        <li><a href="<?php echo SITE_URL; ?>/corporation-types.php">법인의 종류</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/registration-standards.php">건설업등록기준</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/deductible-association.php">공제조합</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/business-diagnosis.html">기업진단</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/management-evaluation.html">경영상태평가</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/index.php#info-build-eval">시공능력평가</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/average-costs.html">업종별평균비율</a></li>
                    </ul>
                </li>
                <li class="has-submenu">
                    <a href="<?php echo SITE_URL; ?>/index.php#merger-split-merge" class="main-menu">분할합병및법인전환</a>
                    <ul class="submenu">
                        <li><a href="<?php echo SITE_URL; ?>/index.php#merger-split-merge">기업분할/합병</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/index.php#merger-corp-conversion">법인전환</a></li>
                    </ul>
                </li>
                <li>
                    <a href="<?php echo SITE_URL; ?>/index.php#consulting">상담신청</a>
                </li>
            </ul>
        </nav>
    </div>
</header>
