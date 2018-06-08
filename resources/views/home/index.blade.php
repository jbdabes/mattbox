@extends ('layouts.app')

@section ('content')

@if (Auth::user()->harambe_mode == 1)
    <div class="video-background">
        <div class="video-foreground">
            <iframe src="https://www.youtube.com/embed/jzS1AkFp_9E?controls=0&showinfo=0&rel=0&autoplay=1&loop=1&playlist=jzS1AkFp_9E" frameborder="0" allowfullscreen></iframe>
        </div>
    </div>
@endif

<div class="container">
    <div class="row main-row justify-content-center align-items-center">
        <div class="col">
            @include ('shoutbox.shoutbox')
        </div>
    </div>
</div>
@endsection

@section ('modals')
    @include ('shoutbox.modals.style-editor')
@endsection