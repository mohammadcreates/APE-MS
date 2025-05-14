
    document.addEventListener('DOMContentLoaded', function () {
        const nameSearch = document.getElementById('nameSearch');
        const dateSearch = document.getElementById('dateSearch');
        const daySearch = document.getElementById('daySearch');
        const tableBody = document.getElementById('membersTableBody');

        function filterTable() {
            const nameTerm = nameSearch.value.trim().toLowerCase();
            const dateTerm = dateSearch.value.trim().toLowerCase();
            const dayTerm = daySearch.value.trim().toLowerCase();
            let hasMatches = false;

            const rows = tableBody.querySelectorAll('tr');
            
            rows.forEach(row => {
                if (row.dataset.searchableName && row.dataset.searchableDate && row.dataset.searchableDay) {
                    const nameMatch = row.dataset.searchableName.includes(nameTerm);
                    const dateMatch = row.dataset.searchableDate.includes(dateTerm);
                    const dayMatch = row.dataset.searchableDay.includes(dayTerm);
                    
                    // Show row only if all active search terms match
                    const isMatch = (nameTerm === '' || nameMatch) && 
                                   (dateTerm === '' || dateMatch) && 
                                   (dayTerm === '' || dayMatch);

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
                    tr.innerHTML = '<td colspan="6" style="text-align:center;">No matching records found</td>';
                    tableBody.appendChild(tr);
                }
            } else if (noResultsRow) {
                noResultsRow.remove();
            }
        }

        nameSearch.addEventListener('input', filterTable);
        dateSearch.addEventListener('input', filterTable);
        daySearch.addEventListener('input', filterTable);
    });
