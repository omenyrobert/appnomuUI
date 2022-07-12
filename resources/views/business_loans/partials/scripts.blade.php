<script>
    $('.btn-pay').on('click',function(e){
        console.log('payment clicked');
        let id = $(this).data('id');
        let amount =  $(this).data('amount');

        $('#repay_id').val(id);
        $('#repay_amount').val(amount);
        $('#repay_amount').text(amount);
    });
</script>