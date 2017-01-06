$(document).ready(function () {
    var navListItems = $('div.setup-panel div a'),
        allWells = $('.setup-content'),
        allNextBtn = $('.nextBtn');

    allWells.hide();

    navListItems.click(function (e) {
        e.preventDefault();
        var $target = $($(this).attr('href')),
            $item = $(this);

        if (!$item.hasClass('disabled')) {
            navListItems.removeClass('btn-primary').addClass('btn-default');
            $item.addClass('btn-primary');
            allWells.hide();
            $target.show();
            $target.find('input:eq(0)').focus();
        }
    });

    allNextBtn.click(function () {

        var curStep = $(this).closest(".setup-content"),
            curStepBtn = curStep.attr("id"),
            nextStepWizard = $('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().next().children("a"),
            curInputs = curStep.find("input[type='text'],input[type='url']"),
            isValid = true;

        console.log(curStepBtn);

        if (curStepBtn === 'step-1') {
            isValid = false;
            $.ajax({
                method: "POST",
                async: false,
                url: "/validate-module",
                data: $("form").serialize()
            }).success(function (data) {
                var formHtml = '';
                $.each(data.things, function (key, item) {
                    formHtml += '<div class="form-group"> <label for="' + item.key + '"> Switch ' + key + 1 + '</label> <input type="text" class="form-control" name="' + item.key + '" id="' + item.key + '" value="' + item.name + '" placeholder="' + item.key + '">  </div>';
                });
                $("#sw").html(formHtml);
                $("#module_name").val(data.module_name);
                isValid = true;
            }).error(function (data) {
                sweetAlert("Oops...", "Please enter valid module key and pin", "error");
                $("#sw").html('');
                $("#module_name").val('');
                isValid = false;
            });
        }

        if (curStepBtn === 'step-2') {
            isValid = false;
            $.ajax({
                method: "POST",
                url: "/save-module",
                async: false,
                data: $("form").serialize()
            }).success(function (data) {

                isValid = true;
            }).error(function (data) {
                sweetAlert("Oops...", "Something went wrong!", "error");


                isValid = false;
            });
        }

        $(".form-group").removeClass("has-error");
        for (var i = 0; i < curInputs.length; i++) {
            if (!curInputs[i].validity.valid) {
                isValid = false;
                $(curInputs[i]).closest(".form-group").addClass("has-error");
            }
        }

        if (isValid)
            nextStepWizard.removeAttr('disabled').trigger('click');
    });

    $(".switch.enabled input").click(function (e) {
        if ($(this).parent().hasClass("enabled")) {
            $thingID = $(this).attr("data-id");
            if ($(this).is(':checked')) {
                SwitchChangeByUser($thingID, 1);
            } else {
                SwitchChangeByUser($thingID, 0);
            }
            $(this).parent().removeClass("enabled");
        } else {
            e.preventDefault();
        }
    });

    $('div.setup-panel div a.btn-primary').trigger('click');
    $('.finish-btn').click(function () {
        swal({
                title: "Good job!",
                text: "You configured the first module!",
                type: "success",
                showCancelButton: false,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "You done !! You are gonna navigation"
            },
            function () {
                window.location = "/";
            });
    });


    // handling onchange checkbox on accessible switch
    $('.switchChange:checkbox').change(function () {
        var id = $(this).attr('id');
        if ($(this).is(":checked")) {
            alert(id)
            SwitchChangeByUser(id,1);
        } else {
            SwitchChangeByUser(id,0)
        }
    });
});

function turnSwitch($thingID, $status) {
    $thingSwitch = $(".switch").find("input[data-id='" + $thingID + "']");
    console.log($thingSwitch);
    if ($status == 1) {
        if (!$thingSwitch.is(':checked')) {
            $thingSwitch.prop("checked", true);
        }
    } else {
        $thingSwitch.prop("checked", false);
    }
}

function SwitchChangeByUser($thingID, $status) {
}
