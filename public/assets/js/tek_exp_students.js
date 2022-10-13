var Tecnotek = Tecnotek || {};

Tecnotek.Students = {
    translates : {},
    init : function() {
        $('#searchText').keyup(function(event){
            Tecnotek.UI.vars["page"] = 1;
            Tecnotek.Students.searchStudents();
        });
        $('#btnSearch').unbind().click(function(event){
            Tecnotek.Students.searchStudents();
        });
        $(".sort_header").click(function() {
            Tecnotek.UI.vars["sortBy"] = $(this).attr("field-name");
            Tecnotek.UI.vars["order"] = $(this).attr("order");
            console.debug("Order by " + Tecnotek.UI.vars["sortBy"] + " " + Tecnotek.UI.vars["order"]);
            $(this).attr("order", Tecnotek.UI.vars["order"] == "asc"? "desc":"asc");
            $(".header-title").removeClass("asc").removeClass("desc").addClass("sortable");
            $(this).children().addClass(Tecnotek.UI.vars["order"]);
            Tecnotek.Students.searchStudents();
        });
        Tecnotek.UI.vars["order"] = "asc";
        Tecnotek.UI.vars["sortBy"] = "carne";
        Tecnotek.UI.vars["page"] = 1;
        Tecnotek.Students.searchStudents();
    },
    searchStudents: function() {
        $("#students-container").html("");
        $("#pagination-container").html("");
        Tecnotek.showWaiting();
        Tecnotek.uniqueAjaxCall(Tecnotek.UI.urls["search"],
            {
                text: $("#searchText").val(),
                sortBy: Tecnotek.UI.vars["sortBy"],
                order: Tecnotek.UI.vars["order"],
                page: Tecnotek.UI.vars["page"]
            },
            function(data){
                if(data.error === true) {
                    Tecnotek.hideWaiting();
                    Tecnotek.showErrorMessage(data.message,true, "", false);
                    //$("#new-relative-btn").hide();
                } else {
                    var baseHtml = $("#studentRowTemplate").html();
                    /*$data = "";
                    $data += '<p id="searchresults">';
                    $data += '    <span class="category">Estudiantes</span>';*/
                    for(i=0; i<data.students.length; i++) {
                        //console.debug(data.students[i]);
                        var row = '<div id="studentRowTemplate" class="row userRow ROW_CLASS" rel="STUDENT_ID">' +
                            baseHtml + '</div>';
                        row = row.replaceAll('ROW_CLASS', (i % 2 == 0? 'tableRowOdd':'tableRow'));
                        row = row.replaceAll('STUDENT_ID', data.students[i].id);
                        row = row.replaceAll('STUDENT_CARNE', data.students[i].carne);
                        row = row.replaceAll('STUDENT_FIRST_NAME', data.students[i].firstname);
                        row = row.replaceAll('STUDENT_LAST_NAME', data.students[i].lastname);
                        var group = (data.students[i].groupyear == null || data.students[i].groupyear == "null")? "":data.students[i].groupyear;
                        row = row.replaceAll('STUDENT_GROUP_YEAR', group);
                        if (data.students[i].gender == 1) {
                            row = row.replaceAll('STUDENT_GENDER', "Hombre");
                        } else {
                            row = row.replaceAll('STUDENT_GENDER', "Mujer");
                        }

                        $("#students-container").append(row);
                    }
                    Tecnotek.AdministratorList.initButtons();
                    Tecnotek.UI.printPagination(data.total, data.filtered, Tecnotek.UI.vars["page"], 30, "pagination-container");
                    $(".paginationButton").unbind().click(function() {
                        Tecnotek.UI.vars["page"] = $(this).attr("page");
                        Tecnotek.Students.searchStudents();
                    });
                    Tecnotek.hideWaiting();
                    //$data += '</p>';
                }
            },
            function(jqXHR, textStatus){
                if (textStatus != "abort") {
                    Tecnotek.hideWaiting();
                    console.debug("Error getting data: " + textStatus);
                }
            }, true, 'searchStudents');
    }
};
Tecnotek.Contacts = {
    translates : {},
    init : function() {
        $('#searchText').keyup(function(event){
            Tecnotek.UI.vars["page"] = 1;
            Tecnotek.Contacts.searchContacts();
        });
        $('#btnSearch').unbind().click(function(event){
            Tecnotek.Contacts.searchContacts();
        });
        $(".sort_header").click(function() {
            Tecnotek.UI.vars["sortBy"] = $(this).attr("field-name");
            Tecnotek.UI.vars["order"] = $(this).attr("order");
            console.debug("Order by " + Tecnotek.UI.vars["sortBy"] + " " + Tecnotek.UI.vars["order"]);
            $(this).attr("order", Tecnotek.UI.vars["order"] == "asc"? "desc":"asc");
            $(".header-title").removeClass("asc").removeClass("desc").addClass("sortable");
            $(this).children().addClass(Tecnotek.UI.vars["order"]);
            Tecnotek.Contacts.searchContacts();
        });
        Tecnotek.UI.vars["order"] = "asc";
        Tecnotek.UI.vars["sortBy"] = "identification";
        Tecnotek.UI.vars["page"] = 1;
        Tecnotek.Contacts.searchContacts();
    },
    searchContacts: function() {
        $("#students-container").html("");
        $("#pagination-container").html("");
        Tecnotek.showWaiting();
        Tecnotek.uniqueAjaxCall(Tecnotek.UI.urls["searchContacts"],
            {
                text: $("#searchText").val(),
                sortBy: Tecnotek.UI.vars["sortBy"],
                order: Tecnotek.UI.vars["order"],
                page: Tecnotek.UI.vars["page"]
            },
            function(data){
                if(data.error === true) {
                    Tecnotek.hideWaiting();
                    Tecnotek.showErrorMessage(data.message,true, "", false);
                    //$("#new-relative-btn").hide();
                } else {
                    var baseHtml = $("#contactRowTemplate").html();
                    /*$data = "";
                     $data += '<p id="searchresults">';
                     $data += '    <span class="category">Estudiantes</span>';*/
                    for(i=0; i<data.contacts.length; i++) {
                        //console.debug(data.students[i]);
                        var row = '<div id="contactRowTemplate" class="row userRow ROW_CLASS" rel="CONTACT_ID">' +
                            baseHtml + '</div>';
                        row = row.replaceAll('ROW_CLASS', (i % 2 == 0? 'tableRowOdd':'tableRow'));
                        row = row.replaceAll('CONTACT_ID', data.contacts[i].id);
                        row = row.replaceAll('CONTACT_CIDENTIFICATION', data.contacts[i].identification);
                        row = row.replaceAll('CONTACT_FIRST_NAME', data.contacts[i].firstname);
                        row = row.replaceAll('CONTACT_LAST_NAME', data.contacts[i].lastname);
                        /*var group = (data.students[i].groupyear == null || data.students[i].groupyear == "null")? "":data.students[i].groupyear;
                        row = row.replaceAll('STUDENT_GROUP_YEAR', group);
                        if (data.students[i].gender == 1) {
                            row = row.replaceAll('STUDENT_GENDER', "Hombre");
                        } else {
                            row = row.replaceAll('STUDENT_GENDER', "Mujer");
                        }*/

                        $("#students-container").append(row);
                    }
                    Tecnotek.ContactList.initButtons();
                    Tecnotek.UI.printPagination(data.total, data.filtered, Tecnotek.UI.vars["page"], 30, "pagination-container");
                    $(".paginationButton").unbind().click(function() {
                        Tecnotek.UI.vars["page"] = $(this).attr("page");
                        Tecnotek.Contacts.searchContacts();
                    });
                    Tecnotek.hideWaiting();
                    //$data += '</p>';
                }
            },
            function(jqXHR, textStatus){
                if (textStatus != "abort") {
                    Tecnotek.hideWaiting();
                    console.debug("Error getting data: " + textStatus);
                }
            }, true, 'searchContacts');
    }
};
Tecnotek.Program = {
    init : function() {

        Tecnotek.Program.initComponents();
        Tecnotek.Program.initButtons();
    },
    initComponents : function() {

        $('#program_form_teacher').children().remove();
        $('#program_form_teacher').append($('<option>', {selected : 'selected', value : $("#teacherid").val(), text: $("#teacherid").val()}));

        $("#program_form_status").val(1);
        $("#program_form_date").val($("#datetemp").val());


        //$("#tecnotek_expediente_programformtype_teacher").val($("#teacherid").val());

        //$('#tecnotek_expediente_programformtype_teacher').val('superadmin');
        //$('#tecnotek_expediente_programformtype_teacher').change();
        //alert($("#teacherid").val());
    },
    initButtons: function() {
    }
};
Tecnotek.Courses = {
    translates : {},
    init : function() {
        $('#searchText').keyup(function(event){
            Tecnotek.UI.vars["page"] = 1;
            Tecnotek.Courses.searchCourses();
        });
        $('#btnSearch').unbind().click(function(event){
            Tecnotek.Courses.searchCourses();
        });
        $(".sort_header").click(function() {
            Tecnotek.UI.vars["sortBy"] = $(this).attr("field-name");
            Tecnotek.UI.vars["order"] = $(this).attr("order");
            console.debug("Order by " + Tecnotek.UI.vars["sortBy"] + " " + Tecnotek.UI.vars["order"]);
            $(this).attr("order", Tecnotek.UI.vars["order"] == "asc"? "desc":"asc");
            $(".header-title").removeClass("asc").removeClass("desc").addClass("sortable");
            $(this).children().addClass(Tecnotek.UI.vars["order"]);
            Tecnotek.Courses.searchCourses();
        });

        $("#openCourseForm").fancybox({
            'beforeLoad' : function(){

            }
        });

        $("#openCourseView").fancybox({
            'beforeLoad' : function(){

            }
        });

        $("#openCourseEdit").fancybox({
            'beforeLoad' : function(){

            }
        });

        $('#courseForm').submit(function(event){
            event.preventDefault();
            Tecnotek.Courses.createCourse();
        });

        $('#courseFormEdit').submit(function(event){
            event.preventDefault();
            Tecnotek.Courses.updateCourse();
        });

        Tecnotek.UI.vars["order"] = "asc";
        Tecnotek.UI.vars["sortBy"] = "id";
        Tecnotek.UI.vars["page"] = 1;
        Tecnotek.Courses.searchCourses();

        Tecnotek.Courses.initButtons();

    },




    initButtons: function() {
        console.debug("CourseList :: initButtons");
        $('.viewButton').unbind();
        $('.viewButton').click(function(event){
            console.debug("Click en view button: " + Tecnotek.UI.urls["getInfoCourseFullURL"]);
            event.preventDefault();
            //Tecnotek.UI.vars["relativeId"] = $(this).attr("rel");
            Tecnotek.ajaxCall(Tecnotek.UI.urls["getInfoCourseFullURL"],
                {courseId: $(this).attr("rel")},
                function(data){
                    if(data.error === true) {
                        Tecnotek.showErrorMessage(data.message,true, "", false);
                    } else {
                        //Tecnotek.showInfoMessage(data.html, true, "", false);
                        $("#courseContainerView").html(data.html);
                        $("#openCourseView").trigger("click");
                        //alert("llega");
                    }
                },
                function(jqXHR, textStatus){
                    Tecnotek.showErrorMessage("Error in request: " + textStatus + ".",
                        true, "", false);
                }, true);
        });

        /*$('.questionsButton').unbind().click(function(event){
            event.preventDefault();
            location.href = Tecnotek.UI.urls["programQ"] + $(this).attr("rel");
            //location.href = Tecnotek.UI.urls["programQ"] + "/" + $(this).attr("rel");
        });*/

        $('.editButton').unbind().click(function(event){
            event.preventDefault();

                //var courseId = $(this).attr("rel");
            var courseId =0;
                Tecnotek.UI.vars["idCourseEdit"]  = courseId;
                Tecnotek.ajaxCall(Tecnotek.UI.urls["getInfoCourseURL"],
                {courseId: $(this).attr("rel")},
                function(data){
                    if(data.error === true) {
                        Tecnotek.showErrorMessage(data.message,true, "", false);
                    } else {
                        //Tecnotek.showInfoMessage(data.html, true, "", false);

                        $("#idCourseEdit").val(data.id);
                        $("#nameCourseEdit").val(data.name);
                        $("#codeCourseEdit").val(data.code);
                        $("#requisitCourseEdit").val(data.requisit);
                        $("#corequisiteCourseEdit").val(data.corequisite);
                        $("#creditCourseEdit").val(data.credit);
                        $("#areaCourseEdit").val(data.area);
                        $("#scheduleCourseEdit").val(data.schedule);
                        $("#groupnumberCourseEdit").val(data.groupnumber);
                        $("#classroomCourseEdit").val(data.classroom);
                        $("#sectionCourseEdit").val(data.section);
                        $("#statusCourseEdit").val(data.status);
                        $("#roomCourseEdit").val(data.room);

                        $("#teacherCourse").val(data.user);

                        $("#courseContainerEdit").html(data.html);
                        $("#openCourseEdit").trigger("click");
                        //alert("llega");
                    }
                },
                function(jqXHR, textStatus){
                    Tecnotek.showErrorMessage("Error in request: " + textStatus + ".",
                        true, "", false);
                }, true);

        });


    },


    searchCourses: function() {
        $("#students-container").html("");
        $("#pagination-container").html("");
        Tecnotek.showWaiting();
        Tecnotek.uniqueAjaxCall(Tecnotek.UI.urls["searchCourses"],
            {
                text: $("#searchText").val(),
                sortBy: Tecnotek.UI.vars["sortBy"],
                order: Tecnotek.UI.vars["order"],
                page: Tecnotek.UI.vars["page"]
            },
            function(data){
                if(data.error === true) {
                    Tecnotek.hideWaiting();
                    Tecnotek.showErrorMessage(data.message,true, "", false);
                } else {


                    var baseHtml = $("#contactRowTemplate").html();
                    for(i=0; i<data.courses.length; i++) {
                        var typeLabel = "Normal"
                        if(data.courses[i].type == 1){
                            var typeLabel = "Normal";
                        }
                        if(data.courses[i].type == 2){
                            var typeLabel = "Verano";
                        }
                        if(data.courses[i].type == 2){
                            var typeLabel = "Tutoria";
                        }

                        var row = '<div id="contactRowTemplate" class="row userRow ROW_CLASS" rel="COURSE_ID">' +
                            baseHtml +  '</div>';
                        row = row.replaceAll('ROW_CLASS', (i % 2 == 0? 'tableRowOdd':'tableRow'));
                        row = row.replaceAll('COURSE_ID', data.courses[i].id);
                        row = row.replaceAll('COURSE_CODE', data.courses[i].code);
                        row = row.replaceAll('COURSE_NAME', data.courses[i].name);
                        row = row.replaceAll('COURSE_TYPE', typeLabel);

                        $("#students-container").append(row);
                    }



                    Tecnotek.Courses.initButtons();
                    Tecnotek.UI.printPagination(data.total, data.filtered, Tecnotek.UI.vars["page"], 30, "pagination-container");
                    $(".paginationButton").unbind().click(function() {
                        Tecnotek.UI.vars["page"] = $(this).attr("page");
                        Tecnotek.Courses.searchCourses();
                    });
                    Tecnotek.hideWaiting();

                }
            },
            function(jqXHR, textStatus){
                if (textStatus != "abort") {
                    Tecnotek.hideWaiting();
                    console.debug("Error getting data Courses: " + textStatus);
                }
            }, true, 'searchCourses');
    },
    createCourse: function() {

        //alert($('#teacherCourse').val());
        Tecnotek.ajaxCall(Tecnotek.UI.urls["createCourseURL"],
            {   name: $('#nameCourse').val(),
                code: $('#codeCourse').val(),
                type: $('#typeCourse').val(),
                requisit: $('#requisitCourse').val(),
                corequisite: $('#corequisiteCourse').val(),
                credit: $('#creditCourse').val(),
                area: $('#areaCourse').val(),
                schedule: $('#scheduleCourse').val(),
                groupnumber: $('#groupnumberCourse').val(),
                classroom: $('#classroomCourse').val(),
                room: $('#roomCourse').val(),
                section: $('#sectionCourse').val(),
                status: $('#statusCourse').val(),
                user: $('#teacherCourseAdd').val()
            },
            function(data){
                if(data.error === true) {
                    Tecnotek.showErrorMessage(data.message,true, "", false);
                } else {
                    $.fancybox.close();
                   /* alert("Se ha guardado la informacion DEL CURSO."); */
                    location.reload(true); //agregado por stuart para hacer la recarga 29/04

                    Tecnotek.Courses.searchCourses();
                }
            },
            function(jqXHR, textStatus){
                Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                $(this).val("");
            }, true);
    },
    updateCourse: function() {
        Tecnotek.ajaxCall(Tecnotek.UI.urls["updateCourseURL"],
            {   courseId: $('#idCourseEdit').val(),
                name: $('#nameCourseEdit').val(),
                code: $('#codeCourseEdit').val(),
                type: $('#typeCourseEdit').val(),
                requisit: $('#requisitCourseEdit').val(),
                corequisite: $('#corequisiteCourseEdit').val(),
                credit: $('#creditCourseEdit').val(),
                area: $('#areaCourseEdit').val(),
                schedule: $('#scheduleCourseEdit').val(),
                groupnumber: $('#groupnumberCourseEdit').val(),
                classroom: $('#classroomCourseEdit').val(),
                room: $('#roomCourseEdit').val(),
                section: $('#sectionCourseEdit').val(),
                status: $('#statusCourseEdit').val(),
                user: $('#teacherCourse').val()
            },
            function(data){
                if(data.error === true) {
                    Tecnotek.showErrorMessage(data.message,true, "", false);
                } else {
                    $.fancybox.close();
                    Tecnotek.Courses.searchCourses();
                }
            },
            function(jqXHR, textStatus){
                Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                $(this).val("");
            }, true);
    }
};
Tecnotek.Record = {
    translates : {},
    init : function() {
        $('#searchText').keyup(function(event){
            Tecnotek.UI.vars["page"] = 1;
            Tecnotek.Record.searchRecords();
        });
        $('#btnSearch').unbind().click(function(event){
            Tecnotek.Record.searchRecords();
        });





        $(".sort_header").click(function() {
            Tecnotek.UI.vars["sortBy"] = $(this).attr("field-name");
            Tecnotek.UI.vars["order"] = $(this).attr("order");
            console.debug("Order by " + Tecnotek.UI.vars["sortBy"] + " " + Tecnotek.UI.vars["order"]);
            $(this).attr("order", Tecnotek.UI.vars["order"] == "asc"? "desc":"asc");
            $(".header-title").removeClass("asc").removeClass("desc").addClass("sortable");
            $(this).children().addClass(Tecnotek.UI.vars["order"]);
            Tecnotek.Record.searchRecords();
        });

        $("#openRecordForm").fancybox({
            'beforeLoad' : function(){

            }
        });




        $("#openRecordView").fancybox({
            'beforeLoad' : function(){

            }
        });

        $("#openRecordEdit").fancybox({
            'beforeLoad' : function(){

            }
        });

        $('#recordForm').submit(function(event){
            event.preventDefault();
            Tecnotek.Record.createRecord();
        });

        $('#recordFormEdit').submit(function(event){
            event.preventDefault();
            Tecnotek.Record.updateRecord();
        });

        Tecnotek.UI.vars["order"] = "desc";
        Tecnotek.UI.vars["sortBy"] = "id";
        Tecnotek.UI.vars["page"] = 1;
        Tecnotek.Record.searchRecords();


        Tecnotek.Record.initButtons();

    },
    initOButtons: function() {


        $('#openRecTba1').click(function(event){
            Tecnotek.Record.anzeigen($(this).attr("rel"));
        });
        $('#openRecTba2').click(function(event){
            Tecnotek.Record.anzeigen($(this).attr("rel"));
        });
        $('#openRecTba3').click(function(event){
            Tecnotek.Record.anzeigen($(this).attr("rel"));
        });
        $('#openRecTba4').click(function(event){
            Tecnotek.Record.anzeigen($(this).attr("rel"));
        });

    },
    initButtons: function() {

        console.debug("RecordList :: initButtons");
        $('.viewButton').unbind();
        $('.viewButton').click(function(event){
            console.debug("Click en view button: " + Tecnotek.UI.urls["getInfoRecordFullURL"]);
            event.preventDefault();
            //Tecnotek.UI.vars["relativeId"] = $(this).attr("rel");
            Tecnotek.ajaxCall(Tecnotek.UI.urls["getInfoRecordFullURL"],
                {recordId: $(this).attr("rel")},
                function(data){
                    if(data.error === true) {
                        Tecnotek.showErrorMessage(data.message,true, "", false);
                    } else {
                        //Tecnotek.showInfoMessage(data.html, true, "", false);
                        $("#recordContainerView").html(data.html);
                        $("#openRecordView").trigger("click");
                        Tecnotek.Record.initOButtons();
                    }
                },
                function(jqXHR, textStatus){
                    Tecnotek.showErrorMessage("Error in request: " + textStatus + ".",
                        true, "", false);
                }, true);

        });

        $('.editButton').unbind();
        $('.editButton').click(function(event){
            event.preventDefault();

            var recordId = $(this).attr("rel");
            Tecnotek.UI.vars["idRecordEdit"]  = recordId;
            Tecnotek.ajaxCall(Tecnotek.UI.urls["getInfoRecordURL"],
                {recordId: $(this).attr("rel")},
                function(data){
                    if(data.error === true) {
                        Tecnotek.showErrorMessage(data.message,true, "", false);
                    } else {
                        //Tecnotek.showInfoMessage(data.html, true, "", false);

                        $("#idRecordEdit").val(data.id);
                        $("#nameRecordEdit").val(data.name);
                        $("#summaryRecordEdit").val(data.summary);

                        $("#typeRecordEdit").val(data.type);
                        //$('#typeRecordEdit').setAttribute(selectec )

                        //$("#typeRecordEdit").val(data.type);
                        //$("#dateRecordEdit").val(data.date);

                        $("#recordContainerEdit").html(data.html);
                        $("#openRecordEdit").trigger("click");
                        //alert("llega");
                    }
                },
                function(jqXHR, textStatus){
                    Tecnotek.showErrorMessage("Error in request: " + textStatus + ".",
                        true, "", false);
                }, true);

        });

    },

    anzeigen: function (name)
    {
        var elem = document.getElementById("p"+name);
        if (elem.style.display=="") {
            elem.style.display="none";
        } else {
            elem.style.display="";
        }
        var elem2 = document.getElementById("pb"+name);
        if (elem.style.display=="") {
            elem2.src = "/images/folder2.png";
        } else {
            elem2.src = "/images/folder.png";
        }
    },

    searchRecords: function() {
        $("#students-container").html("");
        $("#pagination-container").html("");
        Tecnotek.showWaiting();
        Tecnotek.uniqueAjaxCall(Tecnotek.UI.urls["searchRecords"],
            {
                text: $("#searchText").val(),
                sortBy: Tecnotek.UI.vars["sortBy"],
                order: Tecnotek.UI.vars["order"],
                page: Tecnotek.UI.vars["page"]
            },
            function(data){
                if(data.error === true) {
                    Tecnotek.hideWaiting();
                    Tecnotek.showErrorMessage(data.message,true, "", false);
                } else {


                    var baseHtml = $("#contactRowTemplate").html();
                    var status = "";



                    for(i=0; i<data.records.length; i++) {

                        if( data.records[i].status == 1){
                            var status = "Creado";
                        }else {
                            var status = "Cerrado";
                        }

                        var row = '<div id="contactRowTemplate" class="row userRow ROW_CLASS" rel="RECORD_ID">' +
                            baseHtml +  '</div>';
                        row = row.replaceAll('ROW_CLASS', (i % 2 == 0? 'tableRowOdd':'tableRow'));
                        row = row.replaceAll('RECORD_ID', data.records[i].id);
                        row = row.replaceAll('RECORD_NAME', data.records[i].name);
                        row = row.replaceAll('RECORD_DATE', data.records[i].date);
                        row = row.replaceAll('RECORD_STATUS',status);

                        $("#students-container").append(row);
                    }



                    Tecnotek.Record.initButtons();
                    Tecnotek.UI.printPagination(data.total, data.filtered, Tecnotek.UI.vars["page"], 30, "pagination-container");
                    $(".paginationButton").unbind().click(function() {
                        Tecnotek.UI.vars["page"] = $(this).attr("page");
                        Tecnotek.Record.searchRecords();
                    });
                    Tecnotek.hideWaiting();

                }
            },
            function(jqXHR, textStatus){
                if (textStatus != "abort") {
                    Tecnotek.hideWaiting();
                    console.debug("Error getting data Records: " + textStatus);
                }
            }, true, 'searchRecords');
    },
    createRecord: function() {

        //alert($('#teacherRecord').val());
        Tecnotek.ajaxCall(Tecnotek.UI.urls["createRecordURL"],
            {   name: $('#nameRecord').val(),
                summary: $('#summaryRecord').val(),
                type: $('#typeRecord').val()
            },
            function(data){
                if(data.error === true) {
                    Tecnotek.showErrorMessage(data.message,true, "", false);
                } else {
                    $.fancybox.close();
                    Tecnotek.Record.searchRecords();
                }
            },
            function(jqXHR, textStatus){
                Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                $(this).val("");
            }, true);
    },
    updateRecord: function() {
        if($('#idRecordEdit').val() != 0) {
            Tecnotek.ajaxCall(Tecnotek.UI.urls["updateRecordURL"],
                {
                    recordId: $('#idRecordEdit').val(),
                    name: $('#nameRecordEdit').val(),
                    summary: $('#summaryRecordEdit').val(),
                    status: $('#statusRecordEdit').val(),
                    type: $('#typeRecordEdit').val()
                },
                function (data) {
                    if (data.error === true) {
                        Tecnotek.showErrorMessage(data.message, true, "", false);
                    } else {
                        $.fancybox.close();
                        Tecnotek.Record.searchRecords();
                    }
                },
                function (jqXHR, textStatus) {
                    Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                    $(this).val("");
                }, true);
        }
    }
};
Tecnotek.Items = {
    translates : {},
    init : function() {
        $('#searchText').keyup(function(event){
            Tecnotek.UI.vars["page"] = 1;
            Tecnotek.Items.searchItems();
        });
        $('#btnSearch').unbind().click(function(event){
            Tecnotek.Items.searchItems();
        });
        $(".sort_header").click(function() {
            Tecnotek.UI.vars["sortBy"] = $(this).attr("field-name");
            Tecnotek.UI.vars["order"] = $(this).attr("order");
            console.debug("Order by " + Tecnotek.UI.vars["sortBy"] + " " + Tecnotek.UI.vars["order"]);
            $(this).attr("order", Tecnotek.UI.vars["order"] == "asc"? "desc":"asc");
            $(".header-title").removeClass("asc").removeClass("desc").addClass("sortable");
            $(this).children().addClass(Tecnotek.UI.vars["order"]);
            Tecnotek.Items.searchItems();
        });

        $("#openItemForm").fancybox({
            'beforeLoad' : function(){

            }
        });

        $("#openItemView").fancybox({
            'beforeLoad' : function(){

            }
        });

        $("#openItemEdit").fancybox({
            'beforeLoad' : function(){

            }
        });

        $('#itemForm').submit(function(event){
            event.preventDefault();
            Tecnotek.Items.createItem();
        });

        $('#itemFormEdit').submit(function(event){
            event.preventDefault();
            Tecnotek.Items.updateItem();
        });

        Tecnotek.UI.vars["order"] = "asc";
        Tecnotek.UI.vars["sortBy"] = "id";
        Tecnotek.UI.vars["page"] = 1;
        Tecnotek.Items.searchItems();

    },




    initButtons: function() {
        console.debug("ItemList :: initButtons");
        $('.viewButton').unbind();
        $('.viewButton').click(function(event){
            console.debug("Click en view button: " + Tecnotek.UI.urls["getInfoItemFullURL"]);
            event.preventDefault();
            //Tecnotek.UI.vars["relativeId"] = $(this).attr("rel");
            Tecnotek.ajaxCall(Tecnotek.UI.urls["getInfoItemFullURL"],
                {itemId: $(this).attr("rel")},
                function(data){
                    if(data.error === true) {
                        Tecnotek.showErrorMessage(data.message,true, "", false);
                    } else {
                        //Tecnotek.showInfoMessage(data.html, true, "", false);
                        $("#itemContainerView").html(data.html);
                        $("#openItemView").trigger("click");
                        //alert("llega");
                    }
                },
                function(jqXHR, textStatus){
                    Tecnotek.showErrorMessage("Error in request: " + textStatus + ".",
                        true, "", false);
                }, true);
        });

        $('.editButton').unbind();
        $('.editButton').click(function(event){
            event.preventDefault();

            var itemId = $(this).attr("rel");
            Tecnotek.UI.vars["idItemEdit"]  = itemId;
            Tecnotek.ajaxCall(Tecnotek.UI.urls["getInfoItemURL"],
                {itemId: $(this).attr("rel")},
                function(data){
                    if(data.error === true) {
                        Tecnotek.showErrorMessage(data.message,true, "", false);
                    } else {
                        //Tecnotek.showInfoMessage(data.html, true, "", false);

                        $("#idItemEdit").val(data.id);
                        $("#nameItemEdit").val(data.name);
                        $("#codeItemEdit").val(data.code);
                        $("#descriptionItemEdit").val(data.description);

                        $("#categoryItemEdit").val(data.category);

                        $("#itemContainerEdit").html(data.html);
                        $("#openItemEdit").trigger("click");
                        //alert("llega");
                    }
                },
                function(jqXHR, textStatus){
                    Tecnotek.showErrorMessage("Error in request: " + textStatus + ".",
                        true, "", false);
                }, true);

        });


    },


    searchItems: function() {
        $("#students-container").html("");
        $("#pagination-container").html("");
        Tecnotek.showWaiting();
        Tecnotek.uniqueAjaxCall(Tecnotek.UI.urls["searchItems"],
            {
                text: $("#searchText").val(),
                sortBy: Tecnotek.UI.vars["sortBy"],
                order: Tecnotek.UI.vars["order"],
                page: Tecnotek.UI.vars["page"]
            },
            function(data){
                if(data.error === true) {
                    Tecnotek.hideWaiting();
                    Tecnotek.showErrorMessage(data.message,true, "", false);
                } else {


                    var baseHtml = $("#contactRowTemplate").html();
                    for(i=0; i<data.items.length; i++) {
                        var typeLabel = "Normal"
                        if(data.items[i].type == 1){
                            var typeLabel = "En bodega";
                        }
                        if(data.items[i].type == 2){
                            var typeLabel = "En prestamo";
                        }
                        if(data.items[i].type == 3){
                            var typeLabel = "Dañado";
                        }

                        var row = '<div id="contactRowTemplate" class="row userRow ROW_CLASS" rel="ITEM_ID">' +
                            baseHtml +  '</div>';
                        row = row.replaceAll('ROW_CLASS', (i % 2 == 0? 'tableRowOdd':'tableRow'));
                        row = row.replaceAll('ITEM_ID', data.items[i].id);
                        row = row.replaceAll('ITEM_CODE', data.items[i].code);
                        row = row.replaceAll('ITEM_NAME', data.items[i].name);
                        row = row.replaceAll('ITEM_STATUS', typeLabel);

                        $("#students-container").append(row);
                    }



                    Tecnotek.Items.initButtons();
                    Tecnotek.UI.printPagination(data.total, data.filtered, Tecnotek.UI.vars["page"], 30, "pagination-container");
                    $(".paginationButton").unbind().click(function() {
                        Tecnotek.UI.vars["page"] = $(this).attr("page");
                        Tecnotek.Items.searchItems();
                    });
                    Tecnotek.hideWaiting();

                }
            },
            function(jqXHR, textStatus){
                if (textStatus != "abort") {
                    Tecnotek.hideWaiting();
                    console.debug("Error getting data Items: " + textStatus);
                }
            }, true, 'searchItems');
    },
    createItem: function() {

        Tecnotek.ajaxCall(Tecnotek.UI.urls["createItemURL"],
            {   name: $('#nameItem').val(),
                code: $('#codeItem').val(),
                description: $('#descriptionItem').val(),
                category: $('#categoryItem').val(),

            },
            function(data){
                if(data.error === true) {
                    Tecnotek.showErrorMessage(data.message,true, "", false);
                } else {
                    $.fancybox.close();
                    Tecnotek.Items.searchItems();
                }
            },
            function(jqXHR, textStatus){
                Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                $(this).val("");
            }, true);
    },
    updateItem: function() {
        Tecnotek.ajaxCall(Tecnotek.UI.urls["updateItemURL"],
            {   itemId: $('#idItemEdit').val(),
                name: $('#nameItemEdit').val(),
                code: $('#codeItemEdit').val(),
                description: $('#descriptionItemEdit').val(),
                category: $('#categoryItemEdit').val()
            },
            function(data){
                if(data.error === true) {
                    Tecnotek.showErrorMessage(data.message,true, "", false);
                } else {
                    $.fancybox.close();
                    Tecnotek.Items.searchItems();
                }
            },
            function(jqXHR, textStatus){
                Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                $(this).val("");
            }, true);
    }
};

Tecnotek.Category = {
    translates : {},
    init : function() {
        $('#searchText').keyup(function(event){
            Tecnotek.UI.vars["page"] = 1;
            Tecnotek.Category.searchCategories();
        });
        $('#btnSearch').unbind().click(function(event){
            Tecnotek.Category.searchCategories();
        });
        $(".sort_header").click(function() {
            Tecnotek.UI.vars["sortBy"] = $(this).attr("field-name");
            Tecnotek.UI.vars["order"] = $(this).attr("order");
            console.debug("Order by " + Tecnotek.UI.vars["sortBy"] + " " + Tecnotek.UI.vars["order"]);
            $(this).attr("order", Tecnotek.UI.vars["order"] == "asc"? "desc":"asc");
            $(".header-title").removeClass("asc").removeClass("desc").addClass("sortable");
            $(this).children().addClass(Tecnotek.UI.vars["order"]);
            Tecnotek.Category.searchCategories();
        });

        $("#openCategoryForm").fancybox({
            'beforeLoad' : function(){

            }
        });

        $("#openCategoryView").fancybox({
            'beforeLoad' : function(){

            }
        });

        $("#openCategoryEdit").fancybox({
            'beforeLoad' : function(){

            }
        });

        $('#categoryForm').submit(function(event){
            event.preventDefault();
            Tecnotek.Category.createCategory();
        });

        $('#categoryFormEdit').submit(function(event){
            event.preventDefault();
            Tecnotek.Category.updateCategory();
        });

        Tecnotek.UI.vars["order"] = "asc";
        Tecnotek.UI.vars["sortBy"] = "id";
        Tecnotek.UI.vars["page"] = 1;
        Tecnotek.Category.searchCategories();

    },




    initButtons: function() {
        console.debug("CategoryList :: initButtons");
        $('.viewButton').unbind();
        $('.viewButton').click(function(event){
            console.debug("Click en view button: " + Tecnotek.UI.urls["getInfoCategoryFullURL"]);
            event.preventDefault();
            //Tecnotek.UI.vars["relativeId"] = $(this).attr("rel");
            Tecnotek.ajaxCall(Tecnotek.UI.urls["getInfoCategoryFullURL"],
                {categoryId: $(this).attr("rel")},
                function(data){
                    if(data.error === true) {
                        Tecnotek.showErrorMessage(data.message,true, "", false);
                    } else {
                        //Tecnotek.showInfoMessage(data.html, true, "", false);
                        $("#categoryContainerView").html(data.html);
                        $("#openCategoryView").trigger("click");
                        //alert("llega");
                    }
                },
                function(jqXHR, textStatus){
                    Tecnotek.showErrorMessage("Error in request: " + textStatus + ".",
                        true, "", false);
                }, true);
        });

        $('.editButton').unbind();
        $('.editButton').click(function(event){
            event.preventDefault();

            var categoryId = $(this).attr("rel");
            Tecnotek.UI.vars["idCategoryEdit"]  = categoryId;
            Tecnotek.ajaxCall(Tecnotek.UI.urls["getInfoCategoryURL"],
                {categoryId: $(this).attr("rel")},
                function(data){
                    if(data.error === true) {
                        Tecnotek.showErrorMessage(data.message,true, "", false);
                    } else {
                        //Tecnotek.showInfoMessage(data.html, true, "", false);

                        $("#idCategoryEdit").val(data.id);
                        $("#nameCategoryEdit").val(data.name);
                        $("#codeCategoryEdit").val(data.code);
                        $("#descriptionCategoryEdit").val(data.description);


                        $("#categoryContainerEdit").html(data.html);
                        $("#openCategoryEdit").trigger("click");
                        //alert("llega");
                    }
                },
                function(jqXHR, textStatus){
                    Tecnotek.showErrorMessage("Error in request: " + textStatus + ".",
                        true, "", false);
                }, true);

        });


    },


    searchCategories: function() {
        $("#students-container").html("");
        $("#pagination-container").html("");
        Tecnotek.showWaiting();
        Tecnotek.uniqueAjaxCall(Tecnotek.UI.urls["searchCategories"],
            {
                text: $("#searchText").val(),
                sortBy: Tecnotek.UI.vars["sortBy"],
                order: Tecnotek.UI.vars["order"],
                page: Tecnotek.UI.vars["page"]
            },
            function(data){
                if(data.error === true) {
                    Tecnotek.hideWaiting();
                    Tecnotek.showErrorMessage(data.message,true, "", false);
                } else {


                    var baseHtml = $("#contactRowTemplate").html();
                    for(i=0; i<data.categories.length; i++) {

                        var row = '<div id="contactRowTemplate" class="row userRow ROW_CLASS" rel="CATEGORY_ID">' +
                            baseHtml +  '</div>';
                        row = row.replaceAll('ROW_CLASS', (i % 2 == 0? 'tableRowOdd':'tableRow'));
                        row = row.replaceAll('CATEGORY_ID', data.categories[i].id);
                        row = row.replaceAll('CATEGORY_CODE', data.categories[i].code);
                        row = row.replaceAll('CATEGORY_NAME', data.categories[i].name);
                        $("#students-container").append(row);
                    }



                    Tecnotek.Category.initButtons();
                    Tecnotek.UI.printPagination(data.total, data.filtered, Tecnotek.UI.vars["page"], 30, "pagination-container");
                    $(".paginationButton").unbind().click(function() {
                        Tecnotek.UI.vars["page"] = $(this).attr("page");
                        Tecnotek.Category.searchCategories();
                    });
                    Tecnotek.hideWaiting();

                }
            },
            function(jqXHR, textStatus){
                if (textStatus != "abort") {
                    Tecnotek.hideWaiting();
                    console.debug("Error getting data Categories: " + textStatus);
                }
            }, true, 'searchCategories');
    },
    createCategory: function() {

        Tecnotek.ajaxCall(Tecnotek.UI.urls["createCategoryURL"],
            {   name: $('#nameCategory').val(),
                code: $('#codeCategory').val(),
                description: $('#descriptionCategory').val()

            },
            function(data){
                if(data.error === true) {
                    Tecnotek.showErrorMessage(data.message,true, "", false);
                } else {
                    $.fancybox.close();
                    Tecnotek.Category.searchCategories();
                }
            },
            function(jqXHR, textStatus){
                Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                $(this).val("");
            }, true);
    },
    updateCategory: function() {
        Tecnotek.ajaxCall(Tecnotek.UI.urls["updateCategoryURL"],
            {   categoryId: $('#idCategoryEdit').val(),
                name: $('#nameCategoryEdit').val(),
                code: $('#codeCategoryEdit').val(),
                description: $('#descriptionCategoryEdit').val()
            },
            function(data){
                if(data.error === true) {
                    Tecnotek.showErrorMessage(data.message,true, "", false);
                } else {
                    $.fancybox.close();
                    Tecnotek.Category.searchCategories();
                }
            },
            function(jqXHR, textStatus){
                Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                $(this).val("");
            }, true);
    }
};
Tecnotek.Ticket = {
    translates : {},
    init : function() {
        $('#searchText').keyup(function(event){
            Tecnotek.UI.vars["page"] = 1;
            Tecnotek.Ticket.searchTickets();
        });

        $('#computerTicket').change(function(event){
            if($('#computerTicket').prop("checked") == true){
                $('#ncomputerTicket').val(1);
                $('#ncomputerTicket').prop('disabled', false);
            }
            else{
                $('#ncomputerTicket').empty();
                $('#ncomputerTicket').val("");
                $('#ncomputerTicket').prop('disabled', true);
            }
        });

        $('#videobeamTicket').change(function(event){
            if($('#videobeamTicket').prop("checked") == true){
                $('#nvideobeamTicket').val(1);
                $('#nvideobeamTicket').prop('disabled', false);
            }
            else{
                $('#nvideobeamTicket').empty();
                $('#nvideobeamTicket').val("");
                $('#nvideobeamTicket').prop('disabled', true);
            }
        });

        $('#camaraTicket').change(function(event){
            if($('#camaraTicket').prop("checked") == true){
                $('#ncamaraTicket').val(1);
                $('#ncamaraTicket').prop('disabled', false);
            }
            else{
                $('#ncamaraTicket').empty();
                $('#ncamaraTicket').val("");
                $('#ncamaraTicket').prop('disabled', true);
            }
        });


        $('#computerTicketEdit').change(function(event){
            if($('#computerTicketEdit').prop("checked") == true){
                $('#ncomputerTicketEdit').val(1);
                $('#ncomputerTicketEdit').prop('disabled', false);
            }
            else{
                $('#ncomputerTicketEdit').empty();
                $('#ncomputerTicketEdit').val("");
                $('#ncomputerTicketEdit').prop('disabled', true);
            }
        });

        $('#videobeamTicketEdit').change(function(event){
            if($('#videobeamTicketEdit').prop("checked") == true){
                $('#nvideobeamTicketEdit').val(1);
                $('#nvideobeamTicketEdit').prop('disabled', false);
            }
            else{
                $('#nvideobeamTicketEdit').empty();
                $('#nvideobeamTicketEdit').val("");
                $('#nvideobeamTicketEdit').prop('disabled', true);
            }
        });

        $('#camaraTicketEdit').change(function(event){
            if($('#camaraTicketEdit').prop("checked") == true){
                $('#ncamaraTicketEdit').val(1);
                $('#ncamaraTicketEdit').prop('disabled', false);
            }
            else{
                $('#ncamaraTicketEdit').empty();
                $('#ncamaraTicketEdit').val("");
                $('#ncamaraTicketEdit').prop('disabled', true);
            }
        });


        $('#btnSearch').unbind().click(function(event){
            Tecnotek.Ticket.searchTickets();
        });
        $(".sort_header").click(function() {
            Tecnotek.UI.vars["sortBy"] = $(this).attr("field-name");
            Tecnotek.UI.vars["order"] = $(this).attr("order");
            console.debug("Order by " + Tecnotek.UI.vars["sortBy"] + " " + Tecnotek.UI.vars["order"]);
            $(this).attr("order", Tecnotek.UI.vars["order"] == "asc"? "desc":"asc");
            $(".header-title").removeClass("asc").removeClass("desc").addClass("sortable");
            $(this).children().addClass(Tecnotek.UI.vars["order"]);
            Tecnotek.Ticket.searchTickets();
        });

        $("#openTicketForm").fancybox({
            'beforeLoad' : function(){

            }
        });

        $("#openTicketView").fancybox({
            'beforeLoad' : function(){

            }
        });

        $("#openTicketActionView").fancybox({
            'beforeLoad' : function(){

            }
        });

        $("#openTicketEdit").fancybox({
            'beforeLoad' : function(){

            }
        });

        $('#ticketForm').submit(function(event){
            event.preventDefault();
            Tecnotek.Ticket.createTicket();
        });

        $('#ticketFormEdit').submit(function(event){
            event.preventDefault();
            Tecnotek.Ticket.updateTicket();
        });

        $('#messageform').submit(function(event){
            event.preventDefault();
            Tecnotek.Ticket.saveTicketMessage();
        });


        $('#searchBox').keyup(function(event){
            event.preventDefault();
            if($(this).val().length == 0) {
                $('#suggestions').fadeOut(); // Hide the suggestions box
            } else {
                Tecnotek.ajaxCall(Tecnotek.UI.urls["getItemsURL"],
                    {text: $(this).val(), ticketId: Tecnotek.UI.vars["ticketId"]},
                    function(data){
                        if(data.error === true) {
                            Tecnotek.showErrorMessage(data.message,true, "", false);
                        } else {
                            $data = "";
                            $data += '<p id="searchresults">';
                            $data += '    <span class="category">Items</span>';
                            for(i=0; i<data.items.length; i++) {
                                $data += '    <a class="searchResult" rel="' + data.items[i].id + '" name="' +
                                    data.items[i].name + ' ' + data.items[i].code + '">';
                                $data += '      <span class="searchheading">' + ' ' + data.items[i].code +  ' ' + data.items[i].name +  '</span>';
                                $data += '      <span>Incluir este item.</span>';
                                $data += '    </a>';
                            }
                            $data += '</p>';

                            $('#suggestions').fadeIn(); // Show the suggestions box
                            $('#suggestions').html($data); // Fill the suggestions box
                            $('.searchResult').unbind();
                            $('.searchResult').click(function(event){
                                event.preventDefault();
                                Tecnotek.UI.vars["itemId"] = $(this).attr("rel");
                                Tecnotek.UI.vars["itemName"] = $(this).attr("name");


                                Tecnotek.ajaxCall(Tecnotek.UI.urls["setItemToTicket"],
                                    {itemId: $(this).attr("rel"), ticketId: Tecnotek.UI.vars["ticketId"]},
                                    function(data){
                                        if(data.error === true) {
                                            Tecnotek.showErrorMessage(data.message,true, "", false);
                                        } else {
                                            $html = '<div id="item_row_' + data.id + '" class="row userRow" rel="' + data.id + '">';
                                            $html += '<div class="option_width" style="float: left; width: 200px;">' + Tecnotek.UI.vars["itemName"] + '</div>';
                                            $html += '<div class="right imageButton deleteButton deleteItemOfTicket" style="height: 16px;"  title="delete???"  rel="' + data.id + '"></div>';
                                            $html += '<div class="clear"></div>';
                                            $html += '</div>';
                                            $("#itemsList").append($html);

                                            $(this).val("");
                                            $('#suggestions').fadeOut();

                                            Tecnotek.Ticket.initDeleteButtonsOfItems();

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

        Tecnotek.UI.vars["order"] = "asc";
        Tecnotek.UI.vars["sortBy"] = "id";
        Tecnotek.UI.vars["page"] = 1;
        Tecnotek.Ticket.searchTickets();

    },
    initDeleteButtonsOfItems: function(){
        $(".deleteItemOfTicket").unbind();
        $(".deleteItemOfTicket").click(function(event){
            event.preventDefault();
            $ticketItemId = $(this).attr("rel");
            Tecnotek.ajaxCall(Tecnotek.UI.urls["removeItemFromTicketURL"],
                {ticketItemId: $ticketItemId, itemId: Tecnotek.UI.vars["itemId"], ticketId: Tecnotek.UI.vars["ticketId"]},
                function(data){
                    if(data.error === true) {
                        Tecnotek.showErrorMessage(data.message,true, "", false);
                    } else {
                        $("#item_row_" + $ticketItemId).empty().remove();
                    }
                },
                function(jqXHR, textStatus){
                    Tecnotek.showErrorMessage("Error executing request: " + textStatus + ".",
                        true, "", false);
                }, true);
        });
    },
    initButtons: function() {
        console.debug("TicketList :: initButtons");
        $('.viewButton').unbind();
        $('.viewButton').click(function(event){
            console.debug("Click en view button: " + Tecnotek.UI.urls["getInfoTicketFullURL"]);
            event.preventDefault();
            //Tecnotek.UI.vars["relativeId"] = $(this).attr("rel");
            Tecnotek.ajaxCall(Tecnotek.UI.urls["getInfoTicketFullURL"],
                {ticketId: $(this).attr("rel")},
                function(data){
                    if(data.error === true) {
                        Tecnotek.showErrorMessage(data.message,true, "", false);
                    } else {
                        //Tecnotek.showInfoMessage(data.html, true, "", false);
                        $("#ticketContainerView").html(data.html);
                        $("#openTicketView").trigger("click");
                    }
                },
                function(jqXHR, textStatus){
                    Tecnotek.showErrorMessage("Error in request: " + textStatus + ".",
                        true, "", false);
                }, true);
        });

        $('.editButton').unbind();
        $('.editButton').click(function(event){
            event.preventDefault();

            var ticketId = $(this).attr("rel");

            Tecnotek.UI.vars["idTicketEdit"]  = ticketId;
            Tecnotek.ajaxCall(Tecnotek.UI.urls["getInfoTicketURL"],
                {ticketId: $(this).attr("rel")},
                function(data){
                    if(data.error === true) {
                        Tecnotek.showErrorMessage(data.message,true, "", false);
                    } else {
                        //Tecnotek.showInfoMessage(data.html, true, "", false);

                        $("#idTicketEdit").val(data.id);
                        $("#commentsTicketEdit").val(data.comments);
                        $("#authorizedTicketEdit").val(data.authorized);


                        $("#dateTicketEdit").val(data.dateTicket);
                        $("#timeTicketEdit").val(data.hourTicket);

                        //$('#computerTicketEdit').setAttribute('checked','checked');
                        if(data.computer == 1){
                            $("#computerTicketEdit").prop("checked", true);
                            $("#ncomputerTicketEdit").val(data.ncomputer);
                            $('#ncomputerTicketEdit').prop('disabled', false);
                        }else{
                            $("#computerTicketEdit").prop("checked", false);
                            $("#ncomputerTicketEdit").val("");
                            $('#ncomputerTicketEdit').prop('disabled', true);
                        }

                        if(data.camara == 1){
                            $("#camaraTicketEdit").prop("checked", true);
                            $("#ncamaraTicketEdit").val(data.ncamara);
                            $('#ncamaraTicketEdit').prop('disabled', false);
                        }else{
                            $("#camaraTicketEdit").prop("checked", false);
                            $("#ncamaraTicketEdit").val("");
                            $('#ncamaraTicketEdit').prop('disabled', true);
                        }

                        if(data.videobeam == 1){
                            $("#videobeamTicketEdit").prop("checked", true);
                            $("#nvideobeamTicketEdit").val(data.nvideobeam);
                            $('#nvideobeamTicketEdit').prop('disabled', false);
                        }else{
                            $("#videobeamTicketEdit").prop("checked", false);
                            $("#nvideobeamTicketEdit").val("");
                            $('#nvideobeamTicketEdit').prop('disabled', true);
                        }

                        if(data.control == 1){
                            $("#controlTicketEdit").prop("checked", true);
                        }else{
                            $("#controlTicketEdit").prop("checked", false);
                        }

                        if(data.hdmi == 1){
                            $("#hdmiTicketEdit").prop("checked", true);
                        }else{
                            $("#hdmiTicketEdit").prop("checked", false);
                        }
                        if(data.cable == 1){
                            $("#cableTicketEdit").prop("checked", true);
                        }else{
                            $("#cableTicketEdit").prop("checked", false);
                        }
                        if(data.recorder == 1){
                            $("#recorderTicketEdit").prop("checked", true);
                        }else{
                            $("#recorderTicketEdit").prop("checked", false);
                        }
                        if(data.tripod == 1){
                            $("#tripodTicketEdit").prop("checked", true);
                        }else{
                            $("#tripodTicketEdit").prop("checked", false);
                        }
                        if(data.speaker == 1){
                            $("#speakerTicketEdit").prop("checked", true);
                        }else{
                            $("#speakerTicketEdit").prop("checked", false);
                        }
                        if(data.outlet == 1){
                            $("#outletTicketEdit").prop("checked", true);
                        }else{
                            $("#outletTicketEdit").prop("checked", false);
                        }
                        if(data.presenter == 1){
                            $("#presenterTicketEdit").prop("checked", true);
                        }else{
                            $("#presenterTicketEdit").prop("checked", false);
                        }




                        //$('#typeTicketEdit').setAttribute(selectec )

                        //$("#typeTicketEdit").val(data.type);
                        //$("#dateTicketEdit").val(data.date);

                        $("#ticketContainerEdit").html(data.html);
                        $("#openTicketEdit").trigger("click");
                        //alert("llega");
                    }
                },
                function(jqXHR, textStatus){
                    Tecnotek.showErrorMessage("Error in request: " + textStatus + ".",
                        true, "", false);
                }, true);

        });



        $('.questionsButton').unbind();
        $('.questionsButton').click(function(event){
            console.debug("Click en question button: " + Tecnotek.UI.urls["getInfoTicketRealiceURL"]);
            event.preventDefault();
            //Tecnotek.UI.vars["relativeId"] = $(this).attr("rel");
            Tecnotek.UI.vars["ticketId"] = $(this).attr("rel");
            var ticketId = $(this).attr("rel")
            Tecnotek.ajaxCall(Tecnotek.UI.urls["getInfoTicketRealiceURL"],
                {ticketId: ticketId},
                function(data){
                    if(data.error === true) {
                        Tecnotek.showErrorMessage(data.message,true, "", false);
                    } else {
                        //Tecnotek.showInfoMessage(data.html, true, "", false);

                        $("#ticketActionContainerView").html(data.html);
                        $("#openTicketActionView").trigger("click");
                        Tecnotek.Ticket.openItemsToTicket(ticketId);
                    }
                },
                function(jqXHR, textStatus){
                    Tecnotek.showErrorMessage("Error in request: " + textStatus + ".",
                        true, "", false);
                }, true);
        });

        $("#ticketCancel").click(function(event){
            event.preventDefault();
            $.fancybox.close();
        });
        $("#ticketCancelEdit").click(function(event){
            event.preventDefault();
            $.fancybox.close();
        });


    },
    openItemsToTicket: function(ticketId){
        $("#itemsList").empty();
        $("#idTicketEditDetails").val(ticketId);
        //$("#groupNameOfList").html(groupName);
        Tecnotek.ajaxCall(Tecnotek.UI.urls["getTicketItemsURL"],
            {ticketId: ticketId},
            function(data){
                if(data.error === true) {
                    Tecnotek.showErrorMessage(data.message,true, "", false);
                } else {
                    for(i=0; i<data.items.length; i++) {
                        $html = '<div id="item_row_' + data.items[i].id + '" class="row userRow" rel="' + data.items[i].id + '">';
                        $html += '<div class="option_width" style="float: left; width: 200px;">' + data.items[i].name + ' - ' + data.items[i].code + '</div>';
                        $html += '<div class="right imageButton deleteButton deleteItemOfTicket" style="height: 16px;"  title="delete???"  rel="' + data.items[i].id + '"></div>';
                        $html += '<div class="clear"></div>';
                        $html += '</div>';
                        $("#itemsList").append($html);
                    }
                    $("#detailsTicketEditDetails").val(data.detail);
                    Tecnotek.Ticket.initDeleteButtonsOfItems();
                }
            },
            function(jqXHR, textStatus){
                Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".",
                    true, "", false);
                $(this).val("");
                $('#suggestions').fadeOut(); // Hide the suggestions box
            }, true);

        $('#openItemsToTicket').trigger('click');
        //$("#groupFormName").val(groupName);
        //Tecnotek.UI.vars["groupId"] = groupId;
    },
    searchTickets: function() {
        $("#students-container").html("");
        $("#pagination-container").html("");
        Tecnotek.showWaiting();
        Tecnotek.uniqueAjaxCall(Tecnotek.UI.urls["searchTickets"],
            {
                text: $("#searchText").val(),
                sortBy: Tecnotek.UI.vars["sortBy"],
                order: Tecnotek.UI.vars["order"],
                page: Tecnotek.UI.vars["page"]
            },
            function(data){
                if(data.error === true) {
                    Tecnotek.hideWaiting();
                    Tecnotek.showErrorMessage(data.message,true, "", false);
                } else {


                    var baseHtml = $("#contactRowTemplate").html();
                    var status = "";



                    for(i=0; i<data.tickets.length; i++) {


                        if( data.tickets[i].status == 1){
                            var status = "Creado";
                        }else {
                            var status = "Cerrado";
                        }

                        var row = '<div id="contactRowTemplate" class="row userRow ROW_CLASS" rel="TICKET_ID">' +
                            baseHtml +  '</div>';
                        row = row.replaceAll('ROW_CLASS', (i % 2 == 0? 'tableRowOdd':'tableRow'));
                        row = row.replaceAll('TICKET_ID', data.tickets[i].id);
                        row = row.replaceAll('TICKET_NAME', data.tickets[i].comments);
                        row = row.replaceAll('TICKET_DATE', data.tickets[i].datestimated);
                        row = row.replaceAll('TICKET_STATUS',status);

                        $("#students-container").append(row);
                    }



                    Tecnotek.Ticket.initButtons();
                    Tecnotek.UI.printPagination(data.total, data.filtered, Tecnotek.UI.vars["page"], 30, "pagination-container");
                    $(".paginationButton").unbind().click(function() {
                        Tecnotek.UI.vars["page"] = $(this).attr("page");
                        Tecnotek.Ticket.searchTickets();
                    });
                    Tecnotek.hideWaiting();

                }
            },
            function(jqXHR, textStatus){
                if (textStatus != "abort") {
                    Tecnotek.hideWaiting();
                    console.debug("Error getting data Tickets: " + textStatus);
                }
            }, true, 'searchTickets');
    },
    createTicket: function() {

        //alert($('#camaraTicket').val());
        //alert($('#ncamaraTicket').val());

        if($('#computerTicket').prop("checked") == true){
            var computer = 1;
        }else{
            var computer = 0;
        }
        if($('#videobeamTicket').prop("checked") == true){
            var videobeam = 1;
        }else{
            var videobeam = 0;
        }
        if($('#camaraTicket').prop("checked") == true){
            var camara = 1;
        }else{
            var camara = 0;
        }
        if($('#controlTicket').prop("checked") == true){
            var control = 1;
        }else{
            var control = 0;
        }
        if($('#hdmiTicket').prop("checked") == true){
            var hdmi = 1;
        }else{
            var hdmi = 0;
        }
        if($('#cableTicket').prop("checked") == true){
            var cable = 1;
        }else{
            var cable = 0;
        }
        if($('#recorderTicket').prop("checked") == true){
            var recorder = 1;
        }else{
            var recorder = 0;
        }
        if($('#tripodTicket').prop("checked") == true){
            var tripod = 1;
        }else{
            var tripod = 0;
        }
        if($('#controlTicket').prop("checked") == true){
            var control = 1;
        }else{
            var control = 0;
        }
        if($('#hdmiTicket').prop("checked") == true){
            var hdmi = 1;
        }else{
            var hdmi = 0;
        }
        if($('#cableTicket').prop("checked") == true){
            var cable = 1;
        }else{
            var cable = 0;
        }
        if($('#speakerTicket').prop("checked") == true){
            var speaker = 1;
        }else{
            var speaker = 0;
        }
        if($('#outletTicket').prop("checked") == true){
            var outlet = 1;
        }else{
            var outlet = 0;
        }
        if($('#presenterTicket').prop("checked") == true){
            var presenter = 1;
        }else{
            var presenter = 0;
        }

        Tecnotek.ajaxCall(Tecnotek.UI.urls["createTicketURL"],
            {   comments: $('#commentsTicket').val(),
                authorized: $('#authorizedTicket').val(),
                computer: computer,
                ncomputer: $('#ncomputerTicket').val(),
                videobeam: videobeam,
                nvideobeam: $('#nvideobeamTicket').val(),
                camara: camara,
                ncamara: $('#ncamaraTicket').val(),
                control: control,
                hdmi: hdmi,
                cable: cable,
                recorder: recorder,
                tripod: tripod,
                speaker: speaker,
                outlet: outlet,
                presenter: presenter,
                ticketDate: $('#dateTicket').val(),
                ticketHour: $('#timeTicket').val()
            },
            function(data){
                if(data.error === true) {
                    Tecnotek.showErrorMessage(data.message,true, "", false);
                } else {
                    $.fancybox.close();
                    Tecnotek.Ticket.searchTickets();
                }
            },
            function(jqXHR, textStatus){
                Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                $(this).val("");
            }, true);
    },
    updateTicket: function() {
        if($('#computerTicketEdit').prop("checked") == true){
            var computer = 1;
        }else{
            var computer = 0;
        }
        if($('#videobeamTicketEdit').prop("checked") == true){
            var videobeam = 1;
        }else{
            var videobeam = 0;
        }
        if($('#camaraTicketEdit').prop("checked") == true){
            var camara = 1;
        }else{
            var camara = 0;
        }
        if($('#controlTicketEdit').prop("checked") == true){
            var control = 1;
        }else{
            var control = 0;
        }
        if($('#hdmiTicketEdit').prop("checked") == true){
            var hdmi = 1;
        }else{
            var hdmi = 0;
        }
        if($('#cableTicketEdit').prop("checked") == true){
            var cable = 1;
        }else{
            var cable = 0;
        }
        if($('#recorderTicketEdit').prop("checked") == true){
            var recorder = 1;
        }else{
            var recorder = 0;
        }
        if($('#tripodTicketEdit').prop("checked") == true){
            var tripod = 1;
        }else{
            var tripod = 0;
        }
        if($('#controlTicketEdit').prop("checked") == true){
            var control = 1;
        }else{
            var control = 0;
        }
        if($('#hdmiTicketEdit').prop("checked") == true){
            var hdmi = 1;
        }else{
            var hdmi = 0;
        }
        if($('#cableTicketEdit').prop("checked") == true){
            var cable = 1;
        }else{
            var cable = 0;
        }
        if($('#speakerTicketEdit').prop("checked") == true){
            var speaker = 1;
        }else{
            var speaker = 0;
        }
        if($('#outletTicketEdit').prop("checked") == true){
            var outlet = 1;
        }else{
            var outlet = 0;
        }
        if($('#presenterTicketEdit').prop("checked") == true){
            var presenter = 1;
        }else{
            var presenter = 0;
        }

        if($('#idTicketEdit').val() != 0) {
            Tecnotek.ajaxCall(Tecnotek.UI.urls["updateTicketURL"],
                {
                    ticketId: $('#idTicketEdit').val(),
                    comments: $('#commentsTicketEdit').val(),
                    authorized: $('#authorizedTicketEdit').val(),
                    computer: computer,
                    ncomputer: $('#ncomputerTicketEdit').val(),
                    videobeam: videobeam,
                    nvideobeam: $('#nvideobeamTicketEdit').val(),
                    camara: camara,
                    ncamara: $('#ncamaraTicketEdit').val(),
                    control: control,
                    hdmi: hdmi,
                    cable: cable,
                    recorder: recorder,
                    tripod: tripod,
                    speaker: speaker,
                    outlet: outlet,
                    presenter: presenter,
                    ticketDate: $('#dateTicket').val(),
                    ticketHour: $('#timeTicket').val()
                },
                function (data) {
                    if (data.error === true) {
                        Tecnotek.showErrorMessage(data.message, true, "", false);
                    } else {
                        $.fancybox.close();
                        Tecnotek.Ticket.searchTickets();
                    }
                },
                function (jqXHR, textStatus) {
                    Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                    $(this).val("");
                }, true);
        }
    },
    saveTicketMessage: function() {

        if($('#idTicketEditDetails').val() != 0) {
            Tecnotek.ajaxCall(Tecnotek.UI.urls["saveTicketMessage"],
                {
                    ticketId: $('#idTicketEditDetails').val(),
                    details: $('#detailsTicketEditDetails').val()
                },
                function (data) {
                    if (data.error === true) {
                        Tecnotek.showErrorMessage(data.message, true, "", false);
                    } else {
                        $.fancybox.close();
                        Tecnotek.Ticket.searchTickets();
                    }
                },
                function (jqXHR, textStatus) {
                    Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                    $(this).val("");
                }, true);
        }
    }
};
Tecnotek.Charges = {
    translates : {},
    init : function() {
        //alert($("#teacherId").val());
        this.loadChargeStatus($("#teacherId").val());

        $('#searchText').keyup(function(event){
            Tecnotek.UI.vars["page"] = 1;
            Tecnotek.Charges.searchCharges();
        });
        $('#btnSearch').unbind().click(function(event){
            Tecnotek.Charges.searchCharges();
        });
        $(".sort_header").click(function() {
            Tecnotek.UI.vars["sortBy"] = $(this).attr("field-name");
            Tecnotek.UI.vars["order"] = $(this).attr("order");
            console.debug("Order by " + Tecnotek.UI.vars["sortBy"] + " " + Tecnotek.UI.vars["order"]);
            $(this).attr("order", Tecnotek.UI.vars["order"] == "asc"? "desc":"asc");
            $(".header-title").removeClass("asc").removeClass("desc").addClass("sortable");
            $(this).children().addClass(Tecnotek.UI.vars["order"]);
            Tecnotek.Charges.searchCharges();
        });

        $("#openChargeForm").fancybox({
            'beforeLoad' : function(){

            }
        });

        $("#openChargeFull").fancybox({
            'beforeLoad' : function(){

            }
        });

        $("#openChargeView").fancybox({
            'beforeLoad' : function(){

            }
        });

        $("#openChargeEdit").fancybox({
            'beforeLoad' : function(){

            }
        });

        $('#chargeForm').submit(function(event){
            event.preventDefault();
            Tecnotek.Charges.createCharge();
        });

        $('#chargeFormEdit').submit(function(event){
            event.preventDefault();
            Tecnotek.Charges.updateCharge();
        });

        $('.btnSendEmailCharge').click(function(event){
            console.log("email :: initButtons");
            event.preventDefault();
            Tecnotek.Charges.sendEmailCharge($(this).attr('rel'));

        });
        $('.btnSendEmailChargeYes').click(function(event){
            console.log("email :: initButtons");
            event.preventDefault();
            Tecnotek.Charges.sendEmailChargeYes($(this).attr('rel'));

        });
        $('.btnSendEmailCharge').click(function(event){
            console.log("email :: initButtons");
            event.preventDefault();
            Tecnotek.Charges.sendEmailCharge($(this).attr('rel'));

        });


        $("#btnchargeCancel").click(function(event){
            event.preventDefault();
            $.fancybox.close();
        });

        $("#btnsaveSend").click(function(event){
            Tecnotek.Charges.createSendCharge();
            event.preventDefault();
            $.fancybox.close();
        });

        $("#teacherChargeAdd").change(function(event){
            event.preventDefault();
            Tecnotek.Charges.loadChargeInfo($(this).val());
        });
        /*$('.btnCloseEmailCharge').click(function(event){

            event.preventDefault();
            alert("das3");
            //Tecnotek.Charges.SendEmailCharge($(this).attr('rel'));
        });*/

        Tecnotek.UI.vars["order"] = "asc";
        Tecnotek.UI.vars["sortBy"] = "id";
        Tecnotek.UI.vars["page"] = 1;
        Tecnotek.Charges.searchCharges();

        Tecnotek.Charges.initButtons();

    },
    initButtons: function() {
        console.debug("ChargeList :: initButtons");
        $('.viewButton').unbind();
        $('.viewButton').click(function(event){
            console.debug("Click en view button: " + Tecnotek.UI.urls["getInfoChargeFullURL"]);


            event.preventDefault();
            //Tecnotek.UI.vars["relativeId"] = $(this).attr("rel");
            Tecnotek.ajaxCall(Tecnotek.UI.urls["getInfoChargeFullURL"],
                {chargeId: $(this).attr("rel")},
                function(data){
                    if(data.error === true) {
                        Tecnotek.showErrorMessage(data.message,true, "", false);
                    } else {
                        //Tecnotek.showInfoMessage(data.html, true, "", false);
                        $("#chargeContainerView").html(data.html);
                        $("#openChargeView").trigger("click");
                        Tecnotek.Charges.initButtonsView();

                    }
                },
                function(jqXHR, textStatus){
                    Tecnotek.showErrorMessage("Error in request: " + textStatus + ".",
                        true, "", false);
                }, true);
        });

        $('.editButton').unbind();
        $('.editButton').click(function(event){
            event.preventDefault();


            var chargeId = $(this).attr("rel");
            Tecnotek.UI.vars["idChargeEdit"]  = chargeId;
            Tecnotek.ajaxCall(Tecnotek.UI.urls["getInfoChargeURL"],
                {chargeId: $(this).attr("rel")},
                function(data){
                    if(data.error === true) {
                        Tecnotek.showErrorMessage(data.message,true, "", false);
                    } else {
                        //Tecnotek.showInfoMessage(data.html, true, "", false);

                        $("#idChargeEdit").val(data.id);
                        $("#detailinChargeEdit").val(data.detailin);
                        $("#statusChargeEdit").val(data.status);

                        $("#teacherChargeEdit").val(data.user);

                        $("#chargeContainerEdit").html(data.html);
                        $("#openChargeEdit").trigger("click");
                        //alert("llega");
                    }
                },
                function(jqXHR, textStatus){
                    Tecnotek.showErrorMessage("Error in request: " + textStatus + ".",
                        true, "", false);
                }, true);

        });


    },
    initButtonsView: function() {
        console.debug("ChargeList :: initButtonsViews");
        $('.btnSendEmailCharge').click(function(event){
            console.log("email :: initButtons");
            event.preventDefault();
            Tecnotek.Charges.sendEmailCharge($(this).attr('rel'));
        });


        $('.btnCloseEmailCharge').click(function(event){
            console.log("emailclose :: initButtons");
            event.preventDefault();
            Tecnotek.Charges.sendEmailCloseCharge($(this).attr('rel'));
        });



        /*$('.btnCloseEmailCharge').click(function(event){

            event.preventDefault();
            alert("das4");
            //Tecnotek.Charges.SendEmailCharge($(this).attr('rel'));
        });*/

    },
    sendEmailCharge: function($idCharge) {
        //var form = $("#" + $formName);
        //var $content = tinymce.get('mail-content').getContent();
        Tecnotek.ajaxCall(Tecnotek.UI.urls["sendEmailChargeUrl"],
            {chargeId: $idCharge},
            function(data){
                if(data.error === true) {
                    Tecnotek.showErrorMessage(data.message,true, "", false);
                } else {
                    alert("Se ha enviado el correo correctamente.");
                }
            },
            function(jqXHR, textStatus){
                Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                $(this).val("");
            }, true);
    },
    sendEmailChargeYes: function($idCharge) {
        //var form = $("#" + $formName);
        //var $content = tinymce.get('mail-content').getContent();
        Tecnotek.ajaxCall(Tecnotek.UI.urls["sendEmailChargeYes"],
            {   chargeId: $idCharge,
                detailout: $("#detailout").val()},
            function(data){
                if(data.error === true) {
                    Tecnotek.showErrorMessage(data.message,true, "", false);
                } else {
                    alert("Se ha enviado el correo correctamente.");
                    $.fancybox.close();
                    Tecnotek.Charges.loadChargeStatus();
                    $("formAcceptCharge").style.visibility='hidden';
                }
            },
            function(jqXHR, textStatus){
                Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                $(this).val("");
            }, true);
    },
    sendEmailChargeNo: function($idCharge) {
        //var form = $("#" + $formName);
        //var $content = tinymce.get('mail-content').getContent();
        Tecnotek.ajaxCall(Tecnotek.UI.urls["sendEmailChargeNo"],
            {   chargeId: $idCharge,
                detailout: $("#detailout").val()},
            function(data){
                if(data.error === true) {
                    Tecnotek.showErrorMessage(data.message,true, "", false);
                } else {
                    alert("Se ha enviado el correo correctamente.");
                    $.fancybox.close();
                    Tecnotek.Charges.loadChargeStatus();
                    $("formAcceptCharge").style.visibility='hidden';
                }
            },
            function(jqXHR, textStatus){
                Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                $(this).val("");
            }, true);
    },
    sendEmailCloseCharge: function($idCharge) {
        //var form = $("#" + $formName);
        //var $content = tinymce.get('mail-content').getContent();
        Tecnotek.ajaxCall(Tecnotek.UI.urls["sendEmailCloseChargeUrl"],
            {chargeId: $idCharge},
            function(data){
                if(data.error === true) {
                    Tecnotek.showErrorMessage(data.message,true, "", false);
                } else {
                    alert("Se ha enviado el correo correctamente.");
                }
            },
            function(jqXHR, textStatus){
                Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                $(this).val("");
            }, true);
    },
    searchCharges: function() {

        $("#students-container").html("");
        $("#pagination-container").html("");
        Tecnotek.showWaiting();
        Tecnotek.uniqueAjaxCall(Tecnotek.UI.urls["searchCharges"],
            {
                text: $("#searchText").val(),
                sortBy: Tecnotek.UI.vars["sortBy"],
                order: Tecnotek.UI.vars["order"],
                page: Tecnotek.UI.vars["page"]
            },
            function(data){
                if(data.error === true) {
                    Tecnotek.hideWaiting();
                    Tecnotek.showErrorMessage(data.message,true, "", false);

                } else {


                    var baseHtml = $("#contactRowTemplate").html();
                    for(i=0; i<data.charges.length; i++) {
                        var statusLabel = "Error"
                        if(data.charges[i].status == 1){
                            var statusLabel = "Creado";
                        }
                        if(data.charges[i].status == 2){
                            var statusLabel = "Espera de aprobación";
                        }
                        if(data.charges[i].status == 3){
                            var statusLabel = "Aprobada";
                        }
                        if(data.charges[i].status == 4){
                            var statusLabel = "Rechazada";
                        }
                        if(data.charges[i].status == 5){
                            var statusLabel = "Cerrada";
                        }

                        var row = '<div id="contactRowTemplate" class="row userRow ROW_CLASS" rel="CHARGE_ID">' +
                            baseHtml +  '</div>';
                        row = row.replaceAll('ROW_CLASS', (i % 2 == 0? 'tableRowOdd':'tableRow'));
                        row = row.replaceAll('CHARGE_ID', data.charges[i].id);
                        row = row.replaceAll('CHARGE_USER', data.charges[i].user.substring(0, 35));
                        row = row.replaceAll('CHARGE_DETAILIN', data.charges[i].detailin.substring(0, 55));
                        row = row.replaceAll('CHARGE_STATUS', statusLabel);

                        $("#students-container").append(row);
                    }



                    Tecnotek.Charges.initButtons();
                    Tecnotek.UI.printPagination(data.total, data.filtered, Tecnotek.UI.vars["page"], 30, "pagination-container");
                    $(".paginationButton").unbind().click(function() {
                        Tecnotek.UI.vars["page"] = $(this).attr("page");
                        Tecnotek.Charges.searchCharges();
                    });
                    Tecnotek.hideWaiting();

                }
            },
            function(jqXHR, textStatus){
                if (textStatus != "abort") {
                    Tecnotek.hideWaiting();
                    console.debug("Error getting data Charges: " + textStatus);
                }
            }, true, 'searchCharges');

    },
    createCharge: function() {

        Tecnotek.ajaxCall(Tecnotek.UI.urls["createChargeURL"],
            {   detailin: $('#detailinCharge').val(),
                status: $('#statusCharge').val(),
                teacher: $('#teacherChargeAdd').val()
            },
            function(data){
                if(data.error === true) {
                    Tecnotek.showErrorMessage(data.message,true, "", false);
                } else {
                    $.fancybox.close();
                    location.reload(true);
                    Tecnotek.Charges.searchCharges();
                }
            },
            function(jqXHR, textStatus){
                Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                $(this).val("");
            }, true);
    },
    createSendCharge: function() {

        Tecnotek.ajaxCall(Tecnotek.UI.urls["createSendChargeURL"],
            {   detailin: $('#detailinCharge').val(),
                status: $('#statusCharge').val(),
                teacher: $('#teacherChargeAdd').val()
            },
            function(data){
                if(data.error === true) {
                    Tecnotek.showErrorMessage(data.message,true, "", false);
                } else {
                    $.fancybox.close();
                    alert("Se ha enviado la Carga al Profesor.");
                    location.reload(true);
                    Tecnotek.Charges.searchCharges();
                }
            },
            function(jqXHR, textStatus){
                Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                $(this).val("");
            }, true);
    },
    updateCharge: function() {
        Tecnotek.ajaxCall(Tecnotek.UI.urls["updateChargeURL"],
            {   chargeId: $('#idChargeEdit').val(),
                detailin: $('#detailinChargeEdit').val(),
                status: $('#statusChargeEdit').val(),
                user: $('#teacherCharge').val()
            },
            function(data){
                if(data.error === true) {
                    Tecnotek.showErrorMessage(data.message,true, "", false);
                } else {
                    $.fancybox.close();
                    Tecnotek.Charges.searchCharges();
                }
            },
            function(jqXHR, textStatus){
                Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                $(this).val("");
            }, true);
    },
    loadChargeInfo: function(teacherId){
        console.debug("Click en view button: " + Tecnotek.UI.urls["getInfoChargeTeacherFullURL"]);


        event.preventDefault();

        Tecnotek.ajaxCall(Tecnotek.UI.urls["getInfoChargeTeacherFullURL"],
            {teacherId: teacherId},
            function(data){
                if(data.error === true) {
                    Tecnotek.showErrorMessage(data.message,true, "", false);
                } else {
                    //Tecnotek.showInfoMessage(data.html, true, "", false);
                    $("#chargeTeacherContainerView").html(data.html);


                }
            },
            function(jqXHR, textStatus){
                Tecnotek.showErrorMessage("Error in request: " + textStatus + ".",
                    true, "", false);
            }, true);
    },
    loadChargeStatus: function(){
        console.debug("Click en view button: " + Tecnotek.UI.urls["getInfoChargeTeacherStatusURL"]);


        event.preventDefault();
        if($("#teacherId").val() != 0){
            Tecnotek.ajaxCall(Tecnotek.UI.urls["getInfoChargeTeacherStatusURL"],
                {teacherId: $("#teacherId").val()},
                function(data){
                    if(data.error === true) {
                        Tecnotek.showErrorMessage(data.message,true, "", false);
                    } else {
                        //Tecnotek.showInfoMessage(data.html, true, "", false);
                        $("#chargeTeacherStatusContainerView").html(data.html);



                    }
                },
                function(jqXHR, textStatus){
                    Tecnotek.showErrorMessage("Error in request: " + textStatus + ".",
                        true, "", false);
                }, true);
        }

    }
};
Tecnotek.Commissions = {
    translates : {},
    init : function() {
        $('#searchText').keyup(function(event){
            Tecnotek.UI.vars["page"] = 1;
            Tecnotek.Commissions.searchCommissions();
        });
        $('#btnSearch').unbind().click(function(event){
            Tecnotek.Commissions.searchCommissions();
        });
        $(".sort_header").click(function() {
            Tecnotek.UI.vars["sortBy"] = $(this).attr("field-name");
            Tecnotek.UI.vars["order"] = $(this).attr("order");
            console.debug("Order by " + Tecnotek.UI.vars["sortBy"] + " " + Tecnotek.UI.vars["order"]);
            $(this).attr("order", Tecnotek.UI.vars["order"] == "asc"? "desc":"asc");
            $(".header-title").removeClass("asc").removeClass("desc").addClass("sortable");
            $(this).children().addClass(Tecnotek.UI.vars["order"]);
            Tecnotek.Commissions.searchCommissions();
        });

        $("#openCommissionForm").fancybox({
            'beforeLoad' : function(){

            }
        });

        $("#openCommissionView").fancybox({
            'beforeLoad' : function(){

            }
        });

        $("#openCommissionEdit").fancybox({
            'beforeLoad' : function(){

            }
        });

        $('#commissionForm').submit(function(event){
            event.preventDefault();
            Tecnotek.Commissions.createCommission();
        });

        $('#commissionFormEdit').submit(function(event){
            event.preventDefault();
            Tecnotek.Commissions.updateCommission();
        });

        Tecnotek.UI.vars["order"] = "asc";
        Tecnotek.UI.vars["sortBy"] = "id";
        Tecnotek.UI.vars["page"] = 1;
        Tecnotek.Commissions.searchCommissions();


        Tecnotek.Commissions.initButtons();

    },
    initButtons: function() {
        console.debug("CommissionList :: initButtons");
        $('.viewButton').unbind();
        $('.viewButton').click(function(event){
            console.debug("Click en view button: " + Tecnotek.UI.urls["getInfoCommissionFullURL"]);
            event.preventDefault();
            //Tecnotek.UI.vars["relativeId"] = $(this).attr("rel");
            Tecnotek.ajaxCall(Tecnotek.UI.urls["getInfoCommissionFullURL"],
                {commissionId: $(this).attr("rel")},
                function(data){
                    if(data.error === true) {
                        Tecnotek.showErrorMessage(data.message,true, "", false);
                    } else {
                        //Tecnotek.showInfoMessage(data.html, true, "", false);
                        $("#commissionContainerView").html(data.html);
                        $("#openCommissionView").trigger("click");
                        //alert("llega");
                    }
                },
                function(jqXHR, textStatus){
                    Tecnotek.showErrorMessage("Error in request: " + textStatus + ".",
                        true, "", false);
                }, true);
        });


        $('.editButton').unbind();
        $('.editButton').click(function(event){
            event.preventDefault();

            var commissionId = $(this).attr("rel");
            Tecnotek.UI.vars["idCommissionEdit"]  = commissionId;
            Tecnotek.ajaxCall(Tecnotek.UI.urls["getInfoCommissionURL"],
                {commissionId: $(this).attr("rel")},
                function(data){
                    if(data.error === true) {
                        Tecnotek.showErrorMessage(data.message,true, "", false);
                    } else {
                        //Tecnotek.showInfoMessage(data.html, true, "", false);

                        $("#idCommissionEdit").val(data.id);
                        $("#nameCommissionEdit").val(data.name);
                        $("#codeCommissionEdit").val(data.code);
                        $("#statusCommissionEdit").val(data.status);
                        $("#typeCommission").val(data.type);

                        $("#commissionContainerEdit").html(data.html);
                        $("#openCommissionEdit").trigger("click");
                        //alert("llega");
                    }
                },
                function(jqXHR, textStatus){
                    Tecnotek.showErrorMessage("Error in request: " + textStatus + ".",
                        true, "", false);
                }, true);

        });


    },
    searchCommissions: function() {
        $("#students-container").html("");
        $("#pagination-container").html("");
        Tecnotek.showWaiting();
        Tecnotek.uniqueAjaxCall(Tecnotek.UI.urls["searchCommissions"],
            {
                text: $("#searchText").val(),
                sortBy: Tecnotek.UI.vars["sortBy"],
                order: Tecnotek.UI.vars["order"],
                page: Tecnotek.UI.vars["page"]
            },
            function(data){
                if(data.error === true) {
                    Tecnotek.hideWaiting();
                    Tecnotek.showErrorMessage(data.message,true, "", false);
                } else {


                    var baseHtml = $("#contactRowTemplate").html();
                    for(i=0; i<data.commissions.length; i++) {
                        var typeLabel = "Normal"
                        if(data.commissions[i].type == 1){
                            var typeLabel = "Normal";
                        }
                        if(data.commissions[i].type == 2){
                            var typeLabel = "Otro";
                        }

                        var row = '<div id="contactRowTemplate" class="row userRow ROW_CLASS" rel="COMMISSION_ID">' +
                            baseHtml +  '</div>';
                        row = row.replaceAll('ROW_CLASS', (i % 2 == 0? 'tableRowOdd':'tableRow'));
                        row = row.replaceAll('COMMISSION_ID', data.commissions[i].id);
                        row = row.replaceAll('COMMISSION_CODE', data.commissions[i].code);
                        row = row.replaceAll('COMMISSION_NAME', data.commissions[i].name);
                        row = row.replaceAll('COMMISSION_TYPE', typeLabel);

                        $("#students-container").append(row);
                    }



                    Tecnotek.Commissions.initButtons();
                    Tecnotek.UI.printPagination(data.total, data.filtered, Tecnotek.UI.vars["page"], 30, "pagination-container");
                    $(".paginationButton").unbind().click(function() {
                        Tecnotek.UI.vars["page"] = $(this).attr("page");
                        Tecnotek.Commissions.searchCommissions();
                    });
                    Tecnotek.hideWaiting();

                }
            },
            function(jqXHR, textStatus){
                if (textStatus != "abort") {
                    Tecnotek.hideWaiting();
                    console.debug("Error getting data Commissions: " + textStatus);
                }
            }, true, 'searchCommissions');
    },
    createCommission: function() {

        Tecnotek.ajaxCall(Tecnotek.UI.urls["createCommissionURL"],
            {   name: $('#nameCommission').val(),
                code: $('#codeCommission').val(),
                type: $('#typeCommission').val(),
                status: $('#statusCommission').val()
            },
            function(data){
                if(data.error === true) {
                    Tecnotek.showErrorMessage(data.message,true, "", false);
                } else {
                    $.fancybox.close();
                    Tecnotek.Commissions.searchCommissions();
                }
            },
            function(jqXHR, textStatus){
                Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                $(this).val("");
            }, true);
    },
    updateCommission: function() {
        Tecnotek.ajaxCall(Tecnotek.UI.urls["updateCommissionURL"],
            {   commissionId: $('#idCommissionEdit').val(),
                name: $('#nameCommissionEdit').val(),
                code: $('#codeCommissionEdit').val(),
                type: $('#typeCommissionEdit').val(),
                status: $('#statusCommissionEdit').val()
            },
            function(data){
                if(data.error === true) {
                    Tecnotek.showErrorMessage(data.message,true, "", false);
                } else {
                    $.fancybox.close();
                    Tecnotek.Commissions.searchCommissions();
                }
            },
            function(jqXHR, textStatus){
                Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                $(this).val("");
            }, true);
    }
};

Tecnotek.Programs = {
    translates : {},
    init : function() {
        $('#searchText').keyup(function(event){
            Tecnotek.UI.vars["page"] = 1;
            Tecnotek.Programs.searchPrograms();
        });
        $('#btnSearch').unbind().click(function(event){
            Tecnotek.Programs.searchPrograms();
        });

        $("#openProgramCreate").fancybox({
            'beforeLoad' : function(){

            }
        });


        $('#programForm').submit(function(event){
            event.preventDefault();
            Tecnotek.Programs.createProgram();
        });

        $(".sort_header").click(function() {
            Tecnotek.UI.vars["sortBy"] = $(this).attr("field-name");
            Tecnotek.UI.vars["order"] = $(this).attr("order");
            console.debug("Order by " + Tecnotek.UI.vars["sortBy"] + " " + Tecnotek.UI.vars["order"]);
            $(this).attr("order", Tecnotek.UI.vars["order"] == "asc"? "desc":"asc");
            $(".header-title").removeClass("asc").removeClass("desc").addClass("sortable");
            $(this).children().addClass(Tecnotek.UI.vars["order"]);
            Tecnotek.Programs.searchPrograms();
        });
        Tecnotek.UI.vars["order"] = "asc";
        Tecnotek.UI.vars["sortBy"] = "id";
        Tecnotek.UI.vars["page"] = 1;
        Tecnotek.Programs.searchPrograms();
    },
    searchPrograms: function() {
        $("#students-container").html("");
        $("#exstudents-container").html("");
        $("#pagination-container").html("");
        Tecnotek.showWaiting();
        Tecnotek.uniqueAjaxCall(Tecnotek.UI.urls["searchPrograms"],
            {
                text: $("#searchText").val(),
                sortBy: Tecnotek.UI.vars["sortBy"],
                order: Tecnotek.UI.vars["order"],
                page: Tecnotek.UI.vars["page"]
            },
            function(data){
                if(data.error === true) {
                    Tecnotek.hideWaiting();
                    Tecnotek.showErrorMessage(data.message,true, "", false);
                } else {


                    var baseHtml = $("#contactRowTemplate").html();
                    var pdfbutton = "";
                    for(i=0; i<data.programs.length; i++) {
                        var statusLabel = "Ingresado"
                        if(data.programs[i].status == 1){
                            var statusLabel = "Creado";
                            pdfbutton =  '<div class="right imageButton nopdfButton"  title="Archivo no disponible" rel="PROGRAM_ID"></div>';
                        }
                        if(data.programs[i].status == 2){
                            var statusLabel = "Modificado";
                            pdfbutton =  '<div class="right imageButton nopdfButton"  title="Archivo no disponible" rel="PROGRAM_ID"></div>';
                        }
                        if(data.programs[i].status == 3){
                            var statusLabel = "Modificado en espera de revision";
                            pdfbutton =  '<div class="right imageButton nopdfButton"  title="Archivo no disponible" rel="PROGRAM_ID"></div>';
                        }
                        if(data.programs[i].status == 4){
                            var statusLabel = "Revisado correcciones pendientes";
                            pdfbutton =  '<div class="right imageButton nopdfButton"  title="Archivo no disponible" rel="PROGRAM_ID"></div>';
                        }

                        /*if(data.programs[i].status == 5){
                            var statusLabel = "Revisado y aprobado";
                            pdfbutton =  '<div class="right imageButton pdfButton"  title="Descargar pdf" rel="PROGRAM_ID"></div>';
                        }

                        if(data.programs[i].status == 6){
                            var statusLabel = "Finalizado";
                            pdfbutton =  '<div class="right imageButton pdfButton"  title="Descargar pdf" rel="PROGRAM_ID"></div>';
                        }*/
                        if(data.programs[i].status == 5){
                            var statusLabel = "Revisado y aprobado";
                            pdfbutton =  '<div class="right imageButton nopdfButton"  title="Archivo no disponible" rel="PROGRAM_ID"></div>';
                        }

                        if(data.programs[i].status == 6){
                            var statusLabel = "Finalizado";
                            pdfbutton =  '<div class="right imageButton nopdfButton"  title="Archivo no disponible" rel="PROGRAM_ID"></div>';
                        }


                        var row = '<div id="contactRowTemplate" class="row userRow ROW_CLASS" rel="PROGRAM_ID">' +
                            pdfbutton + baseHtml +  '</div>';
                        row = row.replaceAll('ROW_CLASS', (i % 2 == 0? 'tableRowOdd':'tableRow'));
                        row = row.replaceAll('PROGRAM_ID', data.programs[i].id);
                        row = row.replaceAll('PROGRAM_DETAIL', data.programs[i].detail.substring(0, 65));
                        row = row.replaceAll('TITLE_PROGR_DETAIL', data.programs[i].detail);
                        row = row.replaceAll('PROGRAM_NAME', data.programs[i].name.substring(0, 20));
                        row = row.replaceAll('TITLE_PROGR_NAME', data.programs[i].name);
                        row = row.replaceAll('PROGRAM_STATUS', statusLabel);

                        $("#students-container").append(row);
                        pdfbutton = "";
                    }

                    var baseHtml2 = $("#excontactRowTemplate").html();
                    var pdfbutton = "";
                    for(i=0; i<data.exprograms.length; i++) {

                        var statusLabel = "Ingresado"
                        if(data.exprograms[i].status == 1){
                            var statusLabel = "Creado";
                            pdfbutton =  '<div class="right imageButton nopdfButton"  title="Archivo no disponible" rel="EXPROGRAM_ID"></div>';
                        }
                        if(data.exprograms[i].status == 2){
                            var statusLabel = "Modificado";
                            pdfbutton =  '<div class="right imageButton nopdfButton"  title="Archivo no disponible" rel="EXPROGRAM_ID"></div>';
                        }
                        if(data.exprograms[i].status == 3){
                            var statusLabel = "Modificado en espera de revision";
                            pdfbutton =  '<div class="right imageButton nopdfButton"  title="Archivo no disponible" rel="EXPROGRAM_ID"></div>';
                        }
                        if(data.exprograms[i].status == 4){
                            var statusLabel = "Revisado correcciones pendientes";
                            pdfbutton =  '<div class="right imageButton nopdfButton"  title="Archivo no disponible" rel="EXPROGRAM_ID"></div>';
                        }

                        /*if(data.exprograms[i].status == 5){
                            var statusLabel = "Revisado y aprobado";
                            pdfbutton =  '<div class="right imageButton pdfButton"  title="Descargar pdf" rel="EXPROGRAM_ID"></div>';
                        }

                        if(data.exprograms[i].status == 6){
                            var statusLabel = "Finalizado";
                            pdfbutton =  '<div class="right imageButton pdfButton"  title="Descargar pdf" rel="EXPROGRAM_ID"></div>';
                        }*/
                        if(data.exprograms[i].status == 5){
                            var statusLabel = "Revisado y aprobado";
                            pdfbutton =  '<div class="right imageButton nopdfButton"  title="Archivo no disponible" rel="EXPROGRAM_ID"></div>';
                        }

                        if(data.exprograms[i].status == 6){
                            var statusLabel = "Finalizado";
                            pdfbutton =  '<div class="right imageButton nopdfButton"  title="Archivo no disponible" rel="EXPROGRAM_ID"></div>';
                        }




                        var row = '<div id="excontactRowTemplate" class="row userRow ROW_CLASS" rel="EXPROGRAM_ID">' +
                            pdfbutton + baseHtml2 +  '</div>';
                        row = row.replaceAll('ROW_CLASS', (i % 2 == 0? 'tableRowOdd':'tableRow'));
                        row = row.replaceAll('EXPROGRAM_ID', data.exprograms[i].id);
                        row = row.replaceAll('EXPROGRAM_DETAIL', data.exprograms[i].detail.substring(0, 65));
                        row = row.replaceAll('TITLE_EXPROGR_DETAIL', data.exprograms[i].detail);
                        row = row.replaceAll('EXPROGRAM_NAME', data.exprograms[i].name.substring(0, 20));
                        row = row.replaceAll('TITLE_EXPROGR_NAME', data.exprograms[i].name);
                        row = row.replaceAll('EXPROGRAM_STATUS', statusLabel);

                        $("#exstudents-container").append(row);
                        pdfbutton = "";
                    }


                    Tecnotek.ProgramList.initButtons();
                    Tecnotek.UI.printPagination(data.total, data.filtered, Tecnotek.UI.vars["page"], 30, "pagination-container");
                    $(".paginationButton").unbind().click(function() {
                        Tecnotek.UI.vars["page"] = $(this).attr("page");
                        Tecnotek.Programs.searchPrograms();
                    });
                    Tecnotek.hideWaiting();

                }
            },
            function(jqXHR, textStatus){
                if (textStatus != "abort") {
                    Tecnotek.hideWaiting();
                    console.debug("Error getting data Programs: " + textStatus);
                }
            }, true, 'searchPrograms');
    },
    createProgram: function() {
        //alert("entra pero no sale");
        Tecnotek.ajaxCall(Tecnotek.UI.urls["createProgramURL"],
            {   name: $('#nameProgram').val(),
                user: $('#teacherProgram').val(),
                type: $('#typeProgram').val(),
                course: $('#courseProgram').val()
            },
            function(data){
                if(data.error === true) {
                    Tecnotek.showErrorMessage(data.message,true, "", false);
                } else {
                    $.fancybox.close();
                    location.reload(); //Codigo agregado por Stuart 27/04
                    alertify.set('notifier','position','top-left');
                    alertify.success("Se ha guardado la informacion del programa");

                    //alert("Se ha guardado la informacion DEL PROGRAMA.");

                    Tecnotek.Programs.searchPrograms();
                }
            },
            function(jqXHR, textStatus){
                Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                $(this).val("");
            }, true);
    }
};

Tecnotek.ProgramsH = {
    translates : {},
    init : function() {
        $('#searchText').keyup(function(event){
            Tecnotek.UI.vars["page"] = 1;
            Tecnotek.ProgramsH.searchPrograms();
        });
        $('#btnSearch').unbind().click(function(event){
            Tecnotek.ProgramsH.searchPrograms();
        });
        $(".sort_header").click(function() {
            Tecnotek.UI.vars["sortBy"] = $(this).attr("field-name");
            Tecnotek.UI.vars["order"] = $(this).attr("order");
            console.debug("Order by " + Tecnotek.UI.vars["sortBy"] + " " + Tecnotek.UI.vars["order"]);
            $(this).attr("order", Tecnotek.UI.vars["order"] == "asc"? "desc":"asc");
            $(".header-title").removeClass("asc").removeClass("desc").addClass("sortable");
            $(this).children().addClass(Tecnotek.UI.vars["order"]);
            Tecnotek.ProgramsH.searchPrograms();
        });
        Tecnotek.UI.vars["order"] = "asc";
        Tecnotek.UI.vars["sortBy"] = "id";
        Tecnotek.UI.vars["page"] = 1;
        Tecnotek.ProgramsH.searchPrograms();
    },
    searchPrograms: function() {
        $("#students-container").html("");
        $("#pagination-container").html("");
        Tecnotek.showWaiting();
        Tecnotek.uniqueAjaxCall(Tecnotek.UI.urls["searchHPrograms"],
            {
                text: $("#searchText").val(),
                sortBy: Tecnotek.UI.vars["sortBy"],
                order: Tecnotek.UI.vars["order"],
                page: Tecnotek.UI.vars["page"]
            },
            function(data){
                if(data.error === true) {
                    Tecnotek.hideWaiting();
                    Tecnotek.showErrorMessage(data.message,true, "", false);
                } else {


                    var baseHtml = $("#contactRowTemplate").html();
                    var pdfbutton = "";
                    for(i=0; i<data.programs.length; i++) {
                        var statusLabel = "Ingresado"


                         pdfbutton =  '<div class="right imageButton pdfButton"  title="Descargar pdf" rel="PROGRAM_ID"></div>';



                        var row = '<div id="contactRowTemplate" class="row userRow ROW_CLASS" rel="PROGRAM_ID">' +
                            pdfbutton + baseHtml +  '</div>';
                        row = row.replaceAll('ROW_CLASS', (i % 2 == 0? 'tableRowOdd':'tableRow'));
                        row = row.replaceAll('PROGRAM_ID', data.programs[i].id);
                        row = row.replaceAll('PROGRAM_DETAIL', data.programs[i].detail.substring(0, 65));
                        row = row.replaceAll('TITLE_PROGR_DETAIL', data.programs[i].detail);
                        row = row.replaceAll('PROGRAM_NAME', data.programs[i].name.substring(0, 20));
                        row = row.replaceAll('TITLE_PROGR_NAME', data.programs[i].name);
                        row = row.replaceAll('PROGRAM_STATUS', statusLabel);

                        $("#students-container").append(row);
                        pdfbutton = "";
                    }



                    Tecnotek.ProgramList.initButtons();
                    Tecnotek.UI.printPagination(data.total, data.filtered, Tecnotek.UI.vars["page"], 30, "pagination-container");
                    $(".paginationButton").unbind().click(function() {
                        Tecnotek.UI.vars["page"] = $(this).attr("page");
                        Tecnotek.ProgramsH.searchPrograms();
                    });
                    Tecnotek.hideWaiting();

                }
            },
            function(jqXHR, textStatus){
                if (textStatus != "abort") {
                    Tecnotek.hideWaiting();
                    console.debug("Error getting data Programs: " + textStatus);
                }
            }, true, 'searchPrograms');
    }
};

Tecnotek.programEdit = {
    translates : {},
    init : function() {
        $( ".date-input" ).datepicker({
            defaultDate: "0d",
            changeMonth: true,
            dateFormat: "dd-mm-yy",
            showButtonPanel: true,
            currentText: "Hoy",
            numberOfMonths: 1,
            onClose: function( selectedDate ) {
                $( "#" + $(this).attr('id') ).datepicker( "option", "minDate", selectedDate );
            }
        });

        $("#group").change(function(e){
            window.location = Tecnotek.UI.urls["groupUrl"] + "/" + $(this).val();
        });

        $(".questionnaire-textarea").blur(function(e){
            $this = $(this);
            $id = $this.attr("id");
            $("#printarea-" + $id).html($this.val());
        });
        Tecnotek.programEdit.initButtons();
    },
    initButtons : function() {
        $('.btnSubmitForm').click(function(event){
            event.preventDefault();
            Tecnotek.programEdit.submitForm($(this).attr('rel'));
            //alert("entra aca");
            //alert("Submit Form: " + $(this).attr('rel'));
        });

        $('.btnCheckForm').click(function(event){
            event.preventDefault();
            Tecnotek.programEdit.checkForm($(this).attr('rel'));
        });

        $('.btnReCheckForm').click(function(event){
            event.preventDefault();
            Tecnotek.programEdit.recheckForm($(this).attr('rel'));
        });

        $('.btnPdfCheckForm').click(function(event){
            event.preventDefault();
            Tecnotek.programEdit.btnPdfCheckForm($(this).attr('rel'));
        });

        $('.btnPdfCreateCheckForm').click(function(event){
            event.preventDefault();
            Tecnotek.programEdit.btnPdfCreateCheckForm($(this).attr('rel'));
        });

        $(".btnPrintForm").click(function(e){
            e.preventDefault();
            $(".questionnaire-textarea").hide();
            $(".btnPrintForm").hide();
            $(".btnSubmitForm").hide();
            $("#" + $(this).attr("rel")).printElement({printMode:'popup', pageTitle:""});
            $(".questionnaire-textarea").show();
            $(".btnPrintForm").show();
            $(".btnSubmitForm").show();
        });

        $("#openInfoForm").fancybox({
            'afterLoad' : function(){

            }
        });

        $('.infoButton').unbind();
        $('.infoButton').click(function(event){
            console.debug("Click en view button: " + Tecnotek.UI.urls["getInfoQuestionnaireFullURL"]);
            event.preventDefault();
            //Tecnotek.UI.vars["relativeId"] = $(this).attr("rel");
            Tecnotek.ajaxCall(Tecnotek.UI.urls["getInfoQuestionnaireFullURL"],
                {questionnaireId: $(this).attr("rel")},
                function(data){
                    if(data.error === true) {
                        Tecnotek.showErrorMessage(data.message,true, "", false);
                    } else {
                        //Tecnotek.showInfoMessage(data.html, true, "", false);
                        $("#infoContainer").html(data.html);
                        $("#openInfoForm").trigger("click");
                    }
                },
                function(jqXHR, textStatus){
                    Tecnotek.showErrorMessage("Error in request: " + textStatus + ".",
                        true, "", false);
                }, true);

        });

        $('.viewButton').unbind();
        $('.viewButton').click(function(event){
            console.debug("Click en view button: " + Tecnotek.UI.urls["getInfoProgramFullURL"]);
            event.preventDefault();
            //Tecnotek.UI.vars["relativeId"] = $(this).attr("rel");
            Tecnotek.ajaxCall(Tecnotek.UI.urls["getInfoProgramFullURL"],
                {programId: $(this).attr("rel")},
                function(data){
                    if(data.error === true) {
                        Tecnotek.showErrorMessage(data.message,true, "", false);
                    } else {
                        //Tecnotek.showInfoMessage(data.html, true, "", false);
                        $("#programContainer").html(data.html);
                        $("#openProgramForm").trigger("click");
                    }
                },
                function(jqXHR, textStatus){
                    Tecnotek.showErrorMessage("Error in request: " + textStatus + ".",
                        true, "", false);
                }, true);

        });
    },
    checkForm: function($formName) {
        var form = $("#" + $formName);
        //alert($("#programId").val());
        Tecnotek.ajaxCall(Tecnotek.UI.urls["checkProgramFormUrl"],
                {programId: $("#programId").val(),
                    userId: $("#userId").val()},
            function(data){
                if(data.error === true) {
                    Tecnotek.showErrorMessage(data.message,true, "", false);
                } else {
                    alert("Se ha enviado la informacion al coordinador correctamente.");
                }
            },
            function(jqXHR, textStatus){
                Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                $(this).val("");
            }, true);
    },

    recheckForm: function($formName) {
        var form = $("#" + $formName);
        //var $content = tinymce.get('mail-content').getContent();
        //alert($("#programId").val());
        Tecnotek.ajaxCall(Tecnotek.UI.urls["recheckProgramFormUrl"],
            {programId: $("#programId").val(),
                //body:   $content,
                userId: $("#userId").val()},
            function(data){
                if(data.error === true) {
                    Tecnotek.showErrorMessage(data.message,true, "", false);
                } else {
                    alert("Se ha enviado la informacion al profesor correctamente.");
                }
            },
            function(jqXHR, textStatus){
                Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                $(this).val("");
            }, true);
    },

    btnPdfCheckForm: function($formName) {
        var form = $("#" + $formName);
        //var $content = tinymce.get('mail-content').getContent();
        //alert($("#programId").val());
        Tecnotek.ajaxCall(Tecnotek.UI.urls["pdfcheckProgramFormUrl"],
            {programId: $("#programId").val(),
                //body:   $content,
                userId: $("#userId").val()},
            function(data){
                if(data.error === true) {
                    Tecnotek.showErrorMessage(data.message,true, "", false);
                } else {
                    alert("Se ha enviado la informacion al profesor correctamente.");
                }
            },
            function(jqXHR, textStatus){
                Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                $(this).val("");
            }, true);
    },

    btnPdfCreateCheckForm: function($formName) {
        var form = $("#" + $formName);
        //var $content = tinymce.get('mail-content').getContent();
        //alert($("#programId").val());
        Tecnotek.ajaxCall(Tecnotek.UI.urls["pdfcreatecheckProgramFormUrl"],
            {programId: $("#programId").val(),
                //body:   $content,
                userId: $("#userId").val()},
            function(data){
                if(data.error === true) {
                    Tecnotek.showErrorMessage(data.message,true, "", false);
                } else {
                    alert("Se ha creado el archivo correctamente.");
                }
            },
            function(jqXHR, textStatus){
                Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                $(this).val("");
            }, true);
    },




    submitForm: function($formName) {
        var form = $("#" + $formName);
        Tecnotek.ajaxCall(Tecnotek.UI.urls["saveProgramFormUrl"],
            form.serialize(),
            function(data){
                if(data.error === true) {
                    Tecnotek.showErrorMessage(data.message,true, "", false);
                } else {
                    alert("Se ha guardado la informacion correctamente.");

                }
            },
            function(jqXHR, textStatus){
                Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                $(this).val("");
            }, true);
    }
};


Tecnotek.Tickets = {
    translates : {},
    init : function() {
        $('#kinship').change(function(event){
            event.preventDefault();
            if($(this).val() == 99){
                $('#otherDetail').show();
            } else {
                $('#otherDetail').hide();
            }
        });

        $("#asociateButton").click(function(e){
            Tecnotek.createAndAssociateRelative(Tecnotek.Tickets.afterCreateAndAssociateRelative);
        });

        $("#new-relative-btn").fancybox({
            'beforeLoad' : function(){
                $("#new-relative-student").html(Tecnotek.UI.vars["studentName"]);

                /* Clean form */
                $("#firstname").val("");
                $("#lastname").val("");
                $("#identification").val("");
                $("#phonec").val("");
                $("#phonew").val("");
                $("#phoneh").val("");
                $("#workplace").val("");
                $("#email").val("");
                $("#adress").val("");
                $("#restriction").val("");
                $("#kinship").val(1);
                $("#description").val("");
                $('#otherDetail').hide();
            }
        });

        /**/
        $('#searchBox22').keyup(function(event){
            event.preventDefault();
            if($(this).val().length == 0) {
                $('#suggestions2').fadeOut(); // Hide the suggestions box
            } else {
                Tecnotek.ajaxCall(Tecnotek.UI.urls["getContactsURL"],
                    {text: $(this).val(), studentId: Tecnotek.UI.vars["studentId"]},
                    function(data){
                        if(data.error === true) {
                            Tecnotek.showErrorMessage(data.message,true, "", false);
                        } else {
                            $data = "";
                            $data += '<p id="searchresults2">';
                            $data += '    <span class="category">Contactos</span>';
                            for(i=0; i<data.contacts.length; i++) {
                                console.debug();
                                $data += '    <a class="searchResult2" rel="' + data.contacts[i].id + '" name="' +
                                    data.contacts[i].firstname + ' ' + data.contacts[i].lastname + '">';
                                $data += '      <span class="searchheading">' + data.contacts[i].firstname
                                    + ' ' + data.contacts[i].lastname +  '</span>';
                                $data += '      <span>Asociar este contacto.</span>';
                                $data += '    </a>';
                            }
                            $data += '</p>';

                            $('#suggestions2').fadeIn(); // Show the suggestions box
                            $('#suggestions2').html($data); // Fill the suggestions box
                            $('.searchResult2').unbind();
                            $('.searchResult2').click(function(event){
                                event.preventDefault();
                                var relativeName = $(this).attr("name");

                                $detail = "";

                                switch($("#kinship2").val()){
                                    case "1": $detail = "Padre"; break;
                                    case "2": $detail = "Madre"; break;
                                    case "3": $detail = "Hermano"; break;
                                    case "4": $detail = "Hermana"; break;
                                    case "99": $detail = "Otro"; break;
                                }

                                Tecnotek.ajaxCall(Tecnotek.UI.urls["associateContactURL"],
                                    {studentId: Tecnotek.UI.vars["studentId"], contactId: $(this).attr("rel"),
                                        'kinship': $("#kinship2").val(), 'detail': $detail},
                                    function(data){
                                        if(data.error === true) {
                                            Tecnotek.showErrorMessage(data.message,true, "", false);
                                        } else {
                                            /*$html = '<div id="relative_row_' + data.id + '" class="row" rel="' + data.id + '" style="padding: 0px; font-size: 10px;">';
                                            $html += '    <div class="" style="float: left; width: 350px;">' + relativeName + '</div>';
                                            $html += '    <div class="" style="float: left; width: 100px;">' + $detail + '</div>';

                                            $html += '    <div class="right imageButton deleteButton" style="height: 16px;" title="Eliminar" rel="' + data.id + '"></div>';
                                            $html += '    <div class="right imageButton viewButton" style="height: 16px;"  title="Ver"  rel="' + data.id + '"></div>';
                                            $html += '    <div class="clear"></div>';
                                            $html += '</div>';

                                            $("#relativesList").append($html);*/
                                            $('#suggestions2').fadeOut();
                                            Tecnotek.Tickets.afterCreateAndAssociateRelative();
                                            //Tecnotek.StudentShow.initDeleteButtons();
                                        }
                                    },
                                    function(jqXHR, textStatus){
                                        Tecnotek.showErrorMessage("Error setting data: " + textStatus + ".",
                                            true, "", false);
                                        $(this).val("");
                                        $('#suggestions2').fadeOut(); // Hide the suggestions box
                                    }, true);
                            });
                        }
                    },
                    function(jqXHR, textStatus){
                        Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".",
                            true, "", false);
                        $(this).val("");
                        $('#suggestions2').fadeOut(); // Hide the suggestions box
                    }, true);
            }
        });

        $('#searchBox22').blur(function(event){
            event.preventDefault();
            $(this).val("");
            $('#suggestions2').fadeOut(); // Hide the suggestions box
        });
        /**/
        $('#generalTab').click(function(event){
            event.preventDefault();
            $('#relativesSection').hide();
            $('#generalSection').show();
            $('#generalTab').toggleClass("tab-current");
            $('#relativesTab').toggleClass("tab-current");
        });

        $('#student').focus(function(event){
            event.preventDefault();
            $('#student').attr("rel", 0);
            $('#student').val("");
            $("#relative").empty();
        });

        $('#save').click(function(event){
            event.preventDefault();
            $id = $("#student").attr("rel");
            if($id != 0){
                $relative = $("#relative").val();
                if($relative != null){
                    $comments = $("#comments").val();
                    Tecnotek.ajaxCall(Tecnotek.UI.urls["saveTicketURL"],
                        {
                            studentId: $id,
                            relativeId: $relative,
                            comments: $comments
                        },
                        function(data){
                            if(data.error === true) {
                                Tecnotek.showErrorMessage(data.message,true, "", false);
                            } else {

                                location.reload(true);
                            }
                        },
                        function(jqXHR, textStatus){
                            Tecnotek.showErrorMessage("Error saving: " + textStatus + ".",
                                true, "", false);
                        }, true);
                } else {
                    Tecnotek.showErrorMessage(Tecnotek.StudentShow.translates["relative.not.selected"], true, "", false);
                }
            } else {
                Tecnotek.showErrorMessage(Tecnotek.StudentShow.translates["student.not.selected"], true, "", false);
            }
        });

        $('#student').keyup(function(event){
            event.preventDefault();
            if($(this).val().length == 0) {
                $('#suggestions').fadeOut(); // Hide the suggestions box
            } else {

            }
        });

        $('#student').blur(function(event){
            event.preventDefault();
            $(this).val("");
            $('#suggestions').fadeOut(); // Hide the suggestions box
        });

        $('.viewButton').click(function(event){
            event.preventDefault();
            location.href = Tecnotek.UI.urls["show"] + "/" + $(this).attr("rel");
        });

        $('.deleteButton').click(function(event){
            event.preventDefault();
            var id = $(this).attr("rel");
            if (Tecnotek.showConfirmationQuestion(Tecnotek.UI.translates["confirmDelete"])){
                Tecnotek.ajaxCall(Tecnotek.UI.urls["deleteTicketURL"],
                    {
                        id: id
                    },
                    function(data){
                        if(data.error === true) {
                            Tecnotek.showErrorMessage(data.message,true, "", false);
                        } else {
                            $("#ticket_row_" + id).empty().remove();
                        }
                    },
                    function(jqXHR, textStatus){
                        Tecnotek.showErrorMessage("Error saving: " + textStatus + ".",
                            true, "", false);
                    }, true);
            }

        });
    },// End of init
    afterCreateAndAssociateRelative: function(){
        $.fancybox.close();
      Tecnotek.Tickets.loadStudentRelatives();
    },
    loadStudentRelatives: function(){
        console.debug("Load Student Relatives of: " + Tecnotek.UI.vars["studentId"]);
        $("#relative").empty();
        Tecnotek.ajaxCall(Tecnotek.UI.urls["loadStudentRelativesURL"],
            {studentId: Tecnotek.UI.vars["studentId"]},
            function(data){
                if(data.error === true) {
                    $("#new-relative-btn").hide();
                    Tecnotek.showErrorMessage(data.message,true, "", false);
                } else {
                    $("#new-relative-btn").show();
                    $("#relatives-rows").html("");
                    if( data.relatives.length == 0){
                        //Tecnotek.showInfoMessage(Tecnotek.StudentShow.translates["relative.not.exists"], true, "", false);
                        //$('#student').attr("rel", 0);
                        // $('#student').val("");
                    } else {
                        for(i=0; i<data.relatives.length; i++) {
                            $("#relative").append('<option value="' + data.relatives[i].id
                                +'">' + data.relatives[i].contact + ' - ' + data.relatives[i].kinship + '</option>');

                            $relativeRowHtml = '<div class="row" rel="" style="padding: 0px; font-size: 10px;">' +
                                '<div class="" style="float: left; width: 250px;   max-width: 250px;white-space: nowrap; text-overflow: ellipsis; -o-text-overflow: ellipsis; display: inline-block; overflow: hidden;">' + data.relatives[i].contact + '</div>'+
                                '<div class="" style="float: left; width: 100px;">' + data.relatives[i].kinship + '</div>'+
                                '<div class="clear"></div>' +
                            '</div>';
                            $("#relatives-rows").append($relativeRowHtml);
                        }
                    }
                }
            },
            function(jqXHR, textStatus){
                Tecnotek.showErrorMessage("Error setting data: " + textStatus + ".",
                    true, "", false);
                $(this).val("");
                $('#suggestions').fadeOut(); // Hide the suggestions box
            }, true
        );
    }// End of loadStudentRelatives



};


Tecnotek.Projects = {
    translates : {},
    init : function() {
        $('#searchText').keyup(function(event){
            Tecnotek.UI.vars["page"] = 1;
            Tecnotek.Projects.searchProjects();
        });
        $('#btnSearch').unbind().click(function(event){
            Tecnotek.Projects.searchProjects();
        });
        $(".sort_header").click(function() {
            Tecnotek.UI.vars["sortBy"] = $(this).attr("field-name");
            Tecnotek.UI.vars["order"] = $(this).attr("order");
            console.debug("Order by " + Tecnotek.UI.vars["sortBy"] + " " + Tecnotek.UI.vars["order"]);
            $(this).attr("order", Tecnotek.UI.vars["order"] == "asc"? "desc":"asc");
            $(".header-title").removeClass("asc").removeClass("desc").addClass("sortable");
            $(this).children().addClass(Tecnotek.UI.vars["order"]);
            Tecnotek.Projects.searchProjects();
        });

        $("#openProjectForm").fancybox({
            'beforeLoad' : function(){

            }
        });

        $("#openProjectView").fancybox({
            'beforeLoad' : function(){

            }
        });

        $("#openProjectEdit").fancybox({
            'beforeLoad' : function(){

            }
        });

        $('#projectForm').submit(function(event){
            event.preventDefault();
            Tecnotek.Projects.createProject();
        });

        $('#projectFormEdit').submit(function(event){
            event.preventDefault();
            Tecnotek.Projects.updateProject();
        });

        Tecnotek.UI.vars["order"] = "asc";
        Tecnotek.UI.vars["sortBy"] = "id";
        Tecnotek.UI.vars["page"] = 1;
        Tecnotek.Projects.searchProjects();


        Tecnotek.Projects.initButtons();

    },
    initButtons: function() {
        console.debug("ProjectList :: initButtons");
        $('.viewButton').unbind();
        $('.viewButton').click(function(event){
            console.debug("Click en view button: " + Tecnotek.UI.urls["getInfoProjectFullURL"]);
            event.preventDefault();
            //Tecnotek.UI.vars["relativeId"] = $(this).attr("rel");
            Tecnotek.ajaxCall(Tecnotek.UI.urls["getInfoProjectFullURL"],
                {projectId: $(this).attr("rel")},
                function(data){
                    if(data.error === true) {
                        Tecnotek.showErrorMessage(data.message,true, "", false);
                    } else {
                        //Tecnotek.showInfoMessage(data.html, true, "", false);
                        $("#projectContainerView").html(data.html);
                        $("#openProjectView").trigger("click");
                        //alert("llega");
                    }
                },
                function(jqXHR, textStatus){
                    Tecnotek.showErrorMessage("Error in request: " + textStatus + ".",
                        true, "", false);
                }, true);
        });


        $('.editButton').unbind();
        $('.editButton').click(function(event){
            event.preventDefault();

            var projectId = $(this).attr("rel");
            Tecnotek.UI.vars["idProjectEdit"]  = projectId;
            Tecnotek.ajaxCall(Tecnotek.UI.urls["getInfoProjectURL"],
                {projectId: $(this).attr("rel")},
                function(data){
                    if(data.error === true) {
                        Tecnotek.showErrorMessage(data.message,true, "", false);
                    } else {
                        //Tecnotek.showInfoMessage(data.html, true, "", false);

                        $("#idProjectEdit").val(data.id);
                        $("#nameProjectEdit").val(data.name);
                        $("#codeProjectEdit").val(data.code);
                        $("#statusProjectEdit").val(data.status);
                        $("#typeProject").val(data.type);

                        $("#projectContainerEdit").html(data.html);
                        $("#openProjectEdit").trigger("click");
                        //alert("llega");
                    }
                },
                function(jqXHR, textStatus){
                    Tecnotek.showErrorMessage("Error in request: " + textStatus + ".",
                        true, "", false);
                }, true);

        });


    },
    searchProjects: function() {

    },
    createProject: function() {

        Tecnotek.ajaxCall(Tecnotek.UI.urls["createProjectURL"],
            {   name: $('#nameProject').val(),
                code: $('#codeProject').val(),
                type: $('#typeProject').val(),
                status: $('#statusProject').val()
            },
            function(data){
                if(data.error === true) {
                    Tecnotek.showErrorMessage(data.message,true, "", false);
                } else {
                    $.fancybox.close();
                    Tecnotek.Projects.searchProjects();
                }
            },
            function(jqXHR, textStatus){
                Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                $(this).val("");
            }, true);
    },
    updateProject: function() {
        Tecnotek.ajaxCall(Tecnotek.UI.urls["updateProjectURL"],
            {   projectId: $('#idProjectEdit').val(),
                name: $('#nameProjectEdit').val(),
                code: $('#codeProjectEdit').val(),
                type: $('#typeProjectEdit').val(),
                status: $('#statusProjectEdit').val()
            },
            function(data){
                if(data.error === true) {
                    Tecnotek.showErrorMessage(data.message,true, "", false);
                } else {
                    $.fancybox.close();
                    Tecnotek.Projects.searchProjects();
                }
            },
            function(jqXHR, textStatus){
                Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                $(this).val("");
            }, true);
    }
};
