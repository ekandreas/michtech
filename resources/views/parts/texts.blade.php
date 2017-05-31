@foreach(\App\Text::all()->sortBy('prio') as $text)

    <div class="card">
        <header class="card-header" style="background-color: #333; color:#fff;">
            <p class="card-header-title" style="color:#fff;">
                {{ $text->headline }}
            </p>
        </header>
        <div class="card-content">
            <div class="content">
                {!! preg_replace('#<p(.*?)>(.*?)</p>#is', '$2<br/>', $text->body ) !!}
            </div>
        </div>
    </div>

    <br/><br/>

@endforeach
