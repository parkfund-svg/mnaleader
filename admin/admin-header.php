<?php
if (!isset($_SESSION['admin_id'])) {
    redirect(ADMIN_URL . '/login.php');
}
?>
<div class="admin-topbar">
    <div class="topbar-left">
        <h2>M&A LEADER 관리자</h2>
    </div>
    <div class="topbar-right">
        <span class="admin-info">
            👤 <?php echo sanitize($_SESSION['admin_name']); ?> 
            <small>(<?php echo sanitize($_SESSION['admin_role']); ?>)</small>
        </span>
        <a href="<?php echo SITE_URL; ?>/index.html" class="btn-link" target="_blank">🌐 사이트 보기</a>
        <a href="<?php echo ADMIN_URL; ?>/logout.php" class="btn-logout">🚪 로그아웃</a>
    </div>
</div>
