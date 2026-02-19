<aside class="sidebar">
    <div class="nav-header" style="text-align: center; padding: 20px 10px; border-bottom: 1px solid rgba(255,255,255,0.1); margin-bottom: 20px;">
        <h2 style="color: #ffffff; margin: 0; font-size: 28px; font-weight: 700; letter-spacing: 1px;">aiPms</h2>
    </div>
    <nav class="nav">
        <a href="{{ route('gaeste') }}" class="nav-link {{ request()->routeIs('gaeste') ? 'active' : '' }}">
            <i class="fa fa-users"></i> Gäste heute <span style="margin-left: auto; background: rgba(255,255,255,0.2); padding: 2px 8px; border-radius: 10px; font-size: 12px;">156</span>
        </a>
        <a href="{{ route('zimmer') }}" class="nav-link {{ request()->routeIs('zimmer') ? 'active' : '' }}">
            <i class="fa fa-bed"></i> Belegte Zimmer <span style="margin-left: auto; background: rgba(255,255,255,0.2); padding: 2px 8px; border-radius: 10px; font-size: 12px;">42</span>
        </a>
        <a href="{{ route('checkin') }}" class="nav-link {{ request()->routeIs('checkin') ? 'active' : '' }}">
            <i class="fa fa-calendar-check"></i> Check-ins heute <span style="margin-left: auto; background: rgba(255,255,255,0.2); padding: 2px 8px; border-radius: 10px; font-size: 12px;">18</span>
        </a>
        <a href="{{ route('umsatz') }}" class="nav-link {{ request()->routeIs('umsatz') ? 'active' : '' }}">
            <i class="fa fa-euro-sign"></i> Umsatz heute <span style="margin-left: auto; background: rgba(255,255,255,0.2); padding: 2px 8px; border-radius: 10px; font-size: 12px;">€12.450</span>
        </a>
        <div style="border-top: 1px solid rgba(255,255,255,0.1); margin: 15px 0;"></div>
        <a href="{{ route('tagesuebersicht') }}" class="nav-link {{ request()->routeIs('tagesuebersicht') ? 'active' : '' }}">
            <i class="fa fa-calendar-alt"></i> Tagesübersicht
        </a>
        <a href="/reservations" class="nav-link">
            <i class="fa fa-calendar-alt"></i> Reservierungen
        </a>
        <div class="nav-dropdown">
            <div class="nav-dropdown-header" onclick="toggleDropdown(this)">
                <i class="fa fa-database"></i> Stammdaten <i class="fa fa-chevron-down" style="margin-left: auto; font-size: 12px;"></i>
            </div>
            <div class="nav-dropdown-content">
                <a href="/rooms" class="nav-link">
                    <i class="fa fa-bed"></i> Zimmer
                </a>
                <a href="/guests" class="nav-link">
                    <i class="fa fa-users"></i> Gäste
                </a>
                <a href="/articles" class="nav-link">
                    <i class="fa fa-box"></i> Artikel
                </a>
                <a href="/prices" class="nav-link">
                    <i class="fa fa-tag"></i> Preise
                </a>
                <a href="/room-types" class="nav-link">
                    <i class="fa fa-bed"></i> Zimmerarten
                </a>
                <a href="/users" class="nav-link">
                    <i class="fa fa-user-cog"></i> Benutzer
                </a>
            </div>
        </div>
        <a href="/invoices" class="nav-link">
            <i class="fa fa-file-invoice"></i> Rechnungen
        </a>
        <a href="/reports" class="nav-link">
            <i class="fa fa-chart-bar"></i> Berichte
        </a>
    </nav>
    
    <div style="border-top: 1px solid rgba(255,255,255,0.1); margin: auto 10px 10px 10px;"></div>
    
    <form action="/logout" method="POST" style="padding: 0 10px 20px 10px;">
        @csrf
        <button type="submit" style="width: 100%; display: flex; align-items: center; padding: 12px 15px; color: rgba(255,100,100,0.8); text-decoration: none; border-radius: 8px; transition: all 0.3s; background: none; border: none; cursor: pointer; font-size: 14px;">
            <i class="fa fa-sign-out-alt" style="width: 25px; font-size: 18px;"></i> Abmelden
        </button>
    </form>
</aside>

<style>
.nav-dropdown { margin-bottom: 5px; }
.nav-dropdown-header {
    display: flex;
    align-items: center;
    padding: 12px 15px;
    color: rgba(255,255,255,0.8);
    cursor: pointer;
    border-radius: 8px;
    transition: all 0.3s;
}
.nav-dropdown-header:hover {
    background: rgba(255,255,255,0.1);
    color: white;
}
.nav-dropdown-header i:first-child {
    width: 25px;
    font-size: 18px;
}
.nav-dropdown-content {
    display: none;
    padding-left: 15px;
}
.nav-dropdown.open .nav-dropdown-content {
    display: block;
}
.nav-dropdown.open > .nav-dropdown-header {
    background: rgba(255,255,255,0.1);
    color: white;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Check current URL and open corresponding dropdown
    const currentPath = window.location.pathname;
    const dropdowns = document.querySelectorAll('.nav-dropdown');
    
    dropdowns.forEach(dropdown => {
        const links = dropdown.querySelectorAll('.nav-dropdown-content a');
        links.forEach(link => {
            if (link.getAttribute('href') === currentPath) {
                dropdown.classList.add('open');
            }
        });
    });
});

function toggleDropdown(element) {
    element.parentElement.classList.toggle('open');
}
</script>
