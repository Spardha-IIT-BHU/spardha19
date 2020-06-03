var filled = {
    username: 0,
    password: 0
};

var flag = {
    username: 0,
    password: 0
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
    if (x == "password") {
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

function hideError() {
    $('#finalerror').fadeOut();
}

function showError(error) {
    $("#finalerror").fadeIn();
    $("#finalerror").html(error);
}

function disableAll() {
    document.getElementById("login").disabled = "true";
    document.getElementById("login").innerHTML = "Processing...";
}

function validate(form) {
    hideError();
    var f = 1;
    for (var key in flag) {
        check(key, 0);
        filled[key] = 1;
        if (flag[key] == 0) f = 0;
    }
    if (f == 0) {
        showError("Please fill all the details correctly.");
        return false;
    }
    disableAll();
    return true;
}