$(".number").keypress(function(data) {
    if (data.which != 8 && data.which != 0 && (data.which<48 || data.which>57)) {
        // $(".message").html("Please.. Number Only").show().fadeOut("slow");
        return false;
    }
  });