function validateForm() {
    // Grab inputs by ID
    const name = document.getElementById('name');
    const email = document.getElementById('email');
    const password = document.getElementById('password');
    const confirm_password = document.getElementById('confirm_password');
    const age = document.getElementById('age');
    const gender = document.getElementById('gender');
    const phone = document.getElementById('phone');
    const location = document.getElementById('location');
    const dob = document.getElementById('dob');
    
    let isValid = true;

    // Check if name is valid
    if (name.value.trim() === '') {
        showError(name, 'Name is required');
        isValid = false;
    } else {
        showSuccess(name);
    }

    // Check if email is valid
    if (!isValidEmail(email.value)) {
        showError(email, 'Email is not valid');
        isValid = false;
    } else {
        showSuccess(email);
    }

    // Check if password is valid
    if (!isValidPassword(password.value)) {
        showError(password, 'Password must be at least 6 characters, contain at least one uppercase letter, and one special character');
        isValid = false;
    } else {
        showSuccess(password);
    }

    // Check if confirm password matches
    if (confirm_password.value.trim() === '' || password.value !== confirm_password.value) {
        showError(confirm_password, 'Passwords do not match');
        isValid = false;
    } else {
        showSuccess(confirm_password);
    }

    // Check if age is valid
    const dobDate = new Date(dob.value);
    const today = new Date();
    const calculatedAge = calculateAge(dobDate);

    if (dob.value.trim() === '') {
        showError(dob, 'Date of Birth is required');
        isValid = false;
    } else if (dobDate >= today) {
        showError(dob, 'Date of Birth cannot be in the future');
        isValid = false;
    } else if (calculatedAge !== parseInt(age.value)) {
        showError(age, 'Age does not match Date of Birth');
        isValid = false;
    } else if (age.value < 18) {
        showError(age, 'You must be at least 18 years old');
        isValid = false;
    } else {
        showSuccess(age);
        showSuccess(dob);
    }

    // Check if gender is valid
    if (gender.value.trim() === '') {
        showError(gender, 'Gender is required');
        isValid = false;
    } else {
        showSuccess(gender);
    }

    // Check if phone is valid (should be exactly 10 digits)
    if (!/^\d{10}$/.test(phone.value)) {
        showError(phone, 'Phone number must be exactly 10 digits');
        isValid = false;
    } else {
        showSuccess(phone);
    }

    // Check if location is valid
    if (location.value.trim() === '') {
        showError(location, 'Location is required');
        isValid = false;
    } else {
        showSuccess(location);
    }

    return isValid;
}

// Helper function to check email validity
function isValidEmail(email) {
    const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@(([^<>()[\]\.,;:\s@"]+\.[^<>()[\]\.,;:\s@"]{2,}))$/;
    return re.test(String(email).toLowerCase());
}

// Helper function to check password validity
function isValidPassword(password) {
    // At least one uppercase letter, one special character, and at least 6 characters
    const re = /^(?=.*[A-Z])(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{6,}$/;
    return re.test(password);
}

// Helper function to calculate age from DOB
function calculateAge(dob) {
    const today = new Date();
    let age = today.getFullYear() - dob.getFullYear();
    const monthDifference = today.getMonth() - dob.getMonth();
    if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < dob.getDate())) {
        age--;
    }
    return age;
}

// Show error
function showError(input, message) {
    const inputContainer = input.parentElement;
    inputContainer.className = 'input-container error';
    const small = inputContainer.querySelector('small');
    small.innerText = message;
}

// Show success
function showSuccess(input) {
    const inputContainer = input.parentElement;
    inputContainer.className = 'input-container';
}
