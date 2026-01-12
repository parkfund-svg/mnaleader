# 아사달 호스팅 배포 체크리스트

아사달 호스팅에 배포하기 전 확인사항과 단계별 가이드입니다.

## 📋 배포 전 준비사항

### 1. 아사달 호스팅 정보 확인

아사달 관리자 페이지에서 다음 정보를 확인하세요:

- [ ] **FTP 접속 정보**
  - FTP 호스트 주소
  - FTP 사용자명
  - FTP 비밀번호
  - 포트 번호 (보통 21)

- [ ] **데이터베이스 정보**
  - 데이터베이스 이름
  - 데이터베이스 사용자명
  - 데이터베이스 비밀번호
  - 데이터베이스 호스트 (보통 `localhost`)

- [ ] **도메인 정보**
  - 메인 도메인 주소
  - HTTPS 사용 여부
  - SSL 인증서 설정 여부

- [ ] **PHP 버전**
  - PHP 8.0 이상 권장
  - 필요시 PHP 버전 변경 방법 확인

### 2. 로컬 파일 준비

- [ ] 모든 프로젝트 파일 확인
- [ ] `config.php.example` 파일 확인
- [ ] `database.sql` 파일 확인
- [ ] `.htaccess` 파일 확인

---

## 🚀 배포 단계별 가이드

### Step 1: config.php 파일 생성 및 수정

1. **로컬에서 config.php 생성**
   - `config.php.example` 파일을 복사
   - 이름을 `config.php`로 변경

2. **config.php 수정** (아사달 정보로 변경)

```php
<?php
// 운영 환경 설정
error_reporting(0); // 에러 표시 비활성화
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/logs/php_errors.log');

// 타임존 설정
date_default_timezone_set('Asia/Seoul');

// 세션 설정
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 1); // HTTPS 사용 시 1

session_start();

// 데이터베이스 설정 (아사달 정보로 변경)
define('DB_HOST', 'localhost'); // 또는 아사달에서 제공한 호스트
define('DB_NAME', '아사달_데이터베이스명'); // 실제 데이터베이스 이름
define('DB_USER', '아사달_데이터베이스_사용자명'); // 실제 사용자명
define('DB_PASS', '아사달_데이터베이스_비밀번호'); // 실제 비밀번호
define('DB_CHARSET', 'utf8mb4');

// 사이트 설정 (실제 도메인으로 변경)
define('SITE_URL', 'https://your-domain.com'); // 실제 도메인
define('ADMIN_URL', SITE_URL . '/admin');
define('SITE_NAME', '(주)엠앤에이리더 M&A LEADER');

// 경로 설정
define('ROOT_PATH', __DIR__);
define('INCLUDES_PATH', ROOT_PATH . '/includes');
define('ADMIN_PATH', ROOT_PATH . '/admin');
define('UPLOAD_PATH', ROOT_PATH . '/uploads');

// 보안 설정 (반드시 변경!)
define('PASSWORD_SALT', 'MNA_LEADER_2026_SECRET_KEY_변경필요'); // 랜덤 문자열로 변경
define('SESSION_TIMEOUT', 3600);

// 페이지네이션 설정
define('ITEMS_PER_PAGE', 20);
define('TRANSFER_LIST_PER_PAGE', 12);

// 파일 업로드 설정
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'xls', 'xlsx']);

// 이메일 설정 (아사달 SMTP 정보로 변경 가능)
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'your-email@gmail.com');
define('SMTP_PASS', 'your-password');
define('SMTP_FROM', 'noreply@mnaleader.co.kr');
define('SMTP_FROM_NAME', 'M&A LEADER');

// ... (나머지 함수들은 config.php.example과 동일)
```

3. **파일 저장**
   - 수정 완료 후 저장
   - 이 파일을 FTP로 업로드할 예정

### Step 2: FTP 접속 및 파일 업로드

1. **FileZilla 설치** (없는 경우)
   - https://filezilla-project.org 다운로드

2. **FTP 접속**
   - 호스트: 아사달 FTP 호스트
   - 사용자명: 아사달 FTP 사용자명
   - 비밀번호: 아사달 FTP 비밀번호
   - 포트: 21

3. **업로드 위치 확인**
   - 일반적으로 `public_html` 또는 `www` 폴더
   - 아사달 관리자 페이지에서 확인

4. **파일 업로드**
   - 다음 파일/폴더를 업로드:
     ```
     ✅ index.html
     ✅ index.php
     ✅ 모든 .html 파일
     ✅ 모든 .php 파일
     ✅ styles.css
     ✅ subpage.css
     ✅ script.js
     ✅ .htaccess
     ✅ config.php (수정한 파일)
     ✅ images/ 폴더 전체
     ✅ admin/ 폴더 전체
     ✅ includes/ 폴더 전체
     ```

5. **업로드하지 않을 파일**
   ```
   ❌ config.php.example
   ❌ .git/ 폴더
   ❌ docker/ 폴더
   ❌ database.sql (직접 import)
   ❌ *.md 파일 (문서 파일)
   ❌ Dockerfile, docker-compose.yml
   ❌ railway.json, render.yaml
   ```

### Step 3: 폴더 생성 및 권한 설정

1. **uploads 폴더 생성**
   - FTP에서 `uploads` 폴더가 없으면 생성
   - 권한을 **755**로 설정
   - FileZilla: 폴더 우클릭 → "파일 권한" → 755

2. **logs 폴더 생성** (선택사항)
   - 에러 로그를 위한 `logs` 폴더 생성
   - 권한 755 설정

### Step 4: 데이터베이스 설정

1. **아사달 관리자 페이지**
   - MySQL 데이터베이스 관리 메뉴 접속
   - 기존 데이터베이스 사용 또는 새로 생성

2. **phpMyAdmin 접속**
   - 아사달에서 제공하는 phpMyAdmin 접속
   - 데이터베이스 선택

3. **database.sql import**
   - "Import" 탭 클릭
   - "파일 선택" 클릭
   - 로컬의 `database.sql` 파일 선택
   - "실행" 클릭
   - 성공 메시지 확인

### Step 5: 접속 테스트

1. **메인 페이지 테스트**
   - 브라우저에서 `https://your-domain.com` 접속
   - 정상적으로 표시되는지 확인

2. **관리자 페이지 테스트**
   - `https://your-domain.com/admin` 접속
   - 로그인 페이지가 표시되는지 확인

3. **로그인 테스트**
   - 사용자명: `admin`
   - 비밀번호: `admin123!`
   - 로그인 성공 시 비밀번호 즉시 변경!

---

## ✅ 배포 완료 체크리스트

- [ ] FTP 접속 성공
- [ ] 모든 파일 업로드 완료
- [ ] `config.php` 파일 수정 및 업로드 완료
- [ ] `uploads/` 폴더 생성 및 권한 설정 (755)
- [ ] `logs/` 폴더 생성 (선택사항)
- [ ] 데이터베이스 생성 완료
- [ ] `database.sql` import 완료
- [ ] 메인 페이지 접속 확인
- [ ] 관리자 페이지 접속 확인
- [ ] 관리자 로그인 성공
- [ ] 관리자 비밀번호 변경 완료
- [ ] `PASSWORD_SALT` 변경 완료

---

## 🔒 보안 설정 (중요!)

배포 후 반드시 확인:

1. **관리자 비밀번호 변경**
   - 관리자 페이지 로그인
   - 비밀번호 변경

2. **PASSWORD_SALT 변경**
   - `config.php`에서 `PASSWORD_SALT`를 랜덤 문자열로 변경
   - 예: `'MNA_LEADER_' . bin2hex(random_bytes(16))`

3. **에러 표시 비활성화 확인**
   - `error_reporting(0)` 확인
   - `display_errors` 비활성화 확인

4. **HTTPS 설정 확인**
   - SSL 인증서 설치 확인
   - `session.cookie_secure` 활성화 확인

---

## 🆘 문제 해결

### 파일 업로드가 안 될 때
- FTP 연결 상태 확인
- 파일 권한 확인
- 업로드 폴더 경로 확인

### 데이터베이스 연결 오류
- `config.php`의 데이터베이스 정보 확인
- 데이터베이스 호스트 확인 (`localhost` 또는 제공된 호스트)
- 데이터베이스 사용자 권한 확인

### 500 Internal Server Error
- `.htaccess` 파일 확인
- PHP 버전 확인 (8.0 이상)
- 에러 로그 확인 (`logs/php_errors.log`)

### 관리자 로그인 안 될 때
- 데이터베이스에 관리자 계정이 생성되었는지 확인
- `database.sql` import가 완료되었는지 확인

---

## 📞 다음 단계

배포가 완료되면:

1. **기능 테스트**
   - 양도기업 리스트 확인
   - 상담신청 테스트
   - 신규등록 신청 테스트

2. **기존 데이터 마이그레이션** (필요한 경우)
   - 기존 홈페이지 데이터 백업
   - 새 데이터베이스로 이전

3. **모니터링**
   - 에러 로그 확인
   - 사용자 피드백 수집

---

**준비 완료!** 이제 아사달 호스팅에 배포할 수 있습니다! 🚀
