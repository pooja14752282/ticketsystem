function filterTable() {
    const search   = document.getElementById('searchInput')?.value.toLowerCase() || '';
    const status   = document.getElementById('statusFilter')?.value.toLowerCase() || '';
    const priority = document.getElementById('priorityFilter')?.value.toLowerCase() || '';

    const rows = document.querySelectorAll('#ticketTable tbody tr');

    rows.forEach(row => {

        const desc     = row.querySelector('.col-desc')?.innerText.toLowerCase() || '';
        const statusTd = row.querySelector('.col-status')?.innerText.toLowerCase() || '';
        const priorityTd = row.querySelector('.col-priority')?.innerText.toLowerCase() || '';

        const isMatch =
            desc.includes(search) &&
            (!status || statusTd.includes(status)) &&
            (!priority || priorityTd.includes(priority));

        row.style.display = isMatch ? '' : 'none';
    });
}

function showAutoAssign() {
    const selected = document.getElementById('appSelect')?.value;
    const infoBox  = document.getElementById('autoAssignInfo');
    const infoText = document.getElementById('autoAssignText');

    if (!selected) {
        infoBox.style.display = 'none';
        return;
    }

    const match = (appMembers || []).find(m => m.app_assigned === selected);

    if (match) {
        infoText.textContent = '✅ Will be assigned to: ' + match.name;
    } else {
        infoText.textContent = '⚠️ No active support member found for this app.';
    }

    infoBox.style.display = 'block';
}