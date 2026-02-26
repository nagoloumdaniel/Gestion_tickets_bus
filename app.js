<script>
const sign_in_btn = document.querySelector("#sign-in-btn");
const sign_up_btn = document.querySelector("#sign-up-btn");
const container = document.querySelector(".container");
const passwordInput = document.getElementById('passwordInput');
const passwordStrength = document.getElementById('passwordStrength');
const field2 = document.getElementById('field2');
const result = document.getElementById('result');
 


sign_up_btn.addEventListener('click', () => {
    container.classList.add("sign-up-mode");
});

sign_in_btn.addEventListener('click', () => {
    container.classList.remove("sign-up-mode");
});


passwordInput.addEventListener('input', function() {
    const password = passwordInput.value;
    const strength = getPasswordStrength(password);

    passwordStrength.textContent = getStrengthText(strength);
    passwordStrength.className = getStrengthClass(strength);
});

function getPasswordStrength(password) {

    if (password.length < 6) {
    return 0;
    } else if (password.length < 10) {
    return 1;
    } else {
    return 2;
    }
}

function getStrengthText(strength) {
    switch (strength) {
    case 0:
        return 'Mot de passe Faible';
    case 1:
        return 'Mot de passe Moyen';
    case 2:
        return 'Mot de passe Fort';
    default:
        return '';
    }
}

function getStrengthClass(strength) {
    switch (strength) {
    case 0:
        return 'weak';
    case 1:
        return 'medium';
    case 2:
        return 'strong';
    default:
        return '';
    }
}

passwordInput.addEventListener('input', checkFields);
field2.addEventListener('input', checkFields);

function checkFields() {
  const value1 = passwordInput.value;
  const value2 = field2.value;

  if (value1 === value2) {
    result.textContent = '';
  } else {
    result.textContent = 'Entrez un mot de passe identique !';
  }
}
</script>