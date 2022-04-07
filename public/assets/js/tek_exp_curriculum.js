var Tecnotek = Tecnotek || {};

Tecnotek.Curriculum = {
    init : function() {

        Tecnotek.UI.vars["fromEdit"] = false;
        Tecnotek.Curriculum.loadDegreesByTeacher();
        Tecnotek.Curriculum.loadPublicationsByTeacher();
        Tecnotek.Curriculum.loadWexperiencesByTeacher();
        Tecnotek.Curriculum.loadInvestigationsByTeacher();
        Tecnotek.Curriculum.loadImprovesByTeacher();
        Tecnotek.Curriculum.loadAexperiencesByTeacher();
        Tecnotek.Curriculum.loadOexperiencesByTeacher();
        Tecnotek.Curriculum.loadApresentationsByTeacher();
        //Tecnotek.Curriculum.loadWexperiencesFullByTeacher();
        //Tecnotek.Curriculum.loadApresentationsFullByTeacher();
        //Tecnotek.Curriculum.loadInvestigationsFullByTeacher();
        //Tecnotek.Curriculum.loadImprovesFullByTeacher();
        //Tecnotek.Curriculum.loadPublicationsFullByTeacher();
        //Tecnotek.Curriculum.loadDegreesFullByTeacher();

        Tecnotek.Curriculum.loadCurriculumdata();

        $('#degreeTab').click(function(event){
            $('#degreeSection').show();
            $('#publicationSection').hide();
            $('#apresentationSection').hide();
            $('#wexperienceSection').hide();
            $('#investigationSection').hide();
            $('#improveSection').hide();
            $('#publicationTab').removeClass("tab-current");
            $('#wexperienceTab').removeClass("tab-current");
            $('#degreeTab').addClass("tab-current");
            $('#investigationTab').removeClass("tab-current");
            $('#improveTab').removeClass("tab-current");
            $('#apresentationTab').removeClass("tab-current");
        });
        $('#publicationTab').click(function(event){
            $('#degreeSection').hide();
            $('#publicationSection').show();
            $('#apresentationSection').hide();
            $('#wexperienceSection').hide();
            $('#investigationSection').hide();
            $('#improveSection').hide();
            $('#publicationTab').addClass("tab-current");
            $('#wexperienceTab').removeClass("tab-current");
            $('#degreeTab').removeClass("tab-current");
            $('#investigationTab').removeClass("tab-current");
            $('#improveTab').removeClass("tab-current");
            $('#apresentationTab').removeClass("tab-current");
        });
        $('#apresentationTab').click(function(event){
            $('#degreeSection').hide();
            $('#publicationSection').hide();
            $('#apresentationSection').show();
            $('#wexperienceSection').hide();
            $('#investigationSection').hide();
            $('#improveSection').hide();
            $('#publicationTab').removeClass("tab-current");
            $('#wexperienceTab').removeClass("tab-current");
            $('#degreeTab').removeClass("tab-current");
            $('#investigationTab').removeClass("tab-current");
            $('#improveTab').removeClass("tab-current");
            $('#apresentationTab').addClass("tab-current");
        });
        $('#wexperienceTab').click(function(event){
            $('#degreeSection').hide();
            $('#publicationSection').hide();
            $('#apresentationSection').hide();
            $('#wexperienceSection').show();
            $('#investigationSection').hide();
            $('#improveSection').hide();
            $('#publicationTab').removeClass("tab-current");
            $('#wexperienceTab').addClass("tab-current");
            $('#degreeTab').removeClass("tab-current");
            $('#investigationTab').removeClass("tab-current");
            $('#improveTab').removeClass("tab-current");
            $('#apresentationTab').removeClass("tab-current");
        });
        $('#investigationTab').click(function(event){
            $('#degreeSection').hide();
            $('#publicationSection').hide();
            $('#apresentationSection').hide();
            $('#wexperienceSection').hide();
            $('#investigationSection').show();
            $('#improveSection').hide();
            $('#publicationTab').removeClass("tab-current");
            $('#wexperienceTab').removeClass("tab-current");
            $('#degreeTab').removeClass("tab-current");
            $('#investigationTab').addClass("tab-current");
            $('#improveTab').removeClass("tab-current");
            $('#apresentationTab').removeClass("tab-current");
        });
        $('#improveTab').click(function(event){
            $('#degreeSection').hide();
            $('#publicationSection').hide();
            $('#apresentationSection').hide();
            $('#wexperienceSection').hide();
            $('#improveSection').show();
            $('#investigationSection').hide();
            $('#publicationTab').removeClass("tab-current");
            $('#wexperienceTab').removeClass("tab-current");
            $('#degreeTab').removeClass("tab-current");
            $('#investigationTab').removeClass("tab-current");
            $('#improveTab').addClass("tab-current");
            $('#apresentationTab').removeClass("tab-current");
        });
        $('#degreeForm').submit(function(event){
            event.preventDefault();
            Tecnotek.Curriculum.createDegree();
        });
        $('#degreeFormEdit').submit(function(event){
            event.preventDefault();
            Tecnotek.Curriculum.updateDegree();
        });
        $('#wexperienceForm').submit(function(event){
            event.preventDefault();
            Tecnotek.Curriculum.createWexperience();
        });
        $('#aexperienceForm').submit(function(event){
            event.preventDefault();
            Tecnotek.Curriculum.createAexperience();
        });
        $('#oexperienceForm').submit(function(event){
            event.preventDefault();
            Tecnotek.Curriculum.createOexperience();
        });
        $('#publicationForm').submit(function(event){
            event.preventDefault();
            Tecnotek.Curriculum.createPublication();
        });
        $('#apresentationForm').submit(function(event){
            event.preventDefault();
            Tecnotek.Curriculum.createApresentation();
        });
        $('#investigationForm').submit(function(event){
            event.preventDefault();
            Tecnotek.Curriculum.createInvestigation();
        });
        $('#improveForm').submit(function(event){
            event.preventDefault();
            Tecnotek.Curriculum.createImprove();
        });
        $("#openDegreeForm").fancybox({
            'beforeLoad' : function(){
                /*if(Tecnotek.UI.vars["fromEdit"] === false){
                 Tecnotek.UI.vars["entryId"]  = 0;
                 $("#entryFormName").val("");
                 $("#entryFormCode").val("");
                 $("#entryFormPercentage").val("");
                 $("#entryFormMaxValue").val("");
                 $("#entryFormSortOrder").val("");
                 }*/
                //Tecnotek.UI.vars["fromEdit"] = false;
                //Tecnotek.AdminPeriod.preloadEntryForm();
            }
        });

        $("#openWexperienceForm").fancybox({
            'beforeLoad' : function(){
                /*if(Tecnotek.UI.vars["fromEdit"] === false){
                 Tecnotek.UI.vars["entryId"]  = 0;
                 $("#entryFormName").val("");
                 $("#entryFormCode").val("");
                 $("#entryFormPercentage").val("");
                 $("#entryFormMaxValue").val("");
                 $("#entryFormSortOrder").val("");
                 }*/
                //Tecnotek.UI.vars["fromEdit"] = false;
                //Tecnotek.AdminPeriod.preloadEntryForm();
            }
        });

        $("#openAexperienceForm").fancybox({
            'beforeLoad' : function(){
                /*if(Tecnotek.UI.vars["fromEdit"] === false){
                 Tecnotek.UI.vars["entryId"]  = 0;
                 $("#entryFormName").val("");
                 $("#entryFormCode").val("");
                 $("#entryFormPercentage").val("");
                 $("#entryFormMaxValue").val("");
                 $("#entryFormSortOrder").val("");
                 }*/
                //Tecnotek.UI.vars["fromEdit"] = false;
                //Tecnotek.AdminPeriod.preloadEntryForm();
            }
        });

        $("#openOexperienceForm").fancybox({
            'beforeLoad' : function(){
                /*if(Tecnotek.UI.vars["fromEdit"] === false){
                 Tecnotek.UI.vars["entryId"]  = 0;
                 $("#entryFormName").val("");
                 $("#entryFormCode").val("");
                 $("#entryFormPercentage").val("");
                 $("#entryFormMaxValue").val("");
                 $("#entryFormSortOrder").val("");
                 }*/
                //Tecnotek.UI.vars["fromEdit"] = false;
                //Tecnotek.AdminPeriod.preloadEntryForm();
            }
        });

        $("#openInvestigationForm").fancybox({
            'beforeLoad' : function(){
                /*if(Tecnotek.UI.vars["fromEdit"] === false){
                 Tecnotek.UI.vars["entryId"]  = 0;
                 $("#entryFormName").val("");
                 $("#entryFormCode").val("");
                 $("#entryFormPercentage").val("");
                 $("#entryFormMaxValue").val("");
                 $("#entryFormSortOrder").val("");
                 }*/
                //Tecnotek.UI.vars["fromEdit"] = false;
                //Tecnotek.AdminPeriod.preloadEntryForm();
            }
        });

        $("#openImproveForm").fancybox({
            'beforeLoad' : function(){
                /*if(Tecnotek.UI.vars["fromEdit"] === false){
                 Tecnotek.UI.vars["entryId"]  = 0;
                 $("#entryFormName").val("");
                 $("#entryFormCode").val("");
                 $("#entryFormPercentage").val("");
                 $("#entryFormMaxValue").val("");
                 $("#entryFormSortOrder").val("");
                 }*/
                //Tecnotek.UI.vars["fromEdit"] = false;
                //Tecnotek.AdminPeriod.preloadEntryForm();
            }
        });

        $("#openWexperiencefullForm").fancybox({
            'beforeLoad' : function(){
                /*if(Tecnotek.UI.vars["fromEdit"] === false){
                 Tecnotek.UI.vars["entryId"]  = 0;
                 $("#entryFormName").val("");
                 $("#entryFormCode").val("");
                 $("#entryFormPercentage").val("");
                 $("#entryFormMaxValue").val("");
                 $("#entryFormSortOrder").val("");
                 }*/
                //Tecnotek.UI.vars["fromEdit"] = false;
                //Tecnotek.AdminPeriod.preloadEntryForm();
                Tecnotek.Curriculum.loadWexperiencesFullByTeacher();
            }
        });

        $("#openInvestigationfullForm").fancybox({
            'beforeLoad' : function(){
                /*if(Tecnotek.UI.vars["fromEdit"] === false){
                 Tecnotek.UI.vars["entryId"]  = 0;
                 $("#entryFormName").val("");
                 $("#entryFormCode").val("");
                 $("#entryFormPercentage").val("");
                 $("#entryFormMaxValue").val("");
                 $("#entryFormSortOrder").val("");
                 }*/
                //Tecnotek.UI.vars["fromEdit"] = false;
                //Tecnotek.AdminPeriod.preloadEntryForm();
                Tecnotek.Curriculum.loadInvestigationsFullByTeacher();
            }
        });

        $("#openImprovefullForm").fancybox({
            'beforeLoad' : function(){
                /*if(Tecnotek.UI.vars["fromEdit"] === false){
                 Tecnotek.UI.vars["entryId"]  = 0;
                 $("#entryFormName").val("");
                 $("#entryFormCode").val("");
                 $("#entryFormPercentage").val("");
                 $("#entryFormMaxValue").val("");
                 $("#entryFormSortOrder").val("");
                 }*/
                //Tecnotek.UI.vars["fromEdit"] = false;
                //Tecnotek.AdminPeriod.preloadEntryForm();
                Tecnotek.Curriculum.loadImprovesFullByTeacher();
            }
        });

        $("#openAexperiencefullForm").fancybox({
            'beforeLoad' : function(){
                /*if(Tecnotek.UI.vars["fromEdit"] === false){
                 Tecnotek.UI.vars["entryId"]  = 0;
                 $("#entryFormName").val("");
                 $("#entryFormCode").val("");
                 $("#entryFormPercentage").val("");
                 $("#entryFormMaxValue").val("");
                 $("#entryFormSortOrder").val("");
                 }*/
                //Tecnotek.UI.vars["fromEdit"] = false;
                //Tecnotek.AdminPeriod.preloadEntryForm();
                Tecnotek.Curriculum.loadAexperiencesFullByTeacher();
            }
        });

        $("#openOexperiencefullForm").fancybox({
            'beforeLoad' : function(){
                /*if(Tecnotek.UI.vars["fromEdit"] === false){
                 Tecnotek.UI.vars["entryId"]  = 0;
                 $("#entryFormName").val("");
                 $("#entryFormCode").val("");
                 $("#entryFormPercentage").val("");
                 $("#entryFormMaxValue").val("");
                 $("#entryFormSortOrder").val("");
                 }*/
                //Tecnotek.UI.vars["fromEdit"] = false;
                //Tecnotek.AdminPeriod.preloadEntryForm();
                Tecnotek.Curriculum.loadOexperiencesFullByTeacher();
            }
        });

        $("#openApresentationfullForm").fancybox({
            'beforeLoad' : function(){
                /*if(Tecnotek.UI.vars["fromEdit"] === false){
                 Tecnotek.UI.vars["entryId"]  = 0;
                 $("#entryFormName").val("");
                 $("#entryFormCode").val("");
                 $("#entryFormPercentage").val("");
                 $("#entryFormMaxValue").val("");
                 $("#entryFormSortOrder").val("");
                 }*/
                //Tecnotek.UI.vars["fromEdit"] = false;
                //Tecnotek.AdminPeriod.preloadEntryForm();
                Tecnotek.Curriculum.loadApresentationsFullByTeacher();
            }
        });

        $("#openDegreefullForm").fancybox({
            'beforeLoad' : function(){
                /*if(Tecnotek.UI.vars["fromEdit"] === false){
                 Tecnotek.UI.vars["entryId"]  = 0;
                 $("#entryFormName").val("");
                 $("#entryFormCode").val("");
                 $("#entryFormPercentage").val("");
                 $("#entryFormMaxValue").val("");
                 $("#entryFormSortOrder").val("");
                 }*/
                //Tecnotek.UI.vars["fromEdit"] = false;
                //Tecnotek.AdminPeriod.preloadEntryForm();
                Tecnotek.Curriculum.loadDegreesFullByTeacher();
            }
        });

        $("#openPublicationfullForm").fancybox({
            'beforeLoad' : function(){
                /*if(Tecnotek.UI.vars["fromEdit"] === false){
                 Tecnotek.UI.vars["entryId"]  = 0;
                 $("#entryFormName").val("");
                 $("#entryFormCode").val("");
                 $("#entryFormPercentage").val("");
                 $("#entryFormMaxValue").val("");
                 $("#entryFormSortOrder").val("");
                 }*/
                //Tecnotek.UI.vars["fromEdit"] = false;
                //Tecnotek.AdminPeriod.preloadEntryForm();
                Tecnotek.Curriculum.loadPublicationsFullByTeacher();
            }
        });

        $("#openPublicationForm").fancybox({
            'beforeLoad' : function(){
                /*if(Tecnotek.UI.vars["fromEdit"] === false){
                 Tecnotek.UI.vars["entryId"]  = 0;
                 $("#entryFormName").val("");
                 $("#entryFormCode").val("");
                 $("#entryFormPercentage").val("");
                 $("#entryFormMaxValue").val("");
                 $("#entryFormSortOrder").val("");
                 }*/
                //Tecnotek.UI.vars["fromEdit"] = false;
                //Tecnotek.AdminPeriod.preloadEntryForm();
            }
        });

        $("#openApresentationForm").fancybox({
            'beforeLoad' : function(){
                /*if(Tecnotek.UI.vars["fromEdit"] === false){
                 Tecnotek.UI.vars["entryId"]  = 0;
                 $("#entryFormName").val("");
                 $("#entryFormCode").val("");
                 $("#entryFormPercentage").val("");
                 $("#entryFormMaxValue").val("");
                 $("#entryFormSortOrder").val("");
                 }*/
                //Tecnotek.UI.vars["fromEdit"] = false;
                //Tecnotek.AdminPeriod.preloadEntryForm();
            }
        });

        $("#openCurriculumdataForm").fancybox({
            'beforeLoad' : function(){
                /*if(Tecnotek.UI.vars["fromEdit"] === false){
                 Tecnotek.UI.vars["entryId"]  = 0;
                 $("#entryFormName").val("");
                 $("#entryFormCode").val("");
                 $("#entryFormPercentage").val("");
                 $("#entryFormMaxValue").val("");
                 $("#entryFormSortOrder").val("");
                 }*/
                //Tecnotek.UI.vars["fromEdit"] = false;
                //Tecnotek.AdminPeriod.preloadEntryForm();
                Tecnotek.Curriculum.loadCurriculumdataForm();
            }
        });


        $("#openPublicationFormEdit").fancybox({
            'beforeLoad' : function(){
                /*if(Tecnotek.UI.vars["fromEdit"] === false){
                 Tecnotek.UI.vars["entryId"]  = 0;
                 $("#entryFormName").val("");
                 $("#entryFormCode").val("");
                 $("#entryFormPercentage").val("");
                 $("#entryFormMaxValue").val("");
                 $("#entryFormSortOrder").val("");
                 }*/
                //Tecnotek.UI.vars["fromEdit"] = false;
                //Tecnotek.AdminPeriod.preloadEntryForm();

                Tecnotek.Curriculum.editPublication($(this).attr("rel"));
            }
        });

        $("#openDegreeEdit").fancybox({
            'beforeLoad' : function(){

            }
        });



        $("#openStudentsToGroup").fancybox({
            'beforeLoad' : function(){

            },
            'modal': true,
            'width': 650
        });
        $('#searchBox').keyup(function(event){
            event.preventDefault();
            if($(this).val().length == 0) {
                $('#suggestions').fadeOut(); // Hide the suggestions box
            } else {
                Tecnotek.ajaxCall(Tecnotek.UI.urls["getStudentsURL"],
                    {text: $(this).val(), groupId: Tecnotek.UI.vars["groupId"], periodId: Tecnotek.UI.vars["periodId"]},
                    function(data){
                        if(data.error === true) {
                            Tecnotek.showErrorMessage(data.message,true, "", false);
                        } else {
                            $data = "";
                            $data += '<p id="searchresults">';
                            $data += '    <span class="category">Estudiantes</span>';
                            for(i=0; i<data.students.length; i++) {
                                $data += '    <a class="searchResult" rel="' + data.students[i].id + '" name="' +
                                    data.students[i].lastname + ' ' + data.students[i].firstname + '">';
                                $data += '      <span class="searchheading">' + data.students[i].carne
                                    + ' ' + data.students[i].lastname +  ' ' + data.students[i].firstname +  '</span>';
                                $data += '      <span>Incluir este estudiante.</span>';
                                $data += '    </a>';
                            }
                            $data += '</p>';

                            $('#suggestions').fadeIn(); // Show the suggestions box
                            $('#suggestions').html($data); // Fill the suggestions box
                            $('.searchResult').unbind();
                            $('.searchResult').click(function(event){
                                event.preventDefault();
                                Tecnotek.UI.vars["studentId"] = $(this).attr("rel");
                                Tecnotek.UI.vars["studentName"] = $(this).attr("name");

                                Tecnotek.ajaxCall(Tecnotek.UI.urls["setStudentToGroup"],
                                    {studentId: $(this).attr("rel"), groupId: Tecnotek.UI.vars["groupId"], periodId: Tecnotek.UI.vars["periodId"]},
                                    function(data){
                                        if(data.error === true) {
                                            Tecnotek.showErrorMessage(data.message,true, "", false);
                                        } else {
                                            $html = '<div id="student_row_' + data.id + '" class="row userRow" rel="' + data.id + '">';
                                            $html += '<div class="option_width" style="float: left; width: 300px;">' + Tecnotek.UI.vars["studentName"] + '</div>';
                                            $html += '<div class="right imageButton deleteButton deleteStudentOfGroup" style="height: 16px;"  title="delete???"  rel="' + data.id + '"></div>';
                                            $html += '<div class="clear"></div>';
                                            $html += '</div>';
                                            $("#studentsList").append($html);
                                            Tecnotek.AdminPeriod.initDeleteButtonsOfStudents();
                                        }
                                    },
                                    function(jqXHR, textStatus){
                                        Tecnotek.showErrorMessage("Error setting data: " + textStatus + ".",
                                            true, "", false);
                                        $(this).val("");
                                        $('#suggestions').fadeOut(); // Hide the suggestions box
                                    }, true);
                            });
                        }
                    },
                    function(jqXHR, textStatus){
                        Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".",
                            true, "", false);
                        $(this).val("");
                        $('#suggestions').fadeOut(); // Hide the suggestions box
                    }, true);
            }
        });

        $('#searchBox').blur(function(event){
            event.preventDefault();
            $(this).val("");
            $('#suggestions').fadeOut(); // Hide the suggestions box
        });
        Tecnotek.Curriculum.initButtons();

    },
    initDeleteButtonsOfStudents: function(){
        $(".deleteStudentOfGroup").unbind();
        $(".deleteStudentOfGroup").click(function(event){
            event.preventDefault();
            $studentId = $(this).attr("rel");
            Tecnotek.ajaxCall(Tecnotek.UI.urls["removeStudentFromGroupURL"],
                {studentId: $studentId, periodId: Tecnotek.UI.vars["periodId"]},
                function(data){
                    if(data.error === true) {
                        Tecnotek.showErrorMessage(data.message,true, "", false);
                    } else {
                        $("#student_row_" + $studentId).empty().remove();
                    }
                },
                function(jqXHR, textStatus){
                    Tecnotek.showErrorMessage("Error executing request: " + textStatus + ".",
                        true, "", false);
                }, true);
        });
    },
    initButtons : function() {
        $("#groupFormCancel").click(function(event){
            event.preventDefault();
            $.fancybox.close();
        });
        $("#degreeCancel").click(function(event){
            event.preventDefault();
            $.fancybox.close();
        });
        $("#wexperienceCancel").click(function(event){
            event.preventDefault();
            $.fancybox.close();
        });

        $("#investigationCancel").click(function(event){
            event.preventDefault();
            $.fancybox.close();
        });
        $("#improveCancel").click(function(event){
            event.preventDefault();
            $.fancybox.close();
        });
        $("#aexperienceCancel").click(function(event){
            event.preventDefault();
            $.fancybox.close();
        });
        $("#oexperienceCancel").click(function(event){
            event.preventDefault();
            $.fancybox.close();
        });
        $("#publicationCancel").click(function(event){
            event.preventDefault();
            $.fancybox.close();
        });
        $("#apresentationCancel").click(function(event){
            event.preventDefault();
            $.fancybox.close();
        });
        $("#entryFormCancel").click(function(event){
            event.preventDefault();
            $.fancybox.close();
        });
        $("#studentsToGroupCancel").click(function(event){
            event.preventDefault();
            $.fancybox.close();
        });
        $("#grade").change(function(event){
            event.preventDefault();
            Tecnotek.AdminPeriod.loadPeriodInfoByGrade();
        });
        $("#equatedDegree").change(function(event){
            event.preventDefault();
            Tecnotek.Curriculum.changeequatedtxDegree();
        });
        $("#curriculumdataReg").change(function(event){
            event.preventDefault();
            Tecnotek.Curriculum.changecurriculumdataRegtx();
        });
        $("#groupToFormTeacher").change(function(event){
            event.preventDefault();
            Tecnotek.AdminPeriod.loadCourseClassByGroup();
        });
        $("#curriculumdataCancel").click(function(event){
            event.preventDefault();
            $.fancybox.close();
        });

        $('#curriculumdataFormContainer').submit(function(event){
            event.preventDefault();
            Tecnotek.Curriculum.createCurriculumdata();
        });
        $('#publicationFormEditContainer').submit(function(event){
            event.preventDefault();
            Tecnotek.Curriculum.savePublication();
        });
    },
    preloadEntryForm: function(){
        if($("#periodCourses").val() === "0"){
            Tecnotek.showErrorMessage("Por favor seleccione una materia.",true, "", false);
            $.fancybox.close();
        } else {
            $("#entryTitleOption").text((Tecnotek.UI.vars["entryId"] === 0)? "Incluir":"Editar");

            //TODO Must load the list of courses again???
        }
    },
    changeequatedtxDegree: function(){
        temp = $('#equatedDegree').val();
        if( temp == 2){
            $('#equatedtxDegree').empty();
            $('#equatedtxDegree').prop('disabled', false);
        }
        else{
            $('#equatedtxDegree').empty();
            $('#equatedtxDegree').prop('disabled', true);
        }
    },

    changecurriculumdataRegtx: function(){
        temp = $('#changecurriculumdataReg').val();
        if( temp == 2){
            //$('#curriculumdataRegTx').options[2].disabled = true;
            var opt = document.createElement('option');
            opt.value = 10;
            opt.innerHTML = 10;
            $('#changecurriculumdataRegtx').appendChild(opt);
        }
        else{
            //$('#curriculumdataRegTx').options[1].disabled = true;
            var opt = document.createElement('option');
            opt.value = 10;
            opt.innerHTML = 10;
            $('#changecurriculumdataRegtx').appendChild(opt);
        }
    },

    loadCurriculumdataForm: function() {
        //$('.editEntry').unbind();


        Tecnotek.ajaxCall(Tecnotek.UI.urls["loadCurriculumdataFormURL"],
            {   },
            function(data){
                if(data.error === true) {
                    Tecnotek.showErrorMessage(data.message,true, "", false);
                } else {

                    $('#curriculumdataPension').val(data.pension);
                    $('#curriculumdataDegree').val(data.degree);
                    $('#curriculumdataIdform').val($('#curriculumdataId').val());
                    $('#curriculumdataGender').val(data.gender);
                    $('#curriculumdataBirthday').val(data.birthday);
                    $('#curriculumdataEmail').val(data.email);
                    $('#curriculumdataPhone').val(data.phone);
                    $('#curriculumdataReg').val(data.regimen);
                    $('#curriculumdataRegTx').val(data.regimentx);
                    //$("#Doctorado").html($('#curriculumdataGender :selected').text());
                    //$("#curriculumdataDegreLabel").html($('#curriculumdataDegree :selected').text());
                    //$("curriculumdataGender select").val($('#curriculumdataGenderLabel').val());
                    //$('.curriculumdataGender option[value=2]').attr('selected','selected');

                    //Tecnotek.Curriculum.initializeDegreeButtons();
                }
            },
            function(jqXHR, textStatus){
                Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
            }, true);
    },

    createCurriculumdata: function() {
        Tecnotek.ajaxCall(Tecnotek.UI.urls["loadCurriculumdataFormSaveURL"],
            {   curriculumId: $('#curriculumdataIdform').val(),
                degree: $('#curriculumdataDegree').val(),
                gender: $('#curriculumdataGender').val(),
                pension: $('#curriculumdataPension').val(),
                email: $('#curriculumdataEmail').val(),
                birthday: $('#curriculumdataBirthday').val(),
                regimen: $('#curriculumdataReg').val(),
                regimentx: $('#curriculumdataRegTx').val(),
                phone: $('#curriculumdataPhone').val()
            },
            function(data){
                if(data.error === true) {
                    Tecnotek.showErrorMessage(data.message,true, "", false);
                } else {
                    $.fancybox.close();
                    $('#curriculumdataContainer').empty();
                    Tecnotek.Curriculum.loadCurriculumdata();
                }
            },
            function(jqXHR, textStatus){
                Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
            }, true);
    },

    loadCurriculumdata: function() {
        //$('.editEntry').unbind();

        $('#curriculumdataContainer').empty();
        Tecnotek.ajaxCall(Tecnotek.UI.urls["loadCurriculumdataURL"],
            {   },
            function(data){
                if(data.error === true) {
                    Tecnotek.showErrorMessage(data.message,true, "", false);
                } else {

                    $('#curriculumdataContainer').append(data.entriesHtml);
                    //Tecnotek.Curriculum.initializeDegreeButtons();
                }
            },
            function(jqXHR, textStatus){
                Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
            }, true);
    },
    loadDegreesByTeacher: function() {
        //$('.editEntry').unbind();

        $('#nameDegree').empty();
        $('#placeDegree').empty();
        $('#yearDegree').empty();
        $('#degreeRows').empty();
        $('#countryDegree').empty();
        $('#equatedtxDegree').empty();

        /*if(teacherId == 0){//Clean page
            console.debug("Clean page!!!");

        } else {*/

            Tecnotek.ajaxCall(Tecnotek.UI.urls["loadDegreeByTeacherURL"],
                {   },
                function(data){
                    if(data.error === true) {
                        Tecnotek.showErrorMessage(data.message,true, "", false);
                    } else {
                        $('#degreeRows').empty();
                        $('#degreeRows').append(data.entriesHtml);
                        Tecnotek.Curriculum.initializeDegreeButtons();
                    }
                },
                function(jqXHR, textStatus){
                    Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                }, true);
        //}
    },

    loadDegreesFullByTeacher: function() {
        //$('.editEntry').unbind();

        $('#nameDegree').empty();
        $('#placeDegree').empty();
        $('#yearDegree').empty();
        $('#countryDegree').empty();
        $('#equatedtxDegree').empty();
        //$('#wexperiencefullFormContainer').empty();
        /*if(teacherId == 0){//Clean page
         console.debug("Clean page!!!");

         } else {*/

        Tecnotek.ajaxCall(Tecnotek.UI.urls["loadDegreesFullByTeacherURL"],
            {   },
            function(data){
                if(data.error === true) {
                    Tecnotek.showErrorMessage(data.message,true, "", false);
                } else {
                    $('#degreefullFormContainer').html(data.fullhtml);
                    //Tecnotek.Curriculum.initializeWexperienceButtons();
                }
            },
            function(jqXHR, textStatus){
                Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
            }, true);
        //}
    },
    deleteDegree: function(degreeId){
        if(Tecnotek.showConfirmationQuestion("Esta seguro que desea eliminar este título?")) {
            Tecnotek.ajaxCall(Tecnotek.UI.urls["deleteDegreeURL"],
                {   degreeId: degreeId },
                function(data){
                    if(data.error === true) {
                        Tecnotek.showErrorMessage(data.message,true, "", false);
                    } else {
                        $("#degreeRows_" + degreeId).fadeOut('slow', function(){});
                    }
                },
                function(jqXHR, textStatus){
                    Tecnotek.showErrorMessage("Error deleting relacion: " + textStatus + ".", true, "", false);
                }, true);
        }

    },
    updateDegree: function(degreeId){
        Tecnotek.ajaxCall(Tecnotek.UI.urls["updateDegreeURL"],
            {   degreeId: $('#idDegreeEdit').val(),
                name: $('#nameDegreeEdit').val(),
                place: $('#placeDegreeEdit').val(),
                year: $('#yearDegreeEdit').val(),
                eedition: $('#eeditionDegreeEdit').val(),
                equated: $('#equatedDegreeEdit').val(),
                equatedtx: $('#equatedtxDegreeEdit').val(),
                country: $('#countryDegreeEdit').val(),
                inprogress: $('#inprogressDegreeEdit').val()
            },
            function(data){
                if(data.error === true) {
                    Tecnotek.showErrorMessage(data.message,true, "", false);
                } else {
                    $.fancybox.close();
                    Tecnotek.Curriculum.loadDegreesByTeacher();
                }
            },
            function(jqXHR, textStatus){
                Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                $(this).val("");
            }, true);
    },
    initializeDegreeButtons: function(){
        $('.deleteDegree').unbind();
        $('.deleteDegree').click(function(event){
            event.preventDefault();
            Tecnotek.Curriculum.deleteDegree($(this).attr("rel"));
        });
        $('.editDegree').unbind();
        $('.editDegree').click(function(event){
            event.preventDefault();


            var degreeId = $(this).attr("rel");
            Tecnotek.UI.vars["idDegreeEdit"]  = degreeId;
            Tecnotek.ajaxCall(Tecnotek.UI.urls["getInfoDegreeURL"],
                {degreeId: $(this).attr("rel")},
                function(data){
                    if(data.error === true) {
                        Tecnotek.showErrorMessage(data.message,true, "", false);
                    } else {
                        //Tecnotek.showInfoMessage(data.html, true, "", false);

                        $('#idDegreeEdit').val(data.id);

                        $('#nameDegreeEdit').val(data.name);
                        $('#placeDegreeEdit').val(data.place);
                        $('#yearDegreeEdit').val(data.year);
                        $('#eeditionDegreeEdit').val(data.eedition);
                        $('#equatedDegreeEdit').val(data.equated);
                        $('#equatedtxDegreeEdit').val(data.equatedtx);
                        $('#countryDegreeEdit').val(data.country);
                        $('#inprogressDegreeEdit').val(data.inprogress);

                        $("#openDegreeEdit").trigger("click");
                    }
                },
                function(jqXHR, textStatus){
                    Tecnotek.showErrorMessage("Error in request: " + textStatus + ".",
                        true, "", false);
                }, true);

        });
    },
    createDegree: function() {
        Tecnotek.ajaxCall(Tecnotek.UI.urls["createDegreeURL"],
            {   name: $('#nameDegree').val(),
                place: $('#placeDegree').val(),
                year: $('#yearDegree').val(),
                eedition: $('#eeditionDegree').val(),
                equated: $('#equatedDegree').val(),
                equatedtx: $('#equatedtxDegree').val(),
                country: $('#countryDegree').val(),
                inprogress: $('#inprogressDegree').val()
            },
            function(data){
                if(data.error === true) {
                    Tecnotek.showErrorMessage(data.message,true, "", false);
                } else {
                    $.fancybox.close();
                    Tecnotek.Curriculum.loadDegreesByTeacher();
                }
            },
            function(jqXHR, textStatus){
                Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                $(this).val("");
            }, true);
    },
    loadWexperiencesByTeacher: function() {
        //$('.editEntry').unbind();

        $('#jobWexperience').empty();
        $('#positionWexperience').empty();
        $('#placeWexperience').empty();
        $('#yearWexperience').empty();
        $('#wexperienceRows').empty();

        /*if(teacherId == 0){//Clean page
         console.debug("Clean page!!!");

         } else {*/

        Tecnotek.ajaxCall(Tecnotek.UI.urls["loadWexperiencesByTeacherURL"],
            {   },
            function(data){
                if(data.error === true) {
                    Tecnotek.showErrorMessage(data.message,true, "", false);
                } else {
                    $('#wexperienceRows').empty();
                    $('#wexperienceRows').append(data.entriesHtml);
                    Tecnotek.Curriculum.initializeWexperienceButtons();
                }
            },
            function(jqXHR, textStatus){
                Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
            }, true);
        //}
    },
    loadWexperiencesFullByTeacher: function() {
        //$('.editEntry').unbind();

        $('#jobWexperience').empty();
        $('#positionWexperience').empty();
        $('#placeWexperience').empty();
        $('#yearWexperience').empty();
        //$('#wexperiencefullFormContainer').empty();
        /*if(teacherId == 0){//Clean page
         console.debug("Clean page!!!");

         } else {*/

        Tecnotek.ajaxCall(Tecnotek.UI.urls["loadWexperiencesFullByTeacherURL"],
            {   },
            function(data){
                if(data.error === true) {
                    Tecnotek.showErrorMessage(data.message,true, "", false);
                } else {
                    $('#wexperiencefullFormContainer').html(data.fullhtml);
                    //Tecnotek.Curriculum.initializeWexperienceButtons();
                }
            },
            function(jqXHR, textStatus){
                Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
            }, true);
        //}
    },
    deleteWexperience: function(wexperienceId){
        if(Tecnotek.showConfirmationQuestion("Esta seguro que desea eliminar este título?")) {
            Tecnotek.ajaxCall(Tecnotek.UI.urls["deleteWexperienceURL"],
                {   wexperienceId: wexperienceId },
                function(data){
                    if(data.error === true) {
                        Tecnotek.showErrorMessage(data.message,true, "", false);
                    } else {
                        $("#wexperienceRows_" + wexperienceId).fadeOut('slow', function(){});
                    }
                },
                function(jqXHR, textStatus){
                    Tecnotek.showErrorMessage("Error deleting relacion: " + textStatus + ".", true, "", false);
                }, true);
        }

    },
    initializeWexperienceButtons: function(){
        $('.deleteWexperience').unbind();
        $('.deleteWexperience').click(function(event){
            event.preventDefault();
            Tecnotek.Curriculum.deleteWexperience($(this).attr("rel"));
        });
    },
    createWexperience: function() {
        Tecnotek.ajaxCall(Tecnotek.UI.urls["createWexperienceURL"],
            {   job: $('#jobWexperience').val(),
                position: $('#positionWexperience').val(),
                place: $('#placeWexperience').val(),
                year: $('#yearWexperience').val()
            },
            function(data){
                if(data.error === true) {
                    Tecnotek.showErrorMessage(data.message,true, "", false);
                } else {
                    $.fancybox.close();
                    Tecnotek.Curriculum.loadWexperiencesByTeacher();
                }
            },
            function(jqXHR, textStatus){
                Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                $(this).val("");
            }, true);
    },
    loadAexperiencesByTeacher: function() {
        //$('.editEntry').unbind();

        $('#subjectAexperience').empty();
        $('#placeAexperience').empty();
        $('#yearAexperience').empty();
        $('#performanceAexperience').empty();
        $('#aexperienceRows').empty();

        /*if(teacherId == 0){//Clean page
         console.debug("Clean page!!!");

         } else {*/

        Tecnotek.ajaxCall(Tecnotek.UI.urls["loadAexperiencesByTeacherURL"],
            {   },
            function(data){
                if(data.error === true) {
                    Tecnotek.showErrorMessage(data.message,true, "", false);
                } else {
                    $('#aexperienceRows').empty();
                    $('#aexperienceRows').append(data.entriesHtml);
                    Tecnotek.Curriculum.initializeAexperienceButtons();
                }
            },
            function(jqXHR, textStatus){
                Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
            }, true);
        //}
    },
    loadAexperiencesFullByTeacher: function() {
        //$('.editEntry').unbind();

        $('#subjectAexperience').empty();
        $('#placeAexperience').empty();
        $('#yearAexperience').empty();
        $('#performanceAexperience').empty();
        //$('#wexperiencefullFormContainer').empty();
        /*if(teacherId == 0){//Clean page
         console.debug("Clean page!!!");

         } else {*/

        Tecnotek.ajaxCall(Tecnotek.UI.urls["loadAexperiencesFullByTeacherURL"],
            {   },
            function(data){
                if(data.error === true) {
                    Tecnotek.showErrorMessage(data.message,true, "", false);
                } else {
                    $('#aexperiencefullFormContainer').html(data.fullhtml);
                    //Tecnotek.Curriculum.initializeWexperienceButtons();
                }
            },
            function(jqXHR, textStatus){
                Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
            }, true);
        //}
    },
    deleteAexperience: function(aexperienceId){
        if(Tecnotek.showConfirmationQuestion("Esta seguro que desea eliminar este título?")) {
            Tecnotek.ajaxCall(Tecnotek.UI.urls["deleteAexperienceURL"],
                {   aexperienceId: aexperienceId },
                function(data){
                    if(data.error === true) {
                        Tecnotek.showErrorMessage(data.message,true, "", false);
                    } else {
                        $("#aexperienceRows_" + aexperienceId).fadeOut('slow', function(){});
                    }
                },
                function(jqXHR, textStatus){
                    Tecnotek.showErrorMessage("Error deleting relacion: " + textStatus + ".", true, "", false);
                }, true);
        }

    },
    initializeAexperienceButtons: function(){
        $('.deleteAexperience').unbind();
        $('.deleteAexperience').click(function(event){
            event.preventDefault();
            Tecnotek.Curriculum.deleteAexperience($(this).attr("rel"));
        });
    },
    createAexperience: function() {
        Tecnotek.ajaxCall(Tecnotek.UI.urls["createAexperienceURL"],
            {   subject: $('#subjectAexperience').val(),
                place: $('#placeAexperience').val(),
                performance: $('#performanceAexperience').val(),
                year: $('#yearAexperience').val()
            },
            function(data){
                if(data.error === true) {
                    Tecnotek.showErrorMessage(data.message,true, "", false);
                } else {
                    $.fancybox.close();
                    Tecnotek.Curriculum.loadAexperiencesByTeacher();
                }
            },
            function(jqXHR, textStatus){
                Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                $(this).val("");
            }, true);
    },
    loadOexperiencesByTeacher: function() {
        //$('.editEntry').unbind();

        $('#subjectOexperience').empty();
        $('#placeOexperience').empty();
        $('#yearOexperience').empty();
        $('#oexperienceRows').empty();

        /*if(teacherId == 0){//Clean page
         console.debug("Clean page!!!");

         } else {*/

        Tecnotek.ajaxCall(Tecnotek.UI.urls["loadOexperiencesByTeacherURL"],
            {   },
            function(data){
                if(data.error === true) {
                    Tecnotek.showErrorMessage(data.message,true, "", false);
                } else {
                    $('#oexperienceRows').empty();
                    $('#oexperienceRows').append(data.entriesHtml);
                    Tecnotek.Curriculum.initializeOexperienceButtons();
                }
            },
            function(jqXHR, textStatus){
                Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
            }, true);
        //}
    },
    loadOexperiencesFullByTeacher: function() {
        //$('.editEntry').unbind();

        $('#subjectOexperience').empty();
        $('#placeOexperience').empty();
        $('#yearOexperience').empty();
        //$('#wexperiencefullFormContainer').empty();
        /*if(teacherId == 0){//Clean page
         console.debug("Clean page!!!");

         } else {*/

        Tecnotek.ajaxCall(Tecnotek.UI.urls["loadOexperiencesFullByTeacherURL"],
            {   },
            function(data){
                if(data.error === true) {
                    Tecnotek.showErrorMessage(data.message,true, "", false);
                } else {
                    $('#oexperiencefullFormContainer').html(data.fullhtml);
                    //Tecnotek.Curriculum.initializeWexperienceButtons();
                }
            },
            function(jqXHR, textStatus){
                Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
            }, true);
        //}
    },
    deleteOexperience: function(oexperienceId){
        if(Tecnotek.showConfirmationQuestion("Esta seguro que desea eliminar este título?")) {
            Tecnotek.ajaxCall(Tecnotek.UI.urls["deleteOexperienceURL"],
                {   oexperienceId: oexperienceId },
                function(data){
                    if(data.error === true) {
                        Tecnotek.showErrorMessage(data.message,true, "", false);
                    } else {
                        $("#oexperienceRows_" + oexperienceId).fadeOut('slow', function(){});
                    }
                },
                function(jqXHR, textStatus){
                    Tecnotek.showErrorMessage("Error deleting relacion: " + textStatus + ".", true, "", false);
                }, true);
        }

    },
    initializeOexperienceButtons: function(){
        $('.deleteOexperience').unbind();
        $('.deleteOexperience').click(function(event){
            event.preventDefault();
            Tecnotek.Curriculum.deleteOexperience($(this).attr("rel"));
        });
    },
    createOexperience: function() {
        Tecnotek.ajaxCall(Tecnotek.UI.urls["createOexperienceURL"],
            {   subject: $('#subjectOexperience').val(),
                place: $('#placeOexperience').val(),
                year: $('#yearOexperience').val()
            },
            function(data){
                if(data.error === true) {
                    Tecnotek.showErrorMessage(data.message,true, "", false);
                } else {
                    $.fancybox.close();
                    Tecnotek.Curriculum.loadOexperiencesByTeacher();
                }
            },
            function(jqXHR, textStatus){
                Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                $(this).val("");
            }, true);
    },
    loadPublicationsByTeacher: function() {
        //$('.editEntry').unbind();

        $('#subjectPublication').empty();
        $('#placePublication').empty();
        $('#yearPublication').empty();
        $('#publicationRows').empty();

        /*if(teacherId == 0){//Clean page
         console.debug("Clean page!!!");

         } else {*/

        Tecnotek.ajaxCall(Tecnotek.UI.urls["loadPublicationByTeacherURL"],
            {   },
            function(data){
                if(data.error === true) {
                    Tecnotek.showErrorMessage(data.message,true, "", false);
                } else {
                    $('#publicationRows').empty();
                    $('#publicationRows').append(data.entriesHtml);
                    Tecnotek.Curriculum.initializePublicationButtons();
                }
            },
            function(jqXHR, textStatus){
                Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
            }, true);
        //}
    },
    loadPublicationsFullByTeacher: function() {
        //$('.editEntry').unbind();



        $('#subjectPublication').empty();
        $('#placePublication').empty();
        $('#yearPublication').empty();
        //$('#wexperiencefullFormContainer').empty();
        /*if(teacherId == 0){//Clean page
         console.debug("Clean page!!!");

         } else {*/

        Tecnotek.ajaxCall(Tecnotek.UI.urls["loadPublicationsFullByTeacherURL"],
            {   },
            function(data){
                if(data.error === true) {
                    Tecnotek.showErrorMessage(data.message,true, "", false);
                } else {
                    $('#publicationfullFormContainer').html(data.fullhtml);
                    //Tecnotek.Curriculum.initializeWexperienceButtons();
                }
            },
            function(jqXHR, textStatus){
                Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
            }, true);
        //}
    },
    deletePublication: function(publicationId){
        if(Tecnotek.showConfirmationQuestion("Esta seguro que desea eliminar este título?")) {
            Tecnotek.ajaxCall(Tecnotek.UI.urls["deletePublicationURL"],
                {   publicationId: publicationId },
                function(data){
                    if(data.error === true) {
                        Tecnotek.showErrorMessage(data.message,true, "", false);
                    } else {
                        $("#publicationRows_" + publicationId).fadeOut('slow', function(){});
                    }
                },
                function(jqXHR, textStatus){
                    Tecnotek.showErrorMessage("Error deleting relacion: " + textStatus + ".", true, "", false);
                }, true);
        }

    },
    initializePublicationButtons: function(){
        $('.deletePublication').unbind();
        $('.deletePublication').click(function(event){
            event.preventDefault();
            Tecnotek.Curriculum.deletePublication($(this).attr("rel"));
        });
        $('.editPublication').unbind();
        $('.editPublication').click(function(event){
            event.preventDefault();

            //Tecnotek.UI.vars["publicationIdEdit"] = $(this).attr("rel");
            $('#openPublicationFormEdit').trigger('click');
            //Tecnotek.Curriculum.editPublication($(this).attr("rel"));
        });
    },
    createPublication: function() {
        Tecnotek.ajaxCall(Tecnotek.UI.urls["createPublicationURL"],
            {   subject: $('#subjectPublication').val(),
                place: $('#placePublication').val(),
                year: $('#yearPublication').val(),
                authorship: $('#authorshipPublication').val()
            },
            function(data){
                if(data.error === true) {
                    Tecnotek.showErrorMessage(data.message,true, "", false);
                } else {
                    $.fancybox.close();
                    Tecnotek.Curriculum.loadPublicationsByTeacher();
                }
            },
            function(jqXHR, textStatus){
                Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                $(this).val("");
            }, true);
    },
    savePublication: function() {
        Tecnotek.ajaxCall(Tecnotek.UI.urls["savePublicationURL"],
            {   subject: $('#subjectPublication').val(),
                place: $('#placePublication').val(),
                year: $('#yearPublication').val(),
                authorship: $('#authorshipPublication').val()
            },
            function(data){
                if(data.error === true) {
                    Tecnotek.showErrorMessage(data.message,true, "", false);
                } else {
                    $.fancybox.close();
                    //$('#publicationFormContainer').empty();
                    Tecnotek.Curriculum.loadPublicationsByTeacher();
                }
            },
            function(jqXHR, textStatus){
                Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
            }, true);
    },
    editPublication: function(publicationId) {
        Tecnotek.ajaxCall(Tecnotek.UI.urls["loadPublicationFormURL"],
            {  publicationId: publicationId },
            function(data){
                if(data.error === true) {
                    Tecnotek.showErrorMessage(data.message,true, "", false);
                } else {

                    $('#subjectPublicationEdit').val(data.subject);
                    $('#placePublicationEdit').val(data.place);
                    $('#curriculumdataIdform').val($('#curriculumdataId').val());
                    $('#yearPublicationEdit').val(data.year);
                    $('#authorshipPublicationEdit').val(data.authorship);

                    alert("llega");
                }
            },
            function(jqXHR, textStatus){
                Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
            }, true);
    },
    loadApresentationsByTeacher: function() {
        //$('.editEntry').unbind();

        $('#subjectApresentation').empty();
        $('#placeApresentation').empty();
        $('#yearApresentation').empty();
        $('#apresentationRows').empty();

        /*if(teacherId == 0){//Clean page
         console.debug("Clean page!!!");

         } else {*/

        Tecnotek.ajaxCall(Tecnotek.UI.urls["loadApresentationsByTeacherURL"],
            {   },
            function(data){
                if(data.error === true) {
                    Tecnotek.showErrorMessage(data.message,true, "", false);
                } else {
                    $('#apresentationRows').empty();
                    $('#apresentationRows').append(data.entriesHtml);
                    Tecnotek.Curriculum.initializeApresentationButtons();
                }
            },
            function(jqXHR, textStatus){
                Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
            }, true);
        //}
    },
    loadApresentationsFullByTeacher: function() {
        //$('.editEntry').unbind();


        $('#subjectApresentation').empty();
        $('#placeApresentation').empty();
        $('#yearApresentation').empty();
        //$('#wexperiencefullFormContainer').empty();
        /*if(teacherId == 0){//Clean page
         console.debug("Clean page!!!");

         } else {*/

        Tecnotek.ajaxCall(Tecnotek.UI.urls["loadApresentationsFullByTeacherURL"],
            {   },
            function(data){
                if(data.error === true) {
                    Tecnotek.showErrorMessage(data.message,true, "", false);
                } else {
                    $('#apresentationfullFormContainer').html(data.fullhtml);
                    //Tecnotek.Curriculum.initializeWexperienceButtons();
                }
            },
            function(jqXHR, textStatus){
                Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
            }, true);
        //}
    },
    deleteApresentation: function(apresentationId){
        if(Tecnotek.showConfirmationQuestion("Esta seguro que desea eliminar este título?")) {
            Tecnotek.ajaxCall(Tecnotek.UI.urls["deleteApresentationURL"],
                {   apresentationId: apresentationId },
                function(data){
                    if(data.error === true) {
                        Tecnotek.showErrorMessage(data.message,true, "", false);
                    } else {
                        $("#apresentationRows_" + apresentationId).fadeOut('slow', function(){});
                    }
                },
                function(jqXHR, textStatus){
                    Tecnotek.showErrorMessage("Error deleting relacion: " + textStatus + ".", true, "", false);
                }, true);
        }

    },
    initializeApresentationButtons: function(){
        $('.deleteApresentation').unbind();
        $('.deleteApresentation').click(function(event){
            event.preventDefault();
            Tecnotek.Curriculum.deleteApresentation($(this).attr("rel"));
        });
    },
    createApresentation: function() {
        Tecnotek.ajaxCall(Tecnotek.UI.urls["createApresentationURL"],
            {   subject: $('#subjectApresentation').val(),
                place: $('#placeApresentation').val(),
                year: $('#yearApresentation').val()
            },
            function(data){
                if(data.error === true) {
                    Tecnotek.showErrorMessage(data.message,true, "", false);
                } else {
                    $.fancybox.close();
                    Tecnotek.Curriculum.loadApresentationsByTeacher();
                }
            },
            function(jqXHR, textStatus){
                Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                $(this).val("");
            }, true);
    },
    loadInvestigationsByTeacher: function() {
        //$('.editEntry').unbind();

        $('#nameInvestigation').empty();
        //$('#typeInvestigation').empty();
        //$('#statusInvestigation').empty();
        $('#validityInvestigation').empty();
        $('#placeInvestigation').empty();
        $('#descriptorsInvestigation').empty();
        $('#investigationRows').empty();

        /*if(teacherId == 0){//Clean page
         console.debug("Clean page!!!");

         } else {*/

        Tecnotek.ajaxCall(Tecnotek.UI.urls["loadInvestigationsByTeacherURL"],
            {   },
            function(data){
                if(data.error === true) {
                    Tecnotek.showErrorMessage(data.message,true, "", false);
                } else {
                    $('#investigationRows').empty();
                    $('#investigationRows').append(data.entriesHtml);
                    Tecnotek.Curriculum.initializeInvestigationButtons();
                }
            },
            function(jqXHR, textStatus){
                Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
            }, true);
        //}
    },
    loadInvestigationsFullByTeacher: function() {

        $('#nameInvestigation').empty();
        //$('#typeInvestigation').empty();
        //$('#statusInvestigation').empty();
        $('#validityInvestigation').empty();
        $('#placeInvestigation').empty();
        $('#descriptorsInvestigation').empty();

       Tecnotek.ajaxCall(Tecnotek.UI.urls["loadInvestigationsFullByTeacherURL"],
            {   },
            function(data){
                if(data.error === true) {
                    Tecnotek.showErrorMessage(data.message,true, "", false);
                } else {
                    $('#investigationfullFormContainer').html(data.fullhtml);

                }
            },
            function(jqXHR, textStatus){
                Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
            }, true);
        //}
    },
    deleteInvestigation: function(investigationId){
        if(Tecnotek.showConfirmationQuestion("Esta seguro que desea eliminar este título?")) {
            Tecnotek.ajaxCall(Tecnotek.UI.urls["deleteInvestigationURL"],
                {   investigationId: investigationId },
                function(data){
                    if(data.error === true) {
                        Tecnotek.showErrorMessage(data.message,true, "", false);
                    } else {
                        $("#investigationRows_" + investigationId).fadeOut('slow', function(){});
                    }
                },
                function(jqXHR, textStatus){
                    Tecnotek.showErrorMessage("Error deleting relacion: " + textStatus + ".", true, "", false);
                }, true);
        }

    },
    initializeInvestigationButtons: function(){
        $('.deleteInvestigation').unbind();
        $('.deleteInvestigation').click(function(event){
            event.preventDefault();
            Tecnotek.Curriculum.deleteInvestigation($(this).attr("rel"));
        });
    },
    createInvestigation: function() {
        Tecnotek.ajaxCall(Tecnotek.UI.urls["createInvestigationURL"],
            {   name: $('#nameInvestigation').val(),
                status: $('#statusInvestigation').val(),
                place: $('#placeInvestigation').val(),
                descriptors: $('#descriptorsInvestigation').val(),
                validity: $('#validityInvestigation').val(),
                type: $('#typeInvestigation').val()
            },
            function(data){
                if(data.error === true) {
                    Tecnotek.showErrorMessage(data.message,true, "", false);
                } else {
                    $.fancybox.close();
                    Tecnotek.Curriculum.loadInvestigationsByTeacher();
                }
            },
            function(jqXHR, textStatus){
                Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                $(this).val("");
            }, true);
    },
    loadImprovesByTeacher: function() {
        //$('.editEntry').unbind();

        $('#nameImprove').empty();
        $('#yearImprove').empty();
        $('#subjectImprove').empty();
        $('#placeImprove').empty();
        $('#improveRows').empty();

        /*if(teacherId == 0){//Clean page
         console.debug("Clean page!!!");

         } else {*/

        Tecnotek.ajaxCall(Tecnotek.UI.urls["loadImprovesByTeacherURL"],
            {   },
            function(data){
                if(data.error === true) {
                    Tecnotek.showErrorMessage(data.message,true, "", false);
                } else {
                    $('#improveRows').empty();
                    $('#improveRows').append(data.entriesHtml);
                    Tecnotek.Curriculum.initializeImproveButtons();
                }
            },
            function(jqXHR, textStatus){
                Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
            }, true);
        //}
    },
    loadImprovesFullByTeacher: function() {

        $('#nameImprove').empty();
        $('#yearImprove').empty();
        $('#subjectImprove').empty();
        $('#placeImprove').empty();

        Tecnotek.ajaxCall(Tecnotek.UI.urls["loadImprovesFullByTeacherURL"],
            {   },
            function(data){
                if(data.error === true) {
                    Tecnotek.showErrorMessage(data.message,true, "", false);
                } else {
                    $('#improvefullFormContainer').html(data.fullhtml);

                }
            },
            function(jqXHR, textStatus){
                Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
            }, true);
        //}
    },
    deleteImprove: function(improveId){
        if(Tecnotek.showConfirmationQuestion("Esta seguro que desea eliminar este título?")) {
            Tecnotek.ajaxCall(Tecnotek.UI.urls["deleteImproveURL"],
                {   improveId: improveId },
                function(data){
                    if(data.error === true) {
                        Tecnotek.showErrorMessage(data.message,true, "", false);
                    } else {
                        $("#improveRows_" + improveId).fadeOut('slow', function(){});
                    }
                },
                function(jqXHR, textStatus){
                    Tecnotek.showErrorMessage("Error deleting relacion: " + textStatus + ".", true, "", false);
                }, true);
        }

    },
    initializeImproveButtons: function(){
        $('.deleteImprove').unbind();
        $('.deleteImprove').click(function(event){
            event.preventDefault();
            Tecnotek.Curriculum.deleteImprove($(this).attr("rel"));
        });
    },
    createImprove: function() {
        Tecnotek.ajaxCall(Tecnotek.UI.urls["createImproveURL"],
            {   name: $('#nameImprove').val(),
                subject: $('#subjectImprove').val(),
                place: $('#placeImprove').val(),
                year: $('#yearImprove').val()
            },
            function(data){
                if(data.error === true) {
                    Tecnotek.showErrorMessage(data.message,true, "", false);
                } else {
                    $.fancybox.close();
                    Tecnotek.Curriculum.loadImprovesByTeacher();
                }
            },
            function(jqXHR, textStatus){
                Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                $(this).val("");
            }, true);
    }

};