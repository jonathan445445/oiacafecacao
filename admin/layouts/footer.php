        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', () => {
                sidebar.classList.toggle('show');
            });
        }
        
        // Active menu item - compatible avec les liens url()
        document.querySelectorAll('.sidebar-nav .nav-link').forEach(link => {
            link.classList.remove('active');
            const href = link.getAttribute('href');
            const currentUrl = window.location.href;
            
            // Cas 1: accès direct (ex: /admin/contacts.php)
            if (currentUrl.includes('contacts.php') && href.includes('contacts')) {
                link.classList.add('active');
            } else if (currentUrl.includes('articles.php') && href.includes('articles')) {
                link.classList.add('active');
            } else if (currentUrl.includes('photos.php') && href.includes('photos')) {
                link.classList.add('active');
            } else if (currentUrl.includes('videos.php') && href.includes('videos')) {
                link.classList.add('active');
            } else if (currentUrl.includes('documents.php') && href.includes('documents')) {
                link.classList.add('active');
            } else if (currentUrl.includes('categories.php') && href.includes('categories')) {
                link.classList.add('active');
            } else if (currentUrl.includes('users.php') && href.includes('users')) {
                link.classList.add('active');
            } else if (currentUrl.includes('settings.php') && href.includes('settings')) {
                link.classList.add('active');
            } else if (currentUrl.includes('organisations.php') && href.includes('organisations')) {
                link.classList.add('active');
            } else if (currentUrl.includes('projects') && href.includes('projects')) {
                link.classList.add('active');
            } else if (currentUrl.includes('price-trends.php') && href.includes('price-trends')) {
                link.classList.add('active');
            } else if (currentUrl.includes('admin/index.php') && href.includes('admin')) {
                link.classList.add('active');
            }
            
            // Cas 2: accès via le routeur (ex: /index.php?p=admin/contacts)
            const params = new URLSearchParams(window.location.search);
            const pValue = params.get('p');
            if (pValue === 'admin/contacts' && href.includes('contacts')) {
                link.classList.add('active');
            } else if (pValue === 'admin/articles' && href.includes('articles')) {
                link.classList.add('active');
            } else if (pValue === 'admin/photos' && href.includes('photos')) {
                link.classList.add('active');
            } else if (pValue === 'admin/videos' && href.includes('videos')) {
                link.classList.add('active');
            } else if (pValue === 'admin/documents' && href.includes('documents')) {
                link.classList.add('active');
            } else if (pValue === 'admin/categories' && href.includes('categories')) {
                link.classList.add('active');
            } else if (pValue === 'admin/users' && href.includes('users')) {
                link.classList.add('active');
            } else if (pValue === 'admin/settings' && href.includes('settings')) {
                link.classList.add('active');
            } else if (pValue === 'admin/settings-presentation' && href.includes('settings-presentation')) {
                link.classList.add('active');
            } else if (pValue && pValue.includes('organisations') && href.includes('organisations')) {
                link.classList.add('active');
            } else if (pValue && pValue.includes('projects') && href.includes('projects')) {
                link.classList.add('active');
            } else if (pValue === 'admin/price-trends' && href.includes('price-trends')) {
                link.classList.add('active');
            } else if ((!pValue || pValue === 'admin' || pValue === 'admin/index') && href.includes('admin') && !href.includes('contacts') && !href.includes('articles') && !href.includes('photos') && !href.includes('videos') && !href.includes('documents') && !href.includes('categories') && !href.includes('users') && !href.includes('settings') && !href.includes('organisations') && !href.includes('projects')) {
                link.classList.add('active');
            }
        });
    </script>
</body>
</html>
