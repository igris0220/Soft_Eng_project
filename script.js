  // For Log in
  
function validateLoginForm() {
            var email = document.getElementById("email").value;
            var password = document.getElementById("password").value;

            if (email === "" || password === "") {
                alert("Both email and password are required.");
                return false;
            }

            if (!email.includes("@")) {
                alert("Please enter a valid email address.");
                return false;
            }

            // Simulating login by storing user's name (use database authentication in production)
            localStorage.setItem("userName", email.split("@")[0]); // Extracts username from email
            window.location.href = "welcome.html"; // Redirects to welcome page
            return false;
        }





// For Register form


function validateRegisterForm() {
            const name = document.getElementById("name").value.trim();
            const email = document.getElementById("email").value.trim();
            const password = document.getElementById("password").value;
            const confirmPassword = document.getElementById("confirm_password").value;

            if (name === "" || email === "" || password === "" || confirmPassword === "") {
                alert("Please fill in all fields.");
                return false;
            }

            if (password !== confirmPassword) {
                alert("Passwords do not match.");
                return false;
            }

            return true;
        }



// Welcome page 


   function displayUserInfo() {
            var userName = localStorage.getItem("userName");
            document.getElementById("welcome-text").innerText = "Welcome, " + userName + "!";
        }
