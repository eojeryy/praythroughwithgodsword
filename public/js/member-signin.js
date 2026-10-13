const memberPassword = document.getElementById('member-password');
const memberPasswordToggle = document.querySelector('.member-password-toggle');

if (memberPassword && memberPasswordToggle) {
  memberPasswordToggle.hidden = false;
  memberPasswordToggle.addEventListener('click', () => {
    const showPassword = memberPassword.type === 'password';
    memberPassword.type = showPassword ? 'text' : 'password';
    memberPasswordToggle.setAttribute('aria-pressed', String(showPassword));
    memberPasswordToggle.setAttribute('aria-label', showPassword ? 'Hide password' : 'Show password');
    memberPasswordToggle.querySelector('i').className = showPassword ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye';
  });
}
