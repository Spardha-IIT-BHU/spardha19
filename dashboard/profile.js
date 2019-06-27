function validate_username(form) {
    var username = document.getElementById('newusername').value;
    if (username.length < 6 || username.length > 30) {
        document.getElementById("error-username").innerHTML = "Username must be of <b>length 6 - 30</b>.";
        return false;
    }
    else if (!/^[a-zA-Z]/.test(username)) {
        document.getElementById("error-username").innerHTML = "Username must start with an alphabet.";
        return false;
    }
    else if (!/^[0-9a-zA-Z_.-]+$/.test(username)) {
        document.getElementById("error-username").innerHTML = "Username must only contain <b>alphabets</b>, <b>numbers</b> or <b>_</b>, <b>-</b> and <b>.</b>";
        return false;
    }
    return true;
}

function validate_password(form) {
    var password1 = document.getElementById('newpassword1').value;
    var password2 = document.getElementById('newpassword2').value;
    if (password1.length < 6 || password1.length > 30) {
        document.getElementById("error-password").innerHTML = "Password must be of <b>length 6 - 30</b>.";
        return false;
    }
    else if (password1 != password2) {
        document.getElementById("error-password").innerHTML = "Passwords do not match";
        return false;
    }
    return true;
}

function validate_delete(form) {
    var password = document.getElementById('deletepassword').value;
    if (password.length < 6 || password.length > 30) {
        document.getElementById("error-delete").innerHTML = "Password must be of <b>length 6 - 30</b>.";
        return false;
    }
    return true;
}

function checkPhone(n) {
    var num;
    if (n[0] == '+') {
        if (n.length != 13) return 0;
        if (n.substring(1, 3) != "91") return 0;
        num = n.substring(3);
    } else if (n[0] == 0) {
        if (n.length != 11) return 0;
        num = n.substring(1);
    } else {
        num = n;
    }
    if (!$.isNumeric(num) || parseInt(num[0]) < 6 || num.length != 10) {
        return 0;
    }
    return 1;
}

function validate_edit() {
    var name = document.getElementById('newname').value;
    var phone = document.getElementById('newphone').value;
    var designation = document.getElementById('newdesignation').value;
    if (!/^[A-Za-z ]+[\.]*[A-Za-z ]*$/.test(name) || name.length == 0) {
        document.getElementById("error-edit").innerHTML = "Please enter a valid name.";
        return false;
    }
    else if ($.isNumeric(designation) || designation.length == 0) {
        document.getElementById("error-edit").innerHTML = "Please enter a valid designation.";
        return false;
    }
    else if (!checkPhone(phone)) {
        document.getElementById("error-edit").innerHTML = "Please enter a valid phone number.";
        return false;
    }
    return true;
}
