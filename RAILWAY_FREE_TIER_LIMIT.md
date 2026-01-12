# Railway 무료 티어 제한 안내

## ⚠️ 문제

Railway 무료 티어는 **데이터베이스만 배포**할 수 있고, 웹 서비스는 배포할 수 없습니다.

**메시지**: "현재 사용 중인 계정은 제한된 요금제를 사용하고 있어 데이터베이스만 배포할 수 있습니다."

## ✅ 해결 방법

### 방법 1: Render 사용 (추천 - 완전 무료)

Render는 무료 티어에서 웹 서비스를 제공합니다!

**빠른 시작**:

1. **Render 접속**: https://render.com
2. **GitHub로 로그인**
3. **"New +"** → **"Web Service"** 클릭
4. 저장소 연결: `parkfund-svg/mnaleader`
5. 설정:
   - **Name**: `mnaleader-web`
   - **Region**: `Singapore` (한국과 가까움)
   - **Branch**: `master`
   - **Root Directory**: `.`
   - **Environment**: `Docker`
   - **Dockerfile Path**: `./Dockerfile`
   - **Plan**: `Free`
6. **Environment Variables** 추가:
   - MySQL 호스팅 서비스 필요 (PlanetScale, Aiven 등)
   - 또는 PostgreSQL 사용 (database.sql 변환 필요)

**자세한 가이드**: `RENDER_DEPLOY.md` 참조

### 방법 2: Railway 유료 플랜 업그레이드

Railway의 유료 플랜을 사용하면 웹 서비스를 배포할 수 있습니다.

- **Starter**: 월 $5부터
- **Developer**: 월 $20부터

### 방법 3: 다른 무료 호스팅 서비스

#### 옵션 A: InfinityFree (FTP 업로드)

- 완전 무료
- PHP + MySQL 지원
- FTP를 통한 파일 업로드

**가이드**: `DEPLOYMENT.md` 참조

#### 옵션 B: Fly.io

- 무료 티어 제공
- Docker 지원
- GitHub 연동

#### 옵션 C: Vercel (프론트엔드만)

- 무료 티어
- PHP는 제한적 (Serverless Functions 사용 필요)

## 📊 비교표

| 서비스 | 무료 웹 서비스 | 무료 데이터베이스 | GitHub 연동 | 추천 |
|--------|---------------|------------------|-------------|------|
| **Render** | ✅ 예 | ✅ (PostgreSQL) | ✅ | ⭐⭐⭐ |
| Railway | ❌ 아니오 | ✅ (MySQL/PostgreSQL) | ✅ | ⭐ |
| InfinityFree | ✅ 예 | ✅ (MySQL) | ❌ | ⭐⭐ |
| Fly.io | ✅ 예 | ❌ | ✅ | ⭐⭐ |

## 🚀 Render 배포 빠른 가이드

### 1단계: Render 계정 생성

1. https://render.com 접속
2. "Get Started for Free" 클릭
3. GitHub 계정으로 로그인

### 2단계: MySQL 데이터베이스 (외부 서비스 사용)

Render는 PostgreSQL을 기본 제공하므로, MySQL이 필요한 경우:

**옵션 A: PlanetScale (무료 MySQL)**

1. https://planetscale.com 접속
2. 무료 계정 생성
3. 새 데이터베이스 생성
4. 연결 정보 확인

**옵션 B: Aiven (무료 크레딧)**

1. https://aiven.io 접속
2. 무료 크레딧으로 MySQL 서비스 생성

### 3단계: Web Service 생성

1. Render → **"New +"** → **"Web Service"**
2. GitHub 저장소 연결: `parkfund-svg/mnaleader`
3. 설정:
   - **Environment**: `Docker`
   - **Dockerfile Path**: `./Dockerfile`
   - **Plan**: `Free`
4. **Environment Variables** 추가:
   ```
   DB_HOST=[PlanetScale 호스트]
   DB_NAME=[데이터베이스 이름]
   DB_USER=[사용자 이름]
   DB_PASS=[비밀번호]
   SITE_URL=[Render에서 제공하는 URL]
   ```
5. **"Create Web Service"** 클릭

### 4단계: 데이터베이스 초기화

1. PlanetScale/Aiven 콘솔에서 데이터베이스 접속
2. `database.sql` 파일 import

## 💡 추천

**가장 쉬운 방법**: **Render** 사용
- GitHub 연동
- 무료 웹 서비스
- Docker 지원
- 자동 배포

## 📝 참고

- Railway 무료 티어 정책은 변경될 수 있습니다
- Render 무료 티어는 15분간 요청이 없으면 슬리프 모드로 전환됩니다
- 프로덕션 환경에서는 유료 플랜 사용 권장

---

**즉시 시작**: Render로 배포하세요! → https://render.com
