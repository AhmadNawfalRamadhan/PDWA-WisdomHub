/**
 * WisdomHub - JavaScript Helpers
 * 
 * Berisi semua fungsi UI yang dipakai bersama oleh berbagai halaman.
 * File ini di-load oleh layouts/header.php (atau secara manual di halaman auth)
 * sehingga tersedia global.
 */

// ============================================================
// SPLASH SCREEN - Auto-init (Landing Page)
// ============================================================
/**
 * Dieksekusi otomatis saat helpers.js di-load (di <head>).
 * Cek URL params: jika ada search / category / pg
 * maka splash dilewati (user sedang filter/cari buku).
 * Jika URL bersih (akses langsung / F5), splash tetap muncul.
 */
(function () {
    var params = new URLSearchParams(window.location.search);
    var hasFilter = params.has('search') || params.has('category') || params.has('pg');
    if (hasFilter) {
        document.documentElement.classList.add('wh-splash-done');
    }
})();

// ============================================================
// PASSWORD TOGGLE
// ============================================================
/**
 * Toggle visibility untuk input password dengan id "pwd".
 * Gunakan: onclick="togglePwd()"
 */
function togglePwd() {
    const p = document.getElementById('pwd');
    const e = document.getElementById('eye-icon');
    if (!p || !e) return;
    if (p.type === 'password') {
        p.type = 'text';
        e.setAttribute('data-lucide', 'eye-off');
    } else {
        p.type = 'password';
        e.setAttribute('data-lucide', 'eye');
    }
    if (typeof lucide !== 'undefined') lucide.createIcons();
}

// ============================================================
// AVATAR / PROFILE PICTURE PREVIEW
// ============================================================
/**
 * Preview foto profil sebelum diupload.
 * Gunakan: onchange="previewAvatar(this)"
 */
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function (e) {
            const img = document.getElementById('avatar-img');
            const placeholder = document.getElementById('avatar-placeholder');
            const preview = document.getElementById('avatar-preview');

            if (img) {
                img.src = e.target.result;
                img.classList.remove('hidden');
            }
            if (placeholder) placeholder.classList.add('hidden');
            if (preview) {
                preview.classList.remove('border-dashed', 'border-gray-200');
                preview.classList.add('border-solid', 'border-wisdom-primary', 'shadow-xl', 'shadow-wisdom-primary/20');
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// ============================================================
// QR CODE MODAL (Admin Books Index)
// ============================================================
/**
 * Buka modal QR Code untuk buku tertentu.
 */
function openQRModal(src, title, isbn) {
    const titleEl = document.getElementById('qr-modal-title');
    const isbnEl = document.getElementById('qr-modal-isbn');
    const imgEl = document.getElementById('qr-modal-img');
    const downloadEl = document.getElementById('qr-modal-download');
    const newtabEl = document.getElementById('qr-modal-newtab');
    const modal = document.getElementById('qr-modal');

    if (titleEl) titleEl.textContent = title;
    if (isbnEl) isbnEl.textContent = isbn;
    if (imgEl) imgEl.src = src;
    if (downloadEl) {
        downloadEl.href = src;
        downloadEl.setAttribute('download', 'QR_' + isbn + '.png');
    }
    if (newtabEl) newtabEl.href = src;

    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }
    if (typeof lucide !== 'undefined') lucide.createIcons();
}

/**
 * Tutup modal QR Code.
 */
function closeQRModal() {
    const modal = document.getElementById('qr-modal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }
}

// ============================================================
// BOOK DETAIL MODAL (Admin Books Index)
// ============================================================
/**
 * Buka modal detail buku.
 * @param {object} book - Object data buku (dari PHP json_encode).
 */
function openBookDetailModal(book) {
    const titleEl = document.getElementById('detail-title');
    const catEl = document.getElementById('detail-category');
    const isbnEl = document.getElementById('detail-isbn');
    const authorEl = document.getElementById('detail-author');
    const pubEl = document.getElementById('detail-publisher');
    const yearEl = document.getElementById('detail-year');

    if (titleEl) titleEl.textContent = book.title;
    if (catEl) catEl.textContent = book.category_name || 'Umum';
    if (isbnEl) isbnEl.textContent = 'ISBN: ' + book.isbn;
    if (authorEl && authorEl.querySelector('span')) authorEl.querySelector('span').textContent = book.author;
    if (pubEl && pubEl.querySelector('span')) pubEl.querySelector('span').textContent = book.publisher;
    if (yearEl && yearEl.querySelector('span')) yearEl.querySelector('span').textContent = book.year_published;

    const stockEl = document.getElementById('detail-stock');
    if (stockEl) {
        stockEl.innerHTML = `<i data-lucide="layers" class="w-3.5 h-3.5"></i> ${book.stock} dari ${book.total_stock} Tersedia`;
        stockEl.className = `text-sm font-bold flex items-center gap-1.5 ${book.stock === 0 ? 'text-red-500' : (book.stock <= 2 ? 'text-yellow-500' : 'text-emerald-500')}`;
    }

    const descEl = document.getElementById('detail-description');
    if (descEl) descEl.textContent = book.description || 'Tidak ada sinopsis/deskripsi untuk buku ini.';

    const coverImg = document.getElementById('detail-cover');
    const coverIcon = document.getElementById('detail-cover-icon');

    if (coverImg && coverIcon) {
        if (book.cover_image) {
            coverImg.src = book.cover_image;
            coverImg.classList.remove('hidden');
            coverIcon.classList.add('hidden');
        } else {
            coverImg.src = '';
            coverImg.classList.add('hidden');
            coverIcon.classList.remove('hidden');
        }
    }

    const modal = document.getElementById('book-detail-modal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }
    if (typeof lucide !== 'undefined') lucide.createIcons();
}

/**
 * Tutup modal detail buku.
 */
function closeBookDetailModal() {
    const modal = document.getElementById('book-detail-modal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }
}

// ============================================================
// ISBN LOOKUP (Borrows Create - setelah scan QR)
// ============================================================
/**
 * Cari buku di dropdown berdasarkan ISBN yang discan.
 * @param {string} isbn
 */
function lookupISBN(isbn) {
    if (!isbn) return;
    const sel = document.getElementById('book_id');
    if (!sel) return;

    for (let opt of sel.options) {
        if (opt.dataset.isbn && opt.dataset.isbn.replace(/-/g, '') === isbn.replace(/-/g, '')) {
            if (window.bookSelect) {
                window.bookSelect.setValue(opt.value);
                window.bookSelect.disable();
                sel.value = opt.value;
            } else {
                sel.value = opt.value;
                sel.classList.add('pointer-events-none', 'bg-gray-200');
            }

            // Tambah indikator lock
            const parent = sel.closest('.relative');
            if (parent && !parent.querySelector('.lock-indicator')) {
                const lockHtml = '<i data-lucide="lock" class="absolute right-10 top-1/2 -translate-y-1/2 text-wisdom-accent w-4 h-4 lock-indicator z-20"></i>';
                parent.insertAdjacentHTML('beforeend', lockHtml);
                if (typeof lucide !== 'undefined') lucide.createIcons();
            }
            return;
        }
    }
}

// ============================================================
// GLOBAL KEYBOARD SHORTCUT (ESC = tutup modal)
// ============================================================
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
        if (typeof closeQRModal === 'function') closeQRModal();
        if (typeof closeBookDetailModal === 'function') closeBookDetailModal();
    }
});

// ============================================================
// AUTO-HIDE FLASH MESSAGES
// ============================================================
document.addEventListener('DOMContentLoaded', function () {
    setTimeout(() => {
        const f = document.getElementById('flash-msg');
        if (f) f.style.opacity = '0', setTimeout(() => f.remove(), 300);
    }, 4000);

    // Re-enable disabled TomSelect controls on form submit so values are posted to server
    const borrowForm = document.querySelector('form[action*="borrows&action=store"]');
    if (borrowForm) {
        borrowForm.addEventListener('submit', function () {
            if (window.bookSelect) {
                window.bookSelect.enable();
            }
            if (window.userSelect) {
                window.userSelect.enable();
            }
        });
    }
});

// ============================================================
// GLOBAL WISDOM CUSTOM MODALS (Alert & Confirm)
// ============================================================
window.showWisdomAlert = function (message) {
    return new Promise(resolve => {
        const modal = document.getElementById('wisdom-alert-modal');
        const backdrop = document.getElementById('wisdom-alert-backdrop');
        const content = document.getElementById('wisdom-alert-content');
        const btn = document.getElementById('wisdom-alert-btn');

        if (!modal || !backdrop || !content || !btn) {
            alert(message);
            resolve();
            return;
        }

        document.getElementById('wisdom-alert-message').textContent = message;

        modal.classList.remove('hidden');
        modal.classList.add('flex');

        void modal.offsetWidth;

        backdrop.classList.remove('opacity-0');
        content.classList.remove('scale-95', 'opacity-0');

        const close = () => {
            backdrop.classList.add('opacity-0');
            content.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                resolve();
            }, 300);
        };

        btn.onclick = close;

        if (typeof lucide !== 'undefined') lucide.createIcons();
    });
};

window.showWisdomConfirm = function (message, onConfirmCallback) {
    const modal = document.getElementById('wisdom-confirm-modal');
    const backdrop = document.getElementById('wisdom-confirm-backdrop');
    const content = document.getElementById('wisdom-confirm-content');
    const btnCancel = document.getElementById('wisdom-confirm-cancel');
    const btnOk = document.getElementById('wisdom-confirm-ok');

    if (!modal || !backdrop || !content || !btnCancel || !btnOk) {
        if (confirm(message) && onConfirmCallback) onConfirmCallback();
        return;
    }

    document.getElementById('wisdom-confirm-message').textContent = message;

    modal.classList.remove('hidden');
    modal.classList.add('flex');

    void modal.offsetWidth;

    backdrop.classList.remove('opacity-0');
    content.classList.remove('scale-95', 'opacity-0');

    const close = () => {
        backdrop.classList.add('opacity-0');
        content.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 300);
    };

    btnCancel.onclick = close;
    btnOk.onclick = () => {
        close();
        if (onConfirmCallback) onConfirmCallback();
    };

    if (typeof lucide !== 'undefined') lucide.createIcons();
};

// ============================================================
// CAMERA QR SCANNER
// ============================================================
function startQRScanner(inputId) {
    const video = document.createElement('video');
    video.setAttribute('playsinline', '');
    const container = document.getElementById('qr-container');
    if (!container) return;
    container.innerHTML = '';
    container.appendChild(video);

    navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } })
        .then(stream => {
            video.srcObject = stream;
            video.play();
            scanQR(video, inputId, stream);
        })
        .catch(() => showWisdomAlert('Kamera tidak dapat diakses.'));
}

function scanQR(video, inputId, stream) {
    const canvas = document.createElement('canvas');
    const ctx = canvas.getContext('2d');
    const tick = () => {
        if (video.readyState === video.HAVE_ENOUGH_DATA) {
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            ctx.drawImage(video, 0, 0);
            const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
            if (typeof jsQR !== 'undefined') {
                const code = jsQR(imageData.data, imageData.width, imageData.height);
                if (code) {
                    const inputElement = document.getElementById(inputId);
                    if (inputElement) {
                        inputElement.value = code.data;
                        if (typeof lookupISBN === 'function') {
                            lookupISBN(code.data);
                        }
                    }
                    stream.getTracks().forEach(t => t.stop());
                    const c = document.getElementById('qr-container');
                    if (c) {
                        c.innerHTML = '<p class="text-green-600 text-sm font-medium flex items-center gap-2"><i data-lucide="check-circle" class="w-4 h-4"></i> Berhasil scan: ' + code.data + '</p>';
                        if (typeof lucide !== 'undefined') lucide.createIcons();
                    }
                    return;
                }
            }
        }
        requestAnimationFrame(tick);
    };
    requestAnimationFrame(tick);
}

// ============================================================
// INITIALIZE LUCIDE ICONS (Global)
// ============================================================
document.addEventListener('DOMContentLoaded', function () {
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
});

// ============================================================
// ELEGANT DARK MODE TOGGLE (Black & Gold)
// ============================================================
document.addEventListener('DOMContentLoaded', function () {
    const btn = document.getElementById('theme-toggle');
    const iconMoon = document.getElementById('icon-moon');
    const iconSun = document.getElementById('icon-sun');
    const html = document.documentElement;

    function applyTheme(dark) {
        if (!btn || !iconMoon || !iconSun) return;
        if (dark) {
            html.classList.add('dark');
            iconMoon.classList.add('hidden');
            iconSun.classList.remove('hidden');
            btn.classList.remove('hover:border-yellow-400', 'hover:bg-yellow-50', 'hover:text-yellow-600', 'text-gray-500', 'bg-gray-50', 'border-gray-200');
            btn.classList.add('border-yellow-500/40', 'bg-yellow-500/10', 'text-yellow-400');
        } else {
            html.classList.remove('dark');
            iconMoon.classList.remove('hidden');
            iconSun.classList.add('hidden');
            btn.classList.remove('border-yellow-500/40', 'bg-yellow-500/10', 'text-yellow-400');
            btn.classList.add('text-gray-500', 'bg-gray-50', 'border-gray-200');
        }
    }

    // Terapkan kecocokan styling tombol berdasarkan class root html saat ini
    applyTheme(html.classList.contains('dark'));

    if (btn) {
        btn.addEventListener('click', function () {
            const isDark = !html.classList.contains('dark');
            localStorage.setItem('wh-theme', isDark ? 'dark' : 'light');
            applyTheme(isDark);
        });
    }
});

// ============================================================
// ISBN API AUTO-FILL (Google Books & OpenLibrary)
// ============================================================
async function lookupISBNApi(btn) {
    const isbnInput = document.getElementById('isbn');
    if (!isbnInput) return;
    let isbn = isbnInput.value.replace(/[^0-9X]/gi, ''); // bersihkan format

    if (!isbn) {
        showWisdomAlert('Silakan masukkan ISBN terlebih dahulu.');
        isbnInput.focus();
        return;
    }

    const originalText = btn.innerHTML;
    btn.innerHTML = '<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i> Mencari...';
    btn.disabled = true;
    if (typeof lucide !== 'undefined') lucide.createIcons();

    const showSuccessMessage = () => {
        const flash = document.createElement('div');
        flash.className = 'fixed top-4 right-4 bg-emerald-50 text-emerald-800 border border-emerald-200 px-4 py-3 rounded-xl shadow-lg z-50 text-sm font-bold flex items-center gap-2 animate-fade-in';
        flash.innerHTML = '<i data-lucide="check-circle" class="w-4 h-4 text-emerald-500"></i> Data buku ditemukan!';
        document.body.appendChild(flash);
        if (typeof lucide !== 'undefined') lucide.createIcons();
        setTimeout(() => {
            flash.style.opacity = '0';
            flash.style.transition = 'opacity 0.3s';
            setTimeout(() => flash.remove(), 300);
        }, 3000);
    };

    try {
        let found = false;

        // 1. Coba Google Books API
        const response = await fetch(`https://www.googleapis.com/books/v1/volumes?q=isbn:${isbn}`);
        const data = await response.json();

        if (data.items && data.items.length > 0) {
            const bookInfo = data.items[0].volumeInfo;

            if (bookInfo.title) {
                const el = document.querySelector('input[name="title"]');
                if (el) el.value = bookInfo.title;
            }
            if (bookInfo.authors && bookInfo.authors.length > 0) {
                const el = document.querySelector('input[name="author"]');
                if (el) el.value = bookInfo.authors.join(', ');
            }
            if (bookInfo.publisher) {
                const el = document.querySelector('input[name="publisher"]');
                if (el) el.value = bookInfo.publisher;
            }

            if (bookInfo.publishedDate) {
                const year = bookInfo.publishedDate.split('-')[0];
                const el = document.querySelector('input[name="year_published"]');
                if (el) el.value = year;
            }

            if (bookInfo.description) {
                const el = document.querySelector('textarea[name="description"]');
                if (el) el.value = bookInfo.description;
            }

            const coverUrlEl = document.getElementById('cover_url');
            const coverPreviewEl = document.getElementById('cover_preview');
            const coverPreviewContainerEl = document.getElementById('cover_preview_container');
            if (bookInfo.imageLinks && bookInfo.imageLinks.thumbnail) {
                let thumbUrl = bookInfo.imageLinks.thumbnail.replace('http:', 'https:');
                if (coverUrlEl) coverUrlEl.value = thumbUrl;
                if (coverPreviewEl) coverPreviewEl.src = thumbUrl;
                if (coverPreviewContainerEl) coverPreviewContainerEl.classList.remove('hidden');
            } else {
                if (coverUrlEl) coverUrlEl.value = '';
                if (coverPreviewContainerEl) coverPreviewContainerEl.classList.add('hidden');
            }
            found = true;
        }

        // 2. Fallback: Coba OpenLibrary API jika Google Books gagal
        if (!found) {
            const olResponse = await fetch(`https://openlibrary.org/api/books?bibkeys=ISBN:${isbn}&format=json&jscmd=data`);
            const olData = await olResponse.json();
            const olKey = `ISBN:${isbn}`;

            if (olData[olKey]) {
                const olBook = olData[olKey];

                if (olBook.title) {
                    const el = document.querySelector('input[name="title"]');
                    if (el) el.value = olBook.title;
                }
                if (olBook.authors && olBook.authors.length > 0) {
                    const el = document.querySelector('input[name="author"]');
                    if (el) el.value = olBook.authors.map(a => a.name).join(', ');
                }
                if (olBook.publishers && olBook.publishers.length > 0) {
                    const el = document.querySelector('input[name="publisher"]');
                    if (el) el.value = olBook.publishers[0].name;
                }
                if (olBook.publish_date) {
                    const yearMatch = olBook.publish_date.match(/\d{4}/);
                    const el = document.querySelector('input[name="year_published"]');
                    if (el && yearMatch) el.value = yearMatch[0];
                }

                const coverUrlEl = document.getElementById('cover_url');
                const coverPreviewEl = document.getElementById('cover_preview');
                const coverPreviewContainerEl = document.getElementById('cover_preview_container');
                if (olBook.cover && (olBook.cover.large || olBook.cover.medium)) {
                    let coverUrl = olBook.cover.large || olBook.cover.medium;
                    if (coverUrlEl) coverUrlEl.value = coverUrl;
                    if (coverPreviewEl) coverPreviewEl.src = coverUrl;
                    if (coverPreviewContainerEl) coverPreviewContainerEl.classList.remove('hidden');
                } else {
                    if (coverUrlEl) coverUrlEl.value = '';
                    if (coverPreviewContainerEl) coverPreviewContainerEl.classList.add('hidden');
                }
                found = true;
            }
        }

        if (found) {
            showSuccessMessage();
        } else {
            showWisdomAlert('Buku dengan ISBN tersebut tidak ditemukan di Google Books maupun OpenLibrary. Silakan isi manual.');
            const coverUrlEl = document.getElementById('cover_url');
            const coverPreviewContainerEl = document.getElementById('cover_preview_container');
            if (coverUrlEl) coverUrlEl.value = '';
            if (coverPreviewContainerEl) coverPreviewContainerEl.classList.add('hidden');
        }
    } catch (error) {
        console.error('Error fetching book data:', error);
        showWisdomAlert('Terjadi kesalahan saat menghubungi server API. Silakan isi manual.');
    } finally {
        btn.innerHTML = originalText;
        btn.disabled = false;
        if (typeof lucide !== 'undefined') lucide.createIcons();
    }
}

// ============================================================
// SETTINGS OPERATIONAL HOURS ROW BUILDER
// ============================================================
window.addOpsRow = function () {
    const container = document.getElementById('ops-container');
    if (!container) return;
    const row = document.createElement('div');
    row.className = 'flex flex-col md:flex-row items-stretch md:items-center gap-3 ops-row bg-gray-50/50 p-3 md:p-2 rounded-xl border border-gray-200 mt-2';
    row.innerHTML = `
        <select name="ops_day[]" class="w-full md:flex-1 px-3 py-2 border border-gray-300 rounded-xl text-sm focus:ring-wisdom-primary outline-none">
            <option value="Senin - Jumat">Senin - Jumat</option>
            <option value="Sabtu">Sabtu</option>
            <option value="Minggu & Libur">Minggu & Libur</option>
            <option value="Senin">Senin</option>
            <option value="Selasa">Selasa</option>
            <option value="Rabu">Rabu</option>
            <option value="Kamis">Kamis</option>
            <option value="Jumat">Jumat</option>
            <option value="Minggu">Minggu</option>
            <option value="Setiap Hari">Setiap Hari</option>
            <option value="Senin - Sabtu">Senin - Sabtu</option>
        </select>

        <div class="flex items-center justify-between gap-3 w-full md:w-auto">
            <div class="flex items-center gap-2 time-inputs flex-1 md:flex-none">
                <input type="time" name="ops_start[]" value="08:00" class="w-[45%] md:w-32 px-3 py-2 border border-gray-300 rounded-xl text-sm focus:ring-wisdom-primary outline-none text-center">
                <span class="text-gray-500 text-sm">-</span>
                <input type="time" name="ops_end[]" value="16:00" class="w-[45%] md:w-32 px-3 py-2 border border-gray-300 rounded-xl text-sm focus:ring-wisdom-primary outline-none text-center">
            </div>

            <button type="button" onclick="this.closest('.ops-row').remove()" class="p-2 text-red-500 hover:bg-red-50 rounded-xl" title="Hapus Baris">
                <i data-lucide="trash-2" class="w-4 h-4"></i>
            </button>
        </div>
    `;
    container.appendChild(row);
    if (window.lucide) window.lucide.createIcons({ root: row });
};

// ============================================================
// MOBILE SIDEBAR TOGGLE (Hamburger Navigation)
// ============================================================
document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.getElementById('sidebar');
    const backdrop = document.getElementById('sidebar-backdrop');
    const toggleBtn = document.getElementById('sidebar-toggle');
    const closeBtn = document.getElementById('sidebar-close');

    if (!sidebar || !backdrop) return;

    function openSidebar() {
        backdrop.classList.remove('hidden');
        void backdrop.offsetWidth;
        backdrop.classList.remove('opacity-0');
        backdrop.classList.add('opacity-100');

        sidebar.classList.remove('-translate-x-full');
        sidebar.classList.add('translate-x-0');

        document.body.style.overflow = 'hidden';
    }

    function closeSidebar() {
        sidebar.classList.remove('translate-x-0');
        sidebar.classList.add('-translate-x-full');

        backdrop.classList.remove('opacity-100');
        backdrop.classList.add('opacity-0');

        document.body.style.overflow = '';

        setTimeout(() => {
            if (sidebar.classList.contains('-translate-x-full')) {
                backdrop.classList.add('hidden');
            }
        }, 300);
    }

    if (toggleBtn) {
        toggleBtn.addEventListener('click', openSidebar);
    }

    if (closeBtn) {
        closeBtn.addEventListener('click', closeSidebar);
    }

    if (backdrop) {
        backdrop.addEventListener('click', closeSidebar);
    }

    // Close on Escape key
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && !sidebar.classList.contains('-translate-x-full')) {
            closeSidebar();
        }
    });

    // Reset overflow style if user resizes back to desktop width
    window.addEventListener('resize', function () {
        if (window.innerWidth >= 768) {
            document.body.style.overflow = '';
            backdrop.classList.add('hidden');
            backdrop.classList.add('opacity-0');
            backdrop.classList.remove('opacity-100');
            sidebar.classList.remove('translate-x-0');
            sidebar.classList.add('-translate-x-full');
        }
    });
});
