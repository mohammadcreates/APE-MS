document.addEventListener('DOMContentLoaded', function() {
    const nameSearch = document.getElementById('nameSearch');
    const dateSearch = document.getElementById('dateSearch');
    const tableBody = document.getElementById('membersTableBody');

    function filterTable() {
        const nameTerm = nameSearch.value.trim().toLowerCase();
        const dateTerm = dateSearch.value.trim();
        const rows = tableBody.querySelectorAll('tr');
        let hasMatches = false;

        rows.forEach(row => {
            if (row.dataset.searchableName && row.dataset.searchableDate) {
                const nameMatch = row.dataset.searchableName.includes(nameTerm);
                const dateMatch = row.dataset.searchableDate.includes(dateTerm);
                const isMatch = nameMatch && dateMatch;

                row.style.display = isMatch ? '' : 'none';
                if (isMatch) hasMatches = true;
            }
        });

        // Show "no results" message if needed
        const noResultsRow = tableBody.querySelector('.no-results');
        if (!hasMatches && rows.length > 0) {
            if (!noResultsRow) {
                const tr = document.createElement('tr');
                tr.className = 'no-results';
                tr.innerHTML = '<td colspan="7" style="text-align:center;">No matching records found</td>';
                tableBody.appendChild(tr);
            }
        } else if (noResultsRow) {
            noResultsRow.remove();
        }
    }

    nameSearch.addEventListener('input', filterTable);
    dateSearch.addEventListener('input', filterTable);
});
