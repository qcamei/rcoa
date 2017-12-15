//<<点赞部分    
$("#thumbs-up").click(function () {
    var isAdd = $(this).attr("data-add");
    if (isAdd == "false") {
        $.post("/helpcenter/api/post-like", $("#thumbs-up-form").serialize(), function (r) {
            if (r['code'] == 200) {
                $("#thumbs-up").attr("data-add", "true");
                $("#thumbs-up").children("i").removeClass("fa-thumbs-o-up");
                $("#thumbs-up").children("i").addClass("fa-thumbs-up");
                $("#Post-like_count").val(r['data']['number']);
                $(".thumbs-up>font").text(r['data']['number']);
            }
        });
    } else {
        $.post("/helpcenter/api/cancel-post-like", $("#thumbs-up-form").serialize(), function (r) {
            if (r['code'] == 200) {
                $("#thumbs-up").attr("data-add", "false");
                $("#thumbs-up").children("i").removeClass("fa-thumbs-up");
                $("#thumbs-up").children("i").addClass("fa-thumbs-o-up");
                $("#Post-like_count").val(r['data']['number']);
                $(".thumbs-up>font").text(r['data']['number']);
            }
        });
    }
    return false;
});
//点赞部分>>
//<<踩部分    
$("#thumbs-down").click(function () {
    var isAdd = $(this).attr("data-add");
    if (isAdd == "false") {
        $.post("/helpcenter/api/post-unlike", $("#thumbs-down-form").serialize(), function (r) {
            if (r['code'] == 200) {
                $("#thumbs-down").attr("data-add", "true");
                $("#thumbs-down").children("i").removeClass("fa-thumbs-o-down");
                $("#thumbs-down").children("i").addClass("fa-thumbs-down");
                $("#Post-unlike_count").val(r['data']['number']);
                $(".thumbs-down>font").text(r['data']['number']);
            }
        });
    } else {
        $.post("/helpcenter/api/cancel-post-unlike", $("#thumbs-down-form").serialize(), function (r) {
            if (r['code'] == 200) {
                $("#thumbs-down").attr("data-add", "false");
                $("#thumbs-down").children("i").removeClass("fa-thumbs-down");
                $("#thumbs-down").children("i").addClass("fa-thumbs-o-down");
                $("#Post-unlike_count").val(r['data']['number']);
                $(".thumbs-down>font").text(r['data']['number']);
            }
        });
    }
    return false;
});
//踩部分>>

