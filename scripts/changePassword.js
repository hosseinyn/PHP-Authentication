const currentPasswordInput = document.getElementById("current_password");
const newPasswordInput = document.getElementById("new_password");
const confirm_new_password = document.getElementById("confirm_new_password")
const errorText = document.getElementById("error");

function validatePassword(password) {
    const passwordRegex = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[!@#$%^&*()_+\-=\[\]{}|;:'",.<>/?]).{8,20}$/;

    if (passwordRegex.test(password)) {
        return true;
    } else {
        return false;
    }
}

currentPasswordInput.addEventListener("change" , (e) => {
    if (!validatePassword(currentPasswordInput.value)) {
        errorText.innerText = "Password does not meet the requirements (e.g., missing special character, length issue).";
    } else {
        errorText.innerText = ""
    }
})

newPasswordInput.addEventListener("change" , (e) => {
    if (!validatePassword(newPasswordInput.value)) {
        errorText.innerText = "Password does not meet the requirements (e.g., missing special character, length issue).";
    } else {
        errorText.innerText = ""
    }
})

confirm_new_password.addEventListener("change" , (e) => {
    if (!validatePassword(confirm_new_password.value)) {
        errorText.innerText = "Password does not meet the requirements (e.g., missing special character, length issue).";
    } else {
        errorText.innerText = ""
    }
})
