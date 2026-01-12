<?php
if (!defined('ROOT_PATH')) {
    require_once __DIR__ . '/../config.php';
}
?>
<!-- 푸터 -->
<footer class="footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-info">
                <h3><?php echo getSetting('site_name', '(주)엠앤에이리더'); ?></h3>
                <p><strong>M&A LEADER</strong></p>
                <ul class="footer-details">
                    <li>대표이사 : <?php echo getSetting('ceo_name', '김은하'); ?></li>
                    <li>주소 : <?php echo getSetting('company_address', '서울시 서초구 서초중앙로 110, B1층(A190호)'); ?></li>
                    <li>TEL : <?php echo getSetting('company_phone', '02-588-2844'); ?></li>
                    <li>FAX : <?php echo getSetting('company_fax', '02-6944-8260'); ?></li>
                </ul>
            </div>
            <div class="footer-links">
                <h4>관련 사이트</h4>
                <ul>
                    <li><a href="http://www.kiscon.net/" target="_blank">대한건설협회</a></li>
                    <li><a href="http://www.keca.or.kr/" target="_blank">전문건설협회</a></li>
                    <li><a href="https://www.ecfc.co.kr/" target="_blank">전기공사협회</a></li>
                    <li><a href="https://www.kica.or.kr/" target="_blank">정보통신공사협회</a></li>
                    <li><a href="http://www.icfc.or.kr/" target="_blank">소방산업공제조합</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>COPYRIGHT <?php echo date('Y'); ?>. <?php echo getSetting('site_name', '(주)엠앤에이리더'); ?> M&A LEADER. ALL RIGHTS RESERVED.</p>
        </div>
    </div>
</footer>

<!-- Top 버튼 -->
<button id="topBtn" class="top-btn" title="맨 위로">
    <span>▲</span>
</button>

<script src="<?php echo SITE_URL; ?>/script.js"></script>
