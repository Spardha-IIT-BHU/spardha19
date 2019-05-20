var filled = {
    name: 0, designation: 0, institute: 0, year: 0,
    email: 0, phone: 0
};

var flag = {
    name: 0, designation: 0, institute: 0, year: 0,
    email: 0, phone: 0
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
    second.innerHTML = "&nbsp;";
    flag[x] = 1;
}

function showok(x) {
    var icon = document.getElementById(x+"-icon");
    icon.innerHTML = '<span class="glyphicon glyphicon-ok form-control-feedback"></span>';
}

function showcross(x) {
    var icon = document.getElementById(x+"-icon");
    icon.innerHTML = '<span class="glyphicon glyphicon-remove form-control-feedback"></span>';
}

function checkPhone(n) {
    var num;
    if (n[0] == '+') {
        if (n.length != 13) return 0;
        if (n.substring(1,3) != "91") return 0;
        num = n.substring(3);
    }
    else if (n[0] == 0) {
        if (n.length != 11) return 0;
        num = n.substring(1);
    }
    else {
        num = n;
    }
    if(!$.isNumeric(num) || parseInt(num[0])<6 || num.length!=10) {
        return 0;
    }
    return 1;
}

function check (x, arg) {
    var first = document.getElementById(x);
    var firstval = first.value;
    var second = document.getElementById(x+"-error");
    var parent = $(first).parent();
    if (firstval == "") {
        showcross(x);
        return show(x, parent, second, "This field is required.");
    }
    if (x == "name") {
        // Valid
        if (/^[A-Za-z ]+[\.]*[A-Za-z ]*$/.test(firstval)) {
            showok(x);
            hide (x, parent, second);
        }
        else {
            showcross(x);
            show (x, parent, second, "Please enter a valid name.");
            if (arg == 0) {
                second.innerHTML = "&nbsp;";
            }
        }
    }
    if (x == "designation") {
        showok(x);
        hide (x, parent, second);
    }
    if (x == "institute") {
        showok(x);
        hide (x, parent, second);
    }
    if (x == "year") {
        showok(x);
        hide (x, parent, second);
    }
    if (x == "email") {
        // Valid
        var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        if (re.test(String(firstval).toLowerCase())) {
            showok(x);
            hide (x, parent, second);
        }
        else {
            showcross(x);
            show (x, parent, second, "Please enter a valid email address.");
            if (arg == 0) {
                second.innerHTML = "&nbsp;";
            }
        }
    }
    if (x == "phone") {
        // Valid
        if (checkPhone(firstval)) {
            showok(x);
            hide (x, parent, second);
        }
        else {
            showcross(x);
            show (x, parent, second, "Please enter a valid phone number.");
            if (arg == 0) {
                second.innerHTML = "&nbsp;";
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

function hideError(){
    $('#finalerror').html('');
    $('#finalerror').fadeOut();
}
function showError(error){
    $("#finalerror").fadeIn();
    $("#finalerror").html(error);
}

function validate(form) {
    hideError();
    var error = document.getElementById("finalerror");
    var f = 1;
    for (var key in flag) {
        check(key, 0);
        filled[key] = 1;
        if (flag[key] == 0) f = 0;
    }
    if (f == 0) {
        showError("Please enter the correct details.");
        return false;
    }
    if($("form .event:checked").length==0){
        showError("Please select at least one event.");
        return false;
    }
    if(!$("form #terms").is(":checked")){
        showError("Please check above box before proceeding.");
        return false;
    }
    showError("SORRY! &nbsp;REGISTRATION &nbsp;IS &nbsp;CURRENTLY &nbsp;UNAVAILABLE!!");
    return false;
}