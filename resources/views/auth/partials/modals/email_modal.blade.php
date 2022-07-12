<div class="modal fade" id="email_modal" tabindex="-1"aria-labelledby="exampleModalLabel" aria-modal="true" role="dialog" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" >
            <h5 class="modal-title">Email Verification</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    
                </button>
               
            </div>


            <div class="modal-body ">
                @include('auth.partials.forms.emailtoken')
            </div>
        <!-- /.modal-content -->
        </div>
    <!-- /.modal-dialog -->
    </div>
</div>