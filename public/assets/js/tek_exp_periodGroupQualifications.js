var Tecnotek = Tecnotek || {};

Tecnotek.PeriodGroupQualifications = {
    translates : {},
    completeText: "",
    studentsIndex: 0,
    periodId: 0,
    groupId: 0,
    studentsLength: 0,
    init : function() {
        $("#period").change(function(event){
            event.preventDefault();
            $('#subentryFormParent').empty();
            Tecnotek.PeriodGroupQualifications.loadGroupsOfPeriod($(this).val());
        });

        $("#groups").change(function(event){
            event.preventDefault();
            Tecnotek.PeriodGroupQualifications.loadGroupStudents($(this).val());
        });

        $("#students").change(function(event){
            event.preventDefault();
            Tecnotek.PeriodGroupQualifications.loadQualificationsOfGroup($(this).val());
        });

        Tecnotek.PeriodGroupQualifications.loadGroupsOfPeriod($('#period').val());
        Tecnotek.PeriodGroupQualifications.initButtons();
    },
    initButtons : function() {
        $('#btnPrint').click(function(event){
            $("#tableContainer").printElement({printMode:'popup', pageTitle:$(this).attr('rel')});
        });
    },
    loadGroupsOfPeriod: function($periodId) {
        //alert($('#period').attr("convo").val());
        $valVar = $('#period option:selected').attr("convo");
        alert($valVar);
        if($valVar == 1){
            $('input[name=conv]').val(1);
        }else{
            if($valVar == 2){
                $('input[name=conv]').val(2);
            }else{
                $('input[name=conv]').val(0);
            }
        }
        /*
        if( $('#period option:selected').html() == "CONVI 2013"){
            $('input[name=conv]').val(1);
        }else{
            if( $('#period option:selected').html() == "CONVII 2013"){
                $('input[name=conv]').val(2);
            }else{
                if( $('#period option:selected').html() == "CONVI 2014"){
                $('input[name=conv]').val(1);
                }else{
                   if( $('#period option:selected').html() == "CONVII 2014"){
                     $('input[name=conv]').val(2);
                   }else{
                     if( $('#period option:selected').html() == "CONVI 2015"){
                         $('input[name=conv]').val(1);
                     }else{
                        if( $('#period option:selected').html() == "CONVII 2015"){
                          $('input[name=conv]').val(2);
                        }else{
                         $('input[name=conv]').val(0);
                        }
                     }
                   }
                }
            }
        }*/
        console.debug("Load groups of period: " + $periodId);
        if(($periodId!==null)){
            $('#groups').children().remove();
            $('#students').children().remove();
            $('#subentryFormParent').empty();
            $('#tableContainer').hide();
            Tecnotek.ajaxCall(Tecnotek.UI.urls["loadGroupsOfPeriodURL"],
                {   periodId: $periodId },
                function(data){
                    if(data.error === true) {
                        Tecnotek.showErrorMessage(data.message,true, "", false);
                    } else {
                        for(i=0; i<data.groups.length; i++) {
                            $('#groups').append('<option value="' + data.groups[i].id + '">' + data.groups[i].name + '</option>');
                        }
                        Tecnotek.PeriodGroupQualifications.loadGroupStudents($('#groups').val());
                    }
                },
                function(jqXHR, textStatus){
                    Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                    $(this).val("");
                }, true);
        }
    },
    loadGroupStudents: function($groupId) {
        if(($groupId!==null)){
            $('#students').children().remove();
            $('#subentryFormParent').empty();
            Tecnotek.Qualifications.loadQualificationsOfGroup(0);
            Tecnotek.ajaxCall(Tecnotek.UI.urls["loadStudentsGroupURL"],
                {   groupId: $groupId.split("-")[0] },
                function(data){
                    if(data.error === true) {
                        Tecnotek.showErrorMessage(data.message,true, "", false);
                    } else {
                        $('#students').append('<option value="-2"></option>');
                        $('#students').append('<option value="-1">Solo Hoja</option>');
                        $('#students').append('<option value="0">Todo</option>');
                        for(i=0; i<data.students.length; i++) {
                            $('#students').append('<option value="' + data.students[i].id + '">' + data.students[i].lastname + ", " + data.students[i].firstname + '</option>');
                        }
                        Tecnotek.PeriodGroupQualifications.loadQualificationsOfGroup($('#students').val());
                    }
                },
                function(jqXHR, textStatus){
                    Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                    $(this).val("");
                }, false);
        }
    },
    loadQualificationsOfGroup: function(studentId) {
        $('.editEntry').unbind();
        $('#contentBody').empty();
        $('#tableContainer').hide();
        if(studentId === null || studentId == -2){//Clean page
        } else { if(studentId == -1){

            $('#fountainG').show();
            Tecnotek.PeriodGroupQualifications.periodId = $("#period").val();
            Tecnotek.PeriodGroupQualifications.groupId = $("#groups").val();

            studentId = 0;
            Tecnotek.PeriodGroupQualifications.completeText = "";
            Tecnotek.PeriodGroupQualifications.studentsIndex = $('#students option').length;
            Tecnotek.PeriodGroupQualifications.studentsLength = Tecnotek.PeriodGroupQualifications.studentsIndex;
            Tecnotek.PeriodGroupQualifications.loadStudentQualification(studentId);


        }else {
            $('#fountainG').show();
            Tecnotek.PeriodGroupQualifications.periodId = $("#period").val();
            Tecnotek.PeriodGroupQualifications.groupId = $("#groups").val();
            if(studentId != 0){//Single student

                Tecnotek.PeriodGroupQualifications.completeText = "";
                Tecnotek.PeriodGroupQualifications.studentsIndex = $('#students option').length;
                Tecnotek.PeriodGroupQualifications.studentsLength = Tecnotek.PeriodGroupQualifications.studentsIndex;
                Tecnotek.PeriodGroupQualifications.loadStudentQualification(studentId);

            } else {//All Students
                Tecnotek.ajaxCall(Tecnotek.UI.urls["loadQualificationsOfGroupURL"],
                    {   periodId: Tecnotek.PeriodGroupQualifications.periodId,
                        referenceId: studentId,
                        groupId: Tecnotek.PeriodGroupQualifications.groupId,
                        conv: $("#conv").val()},
                    function(data){
                        //$('#fountainG').hide();
                        if(data.error === true) {
                            Tecnotek.showErrorMessage(data.message,true, "", false);
                        } else {
                            Tecnotek.PeriodGroupQualifications.completeText = '<div class="center"><h3><img width="840" height="145" src="/expediente/web/images/' + data.imgHeader + '" alt="" class="image-hover"></h3></div>'
                                + data.html + '<div class="pageBreak"> </div>';
                            Tecnotek.PeriodGroupQualifications.studentsIndex = 2;
                            Tecnotek.PeriodGroupQualifications.studentsLength = $('#students option').length;
                            Tecnotek.PeriodGroupQualifications.processStudentResponse("");
                            //$('#contentHeader').html(tableHeader);
                            //$('#contentBody').html(tableHeader + data.html);
                            //$('#tableContainer').show();
                        }
                    },
                    function(jqXHR, textStatus){
                        $('#fountainG').hide();
                        $( "#spinner-modal" ).dialog( "close" );
                        Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                    }, false);
            }
        }
        }
    },
    loadAllStudentsQualifications: function() {
        studentId = $('#students option:eq(' + Tecnotek.PeriodGroupQualifications.studentsIndex + ')').val();
        Tecnotek.PeriodGroupQualifications.loadStudentQualification(studentId);
    },
    loadStudentQualification: function(studentId) {
        var studentHtml = "";

        Tecnotek.ajaxCall(Tecnotek.UI.urls["loadQualificationsOfGroupURL"],
            {   periodId: Tecnotek.PeriodGroupQualifications.periodId,
                referenceId: studentId,
                groupId: Tecnotek.PeriodGroupQualifications.groupId,
                conv: $("#conv").val()},
            function(data){
                //$('#fountainG').hide();
                if(data.error === true) {
                    Tecnotek.showErrorMessage(data.message,true, "", false);
                } else {
                    $period = $("#period").find(":selected").text();
                    $periodYear = $period.split("-")[1];
if(data.kinder1 == '1'){
                    studentHtml += '<div class="center"><h3><img width="840" height="245" src="/expediente/web/images/notaskinder.png" alt="" class="image-hover"></h3></div>';
}else{
                    studentHtml += '<div class="center"><h3><img width="840" height="145" src="/expediente/web/images/' + data.imgHeader + '" alt="" class="image-hover"></h3></div>';}

                    studentHtml += '<div class="reportContentHeader">';
if(data.kinder1 != '1'){
                    studentHtml += '<div class="left reportContentLabel" style="width: 100%; font-size: 18px; text-align: center;">TARJETA DE CALIFICACIONES</div>';}
                    studentHtml += '<div class="left reportContentLabel" style="width: 100%; font-size: 14px; text-align: center; margin-bottom: 15px;"> </div>';
                    studentHtml += '<div class="left reportContentLabel" style="width: 450px;">Alumno(a):&nbsp;&nbsp;' + data.studentName  + '</div>';
                    studentHtml += '<div class="left reportContentLabel" style="width: 350px;">Secci&oacute;n:&nbsp;&nbsp;' + $("#groups").find(":selected").text() + '</div>';
                    studentHtml += '<div class="clear"></div>';

                    studentHtml += '<div class="left reportContentLabel" style="width: 450px;">Carn&eacute;:&nbsp;&nbsp;' + data.carne  + '</div>';
                    studentHtml += '<div class="left reportContentLabel" style="width: 350px;">Trimestre:&nbsp;&nbsp;' + $period + '</div>';
                    studentHtml += '<div class="clear"></div>';

                    studentHtml += '<div class="left reportContentLabel" style="width: 450px;">&nbsp;&nbsp;</div>';
                    studentHtml += '<div class="left reportContentLabel" style="width: 350px;">Profesor:&nbsp;&nbsp;' + data.teacherGroup + '</div>';
                    studentHtml += '<div class="clear"></div>';
                    //studentHtml += '<div class="left reportContentLabel">Grado y Grupo:</div><div class="left reportContentText">' + $("#groups").find(":selected").text() + '</div><div class="clear"></div>';
                    // studentHtml += '<div class="left reportContentLabel">Estudiante:</div><div class="left reportContentText">' + $("#students").find(":selected").text() + '</div><div class="clear"></div>';
                    studentHtml += "</div>";
                    studentHtml += data.html  + '<div class="pageBreak"> </div>';

                    Tecnotek.PeriodGroupQualifications.processStudentResponse(studentHtml);
                    //$('#contentBody').html(data.html);
                    //$('#tableContainer').show();
                }
            },
            function(jqXHR, textStatus){
                $('#fountainG').hide();
                $( "#spinner-modal" ).dialog( "close" );
                Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
            }, false);

    },
    processStudentResponse: function(html){

        Tecnotek.PeriodGroupQualifications.completeText += html;
        Tecnotek.PeriodGroupQualifications.studentsIndex++;
        console.debug(Tecnotek.PeriodGroupQualifications.studentsIndex + " :: " + Tecnotek.PeriodGroupQualifications.studentsLength);
        if(Tecnotek.PeriodGroupQualifications.studentsIndex < Tecnotek.PeriodGroupQualifications.studentsLength){
            var studentId = $('#students option:eq(' + Tecnotek.PeriodGroupQualifications.studentsIndex + ')').val();
            console.debug("get student: " + studentId);
            Tecnotek.PeriodGroupQualifications.loadStudentQualification(studentId);
        } else {
            Tecnotek.PeriodGroupQualifications.terminateGetAllQualifications();
        }
    },
    terminateGetAllQualifications: function(){
        //console.debug(html);
        $('#fountainG').hide();
        $('#contentBody').html(Tecnotek.PeriodGroupQualifications.completeText);
        $('#tableContainer').show();
    }
};

Tecnotek.PeriodGroupAverages = {
    translates : {},
    completeText: "",
    studentsIndex: 0,
    periodId: 0,
    groupId: 0,
    studentsLength: 0,
    init : function() {
        $("#period").change(function(event){
            event.preventDefault();
            $('#subentryFormParent').empty();
            Tecnotek.PeriodGroupAverages.loadPeriodLevels($(this).val());
        });

        $("#levels").change(function(event){
            event.preventDefault();
            //: function($periodId, $levelId)
            Tecnotek.PeriodGroupAverages.loadGroupsOfPeriodAndLevel($('#period').val(), $(this).val());
        });

        $("#groups").change(function(event){
            event.preventDefault();
            Tecnotek.PeriodGroupAverages.loadAveragesOfGroup($('#period').val(), $('#group').val());
        });

        Tecnotek.PeriodGroupAverages.loadPeriodLevels($('#period').val());
        Tecnotek.PeriodGroupAverages.initButtons();
    },
    initButtons : function() {
        $('#btnPrint').click(function(event){
            $("#tableContainer").printElement({printMode:'popup', pageTitle:$(this).attr('rel')});
        });
    },
    loadPeriodLevels: function($periodId){
        if(($periodId!==null)){
            $('#levels').children().remove();
            $('#groups').children().remove();
            Tecnotek.ajaxCall(Tecnotek.UI.urls["loadLevelsOfPeriodURL"],
                {   periodId: $periodId },
                function(data){
                    if(data.error === true) {
                        Tecnotek.showErrorMessage(data.message,true, "", false);
                    } else {
                        $('#levels').append('<option value="0">Todos</option>');
                        for(i=0; i<data.levels.length; i++) {
                            $('#levels').append('<option value="' + data.levels[i].id + '">' + data.levels[i].name + '</option>');
                        }
                        Tecnotek.PeriodGroupAverages.loadGroupsOfPeriodAndLevel($('#period').val(), $('#levels').val());
                    }
                },
                function(jqXHR, textStatus){
                    Tecnotek.showErrorMessage("Error getting data : " + textStatus + ".", true, "", false);
                    $(this).val("");
                }, true);
        }
    },
    loadGroupsOfPeriod: function($periodId) {
        console.debug("Load groups of period: " + $periodId);
        if(($periodId!==null)){
            $('#groups').children().remove();
            $('#students').children().remove();
            $('#subentryFormParent').empty();
            $('#tableContainer').hide();
            Tecnotek.ajaxCall(Tecnotek.UI.urls["loadGroupsOfPeriodURL"],
                {   periodId: $periodId },
                function(data){
                    if(data.error === true) {
                        Tecnotek.showErrorMessage(data.message,true, "", false);
                    } else {
                        for(i=0; i<data.groups.length; i++) {
                            $('#groups').append('<option value="' + data.groups[i].id + '">' + data.groups[i].name + '</option>');
                        }
                        //Tecnotek.PeriodGroupAverages.loadGroupStudents($('#groups').val());
                    }
                },
                function(jqXHR, textStatus){
                    Tecnotek.showErrorMessage("Error getting data adas: " + textStatus + ".", true, "", false);
                    $(this).val("");
                }, true);
        }
    },
    loadGroupsOfPeriodAndLevel: function($periodId, $levelId) {
        if(($periodId!==null)){
            $('#groups').children().remove();
            Tecnotek.ajaxCall(Tecnotek.UI.urls["loadGroupsOfPeriodAndLevelsURL"],
                {   periodId:   $periodId,
                    levelId:    $levelId},
                function(data){
                    if(data.error === true) {
                        Tecnotek.showErrorMessage(data.message,true, "", false);
                    } else {
                        $('#groups').append('<option value="0-0">Todos</option>');
                        for(i=0; i<data.groups.length; i++) {
                            $('#groups').append('<option value="' + data.groups[i].id + '">' + data.groups[i].name + '</option>');
                        }
                    }
                },
                function(jqXHR, textStatus){
                    Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                    $(this).val("");
                }, true);
        }
    },
    loadAveragesOfGroup: function(periodId, groupId) {
        $('.editEntry').unbind();
        $('#contentBody').empty();
        $('#tableContainer').hide();
        if(periodId === null || groupId == 0){//Clean page
        } else {
            $('#fountainG').show();

            Tecnotek.ajaxCall(Tecnotek.UI.urls["loadAveragesOfGroupURL"],
                {   periodId:   $('#period').val(),
                    levelId:    $('#levels').val(),
                    groupId:    $('#groups').val()},
                function(data){
                    console.debug("Load groups of period: " + data.periodId);
                    $('#fountainG').hide();
                    if(data.error === true) {
                        Tecnotek.showErrorMessage(data.message,true, "", false);
                    } else {
                        $temp = '<div class="center"><h3>Promedios de la sección ' +data.entity[0].grade + '-' +data.entity[0].group + '</h3></div>';
                        $temp += "<table><tr>";
                        $temp += '<td style="height:50px;"><span style="font-family:arial;font-size:16px;">Carnet</span></td>';
                        $temp += '<td style="height:50px;"><span style="font-family:arial;font-size:16px;">Nombre</span></td>';
                        $temp += '<td style="height:50px;"><span style="font-family:arial;font-size:16px;">Promedio</span></td>';
                        $temp += '<td style="height:50px;"><span style="font-family:arial;font-size:16px;">Cuadro de Honor</span></td>';
                        $temp += "</tr>";
                        for(i=0; i<data.entity.length; i++) {
                            //Tecnotek.PeriodGroupAverages.completeText += '<div>' +data.entity[i].name + '-' + data.entity[i].average +'</div>';
                            $temp += "<tr>";
                            $temp += '<td style="height:20px;"><span style="font-family:arial;font-size:16px;">'+data.entity[i].carne  + '</span></td>';
                            $temp += '<td style="height:20px;"><span style="font-family:arial;font-size:16px;">'+data.entity[i].name  + '</span></td>';
                            $temp += '<td style="height:20px;"><span style="font-family:arial;font-size:16px;">'+data.entity[i].average  + '</span></td>';
                            if(data.entity[i].honor == '0'){
                                $temp += '<td align="center"><span style="font-family:arial;font-size:16px;">No</span></td>';
                            }else {
                                $temp += '<td align="center"><span style="font-family:arial;font-size:16px;">Si</span></td>';
                            }
                            $temp += "</tr>";
                        }
                        $temp += "</table>";
                        $('#contentBody').html($temp);
                        $('#fountainG').hide();
                        $('#tableContainer').show();
                    }
                },
                function(jqXHR, textStatus){
                    $('#fountainG').hide();
                    $( "#spinner-modal" ).dialog( "close" );
                    Tecnotek.showErrorMessage("Error getting data svs: " + textStatus + ".", true, "", false);
                }, false);
        }
    }
};


Tecnotek.PeriodGroupObservations = {
    translates : {},
    completeText: "",
    studentsIndex: 0,
    periodId: 0,
    groupId: 0,
    studentsLength: 0,
    init : function() {
        $("#period").change(function(event){
            event.preventDefault();
            $('#subentryFormParent').empty();
            Tecnotek.PeriodGroupObservations.loadGroupsOfPeriod($(this).val());
        });

        $("#groups").change(function(event){
            event.preventDefault();
            Tecnotek.PeriodGroupObservations.loadGroupStudents($(this).val());
        });

        $("#students").change(function(event){
            event.preventDefault();
            Tecnotek.PeriodGroupObservations.loadQualificationsOfGroup($(this).val());
        });

        Tecnotek.PeriodGroupObservations.loadGroupsOfPeriod($('#period').val());
        Tecnotek.PeriodGroupObservations.initButtons();
    },
    initButtons : function() {
        $('#btnPrint').click(function(event){
            $("#tableContainer").printElement({printMode:'popup', pageTitle:$(this).attr('rel')});
        });
    },
    loadGroupsOfPeriod: function($periodId) {
        if( $('#period option:selected').html() == "CONVI"){
            $('input[name=conv]').val(1);
        }else{
            if( $('#period option:selected').html() == "CONVII"){
                $('input[name=conv]').val(2);
            }else{
                $('input[name=conv]').val(0);
            }
        }
        console.debug("Load groups of period: " + $periodId);
        if(($periodId!==null)){
            $('#groups').children().remove();
            $('#students').children().remove();
            $('#subentryFormParent').empty();
            $('#tableContainer').hide();
            Tecnotek.ajaxCall(Tecnotek.UI.urls["loadGroupsOfPeriodURL"],
                {   periodId: $periodId },
                function(data){
                    if(data.error === true) {
                        Tecnotek.showErrorMessage(data.message,true, "", false);
                    } else {
                        for(i=0; i<data.groups.length; i++) {
                            $('#groups').append('<option value="' + data.groups[i].id + '">' + data.groups[i].name + '</option>');
                        }
                        Tecnotek.PeriodGroupObservations.loadGroupStudents($('#groups').val());
                    }
                },
                function(jqXHR, textStatus){
                    Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                    $(this).val("");
                }, true);
        }
    },
    loadGroupStudents: function($groupId) {
        if(($groupId!==null)){
            $('#students').children().remove();
            $('#subentryFormParent').empty();
            //Tecnotek.Qualifications.loadQualificationsOfGroup(0);
            Tecnotek.ajaxCall(Tecnotek.UI.urls["loadStudentsGroupURL"],
                {   groupId: $groupId.split("-")[0] },
                function(data){
                    if(data.error === true) {
                        Tecnotek.showErrorMessage(data.message,true, "", false);
                    } else {
                        $('#students').append('<option value="-2"></option>');
                        $('#students').append('<option value="-1">Solo Hoja</option>');
                        $('#students').append('<option value="0">Todo</option>');
                        for(i=0; i<data.students.length; i++) {
                            $('#students').append('<option value="' + data.students[i].id + '">' + data.students[i].lastname + ", " + data.students[i].firstname + '</option>');
                        }
                        Tecnotek.PeriodGroupObservations.loadQualificationsOfGroup($('#students').val());
                    }
                },
                function(jqXHR, textStatus){
                    Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                    $(this).val("");
                }, false);
        }
    },
    loadQualificationsOfGroup: function(studentId) {
        $('.editEntry').unbind();
        $('#contentBody').empty();
        $('#tableContainer').hide();
        if(studentId === null || studentId == -2){//Clean page
        } else {
            $('#fountainG').show();
            Tecnotek.PeriodGroupObservations.periodId = $("#period").val();
            Tecnotek.PeriodGroupObservations.groupId = $("#groups").val();
            if(studentId != 0){//Single student

                Tecnotek.PeriodGroupObservations.completeText = "";
                Tecnotek.PeriodGroupObservations.studentsIndex = $('#students option').length;
                Tecnotek.PeriodGroupObservations.studentsLength = Tecnotek.PeriodGroupObservations.studentsIndex;
                Tecnotek.PeriodGroupObservations.loadStudentQualification(studentId);

            } else {//All Students
                Tecnotek.ajaxCall(Tecnotek.UI.urls["loadObservationsOfGroupURL"],
                    {   periodId: Tecnotek.PeriodGroupObservations.periodId,
                        referenceId: studentId,
                        groupId: Tecnotek.PeriodGroupObservations.groupId,
                        conv: $("#conv").val()},
                    function(data){
                        //$('#fountainG').hide();
                        if(data.error === true) {
                            Tecnotek.showErrorMessage(data.message,true, "", false);
                        } else {
                            Tecnotek.PeriodGroupObservations.completeText = '<div class="center"><h3><img width="840" height="145" src="/expediente/web/images/' + data.imgHeader + '" alt="" class="image-hover"></h3></div>'
                                + data.html + '<div class="pageBreak"> </div>';
                            Tecnotek.PeriodGroupObservations.studentsIndex = 2;
                            Tecnotek.PeriodGroupObservations.studentsLength = $('#students option').length;
                            Tecnotek.PeriodGroupObservations.processStudentResponse("");
                            //$('#contentHeader').html(tableHeader);
                            //$('#contentBody').html(tableHeader + data.html);
                            //$('#tableContainer').show();
                        }
                    },
                    function(jqXHR, textStatus){
                        $('#fountainG').hide();
                        $( "#spinner-modal" ).dialog( "close" );
                        Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                    }, false);
            }

        }
    },
    loadAllStudentsQualifications: function() {
        studentId = $('#students option:eq(' + Tecnotek.PeriodGroupObservations.studentsIndex + ')').val();
        Tecnotek.PeriodGroupObservations.loadStudentQualification(studentId);
    },
    loadStudentQualification: function(studentId) {
        var studentHtml = "";

        Tecnotek.ajaxCall(Tecnotek.UI.urls["loadObservationsOfGroupURL"],
            {   periodId: Tecnotek.PeriodGroupObservations.periodId,
                referenceId: studentId,
                groupId: Tecnotek.PeriodGroupObservations.groupId,
                conv: $("#conv").val()},
            function(data){
                //$('#fountainG').hide();
                if(data.error === true) {
                    Tecnotek.showErrorMessage(data.message,true, "", false);
                } else {
                    $period = $("#period").find(":selected").text();
                    $periodYear = $period.split("-")[1];
                    studentHtml += '<div class="center"><h3><img width="840" height="145" src="/expediente/web/images/' + data.imgHeader + '" alt="" class="image-hover"></h3></div>';

                    studentHtml += '<div class="reportContentHeader">';
                    studentHtml += '<div class="left reportContentLabel" style="width: 100%; font-size: 18px; text-align: center;">DEPARTAMENTO DE PSICOLOGIA Y ORIENTACION</div>';
                    studentHtml += '<div class="left reportContentLabel" style="width: 100%; font-size: 14px; text-align: center; margin-bottom: 15px;">&nbsp;</div>';
                    studentHtml += '<div class="left reportContentLabel" style="width: 550px;">Nombre del estudiante:&nbsp;&nbsp;' + data.studentName  + '</div>';
                    studentHtml += '<div class="left reportContentLabel" style="width: 250px;">Secci&oacute;n:&nbsp;&nbsp;' + $("#groups").find(":selected").text() + '</div>';
                    studentHtml += '<div class="clear"></div>';

                    studentHtml += '<div class="left reportContentLabel" style="width: 550px;">Carn&eacute;:&nbsp;&nbsp;' + data.carne  + '</div>';
                    studentHtml += '<div class="left reportContentLabel" style="width: 250px;">Trimestre:&nbsp;&nbsp;' + $period + '</div>';
                    studentHtml += '<div class="clear"></div>';

                    studentHtml += '<div class="text-justify" style="width: 800px;">Instrucciones:  Manifestaciones de la conducta del estudiante observadas por los distintos profesores dentro del ambiente escolar. Cada profesor puede indicar m&aacute;s de una manifestaci&oacute;n en su respectiva materia. Cualquier duda consultar en horario de atenci&oacute;n de cada profesor.</div>';
                    studentHtml += '<div class="clear"></div>';


                    studentHtml += "</div>";
                    studentHtml += data.html  + '<div class="pageBreak"> </div>';

                    Tecnotek.PeriodGroupObservations.processStudentResponse(studentHtml);
                    //$('#contentBody').html(data.html);
                    //$('#tableContainer').show();
                }
            },
            function(jqXHR, textStatus){
                $('#fountainG').hide();
                $( "#spinner-modal" ).dialog( "close" );
                Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
            }, false);

    },
    processStudentResponse: function(html){

        Tecnotek.PeriodGroupObservations.completeText += html;
        Tecnotek.PeriodGroupObservations.studentsIndex++;
        console.debug(Tecnotek.PeriodGroupObservations.studentsIndex + " :: " + Tecnotek.PeriodGroupObservations.studentsLength);
        if(Tecnotek.PeriodGroupObservations.studentsIndex < Tecnotek.PeriodGroupObservations.studentsLength){
            var studentId = $('#students option:eq(' + Tecnotek.PeriodGroupObservations.studentsIndex + ')').val();
            console.debug("get student: " + studentId);
            Tecnotek.PeriodGroupObservations.loadStudentQualification(studentId);
        } else {
            Tecnotek.PeriodGroupObservations.terminateGetAllQualifications();
        }
    },
    terminateGetAllQualifications: function(){
        //console.debug(html);
        $('#fountainG').hide();
        $('#contentBody').html(Tecnotek.PeriodGroupObservations.completeText);
        $('#tableContainer').show();
    }
};