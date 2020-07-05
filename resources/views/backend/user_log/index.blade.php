@extends('_layouts.default')

@section('main')


        <h2>User Log <small>overview</small></h2>
        <br/>
        @include('flash::message')

        {{ Datatable::table()
        ->addColumn('id','name', 'message')       // these are the column headings to be shown
        ->setUrl(route('user_log.index'))   // this is the route where data will be retrieved
        ->setOrder(array(0 =>'desc'))
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
