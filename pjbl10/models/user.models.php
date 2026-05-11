<?php
require_once __DIR__ . '/../database/koneksi.php';

class UserModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function emailExists($email) {
    // cek di tabel pembeli
    $stmt = $this->db->prepare("SELECT id_pembeli FROM pembeli WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        return true;
    }
  

    // cek di tabel penjual (admin)
    $stmt = $this->db->prepare("SELECT id_penjual FROM penjual WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    return $stmt->num_rows > 0;
}
    public function register($nama, $email, $password, $role = 'pembeli') {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("INSERT INTO pembeli (nama, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nama, $email, $hashed_password, $role);
        
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function getUserByEmail($email) {
    $stmt = $this->db->prepare("SELECT id_pembeli AS id, nama, email, password, 'pembeli' AS role FROM pembeli WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        return $user;
    }

    $stmt = $this->db->prepare("SELECT id_penjual AS id, nama, email, password, 'admin' AS role FROM penjual WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        return $user;
    }

    return null;
}
}
?>
