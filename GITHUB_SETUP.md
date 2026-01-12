# GitHub 업로드 가이드

이 문서는 프로젝트를 GitHub에 업로드하는 방법을 안내합니다.

## 1. GitHub 저장소 생성

1. GitHub에 로그인합니다
2. 우측 상단의 `+` 버튼을 클릭하고 `New repository`를 선택합니다
3. 저장소 이름을 입력합니다 (예: `mnaleader` 또는 `mna-leader`)
4. 설명을 추가합니다 (선택사항)
5. Public 또는 Private을 선택합니다
6. **README, .gitignore, license는 추가하지 마세요** (이미 프로젝트에 포함되어 있습니다)
7. `Create repository`를 클릭합니다

## 2. 로컬 Git 저장소 설정

프로젝트 폴더에서 다음 명령어를 실행합니다:

```bash
# 현재 상태 확인
git status

# 모든 파일 추가
git add .

# 첫 커밋 생성
git commit -m "Initial commit: M&A LEADER website with PHP backend"

# GitHub 저장소를 원격 저장소로 추가 (YOUR_USERNAME과 REPO_NAME을 실제 값으로 변경)
git remote add origin https://github.com/YOUR_USERNAME/REPO_NAME.git

# 또는 SSH를 사용하는 경우
git remote add origin git@github.com:YOUR_USERNAME/REPO_NAME.git

# 브랜치 이름을 main으로 설정 (필요한 경우)
git branch -M main

# GitHub에 푸시
git push -u origin main
```

## 3. 인증 설정

### Personal Access Token 사용 (HTTPS)

1. GitHub Settings > Developer settings > Personal access tokens > Tokens (classic)
2. `Generate new token` 클릭
3. 필요한 권한 선택 (repo 권한 필요)
4. 토큰 생성 후 복사
5. 푸시 시 비밀번호 대신 토큰 사용

### SSH 키 사용

```bash
# SSH 키 생성 (이미 있다면 생략)
ssh-keygen -t ed25519 -C "your_email@example.com"

# SSH 키를 GitHub에 추가
# 1. ~/.ssh/id_ed25519.pub 파일 내용 복사
# 2. GitHub Settings > SSH and GPG keys > New SSH key
# 3. 키 추가 후 위의 SSH URL 사용
```

## 4. 업데이트 푸시

이후 변경사항을 업로드할 때:

```bash
# 변경사항 확인
git status

# 변경된 파일 추가
git add .

# 또는 특정 파일만 추가
git add 파일명

# 커밋
git commit -m "커밋 메시지"

# GitHub에 푸시
git push
```

## 5. .gitignore 확인

다음 파일들이 GitHub에 업로드되지 않도록 확인하세요:

- `config.php` (민감한 정보 포함)
- `.env` 파일
- `uploads/` 폴더
- 로그 파일
- IDE 설정 파일

## 6. 보안 주의사항

⚠️ **중요**: GitHub에 업로드하기 전에 확인하세요

1. ✅ `config.php`가 `.gitignore`에 포함되어 있는지 확인
2. ✅ 데이터베이스 비밀번호가 코드에 하드코딩되지 않았는지 확인
3. ✅ `.env` 파일이 업로드되지 않도록 확인
4. ✅ 기본 관리자 비밀번호를 변경할 준비
5. ✅ `PASSWORD_SALT`를 운영 환경에서 변경할 준비

## 7. GitHub Actions 설정 (선택사항)

CI/CD를 설정하려면 `.github/workflows/` 폴더에 워크플로우 파일을 추가할 수 있습니다.

## 문제 해결

### 인증 오류
```bash
# 원격 저장소 URL 확인
git remote -v

# URL 변경 (HTTPS로)
git remote set-url origin https://github.com/USERNAME/REPO.git

# URL 변경 (SSH로)
git remote set-url origin git@github.com:USERNAME/REPO.git
```

### 이미 파일이 있는 경우
```bash
# 원격 저장소의 변경사항 가져오기
git pull origin main --allow-unrelated-histories

# 충돌 해결 후
git add .
git commit -m "Merge remote repository"
git push
```

### 커밋 취소
```bash
# 마지막 커밋 취소 (파일은 유지)
git reset --soft HEAD~1

# 마지막 커밋 완전 삭제
git reset --hard HEAD~1
```

## 추가 리소스

- [Git 공식 문서](https://git-scm.com/doc)
- [GitHub 가이드](https://guides.github.com/)
- [Git 명령어 치트시트](https://education.github.com/git-cheat-sheet-education.pdf)
