<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6 center-screen">
            <div class="card animated fadeIn w-90  p-4">
                <div class="card-body">
                    <!-- Title for the OTP verification section -->
                    <h4>ENTER OTP CODE</h4>
                    <br/>
                    <!-- Label for the OTP input field -->
                    <label>6 Digit Code Here</label>
                    <!-- Input field for entering OTP -->
                    <input id="otp" placeholder="Code" class="form-control" type="text"/>
                    <br/>
                    <!-- Button to trigger OTP verification -->
                    <button onclick="VerifyOtp()"  class="btn w-100 float-end bg-gradient-primary">Next</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    async function VerifyOtp() {
         // Retrieve the entered OTP code from the input field
         let otp = document.getElementById('otp').value;
         // Check if the OTP code length is exactly 6 digits
         if(otp.length !==6){
            // Show an error message if the OTP code is invalid
            errorToast('Invalid OTP')
         }
         else{
             // Show loader while processing the request
             showLoader();
             // Send a POST request to the server for OTP verification
             let res=await axios.post('/verify-otp', {
                 otp: otp,
                 email:sessionStorage.getItem('email')
             })
             // Hide loader after request completes
             hideLoader();
             // Handle the response from the server
             if(res.status===200 && res.data['status']==='success'){
                 // If OTP verification is successful, show a success message
                 successToast(res.data['message'])
                 // Clear the email from session storage
                 sessionStorage.clear();
                 // Redirect to the password reset page after a delay of 1 second
                 setTimeout(() => {
                     window.location.href='/resetPassword'
                 }, 1000);
             }
             else{
                 // If OTP verification fails, show an error message
                 errorToast(res.data['message'])
             }
         }
     }
 </script>
 