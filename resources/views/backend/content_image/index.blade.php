@extends('backend._layouts.default')

@section('main')

<div class="row">
    <div class="col-sm-3 col-md-2 sidebar">
        @include('backend._partials.content-tabs', array('contentImages' => true))
    </div>
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        <ol class="breadcrumb">
            <li><a href="/admin/dashboard">Dashboard</a></li>
            <li><a href="{!! URL::route('content.index') !!}">Content</a></li>  
            <li><a href="{!! URL::route('content.edit', $content->id) !!}">edit</a></li>
            <li class="active"><a href="{!! URL::route('content.edit', $content->id) !!}">{!! $content->title !!}</a></li>
            <li class="active">images</li>           
        </ol>

        <a href="{{ URL::route('content.images.create', $content->id) }}" class="btn btn-success pull-right">upload image<i class="entypo-plus"></i></a>

        <h2>Content <small>images</small></h2>
        <hr/>
        @include('flash::message')
			
        <table id="datatable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th class="col-md-3">{{{ trans('table.id') }}}</th>
                    <th class="col-md-3">{{{ trans('table.file') }}}</th>
                    <th class="col-md-3">{{{ trans('table.actions') }}}</th>
                </tr>
            </thead>
        </table>

        <script type="text/javascript">
            $(document).ready(function() {

                oTable = $('#datatable').DataTable({
                    "processing": true,
                    "serverSide": true,
                   "ajax": "{{ URL::route('content.images.index', $content->id) }}",

                 columns: [
              {data: 'thumb', name: 'thumb', orderable: false, searchable: false},
                        {data: 'file', name: 'file'},
                        {data: 'action', name: 'action', orderable: false, searchable: false}
                    ]

                });
            });
        </script>
    </div>
</div>
@stop