<footer class="footer">
    <div class="container">
        <div class="content has-text-centered">
            <p>
                Michtech mappverktyg utvecklat av <a href="https://www.elseif.se">Elseif AB</a>
            </p>
            <p>
                @if(\Illuminate\Support\Facades\Auth::user())
                    Inloggad som {{ \Illuminate\Support\Facades\Auth::user()->name }}.&nbsp;
                    <a href="{{ route('logout') }}">
                        Logga ut
                    </a>
                @else
                    <a href="/login"><i class="fa fa-sign-in"></i> Logga in</a> / <a href="/register"><i class="fa fa-user"></i> Registrera</a>
                @endif
            </p>
        </div>
    </div>
</footer>
