window.onload = function () {
    document.forms['groupeform'].elements['firstname'].focus();
    var blockErrors = document.querySelector('.errors');


    document.forms['groupeform'].elements['email'].onkeyup = function () {
        var emailRegEx = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        if (false === emailRegEx.test(this.value)) {
            blockErrors.innerHTML = 'Saisir un email valide <br>';
        } else {
            blockErrors.innerHTML = '';
        }

    }

    document.forms['groupeform'].onsubmit = function () {
        var email = this.elements['email'].value;
        var firstname = this.elements['firstname'].value;
        var lastname = this.elements['lastname'].value;
        var nickname = this.elements['username'].value;
        var password = this.elements['password'].value;
        var passwordrepeat = this.elements['password_repeat'].value;
        var errorMessage = '';
        if (1 > firstname.length) {
            errorMessage += 'Saisir 1 caractère minimum pour le prénom <br>';
        }
        if (1 > lastname.length) {
            errorMessage += 'Saisir 1 caractère minimum pour le nom <br>';
        }
        if (4 > username.length) {
            errorMessage += 'Saisir 4 caractères minimum pour le pseudo <br>';
        }

        if (6 > password.length) {
            errorMessage += 'Saisir 6 caractères minimum pour le mot de passe<br>';
        }

        if (passwordrepeat !== password) {
            errorMessage += 'Veuillez saisir deux mots de passe identiques<br>';
        }
        if (0 !== errorMessage.length) {
            blockErrors.innerHTML = errorMessage;
            return false;
        }
    };
};