<nav class="navbar navbar-expand-lg navbar-light bg-light mb-4 border-bottom">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    @auth('admin_web')
<span class="navbar-text">
    مرحباً، {{ Auth::guard('admin_web')->user()->name_ar ?: Auth::guard('admin_web')->user()->username }}
</span>
@endauth
                </li>
            </ul>
        </div>
    </div>
</nav>