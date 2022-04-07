if (typeof console == "undefined" || typeof console.log == "undefined" || typeof console.debug == "undefined") var console = { log: function() {}, debug: function() {} };
if (typeof jQuery !== 'undefined') {
	console.debug("JQuery found!!!");
	(function($) {
		$('#spinner').ajaxStart(function() {
			$(this).fadeIn();
		}).ajaxStop(function() {
			$(this).fadeOut();
		});
	})(jQuery);
} else {
	console.debug("JQuery not found!!!");
}
$(document).ready(function() {
    TecnotekTeacher.init();
});
var TecnotekTeacher = {
		module : "",
		imagesURL : "",
        assetsURL : "",
        isPeriodEditable : false,
		isIe: false,
		rowsCounter: 0,
		companiesCounter: 0,
		ftpCounter: 0,
        updateFail: false,
		session : {},
        updatedText: false,
        spinTarget: document.getElementById('spin'),
        spinner: new Spinner({
                    lines: 11, // The number of lines to draw
                    length: 20, // The length of each line
                    width: 4, // The line thickness
                    radius: 10, // The radius of the inner circle
                    corners: 1, // Corner roundness (0..1)
                    rotate: 0, // The rotation offset
                    color: '#000', // #rgb or #rrggbb
                    speed: 1, // Rounds per second
                    trail: 60, // Afterglow percentage
                    shadow: false, // Whether to render a shadow
                    hwaccel: false, // Whether to use hardware acceleration
                    className: 'spinner', // The CSS class to assign to the spinner
                    zIndex: 2e9, // The z-index (defaults to 2000000000)
                    //top: 'auto', // Top position relative to parent in px
                    //left: 'auto' // Left position relative to parent in px
                }).spin(document.getElementById('spin')),
		logout:function(url){
			location.href= url;
		},
        roundTo: function(original){
            //return Math.round(original*100)/100;
            return original.toFixed(2);
        },
		init : function() {
            $( "#spinner-modal" ).dialog({
                height: 140,
                modal: true,
                width: 160,
                resizable: false,
                draggable: false,
                autoOpen: false
            });
            $("#spinner-modal").siblings('div.ui-dialog-titlebar').remove();
            Tecnotek.spinner.spin(document.getElementById('spin'));
			var module = Tecnotek.module;
			console.debug("Module: " + module)
			if (module) {
				switch (module) {
                case "absences":
				case "administratorList":
                case "coordinadorList":
                case "profesorList":
                case "entityList":
                    Tecnotek.AdministratorList.init(); break;
                case "showAdministrador":
                case "showCoordinador":
                case "showProfesor":
                    Tecnotek.AdministratorShow.init(); break;
                case "showEntity":
                    Tecnotek.EntityShow.init(); break;
                case "showClub":
                    Tecnotek.EntityShow.init();
                    Tecnotek.ClubShow.init();
                    break;
                case "showStudent":
                    Tecnotek.EntityShow.init();
                    Tecnotek.StudentShow.init();
                    break;
                case "adminPeriod":
                    Tecnotek.AdminPeriod.init();
                    break;
                case "ticketsIndex":
                    Tecnotek.Tickets.init();
                    break;
                case "courseEntries":
                    Tecnotek.CourseEntries.init();
                    break;
                case "qualifications":
                    Tecnotek.Qualifications.init();
                    break;
                case "observations":
                    Tecnotek.Observations.init();
                    break;
                case "special_qualifications":
                    Tecnotek.SpecialQualifications.init();
                    break;
                case "psicoProfile":
                    Tecnotek.psicoProfile.init();
                    break;
                default:
					break;
				}
			}
			Tecnotek.UI.init();

		},
        ajaxCall : function(url, params, succedFunction, errorFunction, showSpinner) {
            //if(showSpinner) $( "#spinner-modal" ).dialog( "open" );
            var request = $.ajax({
                url: url,
                type: "POST",
                data: params,
                dataType: "json"
            });

            request.done(function(data){
                succedFunction(data);
                $( "#spinner-modal" ).dialog( "close" );
            });
            request.fail(function(data){
                errorFunction(data);
                $( "#spinner-modal" ).dialog( "close" );
            });
        },
        showInfoMessage : function(message, showAlert, divId, showDiv) {
            if ( showAlert ) {
                alert(message);
            }
            if ( showDiv ) {
                $("#" + divId).html(message);
            }
        },
        showErrorMessage : function(message, showAlert, divId, showDiv) {
            if ( showAlert ) {
                alert(message);
            }
            if ( showDiv ) {
                $("#" + divId).html(message);
            }
        },
        showConfirmationQuestion : function(message) {
            return confirm(message);
        },
		UI : {
			translates : {},
			urls : {},
            vars : {},
			intervals : {},
			init : function() {
				/*Tecnotek.Setup.setWaterMark();
				if($.browser.msie){
					Tecnotek.isIe = true;
				}*/
				Tecnotek.UI.initLocales();
				/*$(".tooltip").tooltip({
					showURL:false,
					top: -30});*/
			},
			initLocales: function(){
				/*$("#localeSelector").click(function(){
					//$("#localesLayer").slideUp("slow");
					$( "#localesLayer" ).toggle( "slide", { direction: "down" }, 500 );
				});
				$(".localeLink").click(function(){
					var locale = $(this).attr("lang");
					var url = location.href;

					url = url.split( '?' )[0];
					location.href = url+"?lang="+locale;
				});*/
			},
			btnAccept : "Accept",
			initModal : function(targetDiv, buttonsOpts) {
				$(targetDiv).dialog({
					title : '',
					dialogClass : 'alert',
					closeText : '',
					show : 'highlight',
					hide : 'highlight',
					autoOpen : false,
					bgiframe : true,
					modal : true,
					buttons : buttonsOpts
				});
			},
			closeModal : function(targetDiv) {
				$(targetDiv).dialog('close');
			},
			addModalEvent: function(targetDiv, eventName, toDo){

				$(targetDiv).bind( eventName, function(){
					//console.log("closing");
					toDo();
				});
			},
			modal : function(targetDiv, title, htmlSelector, html, isNewOpen,
					buttonsOpts, width, height) {

				Tecnotek.UI.initModal(targetDiv, buttonsOpts);
				/* Assign div content */
				if (html != '' && htmlSelector != '') {
					$(htmlSelector).html(html);
				} else if (html != '') {
					$(targetDiv).html(html);
				}

				/* Assign title */
				if (title != '') {
					$(targetDiv).dialog('option', 'title', title);
				}

				if (width == 0) {
					width = 280;
				}

				$(targetDiv).dialog('option', 'width', width);
				$(targetDiv).dialog('option', 'closeOnEscape', true);

				if (height != 0) {
					$(targetDiv).dialog('option', 'height', height);
				}

				// true if the modal is not open/ flase if the modal is already open
				// with different content
				if (isNewOpen) {

					$(targetDiv).dialog('open');
				}

				$(targetDiv).css("z-index", "5000");
			},
			validateForm : function(formSelector) {
				//alert("validating form");
				var result = $(formSelector).validationEngine('validate');
				//alert("result "+result);
				return result;
			}
		},
        CourseEntries : {
            translates : {},
            init : function() {
                Tecnotek.UI.vars["fromEdit"] = false;


                $('#entriesTab').click(function(event){
                    $("#subentriesSection").hide();
                    $('#entriesSection').show();
                    $('#subentriesTab').removeClass("tab-current");
                    $('#entriesTab').addClass("tab-current");
                });

                $('#subentriesTab').click(function(event){
                    $('#subentriesSection').show();
                    $("#entriesSection").hide();
                    $('#subentriesTab').addClass("tab-current");
                    $('#entriesTab').removeClass("tab-current");
                });

                $('#subentryForm').submit(function(event){
                    event.preventDefault();
                    Tecnotek.CourseEntries.createEntry();
                });

                $("#period").change(function(event){
                    event.preventDefault();
                    Tecnotek.CourseEntries.loadGroupsOfPeriod($(this).val());
                });

                $("#groups").change(function(event){
                    event.preventDefault();
                    Tecnotek.CourseEntries.loadCoursesOfGroupByTeacher($(this).val());
                });

                $("#courses").change(function(event){
                    event.preventDefault();
                    Tecnotek.CourseEntries.loadEntriesByCourse($(this).val());
                });

                $("#openEntryForm").fancybox({
                    'beforeLoad' : function(){
                        if(Tecnotek.UI.vars["fromEdit"] === false){
                            Tecnotek.UI.vars["subentryId"]  = 0;
                            $("#subentryFormName").val("");
                            $("#subentryFormCode").val("");
                            $("#subentryFormPercentage").val("");
                            $("#subentryFormMaxValue").val("");
                            $("#subentryFormSortOrder").val("");
                        }
                        Tecnotek.UI.vars["fromEdit"] = false;
                        Tecnotek.CourseEntries.preloadEntryForm();
                    }
                });

                Tecnotek.CourseEntries.loadGroupsOfPeriod($('#period').val());
                Tecnotek.CourseEntries.initButtons();
            },
            initButtons : function() {
                $("#entryFormCancel").click(function(event){
                    event.preventDefault();
                    $.fancybox.close();
                });
            },
            preloadEntryForm: function(){
                if(Tecnotek.isPeriodEditable === false){
                    Tecnotek.showInfoMessage("Este periodo no es editable, si es necesario incluir un subrubro consulte al administrador.",true, "", false);
                    $.fancybox.close();
                } else {
                    if($("#courses").val() === undefined || $("#courses").val() === "0"){
                        Tecnotek.showErrorMessage("Por favor seleccione una materia.",true, "", false);
                        $.fancybox.close();
                    } else {
                        if($("#subentryFormParent :selected").length <= 0){
                            Tecnotek.showErrorMessage("No es posible ingresar un subrubro sin un padre. \nNotifique al administrador para la creacion de los rubros previa.",true, "", false);
                            $.fancybox.close();
                        } else {
                            $("#entryTitleOption").text((Tecnotek.UI.vars["subentryId"] === 0)? "Incluir":"Editar");
                        }
                        //TODO Must load the list of courses again???
                    }
                }
            },
            initializeSubEntriesButtons: function(){
                $('.editSubEntry').unbind();
                $('.editSubEntry').click(function(event){
                    event.preventDefault();
                    if(Tecnotek.isPeriodEditable === false){
                        Tecnotek.showInfoMessage("Este periodo no es editable, si es necesario editar el registro consulte al administrador.",true, "", false);
                    } else {
                        var subentryId = $(this).attr("rel");
                        Tecnotek.UI.vars["subentryId"]  = subentryId;
                        $("#subentryFormName").val($("#subentryNameField_" + subentryId).text());
                        $("#subentryFormCode").val($("#subentryCodeField_" + subentryId).text());
                        $("#subentryFormPercentage").val($("#subentryPercentageField_" + subentryId).text());
                        $("#subentryFormMaxValue").val($("#subentryMaxValueField_" + subentryId).text());
                        $("#subentryFormSortOrder").val($("#subentryOrderField_" + subentryId).text());
                        $("#subentryFormParent").val($(this).attr("entryparent"));
                        Tecnotek.UI.vars["fromEdit"] = true;
                        $('#openEntryForm').trigger('click');
                    }
                });

                $('.deleteSubEntry').unbind();
                $('.deleteSubEntry').click(function(event){
                    event.preventDefault();
                    if(Tecnotek.isPeriodEditable === false){
                        Tecnotek.showInfoMessage("Este periodo no es editable, si es necesario eliminar el registro consulte al administrador.",true, "", false);
                    } else {
                        Tecnotek.CourseEntries.deleteSubEntry($(this).attr("rel"));
                    }
                });
            },
            createEntry: function() {
                Tecnotek.ajaxCall(Tecnotek.UI.urls["createEntryURL"],
                    {   parentId: $('#subentryFormParent').val(),
                        name: $('#subentryFormName').val(),
                        code: $('#subentryFormCode').val(),
                        maxValue: $('#subentryFormMaxValue').val(),
                        percentage: $('#subentryFormPercentage').val(),
                        sortOrder: $('#subentryFormSortOrder').val(),
                        groupId: $('#groups').val(),
                        subentryId: Tecnotek.UI.vars["subentryId"]
                    },
                    function(data){
                        if(data.error === true) {
                            Tecnotek.showErrorMessage(data.message,true, "", false);
                        } else {
                            $.fancybox.close();
                            Tecnotek.CourseEntries.loadEntriesByCourse($("#courses").val());
                        }
                    },
                    function(jqXHR, textStatus){
                        Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                    }, true);
            },
            deleteSubEntry: function(subentryId){
                if(Tecnotek.showConfirmationQuestion("Esta seguro que desea eliminar el subrubro?")) {
                    Tecnotek.ajaxCall(Tecnotek.UI.urls["deleteSubEntryURL"],
                        {   subentryId: subentryId },
                        function(data){
                            if(data.error === true) {
                                Tecnotek.showErrorMessage(data.message,true, "", false);
                            } else {
                                $("#subentryRow_" + subentryId).fadeOut('slow', function(){});
                            }
                        },
                        function(jqXHR, textStatus){
                            Tecnotek.showErrorMessage("Error deleting subentry: " + textStatus + ".", true, "", false);
                        }, true);
                }

            },
            loadGroupsOfPeriod: function($periodId) {
                if(($periodId!==null)){
                    $('#groups').children().remove();
                    $('#courses').children().remove();
                    $('#subentryFormParent').empty();
                    Tecnotek.ajaxCall(Tecnotek.UI.urls["loadGroupsOfPeriodURL"],
                        {   periodId: $periodId },
                        function(data){
                            if(data.error === true) {
                                Tecnotek.showErrorMessage(data.message,true, "", false);
                            } else {
                                for(i=0; i<data.groups.length; i++) {
                                    $('#groups').append('<option value="' + data.groups[i].id + '">' + data.groups[i].name + '</option>');
                                }
                                Tecnotek.isPeriodEditable = data.isEditable;
                                Tecnotek.CourseEntries.loadCoursesOfGroupByTeacher($('#groups').val());
                            }
                        },
                        function(jqXHR, textStatus){
                            Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                            $(this).val("");
                        }, false);
                }
            },
            loadCoursesOfGroupByTeacher: function($groupId) {
                if(($groupId!==null)){
                    $('#courses').children().remove();
                    $('#subentryFormParent').empty();
                    Tecnotek.CourseEntries.loadEntriesByCourse(0);
                    Tecnotek.ajaxCall(Tecnotek.UI.urls["loadCoursesOfGroupByTeacherURL"],
                        {   groupId: $groupId },
                        function(data){
                            if(data.error === true) {
                                Tecnotek.showErrorMessage(data.message,true, "", false);
                            } else {
                                for(i=0; i<data.courses.length; i++) {
                                    $('#courses').append('<option value="' + data.courses[i].id + '">' + data.courses[i].name + '</option>');
                                }

                                Tecnotek.CourseEntries.loadEntriesByCourse($('#courses').val());
                            }
                        },
                        function(jqXHR, textStatus){
                            Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                            $(this).val("");
                        }, false);
                }
            },
            loadEntriesByCourse: function(courseId) {
                $('.editEntry').unbind();
                $('#entriesRows').empty();
                $('#subentriesRows').empty();
                $('#subentryFormParent').empty();
                if(courseId == 0){//Clean page
                } else {
                    Tecnotek.ajaxCall(Tecnotek.UI.urls["loadEntriesByCourseURL"],
                        {   periodId: $("#period").val(),
                            courseId: courseId,
                            groupId: $("#groups").val()},
                        function(data){
                            if(data.error === true) {
                                Tecnotek.showErrorMessage(data.message,true, "", false);
                            } else {
                                $('#entriesRows').append(data.entriesHtml);
                                $('#subentriesRows').append(data.subentriesHtml);
                                $('#subentryFormParent').append(data.entries);
                                $('#subentryFormCourseClassId').val(data.courseClassId);

                                /*if( $("#tabsContainer").height() < (data.courses.length * 35 + 80) ){
                                    $("#tabsContainer").height(data.counter * 35 + 80);
                                };*/

                                $("#tabsContainer").height(data.counter * 35 + 80);

                                Tecnotek.CourseEntries.initializeSubEntriesButtons();
                            }
                        },
                        function(jqXHR, textStatus){
                            Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                        }, false);
                }
            }
        },
        Qualifications : {
            translates : {},
            counter: 0,
            waitingTimeout: null,
            init : function() {
                $('#btnPrint').click(function(event){
                    //console.debug("print!!!");
                    //$("#tableContainer").printElement({printMode:'iframe', pageTitle:$(this).attr('rel')});
                });
                $('#generateExcelBtn').click(function(e){
                    var periodId = $("#period").val();
                    var courseId = $("#courses").val();
                    var groupId = $("#groups").val();

                    if (periodId != null && courseId != null && groupId != null){
                        $(this).attr("href",Tecnotek.UI.urls["generateGroupExcel"] + "?periodId="+periodId+"&courseId="+courseId
                            +"&groupId="+groupId);
                    } else {
                        e.preventDefault();
                        Tecnotek.showInfoMessage("Necesita seleccionar el grupo y la materia antes de generar el archivo", true, '', false);
                    }
                });
                $('#viewPrintable').click(function(event){
                    event.preventDefault();
                    var url = Tecnotek.UI.urls["viewPrintableVersionURL"];
                    var windowName = "Calificaciones de Grupo";
                    //var windowSize = windowSizeArray[$(this).attr("rel")];
                    var periodId = $("#period").val();
                    var courseId = $("#courses").val();
                    var groupId = $("#groups").val();
                    if(periodId != null && courseId != null && groupId != null){
                        url += "?periodId=" + periodId + "&groupId=" + groupId + "&courseId=" + courseId;
                        window.open(url, windowName);
                    }
                });
                $("#loadExcelBtn").click(function(e) {
                    var periodId = $("#period").val();
                    var courseId = $("#courses").val();
                    var groupId = $("#groups").val();
                    if(periodId == null || periodId=="null" || periodId=="-1"
                        || courseId == null || courseId=="null" || courseId=="-1"
                        || groupId == null || groupId=="null" || groupId=="-1"){
                        Tecnotek.showInfoMessage("Necesita seleccionar el grupo y la materia antes de generar el archivo", true, '', false);
                        e.preventDefault();
                        e.stopPropagation();
                    }
                });
                $('#entriesTab').click(function(event){
                    $("#subentriesSection").hide();
                    $('#entriesSection').show();
                    $('#subentriesTab').removeClass("tab-current");
                    $('#entriesTab').addClass("tab-current");
                });

                $('#subentriesTab').click(function(event){
                    $('#subentriesSection').show();
                    $("#entriesSection").hide();
                    $('#subentriesTab').addClass("tab-current");
                    $('#entriesTab').removeClass("tab-current");
                });

                $('#subentryForm').submit(function(event){
                    event.preventDefault();
                    Tecnotek.Qualifications.createEntry();
                });

                $("#period").change(function(event){
                    event.preventDefault();
                    $('#subentryFormParent').empty();
                    Tecnotek.Qualifications.loadGroupsOfPeriod($(this).val());
                });

                $("#groups").change(function(event){
                    event.preventDefault();
                    Tecnotek.Qualifications.loadCoursesOfGroupByTeacher($(this).val());
                });

                $("#courses").change(function(event){
                    event.preventDefault();
                    //Tecnotek.Qualifications.loadEntriesOfCourse($(this).val());
                    Tecnotek.Qualifications.loadQualificationsOfGroup($(this).val());
                });
                $('#loadButton').click(function(e) {
                    e.preventDefault();
                    Tecnotek.showErrorMessage("Debe seleccionar un archivo válido antes de continuar con la carga", true, '', false);
                });
                $("#entries").change(function(event){
                    event.preventDefault();
                    if($(this).val() === "0"){
                        $(".entryBase").show();
                    } else {
                        $(".entryBase").hide();
                        $(".entryB_" + $(this).val() + "_").show();
                    }
                });
                //$("#progressbar").progressbar();
                $("#progressbar").hide();
                $('#excelFile').change(function(){
                    var file = this.files[0];
                    name = file.name;
                    size = file.size;
                    type = file.type;
                    $msg = "";
                    if (file.name.length < 1) {
                        $msg = "Ha ocurrido un error al validar el archivo, intente seleccionarlo otra vez";
                    } else if(file.size > 5 * 1024 * 1024) {
                        $msg = "El archivo es demasiado grande y supera el máximo permitido [5MB]";
                    } else if(file.type != 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') {
                        $msg = "El archivo no tiene un formato válido [xlsx]";
                    }
                    if ($msg != "") {
                        $('#loadButton').unbind().click(function(e) {
                            e.preventDefault();
                            Tecnotek.showErrorMessage("Debe seleccionar un archivo válido antes de continuar con la carga", true, '', false);
                        });
                        Tecnotek.showErrorMessage($msg ,true, '', false);
                    } else {
                        $('#loadButton').unbind().click(function(e){
                            e.preventDefault();
                            e.stopPropagation();
                            var url = $('#loadFileForm').attr("action");
                            $("#excelFile").hide();
                            $("#progressbar").show();
                            var formData = new FormData();
                            formData.append('file', document.getElementById('excelFile').files[0]);
                            formData.append('periodId', $("#period").val());
                            formData.append('groupId', $("#groups").val());
                            formData.append('courseId', $("#courses").val());
                            $.ajax({
                                url: $('#loadFileForm').attr("action"),  //server script to process data
                                type: 'POST',
                                async: true,
                                // Form data
                                data: formData,
                                cache: false,
                                enctype: 'multipart/form-data',
                                contentType: false,
                                processData: false,
                                xhr: function() {  // custom xhr
                                    myXhr = $.ajaxSettings.xhr();
                                    if(myXhr.upload){
                                        //myXhr.upload.addEventListener('progress',showProgress, false);
                                    }
                                    return myXhr;
                                },
                                success: function(data) {
                                    $("#progressbar").hide();
                                    $("#excelFile").show();
                                    data = jQuery.parseJSON(data);
                                    if (data['error']) {
                                        Tecnotek.showErrorMessage(data.message, true, '', false);
                                    } else {
                                        Tecnotek.showInfoMessage(data.message, true, '', false);
                                        $.modal.close();
                                        Tecnotek.Qualifications.loadQualificationsOfGroup($("#courses").val());
                                    }
                                },
                                error: function(xhr, ajaxOptions, thrownError) {
                                    $("#progressbar").hide();
                                    $("#excelFile").show();
                                    alert("Something went wrong!");
                                    /*alert(xhr.status);
                                     alert(thrownError);*/
                                }
                            }, 'json');
                        });
                    }
                });
                Tecnotek.Qualifications.loadGroupsOfPeriod($('#period').val());
                Tecnotek.Qualifications.initButtons();
            },
            initButtons : function() {
                $("#entryFormCancel").click(function(event){
                    event.preventDefault();
                    $.fancybox.close();
                });
            },
            loadGroupsOfPeriod: function($periodId) {
                $('#tableContainer').hide();
                if(($periodId!==null)){
                    $('#groups').children().remove();
                    $('#courses').children().remove();
                    $('#entries').children().remove();
                    $('#subentryFormParent').empty();
                    Tecnotek.ajaxCall(Tecnotek.UI.urls["loadGroupsOfPeriodURL"],
                        {   periodId: $periodId },
                        function(data){
                            if(data.error === true) {
                                Tecnotek.showErrorMessage(data.message,true, "", false);
                            } else {
                                for(i=0; i<data.groups.length; i++) {
                                    $('#groups').append('<option value="' + data.groups[i].id + '">' + data.groups[i].name + '</option>');
                                }
                                Tecnotek.isPeriodEditable = data.isEditable;
                                Tecnotek.Qualifications.loadCoursesOfGroupByTeacher($('#groups').val());
                            }
                        },
                        function(jqXHR, textStatus){
                            Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                            $(this).val("");
                        }, true);
                }
            },
            loadEntriesOfCourse: function($courseId) {
                $('#tableContainer').hide();
                if(($courseId!==null)){
                    $('#entries').children().remove();
                    $('#subentryFormParent').empty();
                    Tecnotek.ajaxCall(Tecnotek.UI.urls["loadEntriesOfCourseURL"],
                        {   courseId: $courseId },
                        function(data){
                            if(data.error === true) {
                                Tecnotek.showErrorMessage(data.message,true, "", false);
                            } else {
                                console.debug("Cargando rubros!!!");
                                /*for(i=0; i<data.groups.length; i++) {
                                    $('#groups').append('<option value="' + data.groups[i].id + '">' + data.groups[i].name + '</option>');
                                }
                                Tecnotek.isPeriodEditable = data.isEditable;
                                Tecnotek.Qualifications.loadCoursesOfGroupByTeacher($('#groups').val());*/
                            }
                        },
                        function(jqXHR, textStatus){
                            Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                            $(this).val("");
                        }, true);
                }
            },
            loadCoursesOfGroupByTeacher: function($groupId) {
                $('#tableContainer').hide();
                if(($groupId!==null)){
                    $('#courses').children().remove();
                    $('#entries').children().remove();
                    $('#subentryFormParent').empty();
                    Tecnotek.Qualifications.loadQualificationsOfGroup(0);
                    Tecnotek.ajaxCall(Tecnotek.UI.urls["loadCoursesOfGroupByTeacherURL"],
                        {   groupId: $groupId },
                        function(data){
                            if(data.error === true) {
                                Tecnotek.showErrorMessage(data.message,true, "", false);
                            } else {
                                $('#courses').append('<option value="-1"></option>');
                                for(i=0; i<data.courses.length; i++) {
                                    $('#courses').append('<option value="' + data.courses[i].id + '">' + data.courses[i].name + '</option>');
                                }
                                //Tecnotek.Qualifications.loadQualificationsOfGroup($('#courses').val());
                            }
                        },
                        function(jqXHR, textStatus){
                            Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                            $(this).val("");
                        }, false);
                }
            },
            loadQualificationsOfGroup: function(courseId) {
                $('#tableContainer').hide();
                $('.editEntry').unbind();
                $('#entriesRows').empty();
                $('#subentriesRows').empty();
                $('#subentryFormParent').empty();
                $('#contentBody').empty();
                $('#studentsHeader').empty();
                $('#entries').children().remove();
                if(courseId <= 0 ){//Clean page
                } else {
                    $('#fountainG').show();
                    Tecnotek.ajaxCall(Tecnotek.UI.urls["loadQualificationsOfGroupURL"],
                        {   periodId: $("#period").val(),
                            courseId: courseId,
                            groupId: $("#groups").val()},
                        function(data){
                            if(data.error === true) {
                                Tecnotek.showErrorMessage(data.message,true, "", false);
                            } else {
                                Tecnotek.UI.vars["notaMin"] = data.notaMin;
                                $("#tableContainer").width(data.codesCounter * 46 + 280);

                                $('#contentBody').html(data.html);
                                $('#studentsHeader').html(data.studentsHeader);


                                var height = data.studentsCounter * 26.66 + 300;
                                $("#studentsTableContainer").css("height", height + "px");

                                $(".textField").each(function(){
                                    if($(this).attr("val") !== "-1" && $(this).attr("val").indexOf("val") !== 0){
                                        $(this).val($(this).attr("val"));
                                    }
                                    $(this).trigger("blur");
                                });

                                $('#entries').append('<option value="0">Todos</option>');
                                for(i=0; i<data.entries.length; i++) {
                                    //console.debug("%o", data.entries[i]);
                                    //console.debug("--> " + data.entries[i].id + " :: " + data.entries[i]);
                                    $('#entries').append('<option value="' + data.entries[i].id + '">' + data.entries[i].name + '</option>');
                                }

                                Tecnotek.Qualifications.initializeTable();
                                Tecnotek.UI.vars["forzeBlur"] = true;
                                $(".textField").each(function(){
                                    $(this).trigger("focus");
                                    $(this).trigger("blur");
                                });
                                Tecnotek.UI.vars["forzeBlur"] = false;
                                $('#fountainG').hide();
                                $('#tableContainer').show();
                                //$( "#spinner-modal" ).dialog( "close" );
                            }
                        },
                        function(jqXHR, textStatus){
                            $( "#spinner-modal" ).dialog( "close" );
                            Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                        }, false);
                }
            },
            initializeTable: function() {
                if(Tecnotek.isPeriodEditable === false){
                    $(".textField").attr('disabled', 'disabled');
                }

                $('.editEntry').unbind();
                $('#entriesRows').empty();
                $('#subentriesRows').empty();
                $('#subentryFormParent').empty();

                $(".textField").focus(function(e){
                    Tecnotek.updatedText = false;
                    Tecnotek.UI.vars["textFieldValue"] = $(this).val();
                });

                $('.textField').keyup(function(event) {
                    event.preventDefault();
                    if (event.which == 13) {//Enter key
                        var tabindex = $(this).attr('tabindex');
                        tabindex++; //increment tabindex
                        $(this).blur();
                        $('[tabindex=' + tabindex + ']').focus();
                    } else {
                        if (event.which == 9) {//Tab key

                        } else {
                            Tecnotek.updatedText = true;
                        }
                    }
                });


                $(".textField").blur(function(e){
                    e.preventDefault();
                    $this = $(this);
                    $type = $this.attr('tipo');
                    $max = $this.attr('max');
                    $nota = $this.val();
                    $stdId = $this.attr('std');

                    if(($nota * 1) > ($max * 1)){
                        Tecnotek.showInfoMessage("El valor maximo permitido es " + $max,true, "", false);
                        $this.val("");
                        $nota = "";
                        return;
                    }

                    if(Tecnotek.UI.vars["forzeBlur"] == true){
                        if($type == 1){
                            $percentage = $this.attr('perc');
                            $max = $this.attr('max');
                            $totalField = $("#" + $this.attr('rel'));
                            if($nota == "") {
                                $totalField.html("-");
                            } else {
                                $totalField.html("" + Tecnotek.roundTo(($percentage * $nota / $max)));
                            }
                        } else {
                            $childs = $this.attr('child');
                            $parent = $this.attr('parent');

                            $sum = 0;
                            $sumaPorcentage = 0;
                            $counter = 0;
                            $sumaPorcentagesAsignados = 0;
                            $('.item_' + $parent + "_" + $stdId).each(function() {
                                $notaDigitada = $(this).val();
                                $valorMax = $(this).attr("max");
                                $porcentageAsignado = parseFloat($(this).attr("perc"));
                                $sumaPorcentagesAsignados = $porcentageAsignado;
                                if($notaDigitada != ""){
                                    //100/valor max * nota digitada * %asignado
                                    $sumaPorcentage += (100 / parseFloat($valorMax) * parseFloat($notaDigitada) * ($porcentageAsignado / 100));
                                    $sum += parseFloat( $notaDigitada );
                                    $counter++;
                                }
                            });

                            if($counter == 0){
                                 $("#prom_" + $parent + "_" + $stdId).html("-");
                                 $totalField = $("#" + $this.attr('rel'));
                                 //$("#total_" + $parent + "_" + $stdId).html("-");
                             } else {
                                 $percentage =  $("#prom_" + $parent + "_" + $stdId).attr('perc');
                                 $max = $("#prom_" + $parent + "_" + $stdId).attr('max');
                                 $("#prom_" + $parent + "_" + $stdId).html("" + Tecnotek.roundTo(($sum/$childs)));
                                 //$("#total_" + $parent + "_" + $stdId).html("" + Tecnotek.roundTo(($percentage * ($sum/$childs) / $max)));


                                $porcentageRubro = $("#total_" + $parent + "_" + $stdId).attr("perc");
                                if($sumaPorcentagesAsignados == $porcentageRubro){
                                    $("#total_" + $parent + "_" + $stdId).html("" + Tecnotek.roundTo($sumaPorcentage));
                                } else {
                                    $("#total_" + $parent + "_" + $stdId).html("" + Tecnotek.roundTo($sumaPorcentage / $counter));
                                }

                             }
                        }

                        $sum = 0;
                        $counter = 0;
                        $('.nota_' + $stdId).each(function() {
                            $temp = $(this).html();
                            if($temp != "-"){
                                /*console.debug("Bandera Temp: " + $temp + "<-");
                                $temp = $temp.slice(0, -1);
                                console.debug("Bandera Temp: " + $temp + "<-");*/
                                $sum += parseFloat( $temp );
                                $counter++;
                            }
                        });

                        if($counter == 0){
                            $("#total_trim_" + $stdId).html("-");
                        } else {
                            $totalTrim = Tecnotek.roundTo($sum);
                            $notaMin = (Tecnotek.UI.vars["notaMin"] != undefined)?
                                Tecnotek.UI.vars["notaMin"]:0;
                            $beforeText = ($totalTrim >= $notaMin)? "":"* ";
                            $("#total_trim_" + $stdId).html($beforeText + Tecnotek.roundTo($sum));
                        }
                    } else {
                            if(Tecnotek.updatedText === false) return;

                            var vSubEntryid = $this.attr('entry');
                            var vStudentYearId = $this.attr('stdyid');
                            /////--------------------------------
                            /*Tecnotek.ajaxCall(Tecnotek.UI.urls["saveStudentQualificationURL"],
                                {   subentryId: $this.attr('entry'),
                                    studentYearId: $this.attr('stdyid'),
                                    qualification: $nota},
                                function(data){
                                    if(data.error === true) {
                                        Tecnotek.showErrorMessage(data.message,true, "", false);
                                        $this.val("");
                                    } else {
                                        if($type == 1){
                                            $percentage = $this.attr('perc');
                                            $max = $this.attr('max');
                                            $totalField = $("#" + $this.attr('rel'));
                                            //console.debug("Type = " + $type + ", Nota: " + $nota + ", Perc = " + $percentage + " :: " + $totalField);
                                            if($nota == "") {
                                                $totalField.html("-");
                                            } else {
                                                //console.debug("Calcular total para " + $(this).attr('rel') + ", total = " + ($percentage * $nota / 100));
                                                $totalField.html("" + Tecnotek.roundTo(($percentage * $nota / $max)));
                                            }
                                        } else {
                                            $childs = $this.attr('child');
                                            $parent = $this.attr('parent');

                                            //console.debug("Type = " + $type + ", Nota: " + $nota + " :: childs = " + $childs + " :: $stdId = " + $stdId);
                                            $sum = 0;
                                            $counter = 0;
                                            $sumaPorcentage = 0;
                                            $sumaPorcentagesAsignados = 0;
                                            $('.item_' + $parent + "_" + $stdId).each(function() {
                                                $notaDigitada = $(this).val();
                                                $valorMax = $(this).attr("max");
                                                $porcentageAsignado = parseFloat($(this).attr("perc"));
                                                $sumaPorcentagesAsignados += $porcentageAsignado;
                                                if($notaDigitada != ""){
                                                    //100/valor max * nota digitada * %asignado
                                                    $sumaPorcentage += (100 / parseFloat($valorMax) * parseFloat($notaDigitada) * ($porcentageAsignado / 100));
                                                    $sum += parseFloat( $notaDigitada );
                                                    $counter++;
                                                }

                                            });

                                            if($counter == 0){
                                                $("#prom_" + $parent + "_" + $stdId).html("-");
                                                $totalField = $("#" + $this.attr('rel'));
                                                $("#total_" + $parent + "_" + $stdId).html("-");
                                            } else {
                                                $percentage =  $("#prom_" + $parent + "_" + $stdId).attr('perc');
                                                $max =  $("#prom_" + $parent + "_" + $stdId).attr('max');
                                                $("#prom_" + $parent + "_" + $stdId).html("" + Tecnotek.roundTo(($sum/$childs)));

                                                $porcentageRubro = $("#prom_" + $parent + "_" + $stdId).attr("perc");
                                                console.debug("" + $sumaPorcentagesAsignados + " :: " + $porcentageAsignado + " :: " + ($sumaPorcentagesAsignados == $porcentageRubro) + " :: " + ($sumaPorcentagesAsignados === $porcentageRubro));
                                                if($sumaPorcentagesAsignados == $porcentageRubro){
                                                    $("#total_" + $parent + "_" + $stdId).html("" + Tecnotek.roundTo($sumaPorcentage));
                                                } else {
                                                    $("#total_" + $parent + "_" + $stdId).html("" + Tecnotek.roundTo($sumaPorcentage / $counter));
                                                }
                                            }

                                        }

                                        $sum = 0;
                                        $counter = 0;
                                        $('.nota_' + $stdId).each(function() {
                                            $temp = $(this).html();
                                            if($temp != "-"){
                                                //$temp = $temp.slice(0, -1);
                                                $sum += parseFloat( $temp );
                                                $counter++;
                                            }
                                        });
                                        if($counter == 0){
                                            $("#total_trim_" + $stdId).html("-");
                                        } else {
                                            $("#total_trim_" + $stdId).html("" + Tecnotek.roundTo($sum));
                                        }
                                    }
                                },
                                function(jqXHR, textStatus){
                                    Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                                }, false);*/

                        Tecnotek.Qualifications.cantidadPendientes++;
                        $("#pendientes").html(Tecnotek.Qualifications.cantidadPendientes + " Pendientes").show();

                        Tecnotek.Qualifications.ajaxQueue({
                            url: Tecnotek.UI.urls["saveStudentQualificationURL"],
                            data: {
                                subentryId: vSubEntryid,
                                studentYearId: vStudentYearId,
                                qualification: $nota,
                                elementId: $(this).attr("id"),
                                elementPerc: $this.attr('perc'),
                                elementMax: $this.attr('max'),
                                elementRel: $this.attr('rel'),
                                elementChild: $this.attr('child'),
                                elementParent: $this.attr('parent'),
                                elementStdId: $this.attr('std'),
                                elementType: $type
                            },
                            type: "POST",
                            dataType: "json",
                            success: function( data ) {
                                /*console.debug("Data = [error: " + data['error']
                                    + ", elementId: " + data['elementId']
                                    + ", elementPerc: " + data['elementPerc']
                                    + ", elementMax: " + data.elementMax
                                    + ", elementRel: " + data.elementRel
                                    + ", elementChild: " + data.elementChild
                                    + ", qualification: " + data.qualification
                                    + ", elementParent: " + data.elementParent
                                    + ", elementStdId: " + data.elementStdId
                                    + ", elementType: " + data.elementType + "]");*/

                                if(data.error === true) {
                                    Tecnotek.showErrorMessage(data.message,true, "", false);
                                    $("#" + data.elementId).val("");
                                } else {
                                    if(data.elementType == "1"){
                                        $percentage = data.elementPerc;
                                        $max = data.elementMax;
                                        $totalField = $("#" + data.elementRel);

                                        //console.debug("Type = " + $type + ", Nota: " + $nota + ", Perc = " + $percentage + " :: " + $totalField);
                                        if(data.qualification == "") {
                                            $totalField.html("-");
                                        } else {
                                            //console.debug("Calcular total para " + $(this).attr('rel') + ", total = " + ($percentage * $nota / 100));
                                            $totalField.html("" + Tecnotek.roundTo(($percentage * data.qualification / $max)));
                                        }
                                    } else {
                                        $childs = data.elementChild;
                                        $parent = data.elementParent;

                                        //console.debug("Type = " + $type + ", Nota: " + $nota + " :: childs = " + $childs + " :: $stdId = " + $stdId);
                                        $sum = 0;
                                        $counter = 0;
                                        $sumaPorcentage = 0;
                                        $sumaPorcentagesAsignados = 0;
                                        $('.item_' + $parent + "_" + data.elementStdId).each(function() {
                                            $notaDigitada = $(this).val();
                                            $valorMax = $(this).attr("max");
                                            $porcentageAsignado = parseFloat($(this).attr("perc"));
                                            $sumaPorcentagesAsignados += $porcentageAsignado;
                                            if($notaDigitada != ""){
                                                //100/valor max * nota digitada * %asignado
                                                $sumaPorcentage += (100 / parseFloat($valorMax) * parseFloat($notaDigitada) * ($porcentageAsignado / 100));
                                                $sum += parseFloat( $notaDigitada );
                                                $counter++;
                                            }

                                        });

                                        if($counter == 0){
                                            $("#prom_" + $parent + "_" + data.elementStdId).html("-");
                                            $totalField = $("#" + data.elementRel);
                                            $("#total_" + $parent + "_" + data.elementStdId).html("-");
                                        } else {
                                            $percentage =  $("#prom_" + $parent + "_" + data.elementStdId).attr('perc');
                                            $max =  $("#prom_" + $parent + "_" + data.elementStdId).attr('max');
                                            $("#prom_" + $parent + "_" + data.elementStdId).html("" + Tecnotek.roundTo(($sum/$childs)));

                                            $porcentageRubro = $("#prom_" + $parent + "_" + data.elementStdId).attr("perc");
                                            if($sumaPorcentagesAsignados == $porcentageRubro){
                                                $("#total_" + $parent + "_" + data.elementStdId).html("" + Tecnotek.roundTo($sumaPorcentage));
                                            } else {
                                                $("#total_" + $parent + "_" + data.elementStdId).html("" + Tecnotek.roundTo($sumaPorcentage / $counter));
                                            }
                                        }
                                    }

                                    $sum = 0;
                                    $counter = 0;
                                    $('.nota_' + data.elementStdId).each(function() {
                                        $temp = $(this).html();
                                        if($temp != "-"){
                                            //$temp = $temp.slice(0, -1);
                                            $sum += parseFloat( $temp );
                                            $counter++;
                                        }
                                    });
                                    if($counter == 0){
                                        $("#total_trim_" + data.elementStdId).html("-");
                                    } else {
                                        $totalTrim = Tecnotek.roundTo($sum);
                                        $notaMin = (Tecnotek.UI.vars["notaMin"] != undefined)?
                                            Tecnotek.UI.vars["notaMin"]:0;
                                        $beforeText = ($totalTrim >= $notaMin)? "":"* ";
                                        $("#total_trim_" + data.elementStdId).html($beforeText + Tecnotek.roundTo($sum));
                                        //$("#total_trim_" + data.elementStdId).html("B" + Tecnotek.roundTo($sum));
                                    }
                                }

                                //Termino de procesar una.
                                Tecnotek.Qualifications.cantidadPendientes--;
                                if(Tecnotek.Qualifications.cantidadPendientes == 0){
                                    $("#pendientes").html(Tecnotek.Qualifications.cantidadPendientes + " Pendientes");
                                    $("#pendientes").hide();
                                } else {
                                    //console.debug("Quedan pendientes: " + Tecnotek.Qualifications.cantidadPendientes);
                                    $("#pendientes").html(Tecnotek.Qualifications.cantidadPendientes + " Pendientes");
                                    $("#pendientes").show();
                                }
                            }
                        });
                        /////--------------------------------
                    }

                });
            },
            cantidadPendientes: 0,
            requestsQueue : $({}),
            ajaxQueue : function ( ajaxOpts ){
                // Hold the original complete function.
                var oldComplete = ajaxOpts.complete;

                // Queue our ajax request.
                Tecnotek.Qualifications.requestsQueue.queue(function( next ) {
                    // Create a complete callback to fire the next event in the queue.
                    ajaxOpts.complete = function() {
                        // Fire the original complete if it was there.
                        if ( oldComplete ) {
                            oldComplete.apply( this, arguments );
                        }
                        // Run the next query in the queue.
                        next();
                    };

                    // Run the query.
                    //console.debug("Making an ajax request: " + ajaxOpts.data.subentryId);
                    $.ajax( ajaxOpts );
                });
            },
            enqueueUpdate : function(){
                $(document).queue('myqueue',function(next){
                    Tecnotek.Qualifications.counter+=1;
                    //counter+=1
                    //$('<div />').hide().html(counter).appendTo('#display').fadeIn(1000,next);
                    console.debug("Counter: " + Tecnotek.Qualifications.counter);
                });
            },
            noMoreUpdates : function(){
                $(document).dequeue('myqueue');
            },
            processUpdate : function() {

            }
        },
        Observations : {
            translates : {},
            init : function() {
                $('#btnPrint').click(function(event){
                    //console.debug("print!!!");
                    //$("#tableContainer").printElement({printMode:'iframe', pageTitle:$(this).attr('rel')});
                });

                $('#viewPrintable').click(function(event){
                    event.preventDefault();

                    var url = Tecnotek.UI.urls["viewPrintableVersionURL"];
                    var windowName = "Observaciones de Grupo";
                    //var windowSize = windowSizeArray[$(this).attr("rel")];

                    var periodId = $("#period").val();
                    var courseId = $("#courses").val();
                    var groupId = $("#groups").val();

                    if(periodId != null && courseId != null && groupId != null){
                        url += "?periodId=" + periodId + "&groupId=" + groupId + "&courseId=" + courseId;
                        window.open(url, windowName);
                    }

                });

                $('#entriesTab').click(function(event){
                    $("#subentriesSection").hide();
                    $('#entriesSection').show();
                    $('#subentriesTab').removeClass("tab-current");
                    $('#entriesTab').addClass("tab-current");
                });

                $('#subentriesTab').click(function(event){
                    $('#subentriesSection').show();
                    $("#entriesSection").hide();
                    $('#subentriesTab').addClass("tab-current");
                    $('#entriesTab').removeClass("tab-current");
                });

                $('#subentryForm').submit(function(event){
                    event.preventDefault();
                    Tecnotek.Observations.createEntry();
                });

                $("#period").change(function(event){
                    event.preventDefault();
                    $('#subentryFormParent').empty();
                    Tecnotek.Observations.loadGroupsOfPeriod($(this).val());
                });

                $("#groups").change(function(event){
                    event.preventDefault();
                    Tecnotek.Observations.loadCoursesOfGroupByTeacher($(this).val());
                });

                $("#courses").change(function(event){
                    event.preventDefault();
                    Tecnotek.Observations.loadObservationsOfGroup($(this).val());
                });

                Tecnotek.Observations.loadGroupsOfPeriod($('#period').val());
                Tecnotek.Observations.initButtons();
            },
            initButtons : function() {
                $("#entryFormCancel").click(function(event){
                    event.preventDefault();
                    $.fancybox.close();
                });

                $("#openContactyForm").fancybox({
                    'afterLoad' : function(){

                    }
                });

                $('.viewButton').unbind();
                $('.viewButton').click(function(event){
                    console.debug("Click en view button: " + Tecnotek.UI.urls["getInfoRelativesURL"]);
                    event.preventDefault();
                    //Tecnotek.UI.vars["relativeId"] = $(this).attr("rel");
                    Tecnotek.ajaxCall(Tecnotek.UI.urls["getInfoRelativesURL"],
                        {studentId: $(this).attr("rel")},
                        function(data){
                            if(data.error === true) {
                                Tecnotek.showErrorMessage(data.message,true, "", false);
                            } else {
                                //Tecnotek.showInfoMessage(data.html, true, "", false);
                                $("#contactContainer").html(data.html);
                                $("#openContactyForm").trigger("click");
                            }
                        },
                        function(jqXHR, textStatus){
                            Tecnotek.showErrorMessage("Error in request: " + textStatus + ".",
                                true, "", false);
                        }, true);
                });
            },
            loadGroupsOfPeriod: function($periodId) {
                if(($periodId!==null)){
                    $('#groups').children().remove();
                    $('#courses').children().remove();
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
                                Tecnotek.isPeriodEditable = data.isEditable;
                                Tecnotek.Observations.loadCoursesOfGroupByTeacher($('#groups').val());
                            }
                        },
                        function(jqXHR, textStatus){
                            Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                            $(this).val("");
                        }, true);
                }
            },
            loadCoursesOfGroupByTeacher: function($groupId) {
                if(($groupId!==null)){
                    $('#courses').children().remove();
                    $('#subentryFormParent').empty();
                    Tecnotek.Observations.loadObservationsOfGroup(0);
                    Tecnotek.ajaxCall(Tecnotek.UI.urls["loadCoursesOfGroupByTeacherURL"],
                        {   groupId: $groupId },
                        function(data){
                            if(data.error === true) {
                                Tecnotek.showErrorMessage(data.message,true, "", false);
                            } else {
                                for(i=0; i<data.courses.length; i++) {
                                    $('#courses').append('<option value="' + data.courses[i].id + '">' + data.courses[i].name + '</option>');
                                }
                                Tecnotek.Observations.loadObservationsOfGroup($('#courses').val());
                            }
                        },
                        function(jqXHR, textStatus){
                            Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                            $(this).val("");
                        }, false);
                }
            },
            loadObservationsOfGroup: function(courseId) {
                $('.editEntry').unbind();
                $('#entriesRows').empty();
                $('#subentriesRows').empty();
                $('#subentryFormParent').empty();
                $('#contentBody').empty();
                $('#studentsHeader').empty();
                if(courseId == 0){//Clean page
                } else {
                    Tecnotek.ajaxCall(Tecnotek.UI.urls["loadObservationsOfGroupURL"],
                        {   periodId: $("#period").val(),
                            courseId: courseId,
                            groupId: $("#groups").val()},
                        function(data){
                            if(data.error === true) {
                                Tecnotek.showErrorMessage(data.message,true, "", false);
                            } else {
                                $("#tableContainer").width(data.codesCounter * 46 + 280);
                                $('#contentBody').html(data.html);
                                $('#studentsHeader').html(data.studentsHeader);
                                $('#tableContainer').show();

                                var height = data.studentsCounter * 26.66 + 300;
                                $("#studentsTableContainer").css("height", height + "px");

                                $(".textField").each(function(){
                                    if($(this).attr("val") !== "-1" && $(this).attr("val").indexOf("val") !== 0){
                                        $(this).val($(this).attr("val"));
                                    }
                                    $(this).trigger("blur");
                                });

                                Tecnotek.Observations.initializeTable();
                                Tecnotek.Observations.initButtons(); //agregado

                                //$( "#spinner-modal" ).dialog( "close" );
                            }
                        },
                        function(jqXHR, textStatus){
                            $( "#spinner-modal" ).dialog( "close" );
                            Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                        }, false);
                }
            },
            initializeTable: function() {
                if(Tecnotek.isPeriodEditable === false){
                    $(".observation").attr('disabled', 'disabled');
                }
                $('.editEntry').unbind();
                //$('#entriesRows').empty();
                //$('#subentriesRows').empty();
                //$('#subentryFormParent').empty();

                $(".psicoButton").unbind().click(function(e){
                    var rel = $(this).attr("rel");
                    window.location = Tecnotek.UI.urls["studentPsicosURL"] + "/" + rel;
                });

                $(".observation").unbind();

                $(".observation").focus(function(e){
                    Tecnotek.UI.vars["textFieldValue"] = $(this).val();
                });
                $(".observation").blur(function(e){
                    e.preventDefault();
                    $this = $(this);

                    //window.alert("saliendo de una observacion que quedo con el value: " + $("#teacherid").val());

                    $observation = $this.val();
                    $stdId = $this.attr('std');

                    if(Tecnotek.UI.vars["textFieldValue"] === $observation) return;
                    /*
                    Tecnotek.ajaxCall(Tecnotek.UI.urls["saveStudentObservationURL"],
                        {   courseClass: $this.attr('courseClass'),
                            studentYearId: $this.attr('stdyid'),
                            groupId: $("#groups").val(),
                            userId: $("#teacherid").val(),
                            observation: $observation},
                        function(data){
                            if(data.error === true) {
                                Tecnotek.showErrorMessage(data.message,true, "", false);
                                $this.val("");
                            }
                        },
                        function(jqXHR, textStatus){
                            Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                        }, false);
                    */
                    Tecnotek.Qualifications.cantidadPendientes++;
                    $("#pendientes").html(Tecnotek.Qualifications.cantidadPendientes + " Pendientes").show();
                    Tecnotek.Qualifications.ajaxQueue({
                        url: Tecnotek.UI.urls["saveStudentObservationURL"],
                        data: {
                            courseClass: $this.attr('courseClass'),
                            studentYearId: $this.attr('stdyid'),
                            groupId: $("#groups").val(),
                            userId: $("#teacherid").val(),
                            observation: $observation
                        },
                        type: "POST",
                        dataType: "json",
                        success: function( data ) {
                            if(data.error === true) {
                                Tecnotek.showErrorMessage(data.message,true, "", false);
                                $this.val("");
                            } else {
                            }

                            //Termino de procesar una.
                            Tecnotek.Qualifications.cantidadPendientes--;
                            if(Tecnotek.Qualifications.cantidadPendientes == 0){
                                $("#pendientes").html(Tecnotek.Qualifications.cantidadPendientes + " Pendientes");
                                $("#pendientes").hide();
                                //console.debug("No Quedan Pendientes!!!");
                            } else {
                                //console.debug("Quedan pendientes: " + Tecnotek.Qualifications.cantidadPendientes);
                                $("#pendientes").html(Tecnotek.Qualifications.cantidadPendientes + " Pendientes");
                                $("#pendientes").show();
                            }
                        }
                    });
                });
            }
        },
        SpecialQualifications : {
            translates : {},
            init : function() {

                $("#period").change(function(event){
                    event.preventDefault();
                    $('#subentryFormParent').empty();
                    Tecnotek.SpecialQualifications.loadGroupsOfPeriod($(this).val());
                });

                $("#groups").change(function(event){
                    event.preventDefault();
                    Tecnotek.SpecialQualifications.loadSpecialQualificationsOfGroup();
                    Tecnotek.SpecialQualifications.loadCoursesOfGroupByTeacher($(this).val());
                });
                $("#openPageWithForms").fancybox({
                    'afterLoad' : function(){

                    }
                });

                Tecnotek.SpecialQualifications.loadGroupsOfPeriod($('#period').val());
                Tecnotek.SpecialQualifications.initButtons();
            },
            initButtons : function() {
                $("#entryFormCancel").click(function(event){
                    event.preventDefault();
                    $.fancybox.close();
                });
            },
            loadGroupsOfPeriod: function($periodId) {
                if(($periodId!==null)){
                    $('#groups').children().remove();
                    $('#courses').children().remove();
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
                                Tecnotek.isPeriodEditable = data.isEditable;
                                Tecnotek.SpecialQualifications.loadSpecialQualificationsOfGroup();
                            }
                        },
                        function(jqXHR, textStatus){
                            Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                            $(this).val("");
                        }, true);
                }
            },
            loadSpecialQualificationsOfGroup: function() {
                console.debug("--> loadSpecialQualificationsOfGroup ");
                $('.editEntry').unbind();
                $('#entriesRows').empty();
                $('#subentriesRows').empty();
                $('#subentryFormParent').empty();
                $('#contentBody').empty();
                $('#studentsHeader').empty();
                    Tecnotek.ajaxCall(Tecnotek.UI.urls["loadObservationsOfGroupURL"],
                        {   periodId: $("#period").val(),
                            groupId: $("#groups").val()},
                        function(data){
                            if(data.error === true) {
                                Tecnotek.showErrorMessage(data.message,true, "", false);
                            } else {
                                $("#tableContainer").width(data.codesCounter * 46 + 280);
                                $('#contentBody').html(data.html);
                                $('#studentsHeader').html(data.studentsHeader);
                                $('#tableContainer').show();

                                var height = data.studentsCounter * 26.66 + 300;
                                $("#studentsTableContainer").css("height", height + "px");

                                $(".textField").each(function(){
                                    if($(this).attr("val") !== "-1" && $(this).attr("val").indexOf("val") !== 0){
                                        $(this).val($(this).attr("val"));
                                    }
                                    $(this).trigger("blur");
                                });

                                Tecnotek.SpecialQualifications.initializeTable();

                                //$( "#spinner-modal" ).dialog( "close" );
                            }
                        },
                        function(jqXHR, textStatus){
                            $( "#spinner-modal" ).dialog( "close" );
                            Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                        }, false);
            },
            initializeTable: function() {
                if(Tecnotek.isPeriodEditable === false){
                    $(".observation").attr('disabled', 'disabled');
                }
                $('.specialQualificationsButton').unbind();
                $('.specialQualificationsButton').click(function(e){
                    e.preventDefault();
                    Tecnotek.SpecialQualifications.loadSpecialQualificationsForms($(this).attr('stdyId'));
                });
            },
            loadSpecialQualificationsForms: function($stdyId) {
                $("#specialQualificationsForms").html("");
                Tecnotek.ajaxCall(Tecnotek.UI.urls["loadStudentSpecialQualificationsURL"],
                    {   periodId: $("#period").val(),
                        stdyId: $stdyId
                    },
                    function(data){
                        if(data.error === true) {
                            Tecnotek.showErrorMessage(data.message,true, "", false);
                        } else {
                            $('#specialQualificationsForms').html(data.html);
                            $("#openPageWithForms").trigger("click");
                            Tecnotek.SpecialQualifications.initializeOptions();
                        }
                    },
                    function(jqXHR, textStatus){
                        $( "#spinner-modal" ).dialog( "close" );
                        Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                    }, false);
            },
            initializeOptions: function(){
                $(".sq-option").unbind();
                $(".sq-option").change(function(e){
                    e.preventDefault();
                    $stdyId = $(this).attr('stdyId');
                    $sq = $(this).attr('sq');
                    Tecnotek.SpecialQualifications.saveStudentQualification($stdyId, $sq, $(this).val(), $(this).attr('pn') );
                });
                $('.form-comments').unbind();
                $('.form-comments').blur(function(){
                    $stdyId = $(this).attr('stdyId');
                    $fq = $(this).attr('fq');
                    Tecnotek.SpecialQualifications.saveStudentQualification($stdyId, $fq, $(this).val(), 0);
                });
            },
            saveStudentQualification : function($stdyId, $sq, $value, $pn){
                Tecnotek.Qualifications.cantidadPendientes++;
                $("#pendientes").html(Tecnotek.Qualifications.cantidadPendientes + " Pendientes").show();
                console.debug(Tecnotek.Qualifications.cantidadPendientes + " Pendientes");
                Tecnotek.Qualifications.ajaxQueue({
                    url: Tecnotek.UI.urls["saveStudentQualificationURL"],
                    data: {
                        stdyId: $stdyId,
                        sq:     $sq,
                        value:  $value,
                        pn:     $pn
                    },
                    cache: false,
                    type: "POST",
                    dataType: "json",
                    success: function( data ) {
                        if(data.error === true) {
                        } else {
                        }

                        //Termino de procesar una.
                        Tecnotek.Qualifications.cantidadPendientes--;
                        if(Tecnotek.Qualifications.cantidadPendientes == 0){
                            $("#pendientes").html(Tecnotek.Qualifications.cantidadPendientes + " Pendientes");
                            $("#pendientes").hide();
                            console.debug("No Quedan Pendientes!!!");
                        } else {
                            console.debug("Quedan pendientes: " + Tecnotek.Qualifications.cantidadPendientes);
                            $("#pendientes").html(Tecnotek.Qualifications.cantidadPendientes + " Pendientes");
                            $("#pendientes").show();
                        }
                    }
                });

                /*
                console.debug("Save value of " + $stdyId + " - " + $sq + ": " + $value);
                Tecnotek.ajaxCall(Tecnotek.UI.urls["saveStudentQualificationURL"],
                    {   stdyId: $stdyId,
                        sq:     $sq,
                        value:  $value,
                        pn:     $pn
                    },
                    function(data){
                        if(data.error === true) {

                        }
                    },
                    function(jqXHR, textStatus){
                        //Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                    }, false);*/
            }
        }
	};