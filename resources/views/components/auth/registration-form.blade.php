<div class="container">
    <!-- This div centers the content horizontally -->
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-10 center-screen">
            <!-- This div contains the registration card -->
            <div class="card animated fadeIn w-100 p-3">
                <div class="card-body">
                    <!-- Title of the card -->
                    <h4>Sign Up</h4>
                    <hr/>
                    <!-- Form container -->
                    <div class="container-fluid m-0 p-0">
                        <!-- First row of form inputs -->
                        <div class="row m-0 p-0">
                            <!-- Input field for email -->
                            <div class="col-md-4 p-2">
                                <label>Email Address</label>
                                <input id="email" placeholder="User Email" class="form-control" type="email"/>
                            </div>
                            <!-- Input field for first name -->
                            <div class="col-md-4 p-2">
                                <label>First Name</label>
                                <input id="firstName" placeholder="First Name" class="form-control" type="text"/>
                            </div>
                            <!-- Input field for last name -->
                            <div class="col-md-4 p-2">
                                <label>Last Name</label>
                                <input id="lastName" placeholder="Last Name" class="form-control" type="text"/>
                            </div>
                            <!-- Input field for mobile number -->
                            <div class="col-md-4 p-2">
                                <label>Mobile Number</label>
                                <input id="mobile" placeholder="Mobile" class="form-control" type="mobile"/>
                            </div>
                            <!-- Input field for password -->
                            <div class="col-md-4 p-2">
                                <label>Password</label>
                                <input id="password" placeholder="User Password" class="form-control" type="password"/>
                            </div>
                        </div>
                        <!-- Second row for the submit button -->
                        <div class="row m-0 p-0">
                            <div class="col-md-4 p-2">
                                <!-- Button to trigger the registration process -->
                                <button onclick="onRegistration()" class="btn mt-3 w-100  bg-gradient-primary">Complete</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>


async function onRegistration() {
    // Retrieving input values
    let email = document.getElementById('email').value;
    let firstName = document.getElementById('firstName').value;
    let lastName = document.getElementById('lastName').value;
    let mobile = document.getElementById('mobile').value;
    let password = document.getElementById('password').value;

    // Validation for empty fields
    if(email.length===0){
        errorToast('Email is required')
    }
    else if(firstName.length===0){
        errorToast('First Name is required')
    }
    else if(lastName.length===0){
        errorToast('Last Name is required')
    }
    else if(mobile.length===0){
        errorToast('Mobile is required')
    }
    else if(password.length===0){
        errorToast('Password is required')
    }
    else{
        // If all fields are filled, proceed with registration
        showLoader(); // Show loader while processing the request
        let res=await axios.post("/user-registration",{ // Making POST request to server
            email:email,
            firstName:firstName,
            lastName:lastName,
            mobile:mobile,
            password:password
        })
        hideLoader(); // Hide loader after request completes
        // Checking response status and displaying appropriate message
        if(res.status===200 && res.data['status']==='success'){
            successToast(res.data['message']); // Show success message
            // Redirecting user to login page after successful registration
            setTimeout(function (){
                window.location.href='/userLogin'
            },2000)
        }
        else{
            errorToast(res.data['message']); // Show error message
        }
    }
}

</script>
