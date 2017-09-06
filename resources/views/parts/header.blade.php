<section class="hero header-image">
    <div class="hero-head">
        <header class="nav">
            <div class="container">
                <div class="nav-left">
                    <a class="nav-item" href="/">
                        Mappverktyg
                    </a>
                </div>
                <span class="nav-toggle">
            <span></span>
            <span></span>
            <span></span>
          </span>
                <div class="nav-right nav-menu">
                    @if(\Illuminate\Support\Facades\Auth::user())
                        <a class="nav-item">
                            {{ \Illuminate\Support\Facades\Auth::user()->name }}&nbsp;
                        </a>
                        <a href="{{ route('admin') }}" class="nav-item">
                            Admin
                        </a>
                        <a href="{{ route('logout') }}" class="nav-item">
                            Logga ut
                        </a>
                    @endif
                </div>
            </div>
        </header>
    </div>
    <div class="hero-body" style="background-color: #F9F6F2">
        <div class="container">
            <a href="/">
                <img src="{{ asset('images/michtech-logo-strike.png') }}" height="50px" alt="Michtech" />
            </a>
        </div>
    </div>
</section>
