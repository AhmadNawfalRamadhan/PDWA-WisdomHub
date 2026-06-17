<?php
// app/controllers/BookController.php

class BookController extends BaseController {
    private BookModel     $bookModel;
    private CategoryModel $categoryModel;

    public function __construct() {
        $this->bookModel     = new BookModel();
        $this->categoryModel = new CategoryModel();
    }

    // Public catalog
    public function catalog(): void {
        $search     = $this->param('search');
        $categoryId = (int) $this->param('category');
        $page       = max(1, (int) $this->param('page', '1'));

        $books       = $this->bookModel->getAll($page, $search, $categoryId);
        $categories  = $this->categoryModel->getAll();
        $randomBooks = $this->bookModel->getRandomBooks(15);

        $settingModel = new SettingModel();
        $settings     = $settingModel->getAllSettings();

        $this->view('auth/catalog', compact('books', 'categories', 'search', 'categoryId', 'randomBooks', 'settings'));
    }

    // Admin book list
    public function index(): void {
        $search     = $this->param('search');
        $categoryId = (int) $this->param('category');
        $page       = max(1, (int) $this->param('page', '1'));

        $books      = $this->bookModel->getAll($page, $search, $categoryId);
        $categories = $this->categoryModel->getAll();

        $this->view('admin/books/index', compact('books', 'categories', 'search', 'categoryId'));
    }

    public function show(): void {
        $id   = (int) $this->param('id');
        $book = $this->bookModel->findById($id);
        if (!$book) {
            flashMessage('error', 'Buku tidak ditemukan.');
            $this->redirect('/index.php?page=books');
        }
        $this->view('admin/books/show', compact('book'));
    }

    public function create(): void {
        $categories = $this->categoryModel->getAll();
        $this->view('admin/books/create', compact('categories'));
    }

    public function store(): void {
        if (!$this->isPost()) {
            $this->redirect('/index.php?page=books');
        }

        $data = [
            'isbn'           => $this->input('isbn'),
            'title'          => $this->input('title'),
            'author'         => $this->input('author'),
            'publisher'      => $this->input('publisher'),
            'year_published' => $this->input('year_published'),
            'category_id'    => (int) $this->input('category_id'),
            'stock'          => max(1, (int) $this->input('stock')),
            'description'    => $this->input('description'),
        ];

        if ($this->bookModel->findByIsbn($data['isbn'])) {
            flashMessage('error', 'ISBN sudah terdaftar.');
            $categories = $this->categoryModel->getAll();
            $this->view('admin/books/create', compact('categories', 'data'));
            return;
        }

        // Handle cover upload
        if (!empty($_FILES['cover_image']['name'])) {
            $data['cover_image'] = $this->uploadImage($_FILES['cover_image']);
        } elseif (!empty($_POST['cover_url'])) {
            $data['cover_image'] = $this->input('cover_url');
        }

        $this->bookModel->create($data);
        flashMessage('success', 'Buku berhasil ditambahkan!');
        $this->redirect('/index.php?page=books');
    }

    public function edit(): void {
        $id   = (int) $this->param('id');
        $book = $this->bookModel->findById($id);
        if (!$book) {
            flashMessage('error', 'Buku tidak ditemukan.');
            $this->redirect('/index.php?page=books');
        }
        $categories = $this->categoryModel->getAll();
        $this->view('admin/books/edit', compact('book', 'categories'));
    }

    public function update(): void {
        if (!$this->isPost()) {
            $this->redirect('/index.php?page=books');
        }

        $id   = (int) $this->input('id');
        $book = $this->bookModel->findById($id);
        if (!$book) {
            flashMessage('error', 'Buku tidak ditemukan.');
            $this->redirect('/index.php?page=books');
        }

        $newTotalStock = max(1, (int) $this->input('total_stock'));
        $stockDiff = $newTotalStock - $book['total_stock'];
        $newAvailableStock = max(0, $book['stock'] + $stockDiff);

        $data = [
            'isbn'           => $this->input('isbn'),
            'title'          => $this->input('title'),
            'author'         => $this->input('author'),
            'publisher'      => $this->input('publisher'),
            'year_published' => $this->input('year_published'),
            'category_id'    => (int) $this->input('category_id'),
            'total_stock'    => $newTotalStock,
            'stock'          => $newAvailableStock,
            'description'    => $this->input('description'),
            'cover_image'    => $book['cover_image'],
        ];

        if (!empty($_FILES['cover_image']['name'])) {
            $data['cover_image'] = $this->uploadImage($_FILES['cover_image']);
        }

        $this->bookModel->update($id, $data);
        flashMessage('success', 'Buku berhasil diperbarui!');
        $this->redirect('/index.php?page=books');
    }

    public function delete(): void {
        $id = (int) $this->param('id');
        $this->bookModel->delete($id);
        flashMessage('success', 'Buku berhasil dihapus.');
        $this->redirect('/index.php?page=books');
    }

    private function uploadImage(array $file): ?string {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($file['type'], $allowedTypes)) return null;
        if ($file['size'] > 2 * 1024 * 1024) return null;

        $uploadDir = __DIR__ . '/../../public/assets/covers/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

        $ext      = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid('book_') . '.' . $ext;
        move_uploaded_file($file['tmp_name'], $uploadDir . $filename);
        return 'public/assets/covers/' . $filename;
    }
}