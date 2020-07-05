@extends('_layouts.default')

@section('main')


        <a href="{{ URL::route('role.create') }}" class="btn btn-green btn-icon icon-left pull-right">Create role<i class="entypo-plus"></i></a>

        <h2>User <small>roles</small></h2>
        <br/>
        @include('flash::message')

        {{ Datatable::table()
        ->addColumn('id','name','actions')       // these are the column headings to be shown
        ->setUrl(route('role.index'))   // this is the route where data will be retrieved

        ->render() }}

        <script type="text/javascript">
            var responsiveHelper;
            var breakpointDefinition = {
                tablet: 1024,
                phone : 480
            };
            var tableContainer;

            jQuery(document).ready(function($) {

             $(".dataTables_wrapper select").select2({
                    minimumResultsForSearch: -1
                });

            $(document).on('click', '[data-toggle="delete"]', function(e){
                    e.preventDefault();
                    var title = ($(this).data('delete-title')?$(this).data('delete-title'):'This item');
                    var href = $(this).attr('href');

                    bootbox.dialog({

                      title: "Are you sure you want to remove the item.",
                      message: "Are you sure you want to remove: "+title,
                      buttons: {
                        cancel: {
                          label: "No!",
                          className: "btn-primary",
                          callback: function() {
                          }
                        },
                        delete: {
                          label: "Yes!",
                          className: "btn-danger pull-left",
                          callback: function() {
                            window.location = href;
                          }
                        }
                      }
                    });
                });

            });
        </script>

@stop
