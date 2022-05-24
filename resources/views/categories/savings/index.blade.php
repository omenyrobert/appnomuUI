@extends('layouts.master')
@section('content')
    @include('categories.savings.partials.tables.categories_table')
    @include('categories.savings.partials.modals.create_save_category_modal')
    @include('categories.savings.partials.modals.edit_modal')
    <script>
        $('.btn-edit').on('click',function(e){
            // let category = $('.btn-edit').data('category');
            // console.log('category',category);
            console.log('clicked');
        });
    </script>
@endsection