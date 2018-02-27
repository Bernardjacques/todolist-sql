$(".header--theme-button").on("click", function() {
    var primaryColor = $(this).css("--theme-primary");
    var secondaryColor = $(this).css("--theme-secondary");
    var primarybackground = $(this).css("--secondary-background");

    $(".header--theme-button").removeClass("active");
    $(this).addClass("active");

    $(document.body).css("--primary-color", primaryColor);
    $(document.body).css("--secondary-color", secondaryColor);
    });