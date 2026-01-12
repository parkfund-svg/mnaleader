# Railway를 사용한 GitHub 자동 배포 가이드

Railway는 GitHub과 연동하여 자동으로 배포할 수 있는 무료 클라우드 플랫폼입니다.

## 🚀 빠른 시작

### 1. Railway 계정 생성

1. https://railway.app 접속
2. "Login" 클릭
3. "Deploy from GitHub repo" 선택
4. GitHub 계정으로 로그인 및 권한 승인

### 2. 프로젝트 배포

1. **새 프로젝트 생성**
   - Railway 대시보드에서 "New Project" 클릭
   - "Deploy from GitHub repo" 선택
   - 저장소 선택 (엠앤에이리더 프로젝트)

2. **MySQL 데이터베이스 추가**
   - 프로젝트에서 "+ New" 클릭
   - "Database" 선택
   - "Add MySQL" 선택
   - 데이터베이스가 자동으로 생성됩니다

3. **웹 서비스 배포**
   - Railway가 자동으로 `Dockerfile`을 감지하여 배포를 시작합니다
   - 또는 "+ New" > "GitHub Repo" 선택하여 웹 서비스 추가

4. **환경 변수 설정**
   - 웹 서비스 선택
   - "Variables" 탭 클릭
   - 다음 환경 변수 추가:
     ```
     DB_HOST=${{MySQL.MYSQLHOST}}
     DB_NAME=${{MySQL.MYSQLDATABASE}}
     DB_USER=${{MySQL.MYSQLUSER}}
     DB_PASS=${{MySQL.MYSQLPASSWORD}}
     SITE_URL=${{RAILWAY_PUBLIC_DOMAIN}}
     ```
   - MySQL 서비스의 변수를 참조하려면:
     - MySQL 서비스 선택 > "Variables" 탭
     - 각 변수의 "Reference" 버튼 클릭하여 복사
     - 웹 서비스의 Variables에 붙여넣기

5. **도메인 설정**
   - 웹 서비스 선택
   - "Settings" 탭
   - "Generate Domain" 클릭하여 무료 도메인 생성
   - 또는 "Custom Domain"에서 자신의 도메인 추가

### 3. 데이터베이스 초기화

1. **MySQL에 접속**
   - MySQL 서비스 선택
   - "Connect" 탭에서 연결 정보 확인
   - Railway CLI 또는 외부 MySQL 클라이언트 사용

2. **SQL 파일 import**
   ```bash
   # Railway CLI 설치 (선택사항)
   npm i -g @railway/cli
   
   # 로그인
   railway login
   
   # 프로젝트 연결
   railway link
   
   # MySQL에 연결하여 database.sql 실행
   railway connect mysql
   # 또는 외부 클라이언트에서 연결 정보 사용
   ```

3. **또는 phpMyAdmin 사용**
   - Railway는 직접 phpMyAdmin을 제공하지 않음
   - 외부 도구 사용 또는 SQL 파일을 직접 실행

### 4. config.php 설정

Railway에서는 환경 변수를 사용하므로 `config.php`가 자동으로 환경 변수를 읽습니다.

하지만 Railway에서 직접 파일을 수정할 수 없으므로:

**방법 1: Railway의 파일 시스템 사용**
- Railway는 읽기 전용 파일 시스템을 사용
- `config.php`는 런타임에 생성해야 함

**방법 2: 환경 변수만 사용 (권장)**
- `config.php.example`을 기반으로 환경 변수를 사용하도록 이미 설정되어 있음
- 추가 설정이 필요한 경우 Railway의 "Variables"에서 설정

### 5. 자동 배포 확인

- GitHub에 푸시하면 Railway가 자동으로 감지하여 재배포합니다
- "Deployments" 탭에서 배포 상태 확인 가능

## 📋 환경 변수 목록

Railway에서 설정해야 할 환경 변수:

| 변수명 | 설명 | 예시 |
|--------|------|------|
| `DB_HOST` | MySQL 호스트 | `containers-us-west-XXX.railway.app` |
| `DB_NAME` | 데이터베이스 이름 | `railway` |
| `DB_USER` | 데이터베이스 사용자 | `root` |
| `DB_PASS` | 데이터베이스 비밀번호 | `[Railway에서 생성]` |
| `SITE_URL` | 사이트 URL | `https://your-app.railway.app` |

## 🔧 문제 해결

### 배포 실패
- "Deployments" 탭에서 로그 확인
- Dockerfile이 올바른지 확인
- 환경 변수가 모두 설정되었는지 확인

### 데이터베이스 연결 오류
- MySQL 서비스가 실행 중인지 확인
- 환경 변수가 올바르게 설정되었는지 확인
- MySQL 서비스의 "Connect" 탭에서 연결 정보 확인

### 파일 업로드 오류
- `uploads/` 폴더 권한 확인
- Railway는 영구 스토리지가 제한적이므로 파일 업로드는 외부 스토리지(S3 등) 사용 권장

## 💰 가격

- **무료 티어**: 월 $5 크레딧 제공
- 사용량에 따라 과금
- 소규모 프로젝트는 무료로 사용 가능

## 🔗 유용한 링크

- Railway 공식 문서: https://docs.railway.app
- Railway Discord: https://discord.gg/railway
- Railway Status: https://status.railway.app

---

**참고**: Railway는 Docker를 사용하므로 `Dockerfile`이 필요합니다. 이미 프로젝트에 포함되어 있습니다!
