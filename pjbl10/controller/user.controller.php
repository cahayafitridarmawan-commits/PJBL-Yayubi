<?php
require_once __DIR__ . '/../models/user.models.php';

class UserController {
    private $userModel;

    public function __construct($db) {
        $this->userModel = new UserModel($db);
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function handleRegister($nama, $email, $password) {
        if ($this->userModel->emailExists($email)) {
            return "Email sudah terdaftar!";
        }

        if ($this->userModel->register($nama, $email, $password)) {
            return "Registration successful! Silakan login.";
        } else {
            return "Terjadi kesalahan saat pendaftaran.";
        }
    }

    public function handleLogin($email, $password) {
        $user = $this->userModel->getUserByEmail($email);
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            return true;
        } else {
            return "Email atau password salah!";
        }
    }

    public function logout() {
        session_destroy();
        header("Location: index.php");
        exit();
    }

    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    public function redirectIfLoggedIn() {
        if ($this->isLoggedIn()) {
            header("Location: ../pjbl10/admin/dashboard.php");
            exit();
        }
    }

    public function requireLogin() {
        if (!$this->isLoggedIn()) {
            header("Location: index.php");
            exit();
        }
    }
}

?>
