# Render를 사용한 GitHub 자동 배포 가이드

Render는 GitHub과 연동하여 자동으로 배포할 수 있는 무료 클라우드 플랫폼입니다.

## 🚀 빠른 시작

### 1. Render 계정 생성

1. https://render.com 접속
2. "Get Started for Free" 클릭
3. GitHub 계정으로 로그인 및 권한 승인

### 2. 프로젝트 배포

1. **새 Web Service 생성**
   - Render 대시보드에서 "New +" 클릭
   - "Web Service" 선택
   - GitHub 저장소 연결
   - 저장소 선택 (엠앤에이리더 프로젝트)

2. **서비스 설정**
   - **Name**: `mnaleader-web` (원하는 이름)
   - **Region**: `Singapore` (한국과 가까운 지역)
   - **Branch**: `main` 또는 `master`
   - **Root Directory**: `.` (루트)
   - **Environment**: `Docker`
   - **Dockerfile Path**: `./Dockerfile`
   - **Plan**: `Free`

3. **환경 변수 설정**
   - "Environment" 섹션에서 다음 변수 추가:
     ```
     DB_HOST=[MySQL 호스트]
     DB_NAME=mnaleader
     DB_USER=[MySQL 사용자]
     DB_PASS=[MySQL 비밀번호]
     SITE_URL=[Render에서 제공하는 URL]
     ```
   - **주의**: Render는 기본적으로 PostgreSQL을 제공하므로, MySQL이 필요한 경우:
     - 외부 MySQL 서비스 사용 (예: PlanetScale, Aiven)
     - 또는 PostgreSQL로 마이그레이션

4. **고급 설정**
   - "Advanced" 섹션에서:
     - **Auto-Deploy**: `Yes` (GitHub 푸시 시 자동 배포)
     - **Health Check Path**: `/`

5. **배포 시작**
   - "Create Web Service" 클릭
   - 배포가 자동으로 시작됩니다

### 3. MySQL 데이터베이스 설정

Render는 기본적으로 PostgreSQL을 제공하므로, MySQL이 필요한 경우:

**옵션 1: 외부 MySQL 서비스 사용 (권장)**
- **PlanetScale**: https://planetscale.com (무료 티어 제공)
- **Aiven**: https://aiven.io (무료 크레딧 제공)
- **Railway**: Railway의 MySQL 서비스 사용

**옵션 2: PostgreSQL로 마이그레이션**
- `database.sql`을 PostgreSQL 형식으로 변환
- `config.php`에서 PostgreSQL 연결 사용

### 4. 데이터베이스 초기화

1. **외부 MySQL 서비스 사용 시**
   - 해당 서비스의 관리 도구 사용
   - `database.sql` 파일 import

2. **PostgreSQL 사용 시**
   - Render의 PostgreSQL 서비스 사용
   - SQL 파일을 PostgreSQL 형식으로 변환 필요

### 5. 자동 배포 확인

- GitHub에 푸시하면 Render가 자동으로 감지하여 재배포합니다
- "Events" 탭에서 배포 로그 확인 가능

## 📋 환경 변수 목록

Render에서 설정해야 할 환경 변수:

| 변수명 | 설명 | 예시 |
|--------|------|------|
| `DB_HOST` | 데이터베이스 호스트 | `xxx.mysql.planetscale.com` |
| `DB_NAME` | 데이터베이스 이름 | `mnaleader` |
| `DB_USER` | 데이터베이스 사용자 | `root` |
| `DB_PASS` | 데이터베이스 비밀번호 | `[비밀번호]` |
| `SITE_URL` | 사이트 URL | `https://mnaleader.onrender.com` |

## 🔧 문제 해결

### 배포 실패
- "Events" 탭에서 로그 확인
- Dockerfile이 올바른지 확인
- 환경 변수가 모두 설정되었는지 확인

### 데이터베이스 연결 오류
- 데이터베이스 서비스가 실행 중인지 확인
- 환경 변수가 올바르게 설정되었는지 확인
- 방화벽 설정 확인 (외부 MySQL 사용 시)

### 무료 티어 제한
- Render 무료 티어는 15분간 요청이 없으면 슬리프 모드로 전환
- 첫 요청 시 깨어나는데 시간이 걸릴 수 있음
- 프로덕션 환경에서는 유료 플랜 권장

## 💰 가격

- **무료 티어**: 제한적이지만 사용 가능
- **Starter**: 월 $7부터
- **Professional**: 월 $25부터

## 🔗 유용한 링크

- Render 공식 문서: https://render.com/docs
- Render Status: https://status.render.com
- Render Discord: https://render.com/discord

---

**참고**: Render는 PostgreSQL을 기본 제공하므로, MySQL이 필수인 경우 외부 서비스 사용을 권장합니다!
