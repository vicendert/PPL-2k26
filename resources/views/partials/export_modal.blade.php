<div class="qr-modal-backdrop" id="export-modal-backdrop" role="dialog" aria-modal="true" aria-labelledby="export-modal-title">
    <div class="qr-modal" style="width: min(980px, 95%); height: 88vh; display: flex; flex-direction: column; border-radius: 12px; box-shadow: 0 25px 40px -10px rgba(0,0,0,0.25);">
        <div class="qr-modal-header" style="background: #f8fafc; border-bottom: 1px solid var(--border-color); padding: 12px 18px;">
            <div class="qr-modal-title" id="export-modal-title" style="font-size: 14px; font-weight: 700; color: #1e293b;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                Preview Laporan
            </div>
            <button class="qr-modal-close" id="export-modal-close" aria-label="Tutup modal">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div class="qr-modal-body" style="flex: 1; padding: 0; background: #f8fafc; overflow: hidden; display: flex; flex-direction: column;">
            {{-- iframe for preview --}}
            <iframe id="export-preview-frame" style="width: 100%; flex: 1; border: none; background: #f8fafc;"></iframe>
            
            {{-- footer actions --}}
            <div class="qr-modal-footer" style="background: var(--db-surface); border-top: 1px solid var(--db-border);">
                <a id="export-download-btn" href="#" class="db-btn db-btn-primary qr-confirm-btn">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    Download File
                </a>
                <button class="db-btn qr-cancel-btn" id="export-cancel-btn">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    (function() {
        const exportModal = document.getElementById('export-modal-backdrop');
        const exportFrame = document.getElementById('export-preview-frame');
        const exportDownload = document.getElementById('export-download-btn');
        const exportTitle = document.getElementById('export-modal-title');
        
        function closeExportModal() {
            exportModal.classList.remove('qr-modal-open');
            exportFrame.src = '';
            document.body.style.overflow = '';
        }

        document.getElementById('export-modal-close')?.addEventListener('click', closeExportModal);
        document.getElementById('export-cancel-btn')?.addEventListener('click', closeExportModal);
        exportModal?.addEventListener('click', function(e) {
            if (e.target === exportModal) closeExportModal();
        });

        window.openExportPreview = function(title, previewUrl, downloadUrl) {
            exportTitle.innerHTML = `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg> ${title}`;
            exportFrame.src = previewUrl;
            exportDownload.href = downloadUrl;
            
            exportModal.classList.add('qr-modal-open');
            document.body.style.overflow = 'hidden';
        };

        exportDownload?.addEventListener('click', function() {
            setTimeout(closeExportModal, 500);
        });
    })();
</script>
