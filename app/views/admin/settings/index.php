<?php
$pageTitle = 'Pengaturan Kontak';
require_once __DIR__ . '/../../layouts/header.php';
?>

<div class="card bg-wisdom-paper dark:bg-[#141414] dark:border-white/10 max-w-4xl animate-fade-in">
    <div class="flex items-center gap-3 mb-6 border-b border-gray-100 pb-4">
        <div class="w-10 h-10 bg-wisdom-primary/10 rounded-xl flex items-center justify-center text-wisdom-primary">
            <i data-lucide="settings" class="w-5 h-5"></i>
        </div>
        <div>
            <h2 class="text-xl font-bold text-gray-800">Pengaturan Kontak & Informasi</h2>
            <p class="text-sm text-gray-500 mt-1">Ubah informasi kontak, alamat, dan jam operasional perpustakaan.</p>
        </div>
    </div>

    <form action="index.php?page=settings&action=update" method="POST" class="space-y-6">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Contact Phone -->
            <div class="space-y-1">
                <label for="contact_phone" class="block text-sm font-semibold text-gray-700">Nomor Telepon / WhatsApp</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i data-lucide="phone" class="w-4 h-4 text-gray-400"></i>
                    </div>
                    <input type="tel" id="contact_phone" name="contact_phone" value="<?= htmlspecialchars($settings['contact_phone'] ?? '') ?>"
                        pattern="^[\+]?[(]?[0-9]{2,4}[)]?[-\s\./0-9]*$"
                        title="Hanya boleh angka, spasi, tanda tambah (+), tanda hubung (-), atau kurung ()"
                        oninput="this.value = this.value.replace(/[^0-9+\\-()\\s]/g, '')"
                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-xl text-sm focus:ring-wisdom-primary focus:border-wisdom-primary outline-none transition-shadow"
                        placeholder="Contoh: +62 812-3456-7890" required>
                </div>
                <p class="text-[11px] text-gray-500 mt-1">Gunakan angka yang valid (contoh: +628... atau 08...).</p>
            </div>

            <!-- Contact Email -->
            <div class="space-y-1">
                <label for="contact_email" class="block text-sm font-semibold text-gray-700">Email Utama</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i data-lucide="mail" class="w-4 h-4 text-gray-400"></i>
                    </div>
                    <input type="email" id="contact_email" name="contact_email" value="<?= htmlspecialchars($settings['contact_email'] ?? '') ?>"
                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-xl text-sm focus:ring-wisdom-primary focus:border-wisdom-primary outline-none transition-shadow"
                        placeholder="Contoh: info@wisdomhub.id" required>
                </div>
            </div>
        </div>

        <!-- Address -->
        <div class="space-y-1">
            <label for="contact_address" class="block text-sm font-semibold text-gray-700">Alamat Lengkap</label>
            <div class="relative">
                <div class="absolute top-3 left-3 flex items-start pointer-events-none">
                    <i data-lucide="map-pin" class="w-4 h-4 text-gray-400"></i>
                </div>
                <textarea id="contact_address" name="contact_address" rows="3"
                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-xl text-sm focus:ring-wisdom-primary focus:border-wisdom-primary outline-none transition-shadow"
                    placeholder="Masukkan alamat lengkap perpustakaan..." required><?= htmlspecialchars($settings['contact_address'] ?? '') ?></textarea>
            </div>
        </div>

        <!-- Operational Hours (Structured) -->
        <div class="space-y-3">
            <label class="block text-sm font-semibold text-gray-700">Jam Operasional (Terstruktur)</label>
            
            <div id="ops-container" class="space-y-2">
                <?php 
                $opsData = parseOperationalHours($settings['operational_hours'] ?? '');
                ?>

                <?php foreach ($opsData as $idx => $op): 
                    $dayOpts = ['Senin - Jumat', 'Sabtu', 'Minggu & Libur', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Minggu', 'Setiap Hari', 'Senin - Sabtu'];
                    if (!in_array($op['day'], $dayOpts)) {
                        $dayOpts[] = $op['day'];
                    }
                    $isTutup = strtolower(trim($op['time'])) === 'tutup';
                    $start = '08:00';
                    $end = '16:00';
                    if (!$isTutup) {
                        $parts = explode('-', $op['time']);
                        if (count($parts) == 2) {
                            $start = str_replace('.', ':', trim($parts[0]));
                            $end = str_replace('.', ':', trim($parts[1]));
                        }
                    }
                ?>
                <div class="flex flex-col md:flex-row items-stretch md:items-center gap-3 ops-row bg-gray-50/50 p-3 md:p-2 rounded-xl border border-gray-200">
                    <select name="ops_day[]" class="w-full md:flex-1 px-3 py-2 border border-gray-300 rounded-xl text-sm focus:ring-wisdom-primary outline-none">
                        <?php foreach($dayOpts as $d): ?>
                            <option value="<?= htmlspecialchars($d) ?>" <?= $op['day'] === $d ? 'selected' : '' ?>><?= htmlspecialchars($d) ?></option>
                        <?php endforeach; ?>
                    </select>

                    <div class="flex items-center justify-between gap-3 w-full md:w-auto">
                        <div class="flex items-center gap-2 time-inputs flex-1 md:flex-none">
                            <input type="time" name="ops_start[]" value="<?= htmlspecialchars($start) ?>" class="w-[45%] md:w-32 px-3 py-2 border border-gray-300 rounded-xl text-sm focus:ring-wisdom-primary outline-none text-center">
                            <span class="text-gray-500 text-sm">-</span>
                            <input type="time" name="ops_end[]" value="<?= htmlspecialchars($end) ?>" class="w-[45%] md:w-32 px-3 py-2 border border-gray-300 rounded-xl text-sm focus:ring-wisdom-primary outline-none text-center">
                        </div>

                        <button type="button" onclick="this.closest('.ops-row').remove()" class="p-2 text-red-500 hover:bg-red-50 rounded-xl" title="Hapus Baris">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <button type="button" onclick="addOpsRow()" class="flex items-center gap-1 text-sm font-semibold text-wisdom-primary hover:text-blue-800">
                <i data-lucide="plus-circle" class="w-4 h-4"></i> Tambah Hari/Jam
            </button>
            <p class="text-[11px] text-gray-500">Pilih rentang hari dan atur jam mulai serta jam selesai.</p>
        </div>

        <!-- Info Board -->
        <div class="space-y-1">
            <label for="info_board" class="block text-sm font-semibold text-gray-700">Papan Informasi (Opsional)</label>
            <div class="relative">
                <div class="absolute top-3 left-3 flex items-start pointer-events-none">
                    <i data-lucide="info" class="w-4 h-4 text-gray-400"></i>
                </div>
                <textarea id="info_board" name="info_board" rows="3"
                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-xl text-sm focus:ring-wisdom-primary focus:border-wisdom-primary outline-none transition-shadow"
                    placeholder="Contoh: Perpustakaan tutup lebih awal tanggal 17 Agustus."><?= htmlspecialchars($settings['info_board'] ?? '') ?></textarea>
            </div>
            <p class="text-[11px] text-gray-500 mt-1">Tambahkan pengumuman atau keterangan tambahan di sini.</p>
        </div>


        <div class="pt-4 border-t border-gray-100 flex justify-end">
            <button type="submit"
                class="flex items-center gap-2 bg-wisdom-primary hover:bg-blue-800 text-white px-6 py-2.5 rounded-xl font-bold text-sm transition-all shadow-md hover:shadow-lg">
                <i data-lucide="save" class="w-4 h-4"></i>
                Simpan Pengaturan
            </button>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>
