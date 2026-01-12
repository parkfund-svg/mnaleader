# 자동 푸시 가이드

GitHub에 자동으로 푸시하는 방법입니다.

## 방법 1: GitHub 저장소 생성 후 자동 푸시

### 1단계: GitHub 저장소 생성

1. https://github.com 접속
2. 로그인
3. 우측 상단 `+` 클릭 → `New repository`
4. 저장소 이름 입력 (예: `mnaleader`)
5. 설명 추가 (선택)
6. Public 또는 Private 선택
7. **README, .gitignore, license 추가하지 않기** (이미 있음)
8. `Create repository` 클릭

### 2단계: 원격 저장소 연결 및 푸시

GitHub에서 제공한 명령어를 실행하거나, 아래 명령어를 사용하세요:

```bash
# 원격 저장소 추가 (YOUR_USERNAME과 REPO_NAME을 실제 값으로 변경)
git remote add origin https://github.com/YOUR_USERNAME/REPO_NAME.git

# 브랜치 이름을 main으로 변경 (필요한 경우)
git branch -M main

# GitHub에 푸시
git push -u origin main
```

## 방법 2: AI가 자동으로 푸시

원격 저장소가 설정되면, AI가 자동으로 푸시를 도와드릴 수 있습니다!

### 현재 상태 확인

```bash
git remote -v
```

출력이 없다면 원격 저장소가 설정되지 않은 것입니다.

### 푸시 명령어

원격 저장소가 설정되어 있다면:
```bash
git push origin main
# 또는
git push origin master
```

## 주의사항

### 인증 필요

GitHub에 푸시하려면 인증이 필요합니다:

**옵션 1: Personal Access Token (HTTPS)**
- GitHub Settings > Developer settings > Personal access tokens
- 토큰 생성 후 비밀번호 대신 토큰 사용

**옵션 2: SSH 키 (SSH URL 사용)**
- SSH 키가 이미 설정되어 있다면 SSH URL 사용 가능
- `git@github.com:USERNAME/REPO.git`

## 자동화 스크립트

원격 저장소 URL을 알려주시면, 제가 자동으로 연결하고 푸시해드릴 수 있습니다!

예시:
```
저장소 URL: https://github.com/사용자이름/mnaleader.git
```

---

**팁**: GitHub 저장소 URL만 알려주시면, 제가 나머지를 모두 처리해드립니다!
