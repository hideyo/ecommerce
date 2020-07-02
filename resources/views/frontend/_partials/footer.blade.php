<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 hidden-xs col-sm-4 col-md-4 col-lg-4"> 
                {!! HtmlBlockService::findByPosition("footer-about-us") !!}
            </div>

            <div class="col-xs-12 hidden-xs col-sm-3 col-md-3 col-lg-offset-1 col-lg-4">
                <h5>News</h5>
                @if($footerNews) 
                <ul>
                    @foreach($footerNews as $news)
                    <li><a href="{!! URL::route('newsfrontend.item', array($news->newsGroup->slug, $news->slug)) !!}" title="{!! $news->title !!}">{!! $news->title !!}</a></li>
                    @endforeach
                </ul>
                @endif
            </div>
            <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                {!! HtmlBlockService::findByPosition("footer-contact") !!}  
            </div>
        </div>
    </div>
</footer>