<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6 center-screen">
            <div class="card animated fadeIn w-90 p-4">
                <div class="card-body">
                    <!-- Title for the password reset section -->
                    <h4>SET NEW PASSWORD</h4>
                    <br/>
                    <!-- Label for the new password input field -->
                    <label>New Password</label>
                    <!-- Input field for entering the new password -->
                    <input id="password" placeholder="New Password" class="form-control" type="password"/>
                    <br/>
                    <!-- Label for confirming the new password -->
                    <label>Confirm Password</label>
                    <!-- Input field for confirming the new password -->
                    <input id="cpassword" placeholder="Confirm Password" class="form-control" type="password"/>
                    <br/>
                    <!-- Button to trigger password reset -->
                    <button onclick="ResetPass()" class="btn w-100 bg-gradient-primary">Next</button>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    async function ResetPass() {
          // Retrieve the values entered in the password and confirm password fields
          let password = document.getElementById('password').value;
          let cpassword = document.getElementById('cpassword').value;
  
          // Basic validation: checking if the password field is empty
          if(password.length===0){
              errorToast('Password is required')
          }
          // Checking if the confirm password field is empty
          else if(cpassword.length===0){
              errorToast('Confirm Password is required')
          }
          // Checking if the password and confirm password match
          else if(password!==cpassword){
              errorToast('Password and Confirm Password must be same')
          }
          else{
            // Show loader while processing the request
            showLoader()
            // Send a POST request to the server for password reset
            let res=await axios.post("/reset-password",{password:password});
            // Hide loader after request completes
            hideLoader();
            // Handle the response from the server
            if(res.status===200 && res.data['status']==='success'){
                // If password reset is successful, display a success message
                successToast(res.data['message']);
                // Redirect to the login page after a short delay
                setTimeout(function () {
                    window.location.href="/userLogin";
                },1000);
            }
            else{
              // If password reset fails, display an error message
              errorToast(res.data['message'])
            }
          }
  
      }
  </script>
  