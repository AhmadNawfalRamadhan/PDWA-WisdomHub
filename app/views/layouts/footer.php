    </div><!-- end p-8 -->
</main>

<!-- Global Modals -->
<div id="wisdom-alert-modal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4">
    <div class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity opacity-0" id="wisdom-alert-backdrop"></div>
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm relative z-10 scale-95 opacity-0 transition-all transform duration-300 overflow-hidden" id="wisdom-alert-content">
        <div class="p-6 text-center">
            <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-4 text-blue-500">
                <i data-lucide="info" class="w-8 h-8"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-800 mb-2">Informasi</h3>
            <p id="wisdom-alert-message" class="text-sm text-gray-500 font-medium leading-relaxed"></p>
        </div>
        <div class="p-4 bg-gray-50 border-t border-gray-100 flex justify-center">
            <button id="wisdom-alert-btn" class="px-6 py-2.5 bg-blue-600 text-white text-sm font-bold rounded-xl hover:bg-blue-700 transition-colors w-full">OK Mengerti</button>
        </div>
    </div>
</div>

<div id="wisdom-confirm-modal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4">
    <div class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity opacity-0" id="wisdom-confirm-backdrop"></div>
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm relative z-10 scale-95 opacity-0 transition-all transform duration-300 overflow-hidden" id="wisdom-confirm-content">
        <div class="p-6 text-center">
            <div class="w-16 h-16 bg-yellow-50 rounded-full flex items-center justify-center mx-auto mb-4 text-yellow-500">
                <i data-lucide="help-circle" class="w-8 h-8"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-800 mb-2">Konfirmasi</h3>
            <p id="wisdom-confirm-message" class="text-sm text-gray-500 font-medium leading-relaxed"></p>
        </div>
        <div class="p-4 bg-gray-50 border-t border-gray-100 flex gap-3">
            <button id="wisdom-confirm-cancel" class="flex-1 px-4 py-2.5 bg-white border border-gray-200 text-gray-600 text-sm font-bold rounded-xl hover:bg-gray-50 hover:text-gray-800 transition-colors">Batal</button>
            <button id="wisdom-confirm-ok" class="flex-1 px-4 py-2.5 bg-blue-600 text-white text-sm font-bold rounded-xl hover:bg-blue-700 transition-colors">Ya, Lanjutkan</button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>
</body>
</html>
