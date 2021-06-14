function okCancel(str) {
    var browser = navigator.appName;
    if (str == '')
        str = 'Are you sure?>';
    if (browser == "Microsoft Internet Explorer") {
        if (confirm(str)) {
            return true;
        } else {
            event.returnValue = false;
            return false;
        }
    } else {
        if (confirm(str)) {
            return true;
        } else {
            return false;
        }
    }
}

$(document).ready(function () {
    $('#myModal').on('show.bs.modal', function (e) {
        var image = $(e.relatedTarget).attr('data-full');
        var text = $(e.relatedTarget).attr('alt');
        $(".imagepop").attr("src", image);
        document.getElementById("modaltext").innerHTML = text;
    });
});
