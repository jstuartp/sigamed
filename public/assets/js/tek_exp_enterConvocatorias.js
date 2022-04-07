var Tecnotek = Tecnotek || {};

Tecnotek.EnterConvocatorias = {
    translates : {},
    completeText: "",
    studentsIndex: 0,
    courseId: 0,
    year: 0,
    groupId: 0,
    studentsLength: 0,
    init : function() {
        $("#year").change(function(event){
            event.preventDefault();
            $('#subentryFormParent').empty();
            Tecnotek.EnterConvocatorias.loadGroupsOfYear($(this).val());
        });

        $("#groups").change(function(event){
            event.preventDefault();
            Tecnotek.EnterConvocatorias.loadGroupCourses($(this).val());
        });

        $("#courses").change(function(event){
            event.preventDefault();
            Tecnotek.EnterConvocatorias.loadStudentsList($(this).val());
        });

        Tecnotek.EnterConvocatorias.loadGroupsOfYear($('#year').val());
        Tecnotek.EnterConvocatorias.initButtons();
    },
    setRowsComponentsAction: function(){
        $(".cbConvocatoria1").unbind().change(function(event){
            event.preventDefault();
            var $id = $(this).attr("rel");
            if($(this).is(":checked") ) {
                $("#nota_convocatoria_1_" + $id).removeAttr("disabled");
            } else {
                $("#nota_convocatoria_1_" + $id).attr("disabled", true);
                $("#cb_convocatoria_2_" + $id).prop('checked', false);
                $("#nota_convocatoria_2_" + $id).attr("disabled", true);
            }
        });
        $(".cbConvocatoria2").unbind().change(function(event){
            event.preventDefault();
            var $id = $(this).attr("rel");
            if($(this).is(":checked") ) {
                if($("#nota_convocatoria_1_" + $id).attr("disabled") === "disabled"){
                    $(this).prop('checked', false);
                    Tecnotek.showInfoMessage("No se puede dar la Convocatoria II sin la I",true, "", false);
                } else {
                    $("#nota_convocatoria_2_" + $id).removeAttr("disabled");
                }
            } else {
                $("#nota_convocatoria_2_" + $id).attr("disabled", true);
            }
        });

        $(".nota").unbind().blur(function(event){
            event.preventDefault();

            if($(this).val() === ""){
                $nota = -1;
            } else{
                $nota = $(this).val();
            }
            var $id = $(this).attr("rel");
            Tecnotek.EnterConvocatorias.saveStudentConvocatoria($(this).attr("rel"), Tecnotek.EnterConvocatorias.courseId , $nota, $(this).attr("num"));
        });


        //saveStudentConvocatoria: function($stdYear, $courseId, $nota, $number) {
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
            $('#studentsRows').empty();
            $('#tableContainer').hide();
            Tecnotek.ajaxCall(Tecnotek.UI.urls["loadGroupsOfYearURL"],
                { year: $year },
                function(data){
                    if(data.error === true) {
                        Tecnotek.showErrorMessage(data.message,true, "", false);
                    } else {
                        for(i=0; i<data.groups.length; i++) {
                            $('#groups').append('<option value="' + data.groups[i].id + '">' + data.groups[i].name + '</option>');
                        }
                        Tecnotek.EnterConvocatorias.loadGroupCourses($('#groups').val());
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
            $('#studentsRows').empty();
            Tecnotek.Qualifications.loadQualificationsOfGroup(0);
            Tecnotek.ajaxCall(Tecnotek.UI.urls["loadCoursesOfGroupURL"],
                {   groupId: $groupId.split("-")[0] },
                function(data){
                    if(data.error === true) {
                        Tecnotek.showErrorMessage(data.message,true, "", false);
                    } else {
                        $('#courses').append('<option value="0"></option>');
                        for(i=0; i<data.courses.length; i++) {
                            $('#courses').append('<option value="' + data.courses[i].id + '">' + data.courses[i].name + '</option>');
                        }
                    }
                },
                function(jqXHR, textStatus){
                    Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                    $(this).val("");
                }, false);
        }
    },
    loadStudentsList: function(courseId) {
        $('.editEntry').unbind();
        $('#contentBody').empty();
        $('#tableContainer').hide();
        if(courseId === null || courseId == 0){//Clean page
        } else {
            $('#studentsRows').empty();
            $('#fountainG').show();
            Tecnotek.EnterConvocatorias.courseId = courseId;
            Tecnotek.EnterConvocatorias.year = $("#year").val();
            Tecnotek.EnterConvocatorias.groupId = $("#groups").val();
            Tecnotek.ajaxCall(Tecnotek.UI.urls["getStudentsURL"],
                {   year: Tecnotek.EnterConvocatorias.year,
                    courseId: courseId,
                    groupId: Tecnotek.EnterConvocatorias.groupId},
                function(data){
                    //$('#fountainG').hide();
                    if(data.error === true) {
                        Tecnotek.showErrorMessage(data.message,true, "", false);
                    } else {
                        var html = "";
                        var Re = new RegExp("STDYID","g");
                        for(i=0; i<data.students.length; i++) {
                            html = $("#stdRow_STDYID").clone().attr("id","stdRow_" + data.students[i].id).attr("name","stdRow_" + data.students[i].id).css("display", "block").wrap('<p>').parent().html();
                            html = html.replace(Re, data.students[i].id).replace("STDNAME", data.students[i].name);
                            if( data.students[i].nota1 == null){
                                html = html.replace("statusnota1", 'value="" disabled="disabled"').replace("statuscbnota1", "");
                            } else {
                                html = html.replace("statusnota1",'value="' + data.students[i].nota1 + '"').replace("statuscbnota1", ' checked="checked"');
                            }
                            if( data.students[i].nota2 == null){
                                html = html.replace("statusnota2", 'value="" disabled="disabled"').replace("statuscbnota2", "");
                            } else {
                                html = html.replace("statusnota2",'value="' + data.students[i].nota2 + '"').replace("statuscbnota2", ' checked="checked"');
                            }
                            //if(i%2==0) html = html.replace("tableRowOdd", "");
                            $('#studentsRows').append(html);
                        }
                        Tecnotek.EnterConvocatorias.setRowsComponentsAction();
                    }
                },
                function(jqXHR, textStatus){
                    $('#fountainG').hide();
                    $( "#spinner-modal" ).dialog( "close" );
                    Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                }, false);
        }
    },
    saveStudentConvocatoria: function($stdYear, $courseId, $nota, $number) {
        Tecnotek.ajaxCall(Tecnotek.UI.urls["saveNotaConvotariaURL"],
            {   stdYear: $stdYear,
                course: $courseId,
                nota: $nota,
                number: $number
            },
            function(data){
                if(data.error === true) {
                    Tecnotek.showErrorMessage(data.message,true, "", false);
                } else {
                }
            },
            function(jqXHR, textStatus){
                $('#fountainG').hide();
                $( "#spinner-modal" ).dialog( "close" );
                Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
            }, false);
    }
};