document.addEventListener('DOMContentLoaded', function () {
    // search autocomplete
    const searchInput = document.getElementById('searchInput');
    const searchResults = document.getElementById('searchResults');

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const query = this.value;
            if (query.length > 2) {
                fetch(`search.php?q=${encodeURIComponent(query)}&ajax=1`)
                    .then(response => response.json())
                    .then(data => {
                        let html = '';
                        if (data.length > 0) {
                            html = '<ul class="search-dropdown">';
                            data.forEach(item => {
                                html += `<li><a href="view.php?id=${item.id}">${item.title} (${item.error_code})</a></li>`;
                            });
                            html += '</ul>';
                        }
                        searchResults.innerHTML = html;
                        searchResults.style.display = 'block';
                    });
            } else {
                searchResults.style.display = 'none';
            }
        });
    }
});
