        function loadPage(page) {
            fetch('../src/gatherForActivityMenu.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new URLSearchParams({ page: page })
            })
            .then(response => response.text())
            .then(html => {
                document.getElementById('activity-section-page').innerHTML = html;
            });
        }

        // Load page 1 on first load
        loadPage(1);