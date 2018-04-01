$(".pagination .page-item").click(function($this){
    var select_data_per_page = $("#select_data_count").val();
    var temp = $(this).find("span").html();
    if($(this).find("span").hasClass("fa-angle-right")){
        temp = "chevron_right";
    } else if($(this).find("span").hasClass("fa-angle-double-right")){
        temp = "last_page";
    } else if($(this).find("span").hasClass("fa-angle-left")){
        temp = "chevron_left";
    } else if($(this).find("span").hasClass("fa-angle-double-left")){
        temp = "first_page";
    }
    var index = $(".pagination .page-item.active").attr("index");
    var search_word = $("#search").val();
    if (temp == "chevron_right" || temp == "chevron_left" || temp == "first_page" || temp == "last_page"){
        var data_per_page = $("#select_data_count").val();
        var active_page = parseInt($(".pagination .page-item.active span").html());
        var max_page = Math.ceil($("#max_data").val()/data_per_page);
        var page_count = parseInt($("#page_count").val());
        var add = 1;
        var bottom_page = 1;
        var upper_page = max_page;


        if(temp == "chevron_right"){
            if(active_page + 1 < Math.floor(page_count/2)){
                bottom_page = active_page - index;
            } else {
                bottom_page =  active_page + add - Math.floor(page_count/2) + 1;
            }
            if(bottom_page + page_count <= max_page){
                upper_page = bottom_page + page_count;
            } else {
                upper_page = max_page + 1;
                if(upper_page - page_count > 0){
                    bottom_page = upper_page - page_count;
                } else {
                    bottom_page = 1;
                }
            }
        }else if(temp == "chevron_left"){
            add = -1;
            if(active_page + add - page_count < 0){
                bottom_page = 1;
            } else {
                bottom_page = active_page + 1 - page_count;
            }
            upper_page = bottom_page + page_count;
        } else if(temp == "first_page"){
            add = 0;
            bottom_page = 1;
            upper_page = bottom_page + page_count;
        } else if(temp == "last_page"){
            add = 0;
            if (max_page + 1 - page_count > 0){
                bottom_page = max_page + 1 - page_count;
            } else {
                bottom_page = 1;
            }
            upper_page = max_page + 1;
        }
        if(active_page + add > 0 && active_page + add <= max_page){
            var element_index = 0;
            $(".pagination .page-item .page").each(function(index){
                for(var i=bottom_page; i<upper_page; i++){
                    if( i-bottom_page == index){
                        $(this).html(i);
                    }
                }
                if (index + parseInt(bottom_page) == parseInt(active_page) + add){
                    element_index = index;
                }
            });
            if (temp == "first_page" || temp == "last_page"){
                $(".pagination .page-item.page").each(function(index){
                    $(this).removeClass("active");
                    if(index == 0 && temp == "first_page"){
                        getData(1, select_data_per_page, search_word, 0);
                        $(this).addClass("active");
                    } else if(temp == "last_page"){
                        getData(max_page, select_data_per_page, search_word, 0);
                        if(page_count > max_page){
                            if(index == max_page - 1){
                                $(this).addClass("active");
                            }
                        } else {
                            if(index == page_count - 1){
                                $(this).addClass("active");
                            }
                        }
                    }
                });
            } else {
                getData(parseInt(active_page) + add, select_data_per_page, search_word, 0);
                $(".pagination .page-item.page").each(function(index){
                    $(this).removeClass("active");
                    if(index == element_index){
                        $(this).addClass("active");
                    }
                });
            }
        }
    } else {
        getData(temp, select_data_per_page, search_word, 0);
        $(".pagination .page-item").removeClass("active");
        $(this).addClass("active");
    }
});
function getData(param1, param2, param3, param4) {
    var deferredData = new jQuery.Deferred();
    $.ajax({
        type: "POST",
        url: $("#route").val() +"/page",
        dataType: "json",
        data: {'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>', page: param1, data_per_page: param2, search_word: param3},
        success: function(data) {
            $('#table').bootstrapTable("load", data[0]);
            $("#max_data").val(data[1]);
            if(param4 == 1){
                var data_per_page = $("#select_data_count").val();
                var max_page = Math.ceil($("#max_data").val()/data_per_page);
                $(".pagination .page-item.page").each(function(index){
                    $(this).removeClass("active");
                    $(this).css("display","");
                    $(this).find("a").html(index + 1);
                    if(index == 0){
                        $(this).addClass("active");
                    }
                    if(index >= max_page){
                        $(this).css("display","none");
                    }
                });
            }
        }
    });
    return deferredData; // contains the passed data
};
$("#select_data_count").change(function($this){
    var select_data_per_page = $("#select_data_count").val();
    var search_word = $("#search").val();
    $("#select_data_count").val(select_data_per_page);
    getData(1, select_data_per_page, search_word, 1);
});
$("#search").change(function($this){
    var select_data_per_page = $("#select_data_count").val();
    var search_word = $("#search").val();
    $("#select_data_count").val(select_data_per_page);
    getData(1, select_data_per_page, search_word, 1);
});