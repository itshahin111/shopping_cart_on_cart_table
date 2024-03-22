<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6 center-screen">
            <div class="card animated fadeIn w-90  p-4">
                <div class="card-body">
                    <h4>EMAIL ADDRESS</h4>
                    <br/>
                    <label>Your email address</label>
                    <!-- Input field for the user's email -->
                    <input id="email" placeholder="User Email" class="form-control" type="email"/>
                    <br/>
                    <!-- Button to trigger email verification -->
                    <button onclick="VerifyEmail()"  class="btn w-100 float-end bg-gradient-primary">Next</button>
                </div>
            </div>
        </div>
    </div>
</div>


<script>

    async function SubmitLogin() {
              // Get values from email and password fields
              let email=document.getElementById('email').value;
              let password=document.getElementById('password').value;
  
              // Basic form validation
              if(email.length===0){
                  errorToast("Email is required");
              }
              else if(password.length===0){
                  errorToast("Password is required");
              }
              else{
                  // Show loader while processing the request
                  showLoader();
                  // Send a POST request to the server for user login
                  let res=await axios.post("/user-login",{email:email, password:password});
                  // Hide loader after request completes
                  hideLoader()
                  // Check the response from the server
                  if(res.status===200 && res.data['status']==='success'){
                      // If login is successful, redirect the user to the dashboard
                      window.location.href="/dashboard";
                  }
                  else{
                      // If login fails, display an error message
                      errorToast(res.data['message']);
                  }
              }
      }
  
  </script>
  