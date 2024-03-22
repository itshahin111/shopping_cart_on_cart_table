<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7 animated fadeIn col-lg-6 center-screen">
            <div class="card w-90  p-4">
                <div class="card-body">
                    <h4>SIGN IN</h4>
                    <br/>
                    <!-- Input field for email -->
                    <input id="email" placeholder="User Email" class="form-control" type="email"/>
                    <br/>
                    <!-- Input field for password -->
                    <input id="password" placeholder="User Password" class="form-control" type="password"/>
                    <br/>
                    <!-- Button to trigger login -->
                    <button onclick="SubmitLogin()" class="btn w-100 bg-gradient-primary">Next</button>
                    <hr/>
                    <!-- Links for sign up and password recovery -->
                    <div class="float-end mt-3">
                        <span>
                            <a class="text-center ms-3 h6" href="{{url('/userRegistration')}}">Sign Up </a>
                            <span class="ms-1">|</span>
                            <a class="text-center ms-3 h6" href="{{url('/sendOtp')}}">Forget Password</a>
                        </span>
                    </div>
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
  