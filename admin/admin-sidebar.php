<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<nav class="sidebar-nav">
    <ul>
        <li class="<?php echo $current_page === 'dashboard.php' ? 'active' : ''; ?>">
            <a href="<?php echo ADMIN_URL; ?>/dashboard.php">
                <span class="nav-icon">📊</span>
                <span class="nav-text">대시보드</span>
            </a>
        </li>
        
        <li class="nav-divider">상담 관리</li>
        
        <li class="<?php echo $current_page === 'consultations.php' ? 'active' : ''; ?>">
            <a href="<?php echo ADMIN_URL; ?>/consultations.php">
                <span class="nav-icon">📝</span>
                <span class="nav-text">일반 상담신청</span>
            </a>
        </li>
        
        <li class="<?php echo $current_page === 'registrations.php' ? 'active' : ''; ?>">
            <a href="<?php echo ADMIN_URL; ?>/registrations.php">
                <span class="nav-icon">📋</span>
                <span class="nav-text">신규등록 신청</span>
            </a>
        </li>
        
        <li class="nav-divider">양도양수 관리</li>
        
        <li class="<?php echo $current_page === 'transfer-companies-list.php' ? 'active' : ''; ?>">
            <a href="<?php echo ADMIN_URL; ?>/transfer-companies-list.php">
                <span class="nav-icon">📋</span>
                <span class="nav-text">양도기업리스트</span>
            </a>
        </li>
        
        <li class="<?php echo $current_page === 'transfer-companies.php' ? 'active' : ''; ?>">
            <a href="<?php echo ADMIN_URL; ?>/transfer-companies.php">
                <span class="nav-icon">🏢</span>
                <span class="nav-text">양도기업 관리</span>
            </a>
        </li>
        
        <li class="nav-divider">시스템</li>
        
        <li class="<?php echo $current_page === 'settings.php' ? 'active' : ''; ?>">
            <a href="<?php echo ADMIN_URL; ?>/settings.php">
                <span class="nav-icon">⚙️</span>
                <span class="nav-text">사이트 설정</span>
            </a>
        </li>
        
        <?php if ($_SESSION['admin_role'] === 'super_admin'): ?>
        <li class="<?php echo $current_page === 'admins.php' ? 'active' : ''; ?>">
            <a href="<?php echo ADMIN_URL; ?>/admins.php">
                <span class="nav-icon">👥</span>
                <span class="nav-text">관리자 계정</span>
            </a>
        </li>
        <?php endif; ?>
    </ul>
</nav>
