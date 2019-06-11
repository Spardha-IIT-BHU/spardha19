var select = {"box-shadow": "0 0 5px #555",
              "border": "1px solid #555"};

var deselect = {"box-shadow": "", "border": ""};

function deselectAll () {
    for (var i = 0; i <= 20; i++) {
        var x = "#" + i;
        $(x).css(deselect);
    }
    $("#1300").css(deselect);
}

function sel(i) {
    var x = "#"+i;
    $(x).css(select);
}

$(".a-rule").click(function() {
    deselectAll();
    $(".open-element").slideToggle("fast");
    $(".open-element").removeClass("open-element");
})

$(document).ready(function () {
    $("#0").click(function () {
        deselectAll();
        if ($("#info0").hasClass("open-element")) {
            $("#info0").slideToggle("fast");
            $("#info0").removeClass("open-element");
        }
        else {
            $(".open-element").slideToggle("fast");
            $(".open-element").removeClass("open-element");
            $("#info0").slideToggle("fast");
            $("#info0").addClass("open-element");
            sel(0);
        }
    });
});

$(document).ready(function () {
    $("#0x").click(function () {
        deselectAll();
        $("#info0").slideToggle("fast");
        $("#info0").removeClass("open-element");
    });
});

$(document).ready(function () {
    $("#1").click(function () {
        deselectAll();
        if ($("#info1").hasClass("open-element")) {
            $("#info1").slideToggle("fast");
            $("#info1").removeClass("open-element");
        }
        else {
            $(".open-element").slideToggle("fast");
            $(".open-element").removeClass("open-element");
            $("#info1").slideToggle("fast");
            $("#info1").addClass("open-element");
            sel(1);
        }
    });
});

$(document).ready(function () {
    $("#1x").click(function () {
        deselectAll();
        $("#info1").slideToggle("fast");
        $("#info1").removeClass("open-element");
    });
});

$(document).ready(function () {
    $("#2").click(function () {
        deselectAll();
        if ($("#info2").hasClass("open-element")) {
            $("#info2").slideToggle("fast");
            $("#info2").removeClass("open-element");
        }
        else {
            $(".open-element").slideToggle("fast");
            $(".open-element").removeClass("open-element");
            $("#info2").slideToggle("fast");
            $("#info2").addClass("open-element");
            sel(2);
        }
    });
});

$(document).ready(function () {
    $("#2x").click(function () {
        deselectAll();
        $("#info2").slideToggle("fast");
        $("#info2").removeClass("open-element");
    });
});

$(document).ready(function () {
    $("#3").click(function () {
        deselectAll();
        if ($("#info3").hasClass("open-element")) {
            $("#info3").slideToggle("fast");
            $("#info3").removeClass("open-element");
        }
        else {
            $(".open-element").slideToggle("fast");
            $(".open-element").removeClass("open-element");
            $("#info3").slideToggle("fast");
            $("#info3").addClass("open-element");
            sel(3);
        }
    });
});

$(document).ready(function () {
    $("#3x").click(function () {
        deselectAll();
        $("#info3").slideToggle("fast");
        $("#info3").removeClass("open-element");
    });
});

$(document).ready(function () {
    $("#4").click(function () {
        deselectAll();
        if ($("#info4").hasClass("open-element")) {
            $("#info4").slideToggle("fast");
            $("#info4").removeClass("open-element");
        }
        else {
            $(".open-element").slideToggle("fast");
            $(".open-element").removeClass("open-element");
            $("#info4").slideToggle("fast");
            $("#info4").addClass("open-element");
            sel(4);
        }
    });
});

$(document).ready(function () {
    $("#4x").click(function () {
        deselectAll();
        $("#info4").slideToggle("fast");
        $("#info4").removeClass("open-element");
    });
});

$(document).ready(function () {
    $("#5").click(function () {
        deselectAll();
        if ($("#info5").hasClass("open-element")) {
            $("#info5").slideToggle("fast");
            $("#info5").removeClass("open-element");
        }
        else {
            $(".open-element").slideToggle("fast");
            $(".open-element").removeClass("open-element");
            $("#info5").slideToggle("fast");
            $("#info5").addClass("open-element");
            sel(5);
        }
    });
});

$(document).ready(function () {
    $("#5x").click(function () {
        deselectAll();
        $("#info5").slideToggle("fast");
        $("#info5").removeClass("open-element");
    });
});

$(document).ready(function () {
    $("#6").click(function () {
        deselectAll();
        if ($("#info6").hasClass("open-element")) {
            $("#info6").slideToggle("fast");
            $("#info6").removeClass("open-element");
        }
        else {
            $(".open-element").slideToggle("fast");
            $(".open-element").removeClass("open-element");
            $("#info6").slideToggle("fast");
            $("#info6").addClass("open-element");
            sel(6);
        }
    });
});

$(document).ready(function () {
    $("#6x").click(function () {
        deselectAll();
        $("#info6").slideToggle("fast");
        $("#info6").removeClass("open-element");
    });
});

$(document).ready(function () {
    $("#7").click(function () {
        deselectAll();
        if ($("#info7").hasClass("open-element")) {
            $("#info7").slideToggle("fast");
            $("#info7").removeClass("open-element");
        }
        else {
            $(".open-element").slideToggle("fast");
            $(".open-element").removeClass("open-element");
            $("#info7").slideToggle("fast");
            $("#info7").addClass("open-element");
            sel(7);
        }
    });
});

$(document).ready(function () {
    $("#7x").click(function () {
        deselectAll();
        $("#info7").slideToggle("fast");
        $("#info7").removeClass("open-element");
    });
});

$(document).ready(function () {
    $("#8").click(function () {
        deselectAll();
        if ($("#info8").hasClass("open-element")) {
            $("#info8").slideToggle("fast");
            $("#info8").removeClass("open-element");
        }
        else {
            $(".open-element").slideToggle("fast");
            $(".open-element").removeClass("open-element");
            $("#info8").slideToggle("fast");
            $("#info8").addClass("open-element");
            sel(8)
        }
    });
});

$(document).ready(function () {
    $("#8x").click(function () {
        deselectAll();
        $("#info8").slideToggle("fast");
        $("#info8").removeClass("open-element");
    });
});

$(document).ready(function () {
    $("#9").click(function () {
        deselectAll();
        if ($("#info9").hasClass("open-element")) {
            $("#info9").slideToggle("fast");
            $("#info9").removeClass("open-element");
        }
        else {
            $(".open-element").slideToggle("fast");
            $(".open-element").removeClass("open-element");
            $("#info9").slideToggle("fast");
            $("#info9").addClass("open-element");
            sel(9);
        }
    });
});

$(document).ready(function () {
    $("#9x").click(function () {
        deselectAll();
        $("#info9").slideToggle("fast");
        $("#info9").removeClass("open-element");
    });
});

$(document).ready(function () {
    $("#10").click(function () {
        deselectAll();
        if ($("#info10").hasClass("open-element")) {
            $("#info10").slideToggle("fast");
            $("#info10").removeClass("open-element");
        }
        else {
            $(".open-element").slideToggle("fast");
            $(".open-element").removeClass("open-element");
            $("#info10").slideToggle("fast");
            $("#info10").addClass("open-element");
            sel(10);
        }
    });
});

$(document).ready(function () {
    $("#10x").click(function () {
        deselectAll();
        $("#info10").slideToggle("fast");
        $("#info10").removeClass("open-element");
    });
});

$(document).ready(function () {
    $("#11").click(function () {
        deselectAll();
        if ($("#info11").hasClass("open-element")) {
            $("#info11").slideToggle("fast");
            $("#info11").removeClass("open-element");
        }
        else {
            $(".open-element").slideToggle("fast");
            $(".open-element").removeClass("open-element");
            $("#info11").slideToggle("fast");
            $("#info11").addClass("open-element");
            sel(11);
        }
    });
});

$(document).ready(function () {
    $("#11x").click(function () {
        deselectAll();
        $("#info11").slideToggle("fast");
        $("#info11").removeClass("open-element");
    });
});

$(document).ready(function () {
    $("#12").click(function () {
        deselectAll();
        if ($("#info12").hasClass("open-element")) {
            $("#info12").slideToggle("fast");
            $("#info12").removeClass("open-element");
        }
        else {
            $(".open-element").slideToggle("fast");
            $(".open-element").removeClass("open-element");
            $("#info12").slideToggle("fast");
            $("#info12").addClass("open-element");
            sel(12);
        }
    });
});

$(document).ready(function () {
    $("#12x").click(function () {
        deselectAll();
        $("#info12").slideToggle("fast");
        $("#info12").removeClass("open-element");
    });
});

$(document).ready(function () {
    $("#13").click(function () {
        deselectAll();
        if ($("#info13").hasClass("open-element")) {
            $("#info13").slideToggle("fast");
            $("#info13").removeClass("open-element");
        }
        else {
            $(".open-element").slideToggle("fast");
            $(".open-element").removeClass("open-element");
            $("#info13").slideToggle("fast");
            $("#info13").addClass("open-element");
            sel(13);
        }
    });
});

$(document).ready(function () {
    $("#13x").click(function () {
        deselectAll();
        $("#info13").slideToggle("fast");
        $("#info13").removeClass("open-element");
    });
});


$(document).ready(function () {
    $("#1300").click(function () {
        deselectAll();
        if ($("#info1300").hasClass("open-element")) {
            $("#info1300").slideToggle("fast");
            $("#info1300").removeClass("open-element");
        }
        else {
            $(".open-element").slideToggle("fast");
            $(".open-element").removeClass("open-element");
            $("#info1300").slideToggle("fast");
            $("#info1300").addClass("open-element");
            sel(1300);
        }
    });
});

$(document).ready(function () {
    $("#1300x").click(function () {
        deselectAll();
        $("#info1300").slideToggle("fast");
        $("#info1300").removeClass("open-element");
    });
});



$(document).ready(function () {
    $("#14").click(function () {
        deselectAll();
        if ($("#info14").hasClass("open-element")) {
            $("#info14").slideToggle("fast");
            $("#info14").removeClass("open-element");
        }
        else {
            $(".open-element").slideToggle("fast");
            $(".open-element").removeClass("open-element");
            $("#info14").slideToggle("fast");
            $("#info14").addClass("open-element");
            sel(14);
        }
    });
});

$(document).ready(function () {
    $("#14x").click(function () {
        deselectAll();
        $("#info14").slideToggle("fast");
        $("#info14").removeClass("open-element");
    });
});

$(document).ready(function () {
    $("#15").click(function () {
        deselectAll();
        if ($("#info15").hasClass("open-element")) {
            $("#info15").slideToggle("fast");
            $("#info15").removeClass("open-element");
        }
        else {
            $(".open-element").slideToggle("fast");
            $(".open-element").removeClass("open-element");
            $("#info15").slideToggle("fast");
            $("#info15").addClass("open-element");
            sel(15);
        }
    });
});

$(document).ready(function () {
    $("#15x").click(function () {
        deselectAll();
        $("#info15").slideToggle("fast");
        $("#info15").removeClass("open-element");
    });
});

$(document).ready(function () {
    $("#16").click(function () {
        deselectAll();
        if ($("#info16").hasClass("open-element")) {
            $("#info16").slideToggle("fast");
            $("#info16").removeClass("open-element");
        }
        else {
            $(".open-element").slideToggle("fast");
            $(".open-element").removeClass("open-element");
            $("#info16").slideToggle("fast");
            $("#info16").addClass("open-element");
            sel(16);
        }
    });
});

$(document).ready(function () {
    $("#16x").click(function () {
        deselectAll();
        $("#info16").slideToggle("fast");
        $("#info16").removeClass("open-element");
    });
});


$(document).ready(function () {
    $("#17").click(function () {
        deselectAll();
        if ($("#info17").hasClass("open-element")) {
            $("#info17").slideToggle("fast");
            $("#info17").removeClass("open-element");
        }
        else {
            $(".open-element").slideToggle("fast");
            $(".open-element").removeClass("open-element");
            $("#info17").slideToggle("fast");
            $("#info17").addClass("open-element");
            sel(17);
        }
    });
});

$(document).ready(function () {
    $("#17x").click(function () {
        deselectAll();
        $("#info17").slideToggle("fast");
        $("#info17").removeClass("open-element");
    });
});


$(document).ready(function () {
    $("#18").click(function () {
        deselectAll();
        if ($("#info18").hasClass("open-element")) {
            $("#info18").slideToggle("fast");
            $("#info18").removeClass("open-element");
        }
        else {
            $(".open-element").slideToggle("fast");
            $(".open-element").removeClass("open-element");
            $("#info18").slideToggle("fast");
            $("#info18").addClass("open-element");
            sel(18);
        }
    });
});

$(document).ready(function () {
    $("#18x").click(function () {
        deselectAll();
        $("#info18").slideToggle("fast");
        $("#info18").removeClass("open-element");
    });
});

$(document).ready(function () {
    $("#19").click(function () {
        deselectAll();
        if ($("#info19").hasClass("open-element")) {
            $("#info19").slideToggle("fast");
            $("#info19").removeClass("open-element");
        }
        else {
            $(".open-element").slideToggle("fast");
            $(".open-element").removeClass("open-element");
            $("#info19").slideToggle("fast");
            $("#info19").addClass("open-element");
            sel(19);
        }
    });
});

$(document).ready(function () {
    $("#19x").click(function () {
        deselectAll();
        $("#info19").slideToggle("fast");
        $("#info19").removeClass("open-element");
    });
});



$(document).ready(function () {
    $("#20").click(function () {
        deselectAll();
        if ($("#info20").hasClass("open-element")) {
            $("#info20").slideToggle("fast");
            $("#info20").removeClass("open-element");
        }
        else {
            $(".open-element").slideToggle("fast");
            $(".open-element").removeClass("open-element");
            $("#info20").slideToggle("fast");
            $("#info20").addClass("open-element");
            sel(20);
        }
    });
});

$(document).ready(function () {
    $("#20x").click(function () {
        deselectAll();
        $("#info20").slideToggle("fast");
        $("#info20").removeClass("open-element");
    });
});


// $(document).ready(function () {
//     $("#info0, #info1, #info2, #info3, #info4, #info5, #info6, #info7, #info8, #info9, #info10, #info11, #info12, #info13, #info1300, #info14, #info15, #info16, #info17, #info18, #info19, #info20").click(function () {
//         $(".open-element").slideToggle("fast");
//         $(".open-element").removeClass("open-element");
//     });
// });

$(document).click(function() { 
    if (($("#myModal0").data('bs.modal') || {})._isShown) {
        $('body').css({"overflow-y": "hidden"});
    }
    else if (($("#myModal1").data('bs.modal') || {})._isShown) {
        $('body').css({"overflow-y": "hidden"});
    }
    else if (($("#myModal2").data('bs.modal') || {})._isShown) {
        $('body').css({"overflow-y": "hidden"});
    }
    else if (($("#myModal3").data('bs.modal') || {})._isShown) {
        $('body').css({"overflow-y": "hidden"});
    }
    else if (($("#myModal4").data('bs.modal') || {})._isShown) {
        $('body').css({"overflow-y": "hidden"});
    }
    else if (($("#myModal5").data('bs.modal') || {})._isShown) {
        $('body').css({"overflow-y": "hidden"});
    }
    else if (($("#myModal6").data('bs.modal') || {})._isShown) {
        $('body').css({"overflow-y": "hidden"});
    }
    else if (($("#myModal7").data('bs.modal') || {})._isShown) {
        $('body').css({"overflow-y": "hidden"});
    }
    else if (($("#myModal8").data('bs.modal') || {})._isShown) {
        $('body').css({"overflow-y": "hidden"});
    }
    else if (($("#myModal9").data('bs.modal') || {})._isShown) {
        $('body').css({"overflow-y": "hidden"});
    }
    else if (($("#myModal10").data('bs.modal') || {})._isShown) {
        $('body').css({"overflow-y": "hidden"});
    }
    else if (($("#myModal11").data('bs.modal') || {})._isShown) {
        $('body').css({"overflow-y": "hidden"});
    }
    else if (($("#myModal12").data('bs.modal') || {})._isShown) {
        $('body').css({"overflow-y": "hidden"});
    }
    else if (($("#myModal13").data('bs.modal') || {})._isShown) {
        $('body').css({"overflow-y": "hidden"});
    }
    else if (($("#myModal14").data('bs.modal') || {})._isShown) {
        $('body').css({"overflow-y": "hidden"});
    }
    else if (($("#myModal15").data('bs.modal') || {})._isShown) {
        $('body').css({"overflow-y": "hidden"});
    }
    else if (($("#myModal16").data('bs.modal') || {})._isShown) {
        $('body').css({"overflow-y": "hidden"});
    }
    else if (($("#myModal17").data('bs.modal') || {})._isShown) {
        $('body').css({"overflow-y": "hidden"});
    }
    else if (($("#myModal18").data('bs.modal') || {})._isShown) {
        $('body').css({"overflow-y": "hidden"});
    }
    else if (($("#myModal1300").data('bs.modal') || {})._isShown) {
        $('body').css({"overflow-y": "hidden"});
    }
    else {
        $('body').css({"overflow-y": "visible"});
    }
});