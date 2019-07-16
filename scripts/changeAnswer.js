function changeAnswer(commentId,name) {
    var cancel = "<span id='cancel-reply' class='btn btn-sm btn-outline-secondary mr-4 reply-comment-title'>انصراف</span>";
    $("#c_answer_id").val(commentId);
    $("#comment-title").removeClass('failure-res').removeClass('success-res').addClass('res').html(' دیدگاه خود را در پاسخ به '+'<span>'+name+'</span>'+' بنویسید : ');

    $("#comment-title").append(cancel);

    $("#cancel-reply").click(function(){
        $("#c_answer_id").val(0);
        $("#comment-title").html("دیدگاه خود را درمورد این مطلب بنویسید :").addClass('res').removeClass("failure-res").removeClass("success-res");
    });

    location.href = "#send-comment";
}