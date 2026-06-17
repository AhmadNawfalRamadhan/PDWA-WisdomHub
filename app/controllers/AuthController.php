<?php
// app/controllers/AuthController.php

class AuthController extends BaseController {
    private UserModel $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    public function login(): void {
        if (isLoggedIn()) {
            $this->redirect('/index.php?page=dashboard');
        }

        if ($this->isPost()) {
            $email    = $this->input('email');
            $password = $this->inputRaw('password');
            $user     = $this->userModel->findByEmail($email);

            if ($user && $this->userModel->verifyPassword($password, $user['password'])) {
                $_SESSION['user_id']   = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['role']      = $user['role'];
                $_SESSION['profile_picture'] = $user['profile_picture'] ?? null;
                flashMessage('success', 'Selamat datang, ' . $user['name'] . '!');
                $this->redirect('/index.php?page=dashboard');
            } else {
                flashMessage('error', 'Email atau password salah.');
                $this->view('auth/login', ['email' => $email]);
                return;
            }
        }

        $this->view('auth/login');
    }

    public function register(): void {
        if (isLoggedIn()) {
            $this->redirect('/index.php?page=dashboard');
        }

        if ($this->isPost()) {
            $name       = $this->input('name');
            $email      = $this->input('email');
            $password   = $this->inputRaw('password');
            $studentId  = $this->input('student_id');
            $phone      = $this->input('phone');

            if ($this->userModel->findByEmail($email)) {
                flashMessage('error', 'Email sudah terdaftar.');
                $this->view('auth/register', compact('name', 'email', 'studentId', 'phone'));
                return;
            }

            if (strlen($password) < 6) {
                flashMessage('error', 'Password minimal 6 karakter.');
                $this->view('auth/register', compact('name', 'email', 'studentId', 'phone'));
                return;
            }

            $profilePic = handleProfileUpload('profile_picture');

            $this->userModel->create([
                'name'            => $name,
                'email'           => $email,
                'password'        => $password,
                'role'            => 'student',
                'student_id'      => $studentId,
                'phone'           => $phone,
                'profile_picture' => $profilePic,
            ]);

            flashMessage('success', 'Registrasi berhasil! Silakan login.');
            $this->redirect('/index.php?page=login');
        }

        $this->view('auth/register');
    }

    public function forgotPassword(): void {
        if ($this->isPost()) {
            $email = $this->input('email');
            $user = $this->userModel->findByEmail($email);
            if ($user) {
                $token = bin2hex(random_bytes(32));
                $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
                
                $db = Database::getInstance()->getConnection();
                $stmt = $db->prepare("UPDATE users SET reset_token = ?, reset_expires_at = ? WHERE id = ?");
                $stmt->execute([$token, $expires, $user['id']]);

                $resetLink = APP_URL . "/index.php?page=reset_password&token=" . $token;

                // Cek apakah konfigurasi SMTP sudah diisi
                if (defined('MAIL_USER') && MAIL_USER !== '' && defined('MAIL_PASS') && MAIL_PASS !== '') {
                    try {
                        require_once __DIR__ . '/../../lib/PHPMailer/src/Exception.php';
                        require_once __DIR__ . '/../../lib/PHPMailer/src/PHPMailer.php';
                        require_once __DIR__ . '/../../lib/PHPMailer/src/SMTP.php';

                        $mail = new \PHPMailer\PHPMailer\PHPMailer(true);

                        // Server settings
                        $mail->isSMTP();
                        $mail->Host       = MAIL_HOST;
                        $mail->SMTPAuth   = true;
                        $mail->Username   = MAIL_USER;
                        $mail->Password   = MAIL_PASS;
                        
                        // Menentukan jenis keamanan berdasarkan port
                        if (MAIL_PORT === 465) {
                            $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
                        } else {
                            $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
                        }
                        $mail->Port       = MAIL_PORT;

                        // Recipients
                        $mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);
                        $mail->addAddress($email, $user['name']);

                        // Content
                        $mail->isHTML(true);
                        $mail->Subject = 'Reset Password - ' . APP_NAME;
                        
                        // Body email HTML
                        $mail->Body    = "
                            <div style='background-color: #f8fafc; padding: 40px 20px; font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, Helvetica, Arial, sans-serif;'>
                                <div style='max-width: 500px; margin: 0 auto; background-color: #ffffff; border-radius: 24px; overflow: hidden; box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05); border: 1px solid #e2e8f0;'>
                                    
                                    <!-- Header Banner with Logo -->
                                    <div style='background: linear-gradient(135deg, #1e3a8a 0%, #0f172a 100%); padding: 35px 25px; text-align: center;'>
                                        <!-- Emblem/Logo representation -->
                                        <div style='display: inline-block; width: 60px; height: 60px; background: linear-gradient(135deg, #d97706 0%, #f59e0b 100%); border-radius: 16px; margin-bottom: 12px; box-shadow: 0 8px 20px rgba(217, 119, 6, 0.3); text-align: center; line-height: 60px;'>
                                            <img src='https://api.iconify.design/lucide:library-big.svg?color=%23ffffff&amp;width=128&amp;height=128' width='30' height='30' style='width: 30px; height: 30px; vertical-align: middle; display: inline-block; margin-top: -4px;' alt='Logo' />
                                        </div>
                                        <h1 style='color: #ffffff; margin: 0; font-size: 22px; font-weight: 800; letter-spacing: -0.025em;'>" . APP_NAME . "</h1>
                                        <p style='color: #93c5fd; margin: 4px 0 0 0; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em;'>Pusat Kebijaksanaan</p>
                                    </div>

                                    <!-- Content Body -->
                                    <div style='padding: 35px 30px; color: #334155; font-size: 14px; line-height: 1.6;'>
                                        <h3 style='color: #0f172a; margin: 0 0 12px 0; font-size: 16px; font-weight: 700;'>Halo " . htmlspecialchars($user['name']) . ",</h3>
                                        <p style='margin: 0 0 20px 0; color: #64748b;'>Kami menerima permintaan untuk mereset kata sandi akun Anda di <strong>" . APP_NAME . "</strong>. Silakan klik tombol aman di bawah ini untuk mengatur kata sandi baru Anda:</p>
                                        
                                        <!-- Call to Action Button -->
                                        <div style='text-align: center; margin: 30px 0;'>
                                            <a href='{$resetLink}' style='display: inline-block; background: linear-gradient(135deg, #d97706 0%, #b45309 100%); color: #ffffff; padding: 12px 28px; text-decoration: none; border-radius: 12px; font-weight: 700; font-size: 14px; box-shadow: 0 8px 16px rgba(217, 119, 6, 0.25);'>Atur Ulang Kata Sandi</a>
                                        </div>
                                        
                                        <p style='margin: 20px 0 0 0; color: #64748b;'>Jika Anda tidak meminta perubahan kata sandi ini, silakan abaikan email ini dengan aman. Akun Anda akan tetap aman.</p>
                                    </div>

                                    <!-- Footer -->
                                    <div style='background-color: #f8fafc; padding: 20px 30px; border-top: 1px solid #edf2f7; text-align: center; font-size: 11px; color: #94a3b8;'>
                                        <p style='margin: 0 0 6px 0;'>Tautan reset ini hanya berlaku selama <strong>1 jam</strong>.</p>
                                        <p style='margin: 0;'>&copy; " . date('Y') . " " . APP_NAME . ". Hak Cipta Dilindungi.</p>
                                    </div>

                                </div>
                            </div>
                        ";
                        $mail->AltBody = "Halo " . $user['name'] . ",\n\nSilakan buka tautan berikut untuk mereset password Anda:\n" . $resetLink;

                        $mail->send();
                        flashMessage('success', 'Tautan reset password telah dikirim ke email Anda. Silakan periksa inbox atau spam.');
                    } catch (\Exception $e) {
                        flashMessage('error', 'Gagal mengirim email reset password. Error: ' . $mail->ErrorInfo);
                    }
                } else {
                    // Fallback jika SMTP belum diisi (tampil di layar untuk simulasi lokal)
                    flashMessage('success', "[Simulasi] Link reset: <a href='$resetLink' class='underline font-bold'>Klik di sini</a> (Isi MAIL_USER & MAIL_PASS di .env untuk kirim email asli)");
                }
            } else {
                flashMessage('error', 'Email tidak ditemukan.');
            }
            $this->redirect('/index.php?page=forgot_password');
            return;
        }
        $this->view('auth/forgot_password');
    }

    public function resetPassword(): void {
        $token = $this->input('token') ?: $this->param('token');
        if (!$token) {
            flashMessage('error', 'Token tidak valid.');
            $this->redirect('/index.php?page=login');
            return;
        }

        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM users WHERE reset_token = ?");
        $stmt->execute([$token]);
        $user = $stmt->fetch();

        // Cek apakah user ditemukan dan token belum kadaluarsa (dibandingkan menggunakan PHP time)
        if (!$user || strtotime($user['reset_expires_at']) < time()) {
            flashMessage('error', 'Token kadaluarsa atau tidak valid.');
            $this->redirect('/index.php?page=login');
            return;
        }

        if ($this->isPost()) {
            $password = $this->inputRaw('password');
            if (strlen($password) < 6) {
                flashMessage('error', 'Password minimal 6 karakter.');
                $this->redirect('/index.php?page=reset_password&token=' . $token);
                return;
            }

            $hash = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $db->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expires_at = NULL WHERE id = ?");
            $stmt->execute([$hash, $user['id']]);

            flashMessage('success', 'Password berhasil diubah. Silakan login.');
            $this->redirect('/index.php?page=login');
            return;
        }

        $this->view('auth/reset_password', compact('token'));
    }

    public function logout(): void {
        session_destroy();
        $this->redirect('/index.php?page=login');
    }
}
