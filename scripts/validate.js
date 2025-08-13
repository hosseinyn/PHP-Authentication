// Form validation script
const usernameInput = document.getElementById("username");
const passwordInput = document.getElementById("password");
const errorText = document.getElementById("error");

// Validate username
const validateUsername = () => {
    usernameInput.value = usernameInput.value.replace(/[^a-zA-Z0-9]/g, '');
}

// Validate password
function validatePassword(password) {
    const passwordRegex = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[!@#$%^&*()_+\-=\[\]{}|;:'",.<>/?]).{8,20}$/;

    if (passwordRegex.test(password)) {
        return true;
    } else {
        return false;
    }
}

passwordInput.addEventListener("change" , (e) => {
    if (!validatePassword(passwordInput.value)) {
        errorText.innerText = "Password does not meet the requirements (e.g., missing special character, length issue).";
    } else {
        errorText.innerText = ""
    }
})
