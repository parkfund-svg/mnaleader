// 스크롤 이벤트 - 헤더 고정
window.addEventListener('scroll', function() {
    const header = document.querySelector('.header');
    if (window.scrollY > 100) {
        header.style.boxShadow = '0 2px 20px rgba(0, 0, 0, 0.15)';
    } else {
        header.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.1)';
    }
});

// Top 버튼 표시/숨김
const topBtn = document.getElementById('topBtn');

window.addEventListener('scroll', function() {
    if (window.scrollY > 300) {
        topBtn.classList.add('show');
    } else {
        topBtn.classList.remove('show');
    }
});

// Top 버튼 클릭 - 부드러운 스크롤
topBtn.addEventListener('click', function() {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
});

// 네비게이션 링크 부드러운 스크롤 및 인덱스 리다이렉트
const onIndexPage = window.location.pathname.endsWith('index.html') || window.location.pathname === '/' || window.location.pathname === '';

document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        const targetId = this.getAttribute('href');

        if (!targetId || targetId === '#') return;

        const targetElement = document.querySelector(targetId);

        if (targetElement) {
            e.preventDefault();
            const headerHeight = (document.querySelector('.header')?.offsetHeight || 0);
            const targetPosition = targetElement.getBoundingClientRect().top + window.pageYOffset - headerHeight - 20;

            window.scrollTo({
                top: targetPosition,
                behavior: 'smooth'
            });

            // 모바일에서 메뉴 클릭 후 메뉴 닫기
            if (window.innerWidth <= 768) {
                const navUl = document.querySelector('.nav ul');
                if (navUl && navUl.style.display === 'flex') {
                    navUl.style.display = 'none';
                }
            }
        } else if (!onIndexPage && this.closest('.nav')) {
            // 서브 페이지에서 존재하지 않는 해시 클릭 시 메인으로 이동
            e.preventDefault();
            window.location.href = `index.html${targetId}`;
        }
    });
});

// 메인 메뉴 클릭 시 서브메뉴 토글 (모바일)
document.querySelectorAll('.has-submenu > a.main-menu').forEach(mainMenu => {
    mainMenu.addEventListener('click', function(e) {
        // 모바일에서는 서브메뉴 토글
        if (window.innerWidth <= 768) {
            e.preventDefault();
            const parent = this.parentElement;
            const submenu = parent.querySelector('.submenu');
            
            // 다른 서브메뉴 닫기
            document.querySelectorAll('.has-submenu').forEach(item => {
                if (item !== parent) {
                    const otherSubmenu = item.querySelector('.submenu');
                    if (otherSubmenu) {
                        otherSubmenu.style.display = 'none';
                    }
                }
            });
            
            // 현재 서브메뉴 토글
            if (submenu) {
                submenu.style.display = submenu.style.display === 'block' ? 'none' : 'block';
            }
        }
    });
});


// 상담신청 폼 제출
const consultingForm = document.getElementById('consultingForm');

consultingForm.addEventListener('submit', function(e) {
    e.preventDefault();
    
    // 폼 데이터 수집
    const formData = {
        name: document.getElementById('name').value,
        phone: document.getElementById('phone').value,
        email: document.getElementById('email').value,
        company: document.getElementById('company').value,
        inquiry: document.getElementById('inquiry').value
    };
    
    // 폼 검증
    if (!formData.name || !formData.phone || !formData.inquiry) {
        alert('필수 항목을 모두 입력해주세요.');
        return;
    }
    
    // 개인정보 동의 체크
    const privacyCheckbox = document.querySelector('input[name="privacy"]');
    if (!privacyCheckbox.checked) {
        alert('개인정보처리방침에 동의해주세요.');
        return;
    }
    
    // 실제 서버 전송 로직은 여기에 추가
    // 예: fetch API를 사용한 서버 전송
    
    // 임시 성공 메시지
    alert(`상담 신청이 접수되었습니다.\n\n이름: ${formData.name}\n연락처: ${formData.phone}\n\n빠른 시일 내에 연락드리겠습니다.`);
    
    // 폼 초기화
    consultingForm.reset();
});

// 서비스 카드 애니메이션 (스크롤 시 나타나기)
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver(function(entries) {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, observerOptions);

// 서비스 카드에 애니메이션 적용
document.querySelectorAll('.service-card').forEach((card, index) => {
    card.style.opacity = '0';
    card.style.transform = 'translateY(30px)';
    card.style.transition = `all 0.6s ease ${index * 0.1}s`;
    observer.observe(card);
});

// 모바일 메뉴 토글 (추가 기능)
function setupMobileMenu() {
    const nav = document.querySelector('.nav');
    const navUl = document.querySelector('.nav > ul');
    let menuBtn = document.querySelector('.mobile-menu-btn');

    if (window.innerWidth <= 768) {
        if (!menuBtn) {
            menuBtn = document.createElement('button');
            menuBtn.className = 'mobile-menu-btn';
            menuBtn.innerHTML = '☰';
            menuBtn.style.cssText = `
                display: block;
                background: var(--primary-color);
                color: white;
                border: none;
                padding: 10px 20px;
                font-size: 1.5rem;
                cursor: pointer;
                width: 100%;
            `;
            nav.insertBefore(menuBtn, navUl);

            menuBtn.addEventListener('click', function() {
                const isFlex = navUl.style.display === 'flex';
                navUl.style.display = isFlex ? 'none' : 'flex';
                navUl.style.flexDirection = 'column';
            });
        }
        navUl.style.display = 'none';
    } else {
        if (menuBtn) {
            menuBtn.remove();
        }
        navUl.style.display = 'flex';
        navUl.style.flexDirection = 'row';
        // 데스크탑에서는 모든 서브메뉴를 숨김
        document.querySelectorAll('.submenu').forEach(sm => sm.style.display = '');
    }
}

// 페이지 로드 시 및 리사이즈 시 모바일 메뉴 체크
window.addEventListener('load', setupMobileMenu);
window.addEventListener('resize', setupMobileMenu);

// 전화번호 포맷팅
document.getElementById('phone').addEventListener('input', function(e) {
    let value = e.target.value.replace(/[^0-9]/g, '');
    let formattedValue = '';
    
    if (value.length <= 3) {
        formattedValue = value;
    } else if (value.length <= 7) {
        formattedValue = value.slice(0, 3) + '-' + value.slice(3);
    } else if (value.length <= 11) {
        formattedValue = value.slice(0, 3) + '-' + value.slice(3, 7) + '-' + value.slice(7);
    } else {
        formattedValue = value.slice(0, 3) + '-' + value.slice(3, 7) + '-' + value.slice(7, 11);
    }
    
    e.target.value = formattedValue;
});

// 페이지 로드 애니메이션
window.addEventListener('load', function() {
    document.body.style.opacity = '0';
    document.body.style.transition = 'opacity 0.5s';
    
    setTimeout(function() {
        document.body.style.opacity = '1';
    }, 100);
});

console.log('M&A LEADER 웹사이트가 로드되었습니다.');
