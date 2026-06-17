<?php $pageTitle = 'Edit Buku';
require_once __DIR__ . '/../../layouts/header.php'; ?>
<div class="max-w-3xl mx-auto">
    <a href="index.php?page=books"
        class="inline-flex items-center gap-2 text-sm font-bold text-gray-500 hover:text-wisdom-primary mb-6 transition-colors">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke Katalog
    </a>

    <div class="bg-wisdom-paper rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-wisdom-dark to-wisdom-primary px-8 py-6 text-white flex items-center gap-4">
            <div
                class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center shadow-inner border border-white/20">
                <i data-lucide="edit" class="w-6 h-6 text-yellow-400"></i>
            </div>
            <div>
                <h2 class="text-2xl font-serif font-bold">Edit Data Buku</h2>
                <p class="text-blue-100 text-sm mt-1">Perbarui informasi buku "<?= htmlspecialchars($book['title']) ?>"
                </p>
            </div>
        </div>

        <form method="POST" action="index.php?page=books&action=update" enctype="multipart/form-data"
            class="p-8 space-y-6">
            <input type="hidden" name="id" value="<?= $book['id'] ?>">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nomor ISBN</label>
                    <div class="relative">
                        <i data-lucide="barcode"
                            class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 w-5 h-5"></i>
                        <input type="text" name="isbn" value="<?= htmlspecialchars($book['isbn']) ?>"
                            class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-0 focus:border-wisdom-primary focus:bg-white hover:bg-white outline-none font-mono transition-all"
                            required>
                    </div>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Judul Buku</label>
                    <input type="text" name="title" value="<?= htmlspecialchars($book['title']) ?>"
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-0 focus:border-wisdom-primary focus:bg-white hover:bg-white outline-none transition-all"
                        required>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Pengarang</label>
                    <div class="relative">
                        <i data-lucide="pen-tool"
                            class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 w-4 h-4"></i>
                        <input type="text" name="author" value="<?= htmlspecialchars($book['author']) ?>"
                            class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-0 focus:border-wisdom-primary focus:bg-white hover:bg-white outline-none transition-all"
                            required>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Penerbit</label>
                    <div class="relative">
                        <i data-lucide="building"
                            class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 w-4 h-4"></i>
                        <input type="text" name="publisher" value="<?= htmlspecialchars($book['publisher']) ?>"
                            class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-0 focus:border-wisdom-primary focus:bg-white hover:bg-white outline-none transition-all"
                            required>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Tahun Terbit</label>
                    <div class="relative">
                        <i data-lucide="calendar"
                            class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 w-4 h-4"></i>
                        <input type="number" name="year_published" value="<?= $book['year_published'] ?>"
                            class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-0 focus:border-wisdom-primary focus:bg-white hover:bg-white outline-none transition-all"
                            required>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Kategori</label>
                    <select name="category_id"
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-0 focus:border-wisdom-primary focus:bg-white hover:bg-white outline-none transition-all cursor-pointer">
                        <option value="">Pilih kategori</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= $book['category_id'] == $cat['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Total Stok Keseluruhan</label>
                    <div class="relative">
                        <i data-lucide="layers"
                            class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 w-4 h-4"></i>
                        <input type="number" name="total_stock" value="<?= $book['total_stock'] ?>"
                            class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-0 focus:border-wisdom-primary focus:bg-white hover:bg-white outline-none transition-all"
                            min="1" required>
                    </div>
                    <p class="text-xs text-gray-500 font-medium mt-2 flex items-center gap-1.5"><i data-lucide="info"
                            class="w-3.5 h-3.5 text-blue-500"></i> Stok tersedia saat ini untuk dipinjam: <span
                            class="font-bold text-blue-600"><?= $book['stock'] ?></span></p>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Ganti Sampul Buku</label>
                    <div class="relative">
                        <i data-lucide="image-plus"
                            class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 w-4 h-4"></i>
                        <input type="file" name="cover_image" accept="image/*"
                            class="w-full pl-11 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm outline-none file:mr-4 file:py-1 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer transition-all">
                    </div>
                    <?php if ($book['cover_image']): ?>
                        <div
                            class="mt-3 flex items-start gap-3 p-3 bg-gray-50 border border-gray-100 rounded-xl inline-block">
                            <img src="<?= htmlspecialchars($book['cover_image']) ?>"
                                class="h-16 w-12 rounded-md object-cover shadow-sm">
                            <div>
                                <p class="text-xs font-bold text-gray-600">Sampul Saat Ini</p>
                                <p class="text-[10px] text-gray-400">Biarkan kosong jika tidak ingin mengubah.</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Sinopsis / Deskripsi</label>
                    <textarea name="description" rows="4"
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-0 focus:border-wisdom-primary focus:bg-white hover:bg-white outline-none resize-none transition-all"><?= htmlspecialchars($book['description'] ?? '') ?></textarea>
                </div>
            </div>

            <div class="flex gap-4 pt-4 border-t border-gray-100 mt-8">
                <button type="submit"
                    class="px-8 py-3 bg-wisdom-primary text-white text-sm font-bold rounded-xl hover:bg-blue-900 transition-colors shadow-lg shadow-wisdom-primary/30 flex items-center gap-2">
                    <i data-lucide="save" class="w-4 h-4"></i> Simpan Perubahan
                </button>
                <a href="index.php?page=books"
                    class="px-8 py-3 border border-gray-200 bg-white text-gray-600 text-sm font-bold rounded-xl hover:bg-gray-50 hover:text-gray-800 transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>