@section('scripts')
<script>
    // Pass ticket_options to JS for modal badges
    const ticketOptions = {
        statuses:   @json($statuses->keyBy('value')),
        priorities: @json($priorities->keyBy('value')),
    };

    const ticketShowBaseUrl = "{{ url('/support/tickets') }}";

    function openTicketModal(ticketId) {
        const overlay = document.getElementById('ticketModalOverlay');
        overlay.style.display = 'flex';
        document.getElementById('modal-loading').style.display = 'block';
        document.getElementById('modal-content').style.display = 'none';

        fetch(`${ticketShowBaseUrl}/${ticketId}`, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => {
            if (!res.ok) throw new Error('Failed to load ticket');
            return res.json();
        })
        .then(data => {
            document.getElementById('modal-ticket-id').textContent  = '#' + data.id;
            document.getElementById('modal-category').textContent   = data.category;
            document.getElementById('modal-created').textContent    = data.created_at;
            document.getElementById('modal-description').textContent = data.description;

            // Priority badge — use DB colors if available
            const pOpt   = ticketOptions.priorities[data.priority];
            const pBg    = pOpt ? pOpt.color      : '#f3f4f6';
            const pColor = pOpt ? pOpt.text_color  : '#374151';
            const pLabel = pOpt ? pOpt.label       : capitalize(data.priority);
            document.getElementById('modal-priority').innerHTML =
                `<span style="display:inline-block;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;background:${pBg};color:${pColor};">${pLabel}</span>`;

            // Status badge — use DB colors if available
            const sOpt   = ticketOptions.statuses[data.status];
            const sBg    = sOpt ? sOpt.color      : '#f3f4f6';
            const sColor = sOpt ? sOpt.text_color  : '#374151';
            const sLabel = sOpt ? sOpt.label       : capitalize(data.status.replace('_',' '));
            document.getElementById('modal-status').innerHTML =
                `<span style="display:inline-block;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;background:${sBg};color:${sColor};">${sLabel}</span>`;

            // Attachment
            const attachEl = document.getElementById('modal-attachment');
            if (!data.attachment) {
                attachEl.innerHTML = `
                    <div style="background:#f9fafb;border:1px dashed #d1d5db;border-radius:8px;padding:20px;text-align:center;color:#9ca3af;font-size:13px;">
                        <i class="fas fa-folder-open" style="font-size:22px;display:block;margin-bottom:8px;"></i>
                        No attachment uploaded
                    </div>`;
            } else if (data.is_image) {
                attachEl.innerHTML = `
                    <div style="border:1px solid #e5e7eb;border-radius:8px;overflow:hidden;">
                        <img src="${data.attachment}" alt="Attachment" style="width:100%;max-height:280px;object-fit:contain;display:block;background:#f9fafb;">
                        <div style="padding:10px 14px;display:flex;align-items:center;justify-content:space-between;border-top:1px solid #f3f4f6;">
                            <span style="font-size:12px;color:#000000;"><i class="fas fa-image" style="margin-right:5px;"></i>${data.filename}</span>
                            <a href="${data.attachment}" download="${data.filename}" style="background:#1d4ed8;color:#fff;padding:4px 12px;border-radius:6px;font-size:12px;text-decoration:none;display:inline-flex;align-items:center;gap:5px;">
                                <i class="fas fa-download"></i> Download
                            </a>
                        </div>
                    </div>`;
            } else {
                const extIcons = { pdf: 'fa-file-pdf', doc: 'fa-file-word', docx: 'fa-file-word' };
                const ext  = data.filename.split('.').pop().toLowerCase();
                const icon = extIcons[ext] || 'fa-file-alt';
                attachEl.innerHTML = `
                    <div style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:8px;padding:14px 16px;display:flex;align-items:center;gap:14px;">
                        <div style="width:40px;height:40px;background:#dbeafe;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fas ${icon}" style="color:#1d4ed8;font-size:18px;"></i>
                        </div>
                        <div style="flex:1;">
                            <div style="font-size:13px;font-weight:600;color:#111827;">${data.filename}</div>
                            <div style="font-size:11px;color:#9ca3af;margin-top:2px;">${ext.toUpperCase()} file</div>
                        </div>
                        <a href="${data.attachment}" download="${data.filename}" style="background:#1d4ed8;color:#fff;padding:6px 14px;border-radius:6px;font-size:12px;text-decoration:none;display:inline-flex;align-items:center;gap:5px;">
                            <i class="fas fa-download"></i> Download
                        </a>
                    </div>`;
            }

            document.getElementById('modal-loading').style.display = 'none';
            document.getElementById('modal-content').style.display = 'block';
        })
        .catch(() => {
            document.getElementById('modal-loading').innerHTML =
                `<div style="color:#dc2626;"><i class="fas fa-exclamation-circle" style="display:block;font-size:24px;margin-bottom:8px;"></i>Failed to load ticket. Please try again.</div>`;
        });
    }

    function closeTicketModal() {
        document.getElementById('ticketModalOverlay').style.display = 'none';
    }

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeTicketModal();
    });

    function capitalize(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }
</script>