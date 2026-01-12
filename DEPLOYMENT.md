# 웹 배포 가이드

GitHub에 업로드한 프로젝트를 웹에서 확인할 수 있는 방법을 안내합니다.

## ⚠️ 중요 사항

**GitHub Pages는 PHP를 지원하지 않습니다!** 
이 프로젝트는 PHP 백엔드와 MySQL 데이터베이스가 필요하므로 별도의 PHP 호스팅 서비스가 필요합니다.

## 🆓 무료 호스팅 옵션

### 1. InfinityFree (추천 - 가장 간단)

**장점:**
- 완전 무료
- 무제한 대역폭
- MySQL 데이터베이스 제공
- FTP 접근 가능
- 간단한 설정

**배포 방법:**

1. **계정 생성**
   - https://infinityfree.net 접속
   - 무료 회원가입

2. **웹사이트 추가**
   - Control Panel 로그인
   - "Create Account" 클릭
   - 원하는 도메인 이름 입력 (예: `mnaleader.infinityfreeapp.com`)
   - PHP 버전 선택 (PHP 8.0 이상)

3. **파일 업로드 (방법 1: FTP)**
   ```bash
   # FTP 클라이언트 사용 (FileZilla 등)
   # FTP 접속 정보는 Control Panel에서 확인
   # 모든 프로젝트 파일을 public_html 폴더에 업로드
   ```

4. **파일 업로드 (방법 2: Git)**
   - InfinityFree는 직접 Git 지원하지 않음
   - GitHub 저장소를 클론하여 FTP로 업로드

5. **데이터베이스 설정**
   - Control Panel > MySQL Databases
   - 새 데이터베이스 생성
   - `database.sql` 파일 import
   - `config.php`에서 데이터베이스 정보 업데이트

6. **config.php 생성**
   ```php
   // FTP로 config.php.example을 업로드 후 config.php로 이름 변경
   // 데이터베이스 정보를 실제 호스팅 정보로 수정
   define('DB_HOST', 'sqlXXX.infinityfree.com'); // 제공된 호스트명
   define('DB_NAME', 'epiz_XXXXXX_dbname');
   define('DB_USER', 'epiz_XXXXXX');
   define('DB_PASS', 'your_password');
   define('SITE_URL', 'http://your-domain.infinityfreeapp.com');
   ```

### 2. 000webhost

**배포 방법:**
1. https://www.000webhost.com 회원가입
2. 새 웹사이트 생성
3. FTP 또는 File Manager로 파일 업로드
4. phpMyAdmin에서 데이터베이스 생성 및 import

### 3. Render (클라우드 플랫폼)

**장점:**
- GitHub과 연동 가능
- 자동 배포
- 무료 티어 제공 (제한적)

**배포 방법:**

1. **Render 계정 생성**
   - https://render.com 접속
   - GitHub 계정으로 로그인

2. **Web Service 생성**
   - "New +" > "Web Service"
   - GitHub 저장소 연결
   - 설정:
     - Build Command: `echo "No build needed"`
     - Start Command: PHP 내장 서버 사용 불가, Docker 사용 필요

3. **데이터베이스 추가**
   - "New +" > "PostgreSQL" 또는 외부 MySQL 서비스 필요
   - Render는 PostgreSQL 기본 제공

**참고:** Render에서 PHP와 MySQL을 함께 사용하려면 Docker Compose 설정이 필요합니다.

### 4. Railway (추천 - 최신 플랫폼)

**장점:**
- GitHub 연동
- Docker 지원
- 무료 크레딧 제공
- MySQL 지원

**배포 방법:**

1. **Railway 계정 생성**
   - https://railway.app 접속
   - GitHub 계정으로 로그인

2. **프로젝트 생성**
   - "New Project" 클릭
   - "Deploy from GitHub repo" 선택
   - 저장소 선택

3. **서비스 추가**
   - MySQL 서비스 추가
   - Web Service 추가 (docker-compose.yml 사용)

4. **환경 변수 설정**
   ```
   DB_HOST=mysql.railway.internal
   DB_NAME=railway
   DB_USER=root
   DB_PASS=[Railway에서 제공]
   ```

## 💰 유료 호스팅 (프로덕션 환경)

### 한국 호스팅 서비스

1. **Cafe24** (한국어 지원)
   - https://www.cafe24.com
   - 월 5,000원부터
   - MySQL, PHP 지원

2. **Gabia** (한국어 지원)
   - https://www.gabia.com
   - 다양한 호스팅 플랜

3. **Hosting.kr**
   - https://hosting.kr
   - 저렴한 가격

### VPS (Virtual Private Server)

더 많은 제어가 필요한 경우:

1. **DigitalOcean**
   - 월 $4부터
   - Ubuntu 서버
   - 직접 서버 관리 필요

2. **AWS Lightsail**
   - 월 $3.50부터
   - 간단한 VPS 관리

3. **Naver Cloud Platform** (한국)
   - https://www.ncloud.com
   - 한국 서버 위치

## 🚀 빠른 배포 방법 (InfinityFree 예시)

### Step 1: 파일 준비

로컬에서:
```bash
# config.php.example을 config.php로 복사
cp config.php.example config.php

# config.php를 수정 (나중에 호스팅 정보로 업데이트)
```

### Step 2: FTP 업로드

1. FileZilla 등 FTP 클라이언트 설치
2. InfinityFree에서 제공하는 FTP 정보 입력:
   - Host: ftp.epizy.com
   - Username: epiz_XXXXXX
   - Password: [제공된 비밀번호]
   - Port: 21

3. 모든 파일을 `htdocs` 폴더에 업로드

### Step 3: 데이터베이스 설정

1. InfinityFree Control Panel > MySQL Databases
2. 새 데이터베이스 생성
3. phpMyAdmin 접속
4. `database.sql` 파일 import

### Step 4: config.php 업데이트

FTP 또는 File Manager를 통해 `config.php` 수정:

```php
define('DB_HOST', 'sqlXXX.infinityfree.com');
define('DB_NAME', 'epiz_XXXXXX_dbname');
define('DB_USER', 'epiz_XXXXXX');
define('DB_PASS', 'your_database_password');
define('SITE_URL', 'http://your-domain.infinityfreeapp.com');

// 운영 환경 설정
error_reporting(0);
ini_set('display_errors', 0);
```

### Step 5: 접속 확인

브라우저에서 `http://your-domain.infinityfreeapp.com` 접속

## 📋 배포 체크리스트

배포 전 확인사항:

- [ ] `config.php`가 `.gitignore`에 포함되어 GitHub에 업로드되지 않음
- [ ] `config.php.example`을 `config.php`로 복사하고 실제 값으로 수정
- [ ] 데이터베이스 생성 및 `database.sql` import
- [ ] `SITE_URL`을 실제 도메인으로 변경
- [ ] `PASSWORD_SALT` 변경
- [ ] 기본 관리자 비밀번호 변경
- [ ] `error_reporting` 및 `display_errors` 비활성화 (운영 환경)
- [ ] `uploads/` 폴더 생성 및 권한 설정 (755)
- [ ] PHP 버전 확인 (8.0 이상)

## 🔧 문제 해결

### 데이터베이스 연결 오류
- 호스트명이 올바른지 확인
- 데이터베이스 사용자 권한 확인
- 방화벽 설정 확인

### 파일 업로드 오류
- `uploads/` 폴더 권한 확인 (755 또는 777)
- `php.ini`의 `upload_max_filesize` 확인

### 세션 오류
- `session.save_path` 확인
- 폴더 권한 확인

### 500 Internal Server Error
- `.htaccess` 파일 확인
- PHP 에러 로그 확인
- 파일 권한 확인

## 📞 지원

배포 중 문제가 발생하면:
1. 호스팅 제공업체의 문서 확인
2. PHP 에러 로그 확인
3. 브라우저 개발자 도구 콘솔 확인

## 🔗 유용한 링크

- **InfinityFree**: https://infinityfree.net
- **Railway**: https://railway.app
- **Render**: https://render.com
- **FileZilla FTP**: https://filezilla-project.org
- **phpMyAdmin**: 일반적으로 호스팅 제공업체에서 제공

---

**추천:** 처음 배포하는 경우 **InfinityFree**가 가장 간단하고 빠릅니다!
