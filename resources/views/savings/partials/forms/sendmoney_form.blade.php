<!-- <div class="p-5 bg-white card col-sm-12 col-md-6 mx-auto"> -->
        <form action="{{route('make.withdraw')}}" method="POST"class="">
            @csrf
            <div class="row mt-5 card">
                <div class="col-sm-12">
                    <label>Enter Amount</label>
                    <input type="number" id="amount" name="amount" class="form-control mt-2" placeholder="Enter valid amount" required/>
                </div>
                <div class="col-sm-12">
                      <label>Select Currency </label>
                      <select class="form-control mt-2 single-select" id="currency" name="currency" required>
                      <option value="{{$user->country->currency_code}}" selected>{{$user->country->currency_code}}</option>
                            @foreach($countries as $currency)
                                <option value="{{$currency->currency_code}}">{{$currency->currency_code}}</option>
                            @endforeach
                      
                      </select>
                </div>
                <div class="col-sm-12">
                      <label>Select Withdraw Account</label>
                      <select class="form-control mt-2" id="source" name="source" required>
                          <option value="savings" selected>Savings Account</option>
                          <option value="loans">Loan Account</option>
                      </select>
                </div>
                <div class="col-sm-12">
                      <label>Select Destination Account Type</label>
                      <select class="form-control mt-2" id="type" name="type" required>
                      <option value="select">select destination account type</option>
                          <option value="account">Bank</option>
                          <option value="mobilemoney">Mobile Money</option>
                      </select>
                </div>
                <div class="col-sm-12">
                      <label>Select Destination Account</label>
                      <select class="form-control mt-2" id="destination" name="destination" required>
                          <option value="withdraw">Withdraw to my account</option>
                          <option value="transfer" selected>Withdraw to another account</option>
                      </select>
                </div>
                <div class="col-sm-12">
                      <label>Select Bank or mobile money operator </label>
                      <select class="form-control mt-2 single-select" id="account_bank" name="account_bank" required>
                      <option value="select">select Bank or mobileMoney operator</option>
                        @if(count($banks) > 0 )
                            @foreach($banks as $bank)
                                <option value="{{$bank['code']}}">{{$bank['name']}}</option>
                            @endforeach
                        @else
                            <option value=""></option>
                        @endif
                      </select>
                </div>
                <div class="col-sm-12">
                    <label id="lbl-account-number">Account number Or Phone Number</label>
                    <input type="text" id="account_number" name="account_number" class="form-control"  placeholder="enter  account number or phone number" required>
                </div>
                <div class="col-sm-12">
                    <label >Beneficiary</label>
                    <input type="text" id="beneficiary" name="beneficiary" class="form-control"  placeholder="enter  beneficiary's full names" required>
                </div>
                <div class="col-sm-12">
                    <br/>
                    <button type="submit" class="btn btn-primary form-control">Send Money</button>
                </div>
            </div>
        </form>
<!-- </div> -->
<script>
    function autofillValues(){
        if($('#type option:selected').val() == 'mobilemoney' && $('#destination option:selected').val() == 'withdraw'){
            $('#account_number').val('{{$user->telephone}}');
            $('#account_number').text('{{$user->telephone}}');
            $('#beneficiary').text('{{$user->name}}');
            $('#beneficiary').val('{{$user->name}}');

        }
    }
    $('#type').on('click change',function(e){
        autofillValues();
    });
    $('#destination').on('click change',function(e){
        autofillValues();
    });
</script>

<script>
        $('.single-select').select2({
            theme: 'bootstrap4',
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            allowClear: Boolean($(this).data('allow-clear')),
        });
        $('.multiple-select').select2({
            theme: 'bootstrap4',
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            allowClear: Boolean($(this).data('allow-clear')),
        });
    </script>