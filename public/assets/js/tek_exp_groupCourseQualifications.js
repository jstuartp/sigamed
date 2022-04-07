var Tecnotek = Tecnotek || {};

Tecnotek.GroupCourseQualifications = {
    translates : {},
    completeText: "",
    studentsIndex: 0,
    year: 0,
    groupId: 0,
    studentsLength: 0,
    loadOnlyBachelors: false,
    isMepReport: 0,
    init : function() {
        $("#year").change(function(event){
            event.preventDefault();
            $('#subentryFormParent').empty();
            Tecnotek.GroupCourseQualifications.loadGroupsOfYear($(this).val());
        });

        $("#groups").change(function(event){
            event.preventDefault();
            Tecnotek.GroupCourseQualifications.loadGroupCourses($(this).val());
        });

        $("#courses").change(function(event){
            event.preventDefault();
            Tecnotek.GroupCourseQualifications.loadQualificationsOfGroupByCourse($(this).val());
        });

        Tecnotek.GroupCourseQualifications.loadGroupsOfYear($('#year').val());
        Tecnotek.GroupCourseQualifications.initButtons();
    },
    initButtons : function() {
        $('#btnPrint').click(function(event){
            $("#tableContainer").printElement({printMode:'popup', pageTitle:$(this).attr('rel')});
        });
    },
    loadGroupsOfYear: function($year) {
        if(($year!==null && $year!== undefined)){
            $('#groups').children().remove();
            $('#courses').children().remove();
            $('#subentryFormParent').empty();
            $('#tableContainer').hide();
            Tecnotek.ajaxCall(Tecnotek.UI.urls["loadGroupsOfYearURL"],
                {   year: $year,
                    loadOnlyBachelors: Tecnotek.GroupCourseQualifications.loadOnlyBachelors},
                function(data){
                    if(data.error === true) {
                        Tecnotek.showErrorMessage(data.message,true, "", false);
                    } else {
                        for(i=0; i<data.groups.length; i++) {
                            $('#groups').append('<option value="' + data.groups[i].id + '">' + data.groups[i].name + '</option>');
                        }
                        Tecnotek.GroupCourseQualifications.loadGroupCourses($('#groups').val());
                    }
                },
                function(jqXHR, textStatus){
                    Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                    $(this).val("");
                }, true);
        }
    },
    loadGroupCourses: function($groupId) {
        if(($groupId!==null)){
            $('#courses').children().remove();
            $('#subentryFormParent').empty();
            Tecnotek.Qualifications.loadQualificationsOfGroup(0);
            Tecnotek.ajaxCall(Tecnotek.UI.urls["loadCoursesOfGroupURL"],
                {   groupId: $groupId.split("-")[0] },
                function(data){
                    if(data.error === true) {
                        Tecnotek.showErrorMessage(data.message,true, "", false);
                    } else {
                        $('#courses').append('<option value="0"></option>');
                        //$('#courses').append('<option value="-1">Solo Hoja</option>');
                        //$('#courses').append('<option value="0">Todo</option>');
                        for(i=0; i<data.courses.length; i++) {
                            $('#courses').append('<option value="' + data.courses[i].id + '">' + data.courses[i].name + '</option>');
                        }
                        //Tecnotek.GroupCourseQualifications.loadQualificationsOfGroupByGroup($('#courses').val());
                    }
                },
                function(jqXHR, textStatus){
                    Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                    $(this).val("");
                }, false);
        }
    },
    loadQualificationsOfGroupByCourse: function(courseId) {
        $('.editEntry').unbind();
        $('#contentBody').empty();
        $('#tableContainer').hide();
        if(courseId === null || courseId == 0){//Clean page
        } else {
            $('#fountainG').show();
            Tecnotek.GroupCourseQualifications.year = $("#year").val();
            Tecnotek.GroupCourseQualifications.groupId = $("#groups").val();
            Tecnotek.ajaxCall(Tecnotek.UI.urls["loadQualificationsOfGroupByCourseURL"],
                {   year: Tecnotek.GroupCourseQualifications.year,
                    courseId: courseId,
                    groupId: Tecnotek.GroupCourseQualifications.groupId,
                    isMepReport: Tecnotek.GroupCourseQualifications.isMepReport},
                function(data){
                    //$('#fountainG').hide();
                    if(data.error === true) {
                        Tecnotek.showErrorMessage(data.message,true, "", false);
                    } else {
                        Tecnotek.GroupCourseQualifications.completeText = '<div class="center"><h3><img width="840" height="145" src="/expediente/web/images/' + data.imgHeader + '" alt="" class="image-hover"></h3></div>'
                            + data.html + '<div class="pageBreak"> </div>';
                        Tecnotek.GroupCourseQualifications.processDataResponse("");
                    }
                },
                function(jqXHR, textStatus){
                    $('#fountainG').hide();
                    $( "#spinner-modal" ).dialog( "close" );
                    Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                }, false);
        }
    },
    loadAllStudentsQualifications: function() {
        studentId = $('#students option:eq(' + Tecnotek.GroupCourseQualifications.studentsIndex + ')').val();
        Tecnotek.GroupCourseQualifications.loadStudentQualification(studentId);
    },
    processDataResponse: function(html){
        Tecnotek.GroupCourseQualifications.completeText += html;
        Tecnotek.GroupCourseQualifications.terminateGetAllQualifications();
    },
    terminateGetAllQualifications: function(){
        //console.debug(html);
        $('#fountainG').hide();
        $('#contentBody').html(Tecnotek.GroupCourseQualifications.completeText);
        $('#tableContainer').show();
    }
};