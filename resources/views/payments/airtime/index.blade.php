@extends('layouts.master')
@section('content')
    <div>
    @include('payments.airtime.partials.wizard.operators')

    @include('payments.airtime.partials.modals.buy_airtime_modal')
    @include('payments.airtime.partials.modals.buy_operator_airtime_modal')

    </div>
<!-- <script src="{{asset('js/airtime.js')}}"></script> -->
<script>
    $('.buy-airtime').on('click',function(e){
        let rate = $(this).data('rate');
        $.ajax({
            type:"GET",
            url: "{{route('airtime.iso.operators',['iso'=>'UG'])}}"
        });
    });

    $('.btn-airtime').on('click',function(e){
        console.log("button clicked");
        let operator = $(this).data('operator');
        console.log('operator',operator);
        $('#operator_id').val(operator)
        // $.ajax({
        //     type:"GET",
        //     url: "{{route('airtime.iso.operators',['iso'=>'UG'])}}"
        // });
    });

    $('#select_rate_id').on('change',function(e){
        console.log('inside select rate')
        console.log('selected',$(this).children("option:selected").val())
        if( $(this).children("option:selected").val() != 'select'){
            $('.div-amount').prop('hidden',false);
        }else{
            $('.div-amount').prop('hidden',true);
        }
       
        // $.ajax({
        //     type:"GET",
        //     url: "{{route('airtime.iso.operators',['iso'=>'UG'])}}"
        // });
    });

    $('#amount_op').on('focusout',function(e){
        console.log('inside amount focus out');
        let lower =  $('#select_rate_id').children("option:selected").data('lower'); 
        let upper =  $('#select_rate_id').children("option:selected").data('upper'); 
        console.log('lower',lower);
        console.log('upper',upper);
        if($('#amount_op').val() < lower || $('#amount_op').val() > upper){
            $('#amount_op').addClass('is-invalid');
        }
    });
    $('#amount_op').on('click change',function(e){
            console.log('amount_op clicked or changed');
            $('#amount_op').removeClass('is-invalid');
    });
</script>
@endsection