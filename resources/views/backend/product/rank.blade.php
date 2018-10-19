@extends('backend._layouts.default')

@section('main')

<div class="row rowTopPadding">
    <div class="col-sm-3 col-md-2 sidebar">
        <ul class="nav nav-sidebar">
            <li><a href="{{ URL::route('product.index') }}">Overview <span class="sr-only">(current)</span></a></li>
            <li><a href="{{ URL::route('product.create') }}">Create</a></li>
            <li><a href="{{ URL::route('product.export') }}">Export</a></li>
            <li class="active"><a href="{{ URL::route('product.rank') }}">Ranking</a></li>
        </ul>
    </div>
    <div class="col-sm-9 col-md-10 main">
        <ol class="breadcrumb">
            <li><a href="{{ URL::route('dashboard.index') }}">Dashboard</a></li>
            <li><a href="{{ URL::route('product.index') }}">Products</a></li>  
            <li class="active"><a href="{{ URL::route('product.rank') }}">Ranking</a></li>
        </ol>

        <a href="{{ URL::route('product.create') }}" class="btn btn-success pull-right" aria-label="Left Align"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Create</a>

        <h2>Products <small>Rank</small></h2>
        <hr/>
        {!! Notification::showAll() !!}

        <table id="datatable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th class="col-md-1">{{{ trans('table.rank') }}}</th>
                    <th class="col-md-2">{{{ trans('table.category') }}}</th>
                    <th class="col-md-2">{{{ trans('table.title') }}}</th>
                </tr>
            </thead>
        </table>

        <script type="text/javascript">
        $(document).ready(function() {

            oTable = $('#datatable').DataTable({
                "processing": true,
                "serverSide": true,
                "stateSave": true,
                "ajax": "{{ URL::route('product.rank') }}",
                columns: [
                {data: 'rank', name: 'rank', bVisible: true, bSearchable: false},
            	{data: 'categorytitle', name: 'categorytitle', bVisible: true, bSearchable: true},                        
                {data: 'title', name: 'title', bVisible: true, bSearchable: false},
                ]

            });
        });
        </script>
        
    </div>
</div>   
@stop