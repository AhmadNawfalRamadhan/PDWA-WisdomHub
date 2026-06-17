<?php
// app/controllers/UserController.php

class UserController extends BaseController {
    private UserModel $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    public function index(): void {
        $search = $this->param('search');
        $page   = max(1, (int) $this->param('page', '1'));
        $users  = $this->userModel->getAll($page, $search);
        $this->view('admin/users/index', compact('users', 'search'));
    }

    public function create(): void {
        $this->view('admin/users/create');
    }

    public function store(): void {
        if (!$this->isPost()) {
            $this->redirect('/index.php?page=users');
        }

        $email = $this->input('email');
        if ($this->userModel->findByEmail($email)) {
            flashMessage('error', 'Email sudah terdaftar.');
            $this->redirect('/index.php?page=users&action=create');
            return;
        }

        $this->userModel->create([
            'name'            => $this->input('name'),
            'email'           => $email,
            'password'        => $this->inputRaw('password'),
            'role'            => $this->input('role'),
            'student_id'      => $this->input('student_id'),
            'phone'           => $this->input('phone'),
            'profile_picture' => handleProfileUpload('profile_picture'),
        ]);

        flashMessage('success', 'Pengguna berhasil ditambahkan!');
        $this->redirect('/index.php?page=users');
    }

    public function edit(): void {
        $id   = (int) $this->param('id');
        $user = $this->userModel->findById($id);
        if (!$user) {
            flashMessage('error', 'Pengguna tidak ditemukan.');
            $this->redirect('/index.php?page=users');
        }
        $this->view('admin/users/edit', compact('user'));
    }

    public function update(): void {
        if (!$this->isPost()) {
            $this->redirect('/index.php?page=users');
        }

        $id = (int) $this->input('id');
        $this->userModel->update($id, [
            'name'            => $this->input('name'),
            'email'           => $this->input('email'),
            'role'            => $this->input('role'),
            'student_id'      => $this->input('student_id'),
            'phone'           => $this->input('phone'),
            'profile_picture' => handleProfileUpload('profile_picture'),
        ]);

        flashMessage('success', 'Pengguna berhasil diperbarui!');
        $this->redirect('/index.php?page=users');
    }

    public function delete(): void {
        $id = (int) $this->param('id');
        if ($id == $_SESSION['user_id']) {
            flashMessage('error', 'Tidak bisa menghapus akun sendiri.');
            $this->redirect('/index.php?page=users');
            return;
        }
        $this->userModel->delete($id);
        flashMessage('success', 'Pengguna berhasil dihapus.');
        $this->redirect('/index.php?page=users');
    }
}
