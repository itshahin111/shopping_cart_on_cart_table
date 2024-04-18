<!-- The HTML structure -->
<div class="container">
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card animated fadeIn w-100 p-3">
                <div class="card-body">
                    <h4>User Profile</h4>
                    <hr/>
                    <!-- Form to display and update user information -->
                    <div class="container-fluid m-0 p-0">
                        <div class="row m-0 p-0">
                            <!-- Input fields for email, first name, last name, mobile, and password -->
                            <div class="col-md-4 p-2">
                                <label>Email Address</label>
                                <input readonly id="email" placeholder="User Email" class="form-control" type="email"/>
                            </div>
                            <div class="col-md-4 p-2">
                                <label>First Name</label>
                                <input id="firstName" placeholder="First Name" class="form-control" type="text"/>
                            </div>
                            <div class="col-md-4 p-2">
                                <label>Last Name</label>
                                <input id="lastName" placeholder="Last Name" class="form-control" type="text"/>
                            </div>
                            <div class="col-md-4 p-2">
                                <label>Mobile Number</label>
                                <input id="mobile" placeholder="Mobile" class="form-control" type="mobile"/>
                            </div>
                            <div class="col-md-4 p-2">
                                <label>Password</label>
                                <input id="password" placeholder="User Password" class="form-control" type="password"/>
                            </div>
                        </div>
                        <!-- Button to trigger the onUpdate function -->
                        <div class="row m-0 p-0">
                            <div class="col-md-4 p-2">
                                <button onclick="onUpdate()" class="btn mt-3 w-100  bg-gradient-primary">Update</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript code -->
<script>
    async function SubmitLogin() {
        // Get values from email and password fields
        let email = document.getElementById('email').value;
        let password = document.getElementById('password').value;

        // Basic form validation
        if (email.length === 0) {
            errorToast("Email is required");
        } else if (password.length === 0) {
            errorToast("Password is required");
        } else {
            // Show loader while processing the request         showLoader();
            try {
                // Send a POST request to the server for user login
                let res = await axios.post("/user-login", {
                    email: email,
                    password: password
                });
                // Check the response from the server
                if (res.status === 200 && res.data['status'] === 'success') {
                    // If login is successful, redirect the user to the dashboard
                    window.location.href = "/dashboard";
                } else {
                    // If login fails, display an error message
                    errorToast(res.data['message']);
                }
            } catch (error) {
                // If an error occurs during the request, display a generic error message
                errorToast("Email or password is Wrong. Please try again later.");
            } finally {
                // Hide loader after request completes
                hideLoader();
            }
        }
    }
</script>
