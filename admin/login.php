<?php
require_once __DIR__ . '/../config.php';
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>관리자 로그인 - M&A LEADER</title>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/styles.css">
    <style>
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
        }
        
        .login-box {
            background: white;
            padding: 50px;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 450px;
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .login-header h1 {
            color: #1e3a8a;
            font-size: 2rem;
            margin-bottom: 10px;
        }
        
        .login-header p {
            color: #6b7280;
            font-size: 0.95rem;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #374151;
            font-weight: 600;
        }
        
        .form-group input {
            width: 100%;
            padding: 14px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        .btn-login {
            width: 100%;
            padding: 16px;
            background: #1e3a8a;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .btn-login:hover {
            background: #3b82f6;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(30, 58, 138, 0.3);
        }
        
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.95rem;
        }
        
        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }
        
        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }
        
        .back-link {
            text-align: center;
            margin-top: 20px;
        }
        
        .back-link a {
            color: #6b7280;
            text-decoration: none;
            font-size: 0.9rem;
        }
        
        .back-link a:hover {
            color: #1e3a8a;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <div class="login-header">
                <h1>🔐 관리자 로그인</h1>
                <p>M&A LEADER 관리자 페이지</p>
            </div>
            
            <?php
            $error = '';
            $success = '';
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $username = sanitize($_POST['username'] ?? '');
                $password = $_POST['password'] ?? '';
                
                if (empty($username) || empty($password)) {
                    $error = '아이디와 비밀번호를 입력해주세요.';
                } else {
                    try {
                        $pdo = getDBConnection();
                        $stmt = $pdo->prepare("
                            SELECT id, username, password, name, role 
                            FROM admins 
                            WHERE username = ? AND is_active = 1
                        ");
                        $stmt->execute([$username]);
                        $admin = $stmt->fetch();
                        
                        if ($admin && password_verify($password, $admin['password'])) {
                            // 로그인 성공
                            $_SESSION['admin_id'] = $admin['id'];
                            $_SESSION['admin_username'] = $admin['username'];
                            $_SESSION['admin_name'] = $admin['name'];
                            $_SESSION['admin_role'] = $admin['role'];
                            $_SESSION['last_activity'] = time();
                            
                            // 마지막 로그인 시간 업데이트
                            $updateStmt = $pdo->prepare("UPDATE admins SET last_login = NOW() WHERE id = ?");
                            $updateStmt->execute([$admin['id']]);
                            
                            logAdminActivity('로그인', 'admin', $admin['id']);
                            
                            redirect(ADMIN_URL . '/dashboard.php');
                        } else {
                            $error = '아이디 또는 비밀번호가 일치하지 않습니다.';
                        }
                    } catch (Exception $e) {
                        error_log($e->getMessage());
                        $error = '로그인 처리 중 오류가 발생했습니다.';
                    }
                }
            }
            
            // 로그아웃 메시지
            if (isset($_GET['logout'])) {
                $success = '로그아웃되었습니다.';
            }
            ?>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="username">아이디</label>
                    <input type="text" 
                           id="username" 
                           name="username" 
                           required 
                           autofocus
                           value="<?php echo isset($_POST['username']) ? sanitize($_POST['username']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="password">비밀번호</label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           required>
                </div>
                
                <button type="submit" class="btn-login">로그인</button>
            </form>
            
            <div class="back-link">
                <a href="<?php echo SITE_URL; ?>/index.php">← 메인 페이지로 돌아가기</a>
            </div>
            
            <div style="margin-top: 30px; padding: 15px; background: #f3f4f6; border-radius: 8px; font-size: 0.85rem; color: #6b7280;">
                <strong>기본 계정:</strong><br>
                아이디: admin<br>
                비밀번호: admin123!<br>
                <em style="color: #ef4444;">※ 운영 시 반드시 비밀번호를 변경하세요!</em>
            </div>
        </div>
    </div>
</body>
</html>
