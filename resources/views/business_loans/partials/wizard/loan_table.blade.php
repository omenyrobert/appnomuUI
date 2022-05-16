<div class="row">
    <div class="col-md-6 col-sm-12 card" style="background-color: white;">
        <h5>Loan Details</h5>
        <div>
            <div style="border-bottom: thin solid black; padding:20px; ">
                <span>Loan Type:</span>
                <span style="margin-left:25px ; text-align:end;">Business Loan </span>
            </div>
            <div style="border-bottom: thin solid black; padding:20px; ">
                <span>Loan Amount:</span>
                <span>{{$loan->principal}} UGX</span>
            </div>
            <div style="border-bottom: thin solid black; padding:20px; ">
                <span>Loan Period:</span>
                <span>{{$loan->payment_period}} days</span>
            </div>
            <div style="border-bottom: thin solid black; padding:20px; ">
                <span>Interest Rate:</span>
                <span>{{$loan->interest_rate}} %</span>
            </div>
            <div style="border-bottom: thin solid black; padding:20px; ">
                <span>Loan Installments</span>
                <span>{{$loan->installments}} </span>
            </div>
            <div style="border-bottom: thin solid black; padding:20px; ">
                <span>Application Date</span>
                <span>{{$loan->created_at}} </span>
            </div>
            <div style="border-bottom: thin solid black; padding:20px; ">
                <span>Loan Status:</span>
                <span>{{$loan->status}} </span>
            </div>
            @if($loan->status != 'Requested' && $loan->status != 'Cancelled')
           
                <div style="border-bottom: thin solid black; padding:20px; ">
                    <span>Approval date:</span>
                    <span>{{$loan->approved_at}} </span>
                </div>
                <div style="border-bottom: thin solid black; padding:20px; ">
                    <span>Processing Fee:</span>
                    <span>{{$loan->loanCategory->processing_fees}} </span>
                </div>
                <div style="border-bottom: thin solid black; padding:20px; ">
                    <span>Repayable Amount:</span>
                    <span>{{$loan->payment_amount}} </span>
                </div>
                <div style="border-bottom: thin solid black; padding:20px; ">
                    <span>Due Date:</span>
                    <span>{{$loan->due_date}} </span>
                </div>
            
            @endif
            
        </div>
    </div>

    <div class="col-md-6 col-sm-12 card" style="background-color: white;">
        <h5>Repayments</h5>
        <div>
            @if(count($loan->repayments) != 0)
            @foreach($repayments as $repayment)
                <div style=" padding:20px; " class=" row card">
                    <div class="col-sm-3" >
                        <span>
                            <h6>Amount</h6>
                        </span>
                        <span>
                            {{$repayment->amount}}
                        </span>
                    </div>
                    <div class="col-sm-3">
                        <div>
                            <h6>Due Date</h6>
                        </div>
                        <div>
                            {{$repayment->due_date}}
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div>
                            <h6>status</h6>
                        </div>
                        <div>
                            {{$repayment->status}}
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div>
                            <h6></h6>
                        </div>
                        <div>
                            @if($repayment->status != 'Paid' || $repayment->status != 'On Hold' )
                                <a href="" class="btn {{$repayment->status == 'Over Due' ? 'btn-danger' : 'btn-warning'}}">Pay</a>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
                <span><a href="">view all repayments</a></span>
            @else
            <h6>No Installments Available Yet For This Loan, Until Its Approved</h6>
            @endif
            
        </div>
    </div>
</div>