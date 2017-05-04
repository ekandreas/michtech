<section class="hero is-black is-large header-image" style="background-image: url('./../images/leo.jpg')">
    <div class="hero-head">
        <header class="nav">
            <div class="container">
                <div class="nav-left">
                    <a class="nav-item" href="/">
                        Michtech
                    </a>
                </div>
                <span class="nav-toggle">
            <span></span>
            <span></span>
            <span></span>
          </span>
                <div class="nav-right nav-menu">
                    @if(\Illuminate\Support\Facades\Auth::user())
                        <span class="nav-item">
                            {{ \Illuminate\Support\Facades\Auth::user()->name }}&nbsp;
                        </span>
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
    <div class="hero-body">
        <div class="container has-text-centered">
            <h2 class="title is-2">
                Michtech
            </h2>
            <h2 class="subtitle is-5">
                Prototyp mappverktyg
            </h2>
        </div>
    </div>
</section>
