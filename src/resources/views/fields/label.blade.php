
<!--
Everything between the < script > tags will be replaced with the datatables render method like below
The script methods will be replaced with

"render": function ( data, type, row ) {
    //the code between the script tags
}

you can use the variables data, type and row

-->


<script>
    return `{!! $class->before !!} <label class="{{ $class->class }}">${data}</label> {!! $class->after !!}`;
</script>