-- M&A LEADER 데이터베이스 스키마
-- MySQL 5.7+ 또는 MariaDB 10.2+

CREATE DATABASE IF NOT EXISTS mnaleader CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE mnaleader;

-- 관리자 계정 테이블
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    role ENUM('super_admin', 'admin', 'editor') DEFAULT 'admin',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL,
    is_active TINYINT(1) DEFAULT 1,
    INDEX idx_username (username),
    INDEX idx_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 기본 관리자 계정 생성 (비밀번호: admin123!)
-- 실제 운영 시 반드시 변경하세요!
INSERT INTO admins (username, password, name, email, role) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '관리자', 'admin@mnaleader.co.kr', 'super_admin');

-- 양도기업 리스트 테이블
CREATE TABLE IF NOT EXISTS transfer_companies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    company_name VARCHAR(200) NOT NULL,
    business_type VARCHAR(100) NOT NULL COMMENT '업종 (종합건설업, 전문건설업 등)',
    license_type VARCHAR(200) COMMENT '면허 종류',
    capital_amount BIGINT COMMENT '자본금',
    established_year INT COMMENT '설립연도',
    location VARCHAR(200) COMMENT '소재지',
    credit_rating VARCHAR(20) COMMENT '신용등급',
    workforce INT COMMENT '기술인력 수',
    recent_revenue BIGINT COMMENT '최근 매출액',
    asking_price BIGINT COMMENT '희망 양도가',
    has_debt TINYINT(1) DEFAULT 0 COMMENT '부채 여부',
    debt_amount BIGINT COMMENT '부채액',
    performance_summary TEXT COMMENT '실적 요약',
    special_notes TEXT COMMENT '특이사항',
    contact_method VARCHAR(100) COMMENT '연락방법',
    status ENUM('available', 'negotiating', 'completed', 'cancelled') DEFAULT 'available',
    view_count INT DEFAULT 0,
    is_featured TINYINT(1) DEFAULT 0 COMMENT '추천 매물',
    is_published TINYINT(1) DEFAULT 1,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_business_type (business_type),
    INDEX idx_status (status),
    INDEX idx_published (is_published),
    INDEX idx_featured (is_featured),
    FOREIGN KEY (created_by) REFERENCES admins(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 샘플 양도기업 데이터
INSERT INTO transfer_companies (company_name, business_type, license_type, capital_amount, established_year, location, workforce, asking_price, has_debt, status, is_featured, is_published) VALUES
('㈜한국종합건설', '종합건설업', '토목공사업', 500000000, 2018, '서울 강남구', 5, 1500000000, 0, 'available', 1, 1),
('㈜대한전문건설', '전문건설업', '실내건축공사업', 100000000, 2019, '경기 성남시', 3, 350000000, 0, 'available', 1, 1),
('㈜세양전기', '전기공사업', '전기공사업', 150000000, 2017, '서울 서초구', 4, 550000000, 0, 'available', 0, 1),
('㈜정보통신기술', '정보통신공사업', '정보통신공사업', 150000000, 2020, '서울 금천구', 3, 400000000, 0, 'available', 0, 1);

-- 상담신청 테이블
CREATE TABLE IF NOT EXISTS consultations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    consultation_type VARCHAR(50) NOT NULL COMMENT '상담구분 (일반상담, 신규등록, 양도양수 등)',
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(100),
    company_name VARCHAR(200),
    inquiry TEXT NOT NULL,
    status ENUM('pending', 'in_progress', 'completed', 'cancelled') DEFAULT 'pending',
    admin_note TEXT COMMENT '관리자 메모',
    assigned_to INT COMMENT '담당 관리자',
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    responded_at TIMESTAMP NULL,
    INDEX idx_status (status),
    INDEX idx_created (created_at),
    INDEX idx_phone (phone),
    FOREIGN KEY (assigned_to) REFERENCES admins(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 신규등록 신청 테이블
CREATE TABLE IF NOT EXISTS new_registrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    consult_type VARCHAR(100) NOT NULL COMMENT '상담구분 (종합건설업, 전문건설업 등)',
    company_name VARCHAR(200),
    applicant_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(100),
    registration_type VARCHAR(50) COMMENT '등록형태 (법인신규, 개인신규 등)',
    inquiry TEXT NOT NULL,
    status ENUM('pending', 'in_progress', 'completed', 'cancelled') DEFAULT 'pending',
    admin_note TEXT,
    assigned_to INT,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_status (status),
    INDEX idx_created (created_at),
    FOREIGN KEY (assigned_to) REFERENCES admins(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 관리자 활동 로그 테이블
CREATE TABLE IF NOT EXISTS admin_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    admin_id INT,
    action VARCHAR(100) NOT NULL,
    target_type VARCHAR(50) COMMENT 'consultation, transfer_company 등',
    target_id INT,
    details TEXT,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_admin (admin_id),
    INDEX idx_created (created_at),
    FOREIGN KEY (admin_id) REFERENCES admins(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 사이트 설정 테이블
CREATE TABLE IF NOT EXISTS site_settings (
    setting_key VARCHAR(100) PRIMARY KEY,
    setting_value TEXT,
    description VARCHAR(255),
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 기본 사이트 설정
INSERT INTO site_settings (setting_key, setting_value, description) VALUES
('site_name', '(주)엠앤에이리더 M&A LEADER', '사이트 이름'),
('company_phone', '02-588-2844', '회사 전화번호'),
('company_fax', '02-6944-8260', '회사 팩스번호'),
('company_email', 'contact@mnaleader.co.kr', '회사 이메일'),
('company_address', '서울시 서초구 서초중앙로 110, B1층(A190호)', '회사 주소'),
('ceo_name', '김은하', '대표이사 이름'),
('enable_consultations', '1', '상담신청 활성화'),
('enable_transfer_list', '1', '양도기업리스트 공개');
