var filled = {
    username: 0,
    password1: 0,
    password2: 0,
    institute: 0
};

var flag = {
    username: 0,
    password1: 0,
    password2: 0,
    institute: 0
};

function show(x, parent, second, message) {
    $(parent).removeClass("has-success");
    $(parent).addClass("has-error");
    second.innerHTML = message;
    flag[x] = 0;
}

function hide(x, parent, second) {
    $(parent).removeClass("has-error");
    $(parent).addClass("has-success");
    second.innerHTML = "";
    flag[x] = 1;
}

function showok(x) {
    var icon = document.getElementById(x + "-icon");
    icon.innerHTML = '<span class="glyphicon glyphicon-ok form-control-feedback"></span>';
}

function showcross(x) {
    var icon = document.getElementById(x + "-icon");
    icon.innerHTML = '<span class="glyphicon glyphicon-remove form-control-feedback"></span>';
}

function check(x, arg) {
    var first = document.getElementById(x);
    var firstval = first.value;
    var second = document.getElementById(x + "-error");
    var parent = $(first).parent();
    if (firstval == "") {
        showcross(x);
        return show(x, parent, second, "This field is required.");
    }
    if (x == "username") {
        // Valid
        var is_email = 0;
        for (var i = 0; i < firstval.length; i++) {
            if (firstval.charAt(i) == '@') is_email = 1;
        }

        if (is_email) {
            var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            if (re.test(String(firstval).toLowerCase())) {
                showok(x);
                hide(x, parent, second);
            } else {
                showcross(x);
                show(x, parent, second, "Please enter a valid email address.");
                if (arg == 0) {
                    second.innerHTML = "";
                }
            }
        }
        else {
            if (firstval.length < 6 || firstval.length > 30) {
                showcross(x);
                show(x, parent, second, "Username must be of <b>length 6 - 30</b>.");
                if (arg == 0) {
                    second.innerHTML = "";
                }
            }
            else if (!/^[a-zA-Z]/.test(firstval)) {
                showcross(x);
                show(x, parent, second, "Username must start with an alphabet.");
                if (arg == 0) {
                    second.innerHTML = "";
                }
            }
            else if (/^[0-9a-zA-Z_.-]+$/.test(firstval)) {
                showok(x);
                hide(x, parent, second);
            }
            else {
                showcross(x);
                show(x, parent, second, "Username must only contain <b>alphabets</b>, <b>numbers</b> or <b>_</b>, <b>-</b> and <b>.</b>");
                if (arg == 0) {
                    second.innerHTML = "";
                }
            }
        }
    }
    if (x == "password1") {
        // Valid
        if (firstval.length >= 6 && firstval.length <= 30) {
            showok(x);
            hide(x, parent, second);
        } else {
            showcross(x);
            show(x, parent, second, "Password must be of <b>length 6 - 30</b>.");
            if (arg == 0) {
                second.innerHTML = "";
            }
        }
    }
    if (x == "password2") {
        if (firstval == document.getElementById("password1").value) {
            showok(x);
            hide(x, parent, second);
        } else {
            showcross(x);
            show(x, parent, second, "Passwords do not match.");
            if (arg == 0) {
                second.innerHTML = "";
            }
        }
    }
    if (x == "institute") {
        if (!$.isNumeric(firstval)) {
            showok(x);
            hide(x, parent, second);
        } else {
            showcross(x);
            show(x, parent, second, "Please enter a valid Institute Name.");
            if (arg == 0) {
                second.innerHTML = "";
            }
        }
    }
}

function onInput(x) {
    if (filled[x]) {
        check(x, 0);
    }
}

function onLeave(x) {
    filled[x] = 1;
    check(x, 1);
}

function hideError(x) {
    $('#finalerror' + x).fadeOut();
}

function showError(x, error) {
    $("#finalerror" + x).fadeIn();
    $("#finalerror" + x).html(error);
}

function hideSuccess(x) {
    $('#finalsuccess' + x).fadeOut();
}

function showSuccess(x, success) {
    $("#finalsuccess" + x).fadeIn();
    $("#finalsuccess" + x).html(success);
}

function disableAll() {
    document.getElementById("submit1").disabled = "true";
    document.getElementById("submit1").innerHTML = "Processing...";
}

function disableAll2() {
    $("#OTPdiv").fadeIn();
    document.getElementById("submit1").disabled = "true";
    document.getElementById("submit1").innerHTML = '<i class="fa fa-paper-plane"></i> SUBMIT';
    document.getElementById("username").disabled = "true";
    document.getElementById("password1").disabled = "true";
    document.getElementById("password2").disabled = "true";
    document.getElementById("institute").disabled = "true";
}

function validate(form) {
    hideError(1);
    hideSuccess(1);
    var f = 1;
    for (var key in flag) {
        check(key, 0);
        filled[key] = 1;
        if (flag[key] == 0) f = 0;
    }
    if (f == 0) {
        showError(1, "Please fill all the details correctly.");
        return false;
    }
    disableAll();
    return true;
}

function verifyOTP(form) {
    hideError(1);
    hideSuccess(1);
    hideSuccess(2);
    var otp = document.getElementById("OTPTextBox").value;
    if (otp.length != 6 || !$.isNumeric(otp)) {
        showError(2, "Invalid OTP! Please try again.");
        return false;
    }
    document.getElementById("submit2").disabled = "true";
    document.getElementById("submit2").innerHTML = "Verifying...";
    return true;
}