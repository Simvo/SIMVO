function initAddSemesterListener(target) {
    event.stopImmediatePropagation();
    $(target).click(function (e) {
        e.preventDefault();
        var last_sem = $(this).attr("id").substring(0, $(this).attr("id").length - 4);
        last_sem = last_sem.split(" ");
        last_sem = last_sem[0] + " " + last_sem[1];

        var new_sem = get_semester_letter(get_next_semester(get_semester(last_sem)));
        var new_sem2 = get_semester_letter(get_next_semester(get_semester(new_sem)));
        var new_sem2_class = new_sem2.split(" ");
        new_sem2_class = new_sem2_class[0] + new_sem2_class[1];

        if (new_sem.substring(0, 6) == "SUMMER" && !$(".semester").find("div." + new_sem2_class).length) {
            //add add-button
            var new_semester = '<div class="fill-semester-gap-wrap">';
            new_semester += '<a href="#" id="' + last_sem + '-gap" class="add-semester mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab" style="background-color: #2980b9;"><i class="material-icons" style="color: white">add</i></a>';
            new_semester += '</div>';
            new_semester += '<div class="semester">';
            new_semester += '<h5 style="text-align:center" class="semester-header" id="' + new_sem2.replace(" ", "") + '_header">' + new_sem2 + '</h5>';
            if ($("#required-group-div").length == 0) {
                new_semester += '<a href="#" id="reveal_complementary_courses_' + new_sem.replace(" ", "") + '" data-reveal-id="comp_courses" class="mdl-button mdl-js-button mdl-js-ripple-effect semester-add-comp-course-button reveal_complementary_courses" style="background-color: #aaedff">';
                new_semester += 'Add Course';
                new_semester += '</a>';
            }
            new_semester += '<div class="draggable">';
            new_semester += '<div class="sortable validPosition ' + new_sem2.replace(" ", "") + '" id="' + new_sem2 + " " + new_sem2.replace(" ", "") + '">';
            new_semester += '<div class="custom_card credit_counter" style="text-align:center;">';
            new_semester += '<div class="credit_counter_num" style="display: table-cell; vertical-align: middle; font-size:15px">';
            new_semester += 'CREDITS: 0';
            new_semester += '</div>';
            new_semester += '</div>';
            new_semester += '</div>';
            new_semester += '</div>';
            new_semester += '<div class="delete-semester-wrap">';
            new_semester += '<a href="#" style="opacity:0;" id="' + new_sem2 + '-delete" class="delete-semester mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab"><i class="material-icons">clear</i></a>';
            new_semester += '</div>';
            new_semester += '</div>';

            $(this).parent().before(new_semester);
            renderSortable();

            //add delete listener to new semester
            initDeleteSemesterListener("[id='" + new_sem2 + "-delete']");
            initAddSemesterListener("[id='" + last_sem + "-gap']");
            initComplementaryModalRevealListener("#reveal_complementary_courses_" + new_sem.replace(" ", ""));



            //check if the next semester exists
            test_sem = new_sem2
            test_sem = test_sem.split(" ");
            test_sem = test_sem[0] + " " + test_sem[1];
            var check_sem = formatSemesterID(get_semester_letter(get_next_semester(get_semester(new_sem2))));

            //if the next semester exists then we dont need the button!
            if ($("[id='" + check_sem + "']").length) {
                $(this).parent().remove();
            } else {
                //update the gap
                var newAddButton = '<div class="fill-semester-gap-wrap">';
                newAddButton += '<a href="#" id="' + new_sem2 + '-gap" class="add-semester mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab" style="background-color: #2980b9;"><i class="material-icons" style="color: white">add</i></a>';
                newAddButton += '</div>';
                $(this).parent().before(newAddButton);
                initAddSemesterListener("[id='" + new_sem2 + "-gap']")
                $(this).parent().remove();
            }

        } else {
            var new_semester = '<div class="semester">';
            new_semester += '<h5 style="text-align:center" class="semester-header" id="' + new_sem.replace(" ", "") + '_header">' + new_sem + '</h5>';
            if ($("#required-group-div").length == 0) {
                new_semester += '<a href="#" id="reveal_complementary_courses_' + new_sem.replace(" ", "") + '" data-reveal-id="comp_courses" class="mdl-button mdl-js-button mdl-js-ripple-effect semester-add-comp-course-button reveal_complementary_courses" style="background-color: #aaedff">';
                new_semester += 'Add Course';
                new_semester += '</a>';
            }
            new_semester += '<div class="draggable">';
            new_semester += '<div class="sortable validPosition ' + new_sem.replace(" ", "") + '" id="' + new_sem + " " + new_sem.replace(" ", "") + '">';
            new_semester += '<div class="custom_card credit_counter" style="text-align:center;">';
            new_semester += '<div class="credit_counter_num" style="display: table-cell; vertical-align: middle; font-size:15px">';
            new_semester += 'CREDITS: 0';
            new_semester += '</div>';
            new_semester += '</div>';
            new_semester += '</div>';
            new_semester += '</div>';
            new_semester += '<div class="delete-semester-wrap">';
            new_semester += '<a href="#" style="opacity:0;" id="' + new_sem + '-delete" class="delete-semester mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab"><i class="material-icons">clear</i></a>';
            new_semester += '</div>';
            new_semester += '</div>';

            //$("#course_schedule").append(new_semester);
            $(this).parent().before(new_semester);
            renderSortable();

            //add delete listener to new semester
            initDeleteSemesterListener("[id='" + new_sem + "-delete']");
            initComplementaryModalRevealListener("#reveal_complementary_courses_" + new_sem.replace(" ", ""));

            //check if the next semester exists
            var test_sem = new_sem;
            test_sem = test_sem.split(" ");
            test_sem = test_sem[0] + " " + test_sem[1];
            var check_sem = formatSemesterID(get_semester_letter(get_next_semester(get_semester(new_sem))));

            //if the next semester exists then we dont need the button!
            if ($("[id='" + check_sem + "']").length) {
                $(this).parent().remove();
            } else {
                var newAddButton = '<div class="fill-semester-gap-wrap">';
                newAddButton += '<a href="#" id="' + new_sem + '-gap" class="add-semester mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab" style="background-color: #2980b9;"><i class="material-icons" style="color: white">add</i></a>';
                newAddButton += '</div>';
                $(this).parent().before(newAddButton);
                initAddSemesterListener("[id='" + new_sem + "-gap']")
                $(this).parent().remove();
            }
        }
    });

}

function initDeleteSemesterListener(target) {
    $(target).animate({
        opacity: 1
    }, 300);
    $(target).click(function (e) {
        e.preventDefault();

        var target_sem = $(this).attr("id").substring(0, $(this).attr("id").length - 7);
        target_sem = target_sem.split(" ");
        target_sem = target_sem[0] + " " + target_sem[1];
        var CourseCount = $(this).parent().parent().find("div.custom_card").length - 1;
        var prev_sem = get_semester_letter(get_previous_semester(get_semester(target_sem)));
        var next_sem = get_semester_letter(get_next_semester(get_semester(target_sem)));

        deleteSemester(prev_sem, target_sem, next_sem);

    });
}

function initRemoveCourseListener(target) {
    $(target).click(function (e) {
        e.preventDefault();


        if ($(this).parent().parent().parent().parent().hasClass("add-to-schedule")) {
            //courses that have NOT been added to the schedule have no need for a database call
            $(this).parent().parent().parent().parent().remove();
            refreshComplementaryCourses();
        } else {
            var courseID = $(this).attr("id").substring(7, $(this).attr("id").length);

            //delete from database
            $.ajax({
                type: "delete",
                url: "/flowchart/delete_course_from_schedule",
                data: {
                    id: courseID,
                },
                success: function (data) {
                    var response = JSON.parse(data);

                    // update status bar
                    editStatusBar();
                    if (response === 'Error') {
                        //error handler
                    } else {
                        if (response[3] != 'Exemption') {
                            var semester = get_semester_letter(response[3]);
                            semester = semester.split(" ");
                            semester = semester[0] + semester[1];
                        } else {
                            var semester = 'Exemption';
                        }


                        $("#" + response[5] + response[0]).remove();

                        $("." + semester).find('.credit_counter_num').text('CREDITS: ' + response[1]);


                        for (var group in response[2]) {
                            if (response[2].hasOwnProperty(group)) {
                                var groupProgress = response[2][group];
                                var target = $("td[id='" + group + "']").text("" + groupProgress[0] + "/" + groupProgress[1]);
                            }
                        }

                        for (var group in response[6]) {
                            if (response[6].hasOwnProperty(group)) {
                                var groupProgress = response[6][group];
                                var target = $("td[id='" + group + "']").text("" + groupProgress[0] + "/" + groupProgress[1]);
                            }
                        }

                        refreshDeleteSemester();
                        refreshComplementaryCourses();
                    }
                }
            });
        }
    });
}