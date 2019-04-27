<div id="app">
    <albums-masonry :data="{{ $albums->response()->getContent() }}"></albums-masonry>
</div>