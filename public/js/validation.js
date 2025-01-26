class PasswordValidator {
    form: HTMLFormElement | null;
    passwordField: HTMLInputElement | null;
    confirmPasswordField: HTMLInputElement | null;
    errorMessageElement: HTMLElement | null;

    constructor(formId, passwordId, confirmPasswordId, errorMessageId) {
        if (!this.form || !this.passwordField || !this.confirmPasswordField || !this.errorMessageElement) return;
        this.form = document.getElementById(formId);
        this.passwordField = document.getElementById(passwordId);
        this.confirmPasswordField = document.getElementById(confirmPasswordId);
        this.errorMessageElement = document.getElementById(errorMessageId);

        this.form.addEventListener('submit', (event) => this.validatePasswords(event));
    }

    validatePasswords(event): void {
        const password: string = this.passwordField.value;
        const confirmPassword: string = this.confirmPasswordField.value;

        if (password !== confirmPassword) {
            event.preventDefault();
            this.errorMessageElement.textContent = 'Passwords do not match.';
        }
    }
}

const passwordValidator = new PasswordValidator('registerForm', 'password', 'confirm_password', 'errorMessage');
