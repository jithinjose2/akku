$(document).ready(function () {

    $('#rootwizard').bootstrapWizard({
        onNext: function (tab, navigation, index) {
            console.log(index)
            if (index == 1) {
                $.ajax({
                    method: "POST",
                    url: "/validate-module",
                    data: $("#module").serialize()
                }).success(function (data) {
                    var formHtml = '';
                    $.each(data.things, function (key, item) {
                        formHtml += '<div class="form-group"> <label for="' + item.key + '"> Switch ' + key + 1 + '</label> <input type="text" class="form-control" name="' + item.key + '" id="' + item.key + '" value="' + item.name + '" placeholder="' + item.key + '">  </div>';
                    });
                    $("#sw").html(formHtml);
                    $("#module_name").val(data.module_name);
                    return true;
                }).error(function (data) {
                    sweetAlert("Oops...", "Something went wrong!", "error");
                    return false;
                });
            }

        }, onTabShow: function (tab, navigation, index) {
            var $total = navigation.find('li').length;
            var $current = index + 1;
            var $percent = ($current / $total) * 100;
            $('#rootwizard .progress-bar').css({width: $percent + '%'});
        }
    });
    $('#rootwizard .finish').click(function () {
        var mergeddata = $("#module").serialize() + '&' + $("#switches").serialize();
        $.ajax({
            method: "POST",
            url: "/save-module",
            data: mergeddata
        }).success(function (data) {
            swal("Good job!", "You configured the first module!", "success")
            return true;
        }).error(function (data) {
            sweetAlert("Oops...", "Something went wrong!", "error");
            return false;
        });
    });
});
