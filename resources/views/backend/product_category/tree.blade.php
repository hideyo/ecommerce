@extends('backend._layouts.default')

@section('main')

<div class="row rowTopPadding">
    <div class="col-sm-3 col-md-2 sidebar">
        <ul class="nav nav-sidebar">
            <li><a href="{{ URL::route('product-category.index') }}">Overview <span class="sr-only">(current)</span></a></li>
            <li class="active"><a href="{{ URL::route('product-category.tree') }}">Tree</a></li>
        </ul>
    </div>
    <div class="col-sm-9 col-md-10 main">
        <ol class="breadcrumb">
            <li><a href="{{ URL::route('dashboard.index') }}">Dashboard</a></li>
            <li><a href="{{ URL::route('product-category.index') }}">Product categories</a></li>  
            <li class="active">tree structure</li>
        </ol>

        <h2>Product categories <small>tree</small></h2>
        <hr/>
        {!! Notification::showAll() !!}
        
      <div class="panel panel-primary" data-collapsed="0">
            <div class="panel-body">
                <div id="container">  
               
                </div>
            </div>
        </div>

        <script>
        $(function() {
            $('#container').jstree({
                "core" : {
                    "check_callback" : true,
                    "themes" : { "stripes" : true },
                    'data' : {
                      'url' : function (node) {
                        return node.id === '#' ?
                          '{{ URL::route('product-category.ajax-root-tree') }}' : '{{ URL::route('product-category.ajax-children-tree') }}'
                      },
                      'data' : function (node) {
                        return { 'id' : node.id };
                      }
                    }
                },
                "plugins" : [
                    "contextmenu", "dnd", "search",
                    "state", "types", "wholerow",
                ]
            }).on('move_node.jstree', function (e, data) {
                console.log(data);
                $.get('{{ URL::route('product-category.ajax-move-node') }}', { 'id' : data.node.id, 'parent' : data.parent, 'position' : data.position })
                .fail(function () {
                    data.instance.refresh();
                });
            })
        });
        </script>
    </div>
</div>   
@stop
