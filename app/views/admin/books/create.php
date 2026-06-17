<?php $pageTitle = 'Tambah Buku Baru';
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
                <i data-lucide="book-plus" class="w-6 h-6 text-yellow-400"></i>
            </div>
            <div>
                <h2 class="text-2xl font-serif font-bold">Tambah Buku Baru</h2>
                <p class="text-blue-100 text-sm mt-1">Lengkapi informasi di bawah untuk menambahkan koleksi ke
                    perpustakaan.</p>
            </div>
        </div>

        <form method="POST" action="index.php?page=books&action=store" enctype="multipart/form-data"
            class="p-8 space-y-6">

            <!-- QR Scanner -->
            <div class="bg-blue-50/50 border border-blue-100 rounded-2xl p-5 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-sm font-bold text-blue-800 flex items-center gap-2">
                        <i data-lucide="qr-code" class="w-4 h-4"></i> Scan QR / Barcode ISBN
                    </p>
                    <button type="button" onclick="startQRScanner('isbn')"
                        class="px-4 py-2 bg-white border border-blue-200 text-blue-700 text-xs font-bold rounded-xl hover:bg-blue-50 transition-colors shadow-sm flex items-center gap-2">
                        <i data-lucide="camera" class="w-4 h-4"></i> Buka Kamera
                    </button>
                </div>
                <p class="text-xs text-blue-600/70 mb-2">Gunakan kamera untuk memindai barcode ISBN buku secara
                    otomatis.</p>
                <div id="qr-container" class="rounded-xl overflow-hidden shadow-inner"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nomor ISBN <span
                            class="text-red-500">*</span></label>
                    <div class="flex gap-2">
                        <div class="relative flex-1">
                            <i data-lucide="barcode"
                                class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 w-5 h-5"></i>
                            <input type="text" name="isbn" id="isbn"
                                value="<?= htmlspecialchars($data['isbn'] ?? '') ?>"
                                class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-0 focus:border-wisdom-primary focus:bg-white hover:bg-white outline-none font-mono transition-all"
                                placeholder="Contoh: 978-602-1234-56-7" required>
                        </div>
                        <button type="button" onclick="lookupISBNApi(this)"
                            class="px-5 py-3 bg-wisdom-dark text-white text-sm font-bold rounded-xl hover:bg-black transition-colors shadow-sm flex items-center gap-2 whitespace-nowrap">
                            <i data-lucide="search" class="w-4 h-4"></i> Cari Data
                        </button>
                    </div>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Judul Buku <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="title" value="<?= htmlspecialchars($data['title'] ?? '') ?>"
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-0 focus:border-wisdom-primary focus:bg-white hover:bg-white outline-none transition-all"
                        placeholder="Masukkan judul lengkap buku" required>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Pengarang <span
                            class="text-red-500">*</span></label>
                    <div class="relative">
                        <i data-lucide="pen-tool"
                            class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 w-4 h-4"></i>
                        <input type="text" name="author" value="<?= htmlspecialchars($data['author'] ?? '') ?>"
                            class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-0 focus:border-wisdom-primary focus:bg-white hover:bg-white outline-none transition-all"
                            placeholder="Nama pengarang utama" required>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Penerbit <span
                            class="text-red-500">*</span></label>
                    <div class="relative">
                        <i data-lucide="building"
                            class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 w-4 h-4"></i>
                        <input type="text" name="publisher" value="<?= htmlspecialchars($data['publisher'] ?? '') ?>"
                            class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-0 focus:border-wisdom-primary focus:bg-white hover:bg-white outline-none transition-all"
                            placeholder="Nama penerbit" required>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Tahun Terbit <span
                            class="text-red-500">*</span></label>
                    <div class="relative">
                        <i data-lucide="calendar"
                            class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 w-4 h-4"></i>
                        <input type="number" name="year_published"
                            value="<?= htmlspecialchars($data['year_published'] ?? date('Y')) ?>"
                            class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-0 focus:border-wisdom-primary focus:bg-white hover:bg-white outline-none transition-all"
                            min="1900" max="<?= date('Y') ?>" required>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Kategori</label>
                    <select name="category_id"
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-0 focus:border-wisdom-primary focus:bg-white hover:bg-white outline-none transition-all cursor-pointer">
                        <option value="">Pilih kategori buku</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= ($data['category_id'] ?? '') == $cat['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Jumlah Salinan/Stok <span
                            class="text-red-500">*</span></label>
                    <div class="relative">
                        <i data-lucide="layers"
                            class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 w-4 h-4"></i>
                        <input type="number" name="stock" value="<?= htmlspecialchars($data['stock'] ?? 1) ?>"
                            class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-0 focus:border-wisdom-primary focus:bg-white hover:bg-white outline-none transition-all"
                            min="1" required>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Sampul Buku</label>
                    <div class="relative">
                        <i data-lucide="image-plus"
                            class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 w-4 h-4"></i>
                        <input type="file" name="cover_image" accept="image/*" id="cover_upload"
                            class="w-full pl-11 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-0 focus:border-wisdom-primary focus:bg-white hover:bg-white outline-none transition-all file:mr-4 file:py-1 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                    </div>
                    <p class="text-[11px] text-gray-400 mt-1.5 font-medium ml-1">Format: JPG, PNG. Ukuran maksimal: 2MB.
                    </p>

                    <!-- Cover Preview dari API -->
                    <input type="hidden" name="cover_url" id="cover_url">
                    <div id="cover_preview_container"
                        class="hidden mt-3 p-3 bg-blue-50/50 border border-blue-100 rounded-xl flex gap-4 items-start">
                        <img id="cover_preview" src=""
                            class="w-16 h-24 object-cover rounded-lg shadow-sm border border-gray-200 bg-white">
                        <div class="flex-1">
                            <p class="text-xs font-bold text-blue-800 mb-1">Cover ditemukan dari Internet</p>
                            <p class="text-[11px] text-blue-600/70">Gambar ini akan dipakai secara otomatis jika Anda
                                tidak meng-upload file manual.</p>
                        </div>
                    </div>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Sinopsis / Deskripsi</label>
                    <textarea name="description" rows="4"
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-0 focus:border-wisdom-primary focus:bg-white hover:bg-white outline-none resize-none transition-all"
                        placeholder="Tuliskan ringkasan singkat tentang isi buku..."><?= htmlspecialchars($data['description'] ?? '') ?></textarea>
                </div>
            </div>

            <div class="flex gap-4 pt-4 border-t border-gray-100 mt-8">
                <button type="submit"
                    class="px-8 py-3 bg-wisdom-primary text-white text-sm font-bold rounded-xl hover:bg-blue-900 transition-colors shadow-lg shadow-wisdom-primary/30 flex items-center gap-2">
                    <i data-lucide="save" class="w-4 h-4"></i> Simpan Buku
                </button>
                <a href="index.php?page=books"
                    class="px-8 py-3 border border-gray-200 bg-white text-gray-600 text-sm font-bold rounded-xl hover:bg-gray-50 hover:text-gray-800 transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
</script>

<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>