<script type="text/javascript">

    /**
     * Get a parameter from the url
     * 
     * @param {type} param
     * @returns {unresolved}
     */
    function getParameterFromUrl(param)
    {
        return new URLSearchParams(window.location.search).get(param);
    }

    /**
     * Generate unique ID
     *
     * @param {String} prefix
     * @returns {String}
     */
    function uniqueId(prefix = "_")
    {
        return prefix + Math.random().toString(36).substr(2, 9);
    }

    let uri = location.href;
    let mark = uri.includes('?') ? '&' : '?';
    let filters = ``;
    let table{{ $view->tableId }};
    
    @foreach($view->filters as $filter)
    @if($loop->first)
//    $(`#{{ $view->tableId }}datatable-filters`).before(`<label>{{ __("datatables::datatables.filter") }}</label>`);
    @endif
    $(`#{{ $view->tableId }}datatable-filters`).append(`{!! $filter->build !!}`);
    
    $(document).on('{{ $filter->getTrigger() }}', `#{{ $filter->getID() }}`, () => {
        triggerFilters();
    });
    @endforeach
    
    /**
     * Trigger the filters
     * 
     */
    function triggerFilters()
    {
        filters = `&filter=`;
        $(`.datatable-filter`).each((index, e) => {
            if($(e).val() === 'lfalse'){
                return;
            }
            filters += `${$(e).attr('name')}*${$(e).val()}|`;
        });
        reloadTable();
    }
    
    /**
     * Reload the table
     * 
     */
    function reloadTable()
    {
        table{{ $view->tableId }}.ajax.url(`${uri}${mark}laravel-datatables=active&id={{ $view->id }}&${filters}`).load();
    }
    
    setTimeout(() => {
        table{{ $view->tableId }}.ajax.reload( null, false );
    },10000);
    
    let Json{{ $view->tableId }} = {
        "language" : @json(__("datatables::datatables")),
        "paging": true,
        "processing": true,
        "serverSide": true,
        "dom" : "{!! $view->dom !!}",
        "ajax": `${uri}${mark}laravel-datatables=active&id={{ $view->id }}${filters}`,
        "pageLength" : {{ $view->pageLength }},
        "order" : [
            @foreach($view->order as $order)
            [
                {{ $order[0] }},
                "{{ $order[1] }}"
            ],
            @endforeach
        ],
        "columns": [
            @foreach($view->columns as $column)
            @json($column),
            @endforeach
        ],
        "columnDefs": [
            @foreach($view->defs as $def)
            {
                "class" : "{{ isset($def["class"]) ? $def["class"] : '' }}",
                "render": function ( data, type, row ) {
                    let output = "";
                    @foreach($def['rendered'] as $index => $render)

                        @php
                            $class = $def["def"][$index];
                        @endphp

                        function {{$def['id']}}{{ $index }}(data, type, row) {
                            @if($class->overwrite)
                                @if(strlen($class->columnPath())> 0)
                                if(!row.{{ $class->columnPath() }}){
                                    return "{!! $class->before !!} {{ $class->returnWhenEmpty }} {!! $class->after !!}";
                                }
                                @endif
                                data = row.{{ $class->overwrite }};
                            @endif
                            //empty check
                            @if($class->emptyCheck)
                            
                            if(!data @if(strlen($class->columnPath())> 0) || !row.{{ $class->columnPath() }} @endif){
                                return "{!! $class->before !!} {{ $class->returnWhenEmpty }} {!! $class->after !!}";
                            }
                            @endif

                            @if($class->condition)
                                if(!row.{{ $class->condition }}){
                                    return "{!! $class->before !!} {{ $class->returnWhenEmpty }} {!! $class->after !!}";
                                }
                            @endif
                            

                            {!! $render !!}
                        };

                        output += {{$def['id']}}{{ $index }}(data, type, row);
                    @endforeach

                    return output;
                },
                "targets": {{ $def["target"] }}
            },
            @endforeach
        ]
    };
    @if($view->rememberPage)
        if(getParameterFromUrl('datatables-page')){
            Json{{ $view->tableId }}.displayStart = (getParameterFromUrl('datatables-page') -1) * Json{{ $view->tableId }}.pageLength;
        }
    @endif

    /**
     * Init the datatable
     *
     */
    function initDatatable{{ $view->tableId }}()
    {
        table{{ $view->tableId }} = $('#{{ $view->tableId }}').DataTable(Json{{ $view->tableId }});
        @if($view->rememberPage)
        $(document).on('click', '#{{ $view->tableId }}_wrapper .paginate_button', () => {
           window.history.pushState('laravel-datatable', 'pagination', `${location.origin}${location.pathname}?datatables-page=${table.page.info().page + 1}`);
        });
        @endif
    }

    /**
     * On document ready
     *
     */
    $(document).ready(() => {
        if(!$.fn.DataTable){
            $(`head`).append(`<link type="text/css" rel="stylesheet" href="https://cdn.datatables.net/v/bs4/dt-1.10.18/fh-3.1.4/datatables.min.css" />`);
            $.getScript(`https://cdn.datatables.net/v/bs4/dt-1.10.18/fh-3.1.4/datatables.min.js`, () => {
                initDatatable{{ $view->tableId }}();
            });
        }else{
            initDatatable{{ $view->tableId }}();
        }
    });

</script>