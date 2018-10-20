<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-sm-3">
                {!! HtmlBlockService::findByPosition("footer-about-us") !!}
            </div>

            <div class="col-sm-3">
                <h5>News</h5>
                @if($footerNews)
                <ul>
                    @foreach($footerNews as $news)
                    <li><a href="{!! URL::route('newsfrontend.item', array($news->newsGroup->slug, $news->slug)) !!}" title="{!! $news->title !!}">{!! str_limit($news->title, 24) !!}</a></li>
                    @endforeach
                </ul>
                @endif
            </div>

             <div class="col-sm-3">
                            <h5>Links</h5>
                            @if($footerNews)
                            <ul>
                                <li>Terms and conditions</li>
                                <li>Privacy policy</li>
                            </ul>
                            @endif
                        </div>

            <div class="col-sm-3">
                {!! HtmlBlockService::findByPosition("footer-contact") !!}  
            </div>
        </div>
    </div>
</footer>