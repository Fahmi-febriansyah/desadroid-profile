            </main>
        </div>
    </div>
</body>
</html>

<script>
// Mobile sidebar toggle
document.addEventListener('DOMContentLoaded', function(){
    var btn = document.getElementById('sidebarToggle');
    var sidebar = document.querySelector('.sidebar');
    if (btn && sidebar) {
        btn.addEventListener('click', function(){
            sidebar.classList.toggle('open');
        });
    }
    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(e){
        if (window.innerWidth <= 768) {
            if (sidebar && !sidebar.contains(e.target) && !btn.contains(e.target)) {
                sidebar.classList.remove('open');
            }
        }
    });
    // Wrap tables in .table-responsive if not already wrapped (improves responsiveness)
    try {
        var contents = document.querySelectorAll('.content');
        contents.forEach(function(container){
            var tables = container.querySelectorAll('table');
            tables.forEach(function(tbl){
                if (!tbl.parentElement.classList.contains('table-responsive')) {
                    var wrapper = document.createElement('div');
                    wrapper.className = 'table-responsive';
                    tbl.parentElement.insertBefore(wrapper, tbl);
                    wrapper.appendChild(tbl);
                }
            });
        });
    } catch (e) {
        console.warn('Table wrapping script error', e);
    }
});
</script>
