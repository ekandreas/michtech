@foreach(\App\Text::all()->sortBy('prio') as $text)

    <div class="card">
        <div class="card-content">
            <div class="content">
                <h2 class="is-title">{{ $text->headline }}</h2>
                {!! preg_replace('#<p(.*?)>(.*?)</p>#is', '$2<br/>', $text->body ) !!}
            </div>
        </div>
    </div>

    <br/><br/>

@endforeach
