document.addEventListener('DOMContentLoaded', function() {
    const nameSearch = document.getElementById('nameSearch');
    const tableBody = document.getElementById('membersTableBody');

    function filterTable() {
        const nameTerm = nameSearch.value.trim().toLowerCase();
        const rows = tableBody.querySelectorAll('tr');
        let hasMatches = false;

        rows.forEach(row => {
            if (row.dataset.searchableName) {
                const nameMatch = row.dataset.searchableName.includes(nameTerm);
                row.style.display = nameMatch ? '' : 'none';
                if (nameMatch) hasMatches = true;
            }
        });

        // Show "no results" message if needed
        const noResultsRow = tableBody.querySelector('.no-results');
        if (!hasMatches && rows.length > 0) {
            if (!noResultsRow) {
                const tr = document.createElement('tr');
                tr.className = 'no-results';
                tr.innerHTML = '<td colspan="4" class="text-center py-4"><i class="fas fa-info-circle fa-2x text-muted mb-2"></i><br>No matching users found</td>';
                tableBody.appendChild(tr);
            }
        } else if (noResultsRow) {
            noResultsRow.remove();
        }
    }

    nameSearch.addEventListener('input', filterTable);
}); 