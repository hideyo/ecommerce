@extends('frontend._layouts.default')
@section('meta_title', 'News')
@section('meta_description', '')
@section('meta_keywords', '')
@section('main') 

<div class="row">
    <div class="col-sm-12 col-md-12 col-lg-12">
        <ol class="breadcrumb">
            <li><a href="/">Home</a></li>
            <li><a href="/nieuws">News</a></li>
            <li><a href="/nieuws">Overview</a></li>
        </ol>
    </div>
</div>



<div class="news">
    <div class="row">

        <div class="col-lg-3">

            <div class="sidebar jumbotron">
                <h3>Groups</h3>
                @if($newsGroups)
                <ul>
                @foreach($newsGroups as $group)
                    <li><a href="{!! URL::route('news.group', array($group->slug)) !!}">{{ $group->title }}</a></li>
                @endforeach
                </ul>
                @endif

            </div>
       
     
        </div>    

        <div class="col-lg-offset-1 col-lg-8">

            @foreach ($news['result']->chunk(3) as $newschunk)
            <div class="row">
                @foreach ($newschunk as $news)
                <div class="small-15 medium-5 large-15 columns">

                    <div class="product-block news-block">
                        <div class="row">
                            <a href="{!! URL::route('news.item', array($news->newsGroup->slug, $news->slug)) !!}" title="ga naar {{ $news['title'] }}">

                                <div class="small-4 medium-2 large-3 columns">

                                    <div class="image"> 
                                        @if($news->newsImages->count())
                                        <img src="/files/news/200x200/{!! $news->newsImages->first()->news_id !!}/{!! $news->newsImages->first()->file !!}" class="" alt="" />          
                                        <div class="overlay"></div>
                                        @endif
                                    </div>

                                </div>


                                <div class="small-11 medium-9 large-12 columns">
                                    <div class="text"> 
                                        <h3>{{ $news['title'] }}</h3>
                                        <p>{{ $news->short_description  }}</p>
                                        <a href="{!! URL::route('news.item', array($news->newsGroup->slug, $news->slug)) !!}" class="btn btn-success text-right">Read more</a>        
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div> 

                </div>
                @endforeach
            </div>
            @endforeach

            <div class="row news-filters">

                <div class="small-15 text-right columns">
                    @if($selectedPage != 1)
                    <a href="?page={!! $selectedPage - 1 !!}"><</a>
                    @endif
                    @if($news['totalPages'] != 1)
                    @for($i = 0; $i < $news['totalPages']; $i++)

                    @if($selectedPage == ($i + 1)) 
                    <a href="?page={!! $i + 1 !!}"><strong>{!! $i + 1 !!}</strong></a>
                    @else
                    <a href="?page={!! $i + 1 !!}">{!! $i + 1 !!}</a>
                    @endif
                    @endfor


                    @endif

                    @if($selectedPage != $news['totalPages'])
                    <a href="?page={!! $selectedPage + 1 !!}">></a>
                    @endif 
                </div>
            </div>

        </div>
    </div>
</div>
@stop
