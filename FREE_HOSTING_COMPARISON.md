# 완전 무료 호스팅 옵션 비교

데이터베이스까지 포함한 완전 무료 호스팅 옵션입니다.

## 🏆 추천: InfinityFree (완전 무료 PHP + MySQL)

### 장점
- ✅ **완전 무료** (웹 서비스 + MySQL 데이터베이스)
- ✅ PHP 8.0+ 지원
- ✅ MySQL 데이터베이스 제공
- ✅ 무제한 대역폭
- ✅ FTP 접근 가능
- ✅ 간단한 설정

### 단점
- ❌ GitHub 자동 배포 없음 (FTP 업로드 필요)
- ❌ 무료 도메인만 제공 (예: `yourname.infinityfreeapp.com`)
- ❌ 광고 표시 (유료 플랜으로 제거 가능)

### 빠른 시작

1. **계정 생성**
   - https://infinityfree.net 접속
   - 무료 회원가입

2. **웹사이트 생성**
   - Control Panel 로그인
   - "Create Account" 클릭
   - 도메인 이름 입력 (예: `mnaleader.infinityfreeapp.com`)
   - PHP 버전 선택 (PHP 8.0 이상)

3. **파일 업로드 (FTP)**
   - FileZilla 등 FTP 클라이언트 설치
   - FTP 접속 정보 (Control Panel에서 확인):
     - Host: `ftpupload.net` 또는 제공된 FTP 서버
     - Username: `epiz_XXXXXX`
     - Password: [제공된 비밀번호]
     - Port: 21
   - 모든 프로젝트 파일을 `htdocs` 폴더에 업로드

4. **데이터베이스 설정**
   - Control Panel > MySQL Databases
   - 새 데이터베이스 생성
   - phpMyAdmin 접속
   - `database.sql` 파일 import

5. **config.php 설정**
   - FTP로 `config.php.example` 업로드
   - 이름을 `config.php`로 변경
   - 데이터베이스 정보 수정:
   ```php
   define('DB_HOST', 'sqlXXX.infinityfree.com');
   define('DB_NAME', 'epiz_XXXXXX_dbname');
   define('DB_USER', 'epiz_XXXXXX');
   define('DB_PASS', 'your_password');
   define('SITE_URL', 'http://your-domain.infinityfreeapp.com');
   ```

**자세한 가이드**: `DEPLOYMENT.md` 참조

---

## 옵션 2: Render (웹 서비스 무료) + 무료 MySQL

### Render 웹 서비스 (무료)
- ✅ GitHub 자동 배포
- ✅ Docker 지원
- ✅ 무료 웹 서비스

### 무료 MySQL 옵션

#### A. PlanetScale (무료 티어 제한적)
- 무료 티어: 제한적 (최근 정책 변경)
- https://planetscale.com

#### B. Aiven (무료 크레딧)
- $300 무료 크레딧 제공
- https://aiven.io

#### C. Railway (데이터베이스만 무료)
- Railway 무료 티어로 MySQL 데이터베이스만 생성
- Render 웹 서비스와 연결

#### D. FreeSQLDatabase
- 완전 무료 MySQL 호스팅
- https://www.freesqldatabase.com

---

## 옵션 3: Render + PostgreSQL (완전 무료)

Render는 PostgreSQL을 무료로 제공합니다.

### 장점
- ✅ Render 웹 서비스 무료
- ✅ Render PostgreSQL 무료
- ✅ GitHub 자동 배포
- ✅ 완전 무료

### 단점
- ❌ MySQL → PostgreSQL 마이그레이션 필요
- ❌ database.sql 파일 변환 필요

### PostgreSQL 마이그레이션

1. **database.sql을 PostgreSQL 형식으로 변환**
   - MySQL 문법을 PostgreSQL 문법으로 변경
   - 주요 변경사항:
     - `AUTO_INCREMENT` → `SERIAL`
     - `TINYINT(1)` → `BOOLEAN`
     - `DATETIME` → `TIMESTAMP`
     - 백틱(`) 제거

2. **config.php 수정**
   ```php
   // PostgreSQL 연결
   $dsn = "pgsql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";port=5432";
   ```

---

## 📊 완전 무료 옵션 비교

| 옵션 | 웹 서비스 | 데이터베이스 | GitHub 연동 | 설정 난이도 | 추천 |
|------|----------|-------------|------------|------------|------|
| **InfinityFree** | ✅ 무료 | ✅ MySQL 무료 | ❌ | ⭐⭐ 쉬움 | ⭐⭐⭐ |
| Render + Railway DB | ✅ 무료 | ✅ MySQL 무료 | ✅ | ⭐⭐⭐ 보통 | ⭐⭐ |
| Render + PostgreSQL | ✅ 무료 | ✅ PostgreSQL 무료 | ✅ | ⭐⭐⭐⭐ 어려움 | ⭐⭐ |
| Render + FreeSQL | ✅ 무료 | ✅ MySQL 무료 | ✅ | ⭐⭐⭐ 보통 | ⭐⭐ |

---

## 💡 최종 추천

### 가장 쉬운 방법: InfinityFree
- 완전 무료
- PHP + MySQL 모두 제공
- 설정이 간단
- FTP 업로드만 하면 됨

### GitHub 자동 배포가 필요하면: Render + Railway DB
- Render: 웹 서비스 (무료)
- Railway: MySQL 데이터베이스만 생성 (무료)
- GitHub 자동 배포 지원

---

## 🚀 InfinityFree 빠른 시작 (5분)

1. https://infinityfree.net 회원가입
2. "Create Account" → 도메인 입력
3. FileZilla로 파일 업로드
4. MySQL 데이터베이스 생성
5. config.php 설정
6. 완료!

**자세한 가이드**: `DEPLOYMENT.md`의 "InfinityFree" 섹션 참조

---

**결론**: 완전 무료로 시작하려면 **InfinityFree**가 가장 간단하고 빠릅니다!
