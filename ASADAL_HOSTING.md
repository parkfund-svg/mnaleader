# 아사달 호스팅 배포 가이드

기존 아사달 호스팅에 프로젝트를 배포하는 방법입니다.

## 🎯 아사달 호스팅 배포 방법

### 방법 1: FTP로 직접 업로드 (가장 간단)

#### 1단계: 파일 준비

1. **로컬에서 프로젝트 확인**
   - 모든 파일이 준비되어 있는지 확인
   - `config.php.example` 파일 확인

2. **config.php 생성**
   - `config.php.example`을 복사하여 `config.php` 생성
   - 아사달 호스팅의 데이터베이스 정보로 수정 준비

#### 2단계: FTP 접속

1. **FTP 클라이언트 설치** (없는 경우)
   - FileZilla 다운로드: https://filezilla-project.org
   - 또는 아사달 호스팅의 파일 관리자 사용

2. **FTP 접속 정보 확인**
   - 아사달 호스팅 관리자 페이지 로그인
   - FTP 접속 정보 확인:
     - **FTP 호스트**: (아사달에서 제공)
     - **FTP 사용자명**: (아사달에서 제공)
     - **FTP 비밀번호**: (아사달에서 제공)
     - **포트**: 21 (일반적으로)

3. **FTP 접속**
   - FileZilla에서 접속 정보 입력
   - 연결

#### 3단계: 파일 업로드

1. **업로드 위치 확인**
   - 일반적으로 `public_html` 또는 `www` 폴더
   - 또는 `htdocs` 폴더
   - 아사달 호스팅 관리자 페이지에서 확인

2. **파일 업로드**
   - 로컬 프로젝트 폴더의 **모든 파일** 선택
   - FTP의 업로드 폴더로 드래그 앤 드롭
   - 업로드 완료 대기

3. **업로드할 파일 목록**
   ```
   ✅ 모든 .html 파일
   ✅ 모든 .php 파일
   ✅ 모든 .css 파일
   ✅ 모든 .js 파일
   ✅ images/ 폴더 전체
   ✅ admin/ 폴더 전체
   ✅ includes/ 폴더 전체
   ✅ config.php (수정 후)
   ✅ .htaccess
   ❌ config.php.example (업로드 불필요)
   ❌ .git/ 폴더 (업로드 불필요)
   ❌ docker/ 폴더 (업로드 불필요)
   ❌ database.sql (직접 import)
   ```

#### 4단계: 데이터베이스 설정

1. **아사달 호스팅 관리자 페이지**
   - MySQL 데이터베이스 관리 메뉴 접속
   - 기존 데이터베이스 사용 또는 새로 생성

2. **데이터베이스 정보 확인**
   - 데이터베이스 이름
   - 데이터베이스 사용자명
   - 데이터베이스 비밀번호
   - 데이터베이스 호스트 (보통 `localhost`)

3. **database.sql import**
   - phpMyAdmin 접속 (아사달에서 제공)
   - 데이터베이스 선택
   - "Import" 탭 클릭
   - `database.sql` 파일 선택
   - "실행" 클릭

#### 5단계: config.php 설정

1. **FTP로 config.php 수정**
   - FileZilla에서 `config.php` 파일 찾기
   - 우클릭 → "보기/편집" (또는 다운로드 후 수정)
   - 다음 정보 수정:

```php
// 데이터베이스 설정
define('DB_HOST', 'localhost'); // 또는 아사달에서 제공한 호스트
define('DB_NAME', '아사달_데이터베이스명');
define('DB_USER', '아사달_데이터베이스_사용자명');
define('DB_PASS', '아사달_데이터베이스_비밀번호');
define('DB_CHARSET', 'utf8mb4');

// 사이트 설정
define('SITE_URL', 'https://your-domain.com'); // 실제 도메인
define('ADMIN_URL', SITE_URL . '/admin');
define('SITE_NAME', '(주)엠앤에이리더 M&A LEADER');

// 운영 환경 설정
error_reporting(0); // 에러 표시 비활성화
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/logs/php_errors.log');

// HTTPS 사용 시
ini_set('session.cookie_secure', 1); // HTTPS 사용 시 1로 변경
```

2. **파일 저장 및 업로드**
   - 수정 후 저장
   - FTP로 다시 업로드 (덮어쓰기)

#### 6단계: 폴더 권한 설정

1. **uploads 폴더 생성 및 권한 설정**
   - FTP에서 `uploads` 폴더 확인
   - 없으면 생성
   - 폴더 권한을 **755** 또는 **777**로 설정
   - FileZilla: 폴더 우클릭 → "파일 권한" → 755 입력

2. **logs 폴더 생성** (선택사항)
   - 에러 로그를 위한 `logs` 폴더 생성
   - 권한 755 설정

#### 7단계: 접속 확인

1. **메인 페이지 확인**
   - 브라우저에서 `https://your-domain.com` 접속
   - 정상적으로 표시되는지 확인

2. **관리자 페이지 확인**
   - `https://your-domain.com/admin` 접속
   - 로그인 시도 (기본: `admin` / `admin123!`)
   - 로그인 후 비밀번호 변경 권장

---

### 방법 2: 아사달 파일 관리자 사용

아사달 호스팅에 파일 관리자가 있는 경우:

1. **아사달 관리자 페이지 로그인**
2. **파일 관리자 메뉴 접속**
3. **업로드 폴더로 이동**
4. **파일 업로드**
   - ZIP 파일로 압축하여 업로드
   - 또는 개별 파일 업로드
5. **압축 해제** (ZIP인 경우)
6. **config.php 수정** (파일 관리자의 편집 기능 사용)

---

### 방법 3: Git을 통한 배포 (고급)

아사달 호스팅이 SSH 접근을 지원하는 경우:

1. **SSH 접속 정보 확인**
   - 아사달 관리자 페이지에서 SSH 정보 확인

2. **SSH로 접속**
   ```bash
   ssh username@your-domain.com
   ```

3. **Git 저장소 클론**
   ```bash
   cd public_html
   git clone https://github.com/parkfund-svg/mnaleader.git .
   ```

4. **config.php 생성**
   ```bash
   cp config.php.example config.php
   # config.php 수정
   ```

---

## 📋 체크리스트

배포 전 확인사항:

- [ ] FTP 접속 정보 확인
- [ ] 데이터베이스 정보 확인
- [ ] `config.php` 파일 생성 및 수정
- [ ] 모든 파일 업로드 완료
- [ ] `uploads/` 폴더 생성 및 권한 설정 (755)
- [ ] `database.sql` import 완료
- [ ] `.htaccess` 파일 업로드 확인
- [ ] 사이트 URL 확인 및 접속 테스트
- [ ] 관리자 페이지 로그인 테스트

---

## 🔧 아사달 호스팅 특이사항

### PHP 버전 확인
- 아사달 관리자 페이지에서 PHP 버전 확인
- PHP 8.0 이상 권장
- 필요시 PHP 버전 변경

### 데이터베이스 호스트
- 대부분 `localhost` 사용
- 일부는 별도 호스트 제공 (아사달에서 확인)

### SSL 인증서
- 아사달에서 SSL 인증서 설정 확인
- HTTPS 사용 시 `session.cookie_secure` 활성화

### 이메일 설정
- 아사달의 SMTP 서버 정보 사용 가능
- `config.php`의 SMTP 설정 수정

---

## 🆘 문제 해결

### FTP 접속 오류
- 포트 번호 확인 (21 또는 22)
- 방화벽 설정 확인
- FTP 모드 확인 (Active/Passive)

### 데이터베이스 연결 오류
- 호스트명 확인 (`localhost` 또는 제공된 호스트)
- 데이터베이스 사용자 권한 확인
- 비밀번호 확인

### 파일 권한 오류
- `uploads/` 폴더 권한 755 또는 777로 설정
- 파일 업로드 기능 테스트

### 500 Internal Server Error
- `.htaccess` 파일 확인
- PHP 에러 로그 확인
- 아사달 지원팀 문의

---

## 📞 아사달 호스팅 지원

- 아사달 고객센터: 아사달 호스팅 관리자 페이지에서 확인
- 기술 지원: 호스팅 관리자 페이지의 지원 메뉴

---

## 💡 팁

1. **백업 먼저**
   - 기존 홈페이지 백업
   - 데이터베이스 백업

2. **점진적 배포**
   - 테스트 서브도메인에서 먼저 테스트
   - 문제없으면 메인 도메인에 적용

3. **기존 데이터 유지**
   - 기존 데이터베이스가 있다면 백업 후 새로 import
   - 또는 기존 데이터와 병합

---

**가장 간단한 방법**: FTP로 파일 업로드 → 데이터베이스 import → config.php 수정 → 완료!
