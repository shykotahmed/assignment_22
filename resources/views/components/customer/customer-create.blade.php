<div class="modal" id="create-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create Category</h5>
                </div>
                <div class="modal-body">
                    <form id="save-form">
                        <div class="container">
                            <div class="row">
                                <div class="col-12 p-1">
                                    <label class="form-label">Customer Name *</label>
                                    <input type="text" class="form-control" id="name">
                                </div>
                                <div class="col-12 p-1">
                                    <label class="form-label">Customer Email *</label>
                                    <input type="text" class="form-control" id="email">
                                </div>
                                <div class="col-12 p-1">
                                    <label class="form-label">Customer Address *</label>
                                    <input type="text" class="form-control" id="address">
                                </div>
                                <div class="col-12 p-1">
                                    <label class="form-label">Customer Phone *</label>
                                    <input type="text" class="form-control" id="phone">
                                </div>
                                
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button id="modal-close" class="btn btn-sm btn-danger" data-bs-dismiss="modal" aria-label="Close">Close</button>
                    <button onclick="Save()" class="btn btn-sm  btn-success" >Save</button>
                </div>
            </div>
    </div>
</div>

<script>
    async function Save(){
        let name=document.getElementById('name').value;
        let email=document.getElementById('email').value;
        let address=document.getElementById('address').value;
        let phone=document.getElementById('phone').value;
        
        if(name.length===0){
            errorToast("Customer name is Required!")
        }else if(email.length===0){
            errorToast("Customer email is Required!")
        }else if(address.length===0){
            errorToast("Customer address is Required!")
        }else if(phone.length===0){
            errorToast("Customer phone is Required!")
        }else{
            document.getElementById('modal-close').click();

            showLoader();
            let url = "{{url('/create-customer')}}";
            let createData = {
                'name':name,
                'email':email,
                'address':address,
                'phone':phone
            }
            const res = await axios.post(url,createData);
            hideLoader();

            if(res.status===201){
                successToast('Category created successful');
                document.getElementById('save-form').reset();
                await getList();
            }else{
                errorToast("Request fail!");
            }
        }
    }
</script>