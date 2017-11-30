@extends('layouts.master')

@section('content')
    <div class="container">
        <h1 class="title">DIREKTUPPLADDNING</h1>

        <p>Vi testar direktuppladdning till S3.</p>

        <br/>
        <br/>

        <span class="button is-primary" id="add">Välj dokument</span>
        <a class="button is-info" id="start">Starta uppladdning</a>
        <a class="button is-warning" id="pause">Pausa uppladdning</a>

        <!-- This area will be filled with our results (for debugging) -->
        <br/>
        <br/>

        <progress id="progress" class="progress is-large" value="0" max="100"></progress>

    </div>
@endsection

@section('scripts')

    <script
            src="https://code.jquery.com/jquery-3.2.1.min.js"
            integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
            crossorigin="anonymous"></script>

    <script src="https://unpkg.com/resumablejs/resumable.js"></script>

    <script async type="text/javascript" src="{{ mix('dist/app.js') }}"></script>

    <script>

        var r = new Resumable({
            target: '/direkt/upload',
            testChunks: true,
            uploadMethod: 'POST'
        });

        r.assignBrowse(document.getElementById('add'));

        $('#start').click(function () {
            r.upload();
        });

        $('#pause').click(function () {
            if (r.files.length > 0) {
                if (r.isUploading()) {
                    return r.pause();
                }
                return r.upload();
            }
        });

        var progressBar = new ProgressBar($('#progress'));

        r.on('fileAdded', function (file, event) {
            progressBar.fileAdded();
        });

        r.on('fileSuccess', function (file, message) {
            progressBar.finish();
        });

        r.on('progress', function () {
            progressBar.uploading(r.progress() * 100);
            $('#pause').find('.glyphicon').removeClass('glyphicon-play').addClass('glyphicon-pause');
        });

        r.on('pause', function () {
            $('#pause').find('.glyphicon').removeClass('glyphicon-pause').addClass('glyphicon-play');
        });

        function ProgressBar(ele) {
            this.thisEle = $(ele);

            this.fileAdded = function () {
                (this.thisEle).removeClass('hide').find('.progress-bar').css('width', '0%');
            },

                this.uploading = function (progress) {
                    (this.thisEle).find('.progress-bar').attr('style', "width:" + progress + '%');
                },

                this.finish = function () {
                    (this.thisEle).addClass('hide').find('.progress-bar').css('width', '0%');
                }
        }


    </script>

@endsection