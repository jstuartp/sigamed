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
	Tecnotek.init();
});
var Tecnotek = {
		module : "",
		imagesURL : "",
        assetsURL : "",
		isIe: false,
		rowsCounter: 0,
		companiesCounter: 0,
		ftpCounter: 0,
        updateFail: false,
		session : {},
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
            zIndex: 2e9 // The z-index (defaults to 2000000000)
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
        showWaiting: function() {
            $("body").addClass("loading");
        },
        hideWaiting: function() {
            $("body").removeClass("loading");
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
                case "penalties":
                    Tecnotek.AdministratorList.init();
                    Tecnotek.AdministratorList.initBtnSearch();
                    Tecnotek.Penalties.init();
                    break;
                case "extrapoints":
                    Tecnotek.AdministratorList.init();
                    Tecnotek.AdministratorList.initBtnSearch();
                    Tecnotek.Extrapoints.init();
                    break;
                case "absencesByGroup":
                    Tecnotek.AbsencesByGroup.init();
                    break;
                case "absences":
                    Tecnotek.AdministratorList.init();
                    Tecnotek.AdministratorList.initBtnSearch();
                    Tecnotek.Absences.init();
                    break;
                case "absencesTypes":
                    Tecnotek.AbsencesTypes.init();
                    break;
				case "administratorList":
                case "coordinadorList":
                case "profesorList":
                case "entityList":
                    Tecnotek.AdministratorList.init();
                    Tecnotek.AdministratorList.initBtnSearch(); break;
                case "studentList":
                    Tecnotek.AdministratorList.init();
                    Tecnotek.Students.init(); break;
                case "contactList":
                    Tecnotek.ContactList.init();
                    Tecnotek.Contacts.init(); break;
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
                case "showRoute":
                    Tecnotek.EntityShow.init();
                    Tecnotek.RouteShow.init();
                    break;
                case "showStudent":
                    Tecnotek.EntityShow.init();
                    Tecnotek.StudentShow.init();
                    break;
                case "adminPeriod":
                    Tecnotek.AdminPeriod.init();
                    break;
                    case "curriculumList":
                    console.log("Calling innit of curriculum");
                    Tecnotek.Curriculum.init();
                    break;
                case "ticketsIndex":
                    Tecnotek.Tickets.init();
                    break;
                case "ticketsSearch":
                    Tecnotek.TicketsSearch.init();
                    break;
                case "reports":
                    Tecnotek.Reports.init();
                    break;
                case "reportClubs":
                    Tecnotek.Reports.init();
                    Tecnotek.ReportClubs.init();
                    break;
                case "permisosUsuarios":
                    Tecnotek.PermisosUsuarios.init();
                    break;
                case "qualifications":
                    Tecnotek.Qualifications.init();
                    break;
                case "printQualifications":
                    Tecnotek.PrintQualifications.init();
                    break;
                case "periodGroupQualifications":
                    Tecnotek.PeriodGroupQualifications.init();
                    break;
                case "PeriodGroupObservations":
                    Tecnotek.PeriodGroupObservations.init();
                    break;
                case "groupCourseQualifications":
                    Tecnotek.GroupCourseQualifications.init();
                    break;
                case "enterConvocatorias":
                    Tecnotek.EnterConvocatorias.init();
                    break;
                case "periodGroupAverages":
                    Tecnotek.PeriodGroupAverages.init();
                    break;
                case "periodGroupAbsences":
                    Tecnotek.PeriodGroupAbsences.init();
                    break;
                case "groupQualificationsByRubro":
                    Tecnotek.GroupQualificationsByRubro.init();
                    break;
                case "studentQualificationsDetail":
                    Tecnotek.StudentQualifications.init();
                    break;
                case "studentPenaltiesReport":
                    Tecnotek.studentPenalties.init();
                    break;
                case "psicoProfile":
                    Tecnotek.psicoProfile.init();
                    break;
                case "emails":
                    Tecnotek.Emails.init();
                    break;
                case "questionnairesList":
                    Tecnotek.Questionnaires.init();
                    break;
                case "psicoLog":
                    Tecnotek.Visits.init();
                    break;
                case "programList":
                    Tecnotek.ProgramList.init();
                    Tecnotek.Programs.init(); break;
                case "programHistoric":
                    //Tecnotek.ProgramList.init();
                    Tecnotek.ProgramsH.init(); break;
                case "programs":
                    Tecnotek.Program.init(); break;
                case "programEdit":
                    Tecnotek.programEdit.init(); break;
                    break;
                case "courseList":
                    Tecnotek.Courses.init();
                    break;
                case "recordList":
                    Tecnotek.Record.init();
                    break;
                case "ticketList":
                    Tecnotek.Ticket.init();
                    break;
                case "itemList":
                    Tecnotek.Items.init();
                    break;
                case "categoryList":
                    Tecnotek.Category.init();
                    break;
                case "chargeList":
                    Tecnotek.Charges.init();
                    break;
                case "commissionList":
                    Tecnotek.Commissions.init();
                    break;
                    //Tecnotek.AdministratorList.initBtnSearch(); break;
                default:
					break;
				}
			}
			Tecnotek.UI.init();

		},
        uniqueAjaxCall : function(url, params, succedFunction, errorFunction, showSpinner, requestId) {
            Tecnotek.UI.vars['request-'+requestId] = $.ajax({
                url: url,
                type: "POST",
                data: params,
                dataType: "json",
                beforeSend: function() {
                    if (Tecnotek.UI.vars['request-'+requestId] != null) {
                        Tecnotek.UI.vars['request-'+requestId].abort();
                    }
                }
            });

            Tecnotek.UI.vars['request-'+requestId].done(succedFunction);

            Tecnotek.UI.vars['request-'+requestId].fail(errorFunction);
        },
        ajaxCall : function(url, params, succedFunction, errorFunction, showSpinner) {
            var request = $.ajax({
                url: url,
                type: "POST",
                data: params,
                dataType: "json"
            });

            request.done(succedFunction);

            request.fail(errorFunction);
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
			},
            printPagination: function($total, $filtered, $currentPage, $recordsPerPage, $containerId) {
                $total = $total * 1;
                $filtered = $filtered * 1;
                $currentPage = $currentPage * 1;
                $recordsPerPage = $recordsPerPage * 1;
                if ($filtered == 0) {
                    var html = '<div class="empty-results">No se han encontrado resultados</div>';
                    $("#" + $containerId).html(html);
                    return;
                }
                var $totalPages = Math.ceil($filtered / $recordsPerPage);
                var $previousPage = $currentPage - 1;
                var $nextPage = $currentPage + 1;
                /*console.debug("$totalPages: " + $totalPages + ", $previousPage: " + $previousPage + ", $nextPage: " + $nextPage
                    + ", $currentPage: " + $currentPage, ", $filtered: " + $filtered );*/
                var html = '<div class="pagination"><div class="left">';
                if ($previousPage > 0) {
                    html += '<span class="first paginationButton" page="1">&lt;&lt;</span>';
                    html += '<span class="previous paginationButton" page="' + $previousPage + '">&lt;</span>';
                }
                var $totalButtons = 4;
                var $totalPreviousButtons = 2;
                for( var i = $totalPreviousButtons; i > 0; i--) {
                    var $page = $currentPage-i;
                    if ($page > 0) {
                        html += '<span class="page paginationButton" page="' + $page + '">' + $page + '</span>';
                        $totalButtons--;
                    }
                }
                html += '<span class="current">' + $currentPage + '</span>';
                for( var i = $totalButtons; i > 0; i--) {
                    var $page = $currentPage+($totalButtons - i + 1);
                    if ($page <= $totalPages) {
                        html += '<span class="page paginationButton" page="' + $page + '">' + $page + '</span>';
                    }
                }
                if ($nextPage <= $totalPages) {
                    html += '<span class="next paginationButton" page="' + $nextPage + '">&gt;</span>';
                    html += '<span class="last paginationButton" page="' + $totalPages + '">&gt;&gt;</span>';
                }
                html += '</div>';
                if ($filtered == $total) {
                    html += '<div class="right pagination-msg">Total de registros: ' + $total + '</div>';
                } else {
                    html += '<div class="right pagination-msg">' + $filtered + ' filtrados de un total de ' + $total + '</div>';
                }
                html += '</div>';
                html += '<div class="clear"></div>';
                $("#" + $containerId).html(html);
            }
		},
        Reports : {
            init : function() {
                Tecnotek.Reports.initComponents();
                Tecnotek.Reports.initButtons();
            },
            initComponents : function() {
                $('.check').change(function(event){
                    $("#" + $(this).attr("rel")).val("");
                    if($(this).is(':checked')){
                        $("#" + $(this).attr("rel")).attr("disabled",false);
                    } else {
                        $("#" + $(this).attr("rel")).attr("disabled",true);
                    }
                });
            },
            initButtons : function() {
                $('#btnEliminar').click(function(event){
                    if (Tecnotek.showConfirmationQuestion(Tecnotek.UI.translates["confirmDelete"])){
                        location.href = Tecnotek.UI.urls["deleteURL"];
                    }
                });
                $('#btnPrint').click(function(event){
                    $("#reportAcademic").printElement({printMode:'popup', pageTitle:$(this).attr('rel')});
                });

                $('#btnSearch').click(function(event){
                    event.preventDefault();
                    var url = location.href;
                    var text = $("#searchText").val();
                    if( url.indexOf("?") > -1 ){
                        if( url.indexOf("text=") > -1 ){
                            url += "&q=1";
                            url = url.replace(/(text=).*?(&)/,'$1' + text + '$2');
                            url = url.replace("&q=1","");
                        } else {
                            url += "&text=" + text;
                        }
                    } else {
                        url += "?text=" + text;
                    }
                    window.location.href= url;
                });

                $('#btnSearchLevel').click(function(event){
                    event.preventDefault();
                    var url = location.href;
                    var text = $("#searchText").val();
                    var day = $("#day").val();
                    if( url.indexOf("?") > -1 ){
                        if( url.indexOf("day=") > -1 ){
                            url += "&q=1";
                            url = url.replace(/(day=).*?(&)/,'$1' + day + '$2');
                            url = url.replace("&q=1","");
                        } else {
                            url += "&day=" + day + "&text=" + text;
                        }
                    } else {
                        url += "?day=" + day + "&text=" + text;
                    }
                    window.location.href= url;
                });

                $('#btnSearchRoute').click(function(event){
                    event.preventDefault();
                    var url = location.href;
                    var text = $("#searchText").val();
                    var day = $("#day").val();
                    if( url.indexOf("?") > -1 ){
                        if( url.indexOf("day=") > -1 ){
                            url += "&q=1";
                            url = url.replace(/(day=).*?(&)/,'$1' + day + '$2');
                            url = url.replace("&q=1","");
                        } else {
                            url += "&day=" + day + "&text=" + text;
                        }
                    } else {
                        url += "?day=" + day + "&text=" + text;
                    }
                    window.location.href= url;
                });

                $('#btnPrintTransporte').click(function(event){
                    $("#report").printElement({printMode:'popup', pageTitle:$(this).attr('rel')});
                });

                $('#btnSearchTransporte').click(function(event){
                    event.preventDefault();
                    var url = location.href;
                    var text = $("#searchText").val();
                    var text2 = $("#searchText2").val();
                    if( url.indexOf("?") > -1 ){
                        if(( url.indexOf("text=") > -1 )&&(url.indexOf("text2=") > -1)){
                            url += "&q=1";
                            url = url.replace(/(text=).*?(&)/,'$1' + text + '$2');
                            url = url.replace(/(text2=).*?(&)/,'$1' + text2 + '$2');
                            url = url.replace("&q=1","");
                        } else {
                            url += "&text=" + text +"&text2=" + text2;
                        }
                    } else {
                        url += "?text=" + text+"&text2=" + text2;
                    }
                    window.location.href= url;
                });
                $("#routesList").change(function(event){
                    event.preventDefault();
                    $('#subentryFormParent').empty();
                    Tecnotek.Reports.loadRoute($(this).val());
                });
                $("#routesListClub").change(function(event){
                    event.preventDefault();
                    $('#subentryFormParent').empty();
                    Tecnotek.Reports.loadRouteClub($(this).val());
                });
                $("#institution").change(function(event){
                    event.preventDefault();
                    $('#subentryFormParent').empty();
                    Tecnotek.Reports.loadGroupInstitution($(this).val());
                });
                $("#grade").change(function(event){
                    event.preventDefault();
                    $('#subentryFormParent').empty();
                    Tecnotek.Reports.loadGroupGrade($(this).val());
                });

                $("#groupSelect").change(function(event){
                    event.preventDefault();
                    $('#subentryFormParent').empty();
                    Tecnotek.Reports.loadGroupGroup($(this).val());
                });

                $("#teachers").change(function(event){
                    event.preventDefault();
                    $('#subentryFormParent').empty();
                    Tecnotek.Reports.loadChargesTeacher($(this).val());
                });

            },
            loadRoute : function($routeId) {
                if(($routeId!==null && $routeId!=='0')){
                    //$('#report').empty();


                    $('.routeContainer').hide();
                    $('.route_' + $routeId).show();
                }else {
                    $('.routeContainer').show();
                }
            },
            loadRouteClub : function($routeId) {
                if(($routeId!==null && $routeId!=='0')){

                    $('.routeContainerClub').hide();
                    $('.route_' + $routeId).show();
                }else {
                    $('.routeContainerClub').show();
                }
            },
            loadChargesTeacher : function($routeId) {
                if(($routeId!==null && $routeId!=='0')){

                    $('.listContainerTeacher').hide();
                    $('.charge_' + $routeId).show();
                }else {
                    $('.listContainerTeacher').show();
                }
            },
            loadGroupInstitution : function($institutionId) {
                if(($institutionId!==null && $institutionId!=='0')){
                    //$('#report').empty();
                    $('.groupContainer').hide();
                    $('.inst_' + $institutionId).show();
                    //alert('.inst_' + $institutionId);
                }else {
                    $('.groupContainer').show();
                }
            },

            loadGroupGrade : function($gradeId) {
                if(($gradeId!==null && $gradeId!=='0')){

                    $('.groupContainer').hide();
                    $('.niv_' + $gradeId).show();
                }else {
                    $('.groupContainer').show();
                }
            },

            loadGroupGroup : function($groupId) {
                if(($groupId!==null && $groupId!=='0')){

                    $('.groupContainer').hide();
                    $('.groupS_' + $groupId).show();
                }else {
                    $('.groupContainer').show();
                }
            }
        },
        ReportClubs : {
            init : function() {
                Tecnotek.ReportClubs.initComponents();
                Tecnotek.ReportClubs.initButtons();

                $("#ListClub").change(function(event){
                    event.preventDefault();
                    $('#subentryFormParent').empty();
                    Tecnotek.ReportClubs.loadListClub($(this).val());
                });

                $('#generateExcelBtn').click(function(e){
                    var periodId = 5;

                    if (periodId != null){
                        $(this).attr("href",Tecnotek.UI.urls["generateGroupExcel"] + "?periodId="+periodId);
                    } else {
                        e.preventDefault();
                        Tecnotek.showInfoMessage("Necesita seleccionar el grupo y la materia antes de generar el archivo", true, '', false);
                    }
                });
            },
            initComponents : function() {
                $('#withStudents').change(function(event){
                    if($(this).is(':checked')){
                        $("#details").show();
                    } else {
                        $("#details").hide();
                    }
                });
            },
            initButtons : function() {
            },
            loadListClub : function($routeId) {

                if(($routeId!==null && $routeId!=='0')){

                    $('.ContainerClub').hide();
                    $('.club_' + $routeId).show();
                }else {
                    $('.ContainerClub').show();
                }
            }
        },
		AdministratorList : {
			init : function() {
				Tecnotek.AdministratorList.initComponents();
				Tecnotek.AdministratorList.initButtons();
			},
			initComponents : function() {
			},
            initBtnSearch: function() {
                $('#btnSearch').unbind().click(function(event){
                    event.preventDefault();
                    var url = location.href;
                    var text = $("#searchText").val();
                    if( url.indexOf("?") > -1 ){
                        if( url.indexOf("text=") > -1 ){
                            url += "&q=1";
                            url = url.replace(/(text=).*?(&)/,'$1' + text + '$2');
                            url = url.replace("&q=1","");
                        } else {
                            url += "&text=" + text;
                        }
                    } else {
                        url += "?text=" + text;
                    }
                    window.location.href= url;
                });
            },
			initButtons : function() {
                console.debug("AdministratorList :: initButtons");
                $('.editButton').unbind().click(function(event){
                    event.preventDefault();
                    console.debug("AdministratorList :: initButtons :: editButton Event");
                    location.href = Tecnotek.UI.urls["edit"] + "/" + $(this).attr("rel");
                });
                $('.viewButton').unbind().click(function(event){
                    event.preventDefault();
                    location.href = Tecnotek.UI.urls["show"] + "/" + $(this).attr("rel");
				});
                $('.adminButton').unbind().click(function(event){
                    event.preventDefault();
                    location.href = Tecnotek.UI.urls["admin"] + "/" + $(this).attr("rel");
                });
                $('.psicoButton').unbind().click(function(event){
                    event.preventDefault();
                    location.href = Tecnotek.UI.urls["psico"] + "/" + $(this).attr("rel");
                });
                $('.religionButton').unbind().click(function(event){
                    event.preventDefault();
                    location.href = Tecnotek.UI.urls["religion"] + "/" + $(this).attr("rel");
                });
                $('.emergencyButton').unbind().click(function(event){
                    event.preventDefault();
                    location.href = Tecnotek.UI.urls["emergency"] + "/" + $(this).attr("rel");
                });

                $('.questionsButton').unbind();
                $('.questionsButton').click(function(event){
                    event.preventDefault();

                    var periodId = $(this).attr("rel");
                    Tecnotek.UI.vars["idPeriodEdit"]  = periodId;
                    Tecnotek.ajaxCall(Tecnotek.UI.urls["getInfoPeriodURL"],
                        {periodId: $(this).attr("rel")},
                        function(data){
                            if(data.error === true) {
                                Tecnotek.showErrorMessage(data.message,true, "", false);
                            } else {
                                //Tecnotek.showInfoMessage(data.html, true, "", false);

                                $("#idPeriodEdit").val(data.id);
                                $("#namePeriodEdit").val(data.name);
                                $("#yearPeriodEdit").val(data.code);
                                $("#orderInYearPeriodEdit").val(data.requisit);
                                $("#corequisiteCourseEdit").val(data.corequisite);
                                $("#creditCourseEdit").val(data.credit);

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


                $(".btnPrint").click(function(e){
                    e.preventDefault();
                    $("#" + $(this).attr("rel")).printElement({printMode:'popup', pageTitle:""});

                });

                $('.logButton').unbind().click(function(event){
                    event.preventDefault();
                    location.href = Tecnotek.UI.urls["log"] + "/" + $(this).attr("rel");
                });


                $("#openPeriodForm").fancybox({
                    'beforeLoad' : function(){

                    }
                });

                $("#openPeriodEditForm").fancybox({
                    'beforeLoad' : function(){

                    }
                });

                $("#openUserCreate").fancybox({
                    'afterLoad' : function(){

                    }
                });

                $('#userForm').submit(function(event){
                    event.preventDefault();
                    Tecnotek.AdministratorList.createUser();
                });


                $('#periodForm').submit(function(event){
                    event.preventDefault();
                    Tecnotek.AdministratorList.createPeriod();
                });

                $('#periodFormEdit').submit(function(event){
                    event.preventDefault();
                    Tecnotek.AdministratorList.updatePeriod();
                });

			},
			submit : function() {
				//$("#frmCreateAccount").submit();
			},
            createPeriod: function() {

                Tecnotek.ajaxCall(Tecnotek.UI.urls["createPeriodURL"],
                    {   name: $('#namePeriod').val(),
                        order: $('#orderInYearPeriod').val(),
                        year: $('#yearPeriod').val()
                    },
                    function(data){
                        if(data.error === true) {
                            Tecnotek.showErrorMessage(data.message,true, "", false);
                        } else {
                            $.fancybox.close();
                            //Tecnotek.Period.searchPeriods();
                        }
                    },
                    function(jqXHR, textStatus){
                        Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                        $(this).val("");
                    }, true);
            },
            updatePeriod: function() {
                Tecnotek.ajaxCall(Tecnotek.UI.urls["updatePeriodURL"],
                    {   name: $('#namePeriodEdit').val(),
                        order: $('#orderInYearPeriodEdit').val(),
                        year: $('#yearPeriodEdit').val(),
                        actual: $('#actualPeriodEdit').val(),
                        editable: $('#editablePeriodEdit').val()
                    },
                    function(data){
                        if(data.error === true) {
                            Tecnotek.showErrorMessage(data.message,true, "", false);
                        } else {
                            $.fancybox.close();
                            //Tecnotek.Period.searchPeriods();
                        }
                    },
                    function(jqXHR, textStatus){
                        Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                        $(this).val("");
                    }, true);
            },
            createUser: function() {
                //alert("entra pero no sale");
                Tecnotek.ajaxCall(Tecnotek.UI.urls["createUserURL"],
                    {   firstname: $('#firstnameUser').val(),
                        lastname: $('#lastnameUser').val(),
                        username: $('#usernameUser').val(),
                        email: $('#emailUser').val()
                    },
                    function(data){
                        if(data.error === true) {
                            Tecnotek.showErrorMessage(data.message,true, "", false);
                        } else {
                            $.fancybox.close();
                            //Tecnotek.AdministratorList.searchUsers();
                        }
                    },
                    function(jqXHR, textStatus){
                        Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                        $(this).val("");
                    }, true);
            }
		},
        ContactList : {
            init : function() {
                Tecnotek.ContactList.initComponents();
                Tecnotek.ContactList.initButtons();
            },
            initComponents : function() {
            },
            initBtnSearch: function() {
                $('#btnSearch').unbind().click(function(event){
                    event.preventDefault();
                    var url = location.href;
                    var text = $("#searchText").val();
                    if( url.indexOf("?") > -1 ){
                        if( url.indexOf("text=") > -1 ){
                            url += "&q=1";
                            url = url.replace(/(text=).*?(&)/,'$1' + text + '$2');
                            url = url.replace("&q=1","");
                        } else {
                            url += "&text=" + text;
                        }
                    } else {
                        url += "?text=" + text;
                    }
                    window.location.href= url;
                });
            },
            initButtons : function() {
                console.debug("AdministratorList :: initButtons");
                $('.editButton').unbind().click(function(event){
                    event.preventDefault();
                    console.debug("AdministratorList :: initButtons :: editButton Event");
                    location.href = Tecnotek.UI.urls["edit"] + "/" + $(this).attr("rel");
                });

                $('.adminButton').unbind().click(function(event){
                    event.preventDefault();
                    location.href = Tecnotek.UI.urls["admin"] + "/" + $(this).attr("rel");
                });

                $("#openContactyForm").fancybox({
                    'afterLoad' : function(){

                    }
                });

                $('.viewButton').unbind();
                $('.viewButton').click(function(event){
                    console.debug("Click en view button: " + Tecnotek.UI.urls["getInfoRelativesFullURL"]);
                    event.preventDefault();
                    //Tecnotek.UI.vars["relativeId"] = $(this).attr("rel");
                    Tecnotek.ajaxCall(Tecnotek.UI.urls["getInfoRelativesFullURL"],
                        {contactId: $(this).attr("rel")},
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
            submit : function() {
                //$("#frmCreateAccount").submit();
            }
        },
        ProgramList : {
            init : function() {
                Tecnotek.ProgramList.initComponents();
                Tecnotek.ProgramList.initButtons();

                $('#programEditForm').submit(function(event){
                    event.preventDefault();
                    Tecnotek.Courses.updateProgram();
                });
            },
            initComponents : function() {
            },
            initBtnSearch: function() {
                $('#btnSearch').unbind().click(function(event){
                    event.preventDefault();
                    var url = location.href;
                    var text = $("#searchText").val();
                    if( url.indexOf("?") > -1 ){
                        if( url.indexOf("text=") > -1 ){
                            url += "&q=1";
                            url = url.replace(/(text=).*?(&)/,'$1' + text + '$2');
                            url = url.replace("&q=1","");
                        } else {
                            url += "&text=" + text;
                        }
                    } else {
                        url += "?text=" + text;
                    }
                    window.location.href= url;
                });
            },
            initButtons : function() {
                console.debug("AdministratorList :: initButtons");
                /*$('.editButton').unbind().click(function(event){
                    event.preventDefault();
                    console.debug("AdministratorList :: initButtons :: editButton Event");
                    location.href = Tecnotek.UI.urls["edit"] + "/" + $(this).attr("rel");
                });*/


                $('.questionsButton').unbind().click(function(event){
                    event.preventDefault();
                    location.href = Tecnotek.UI.urls["programQ"] + $(this).attr("rel");
                    //location.href = Tecnotek.UI.urls["programQ"] + "/" + $(this).attr("rel");
                });

                $('.nopdfButton').click(function(event){
                    event.preventDefault();
                    alert("El programa aun no se ha finalizado");
                });

/*
                $('.viewButton').unbind();
                $('.viewButton').click(function(event){
                    alert("jodase");
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

                $('.adminButton').unbind().click(function(event){
                    event.preventDefault();
                    location.href = Tecnotek.UI.urls["admin"] + "/" + $(this).attr("rel");
                });

                $('.questionsButton').unbind().click(function(event){
                    event.preventDefault();
                    location.href = Tecnotek.UI.urls["programQ"] + "/" + $(this).attr("rel");
                });

                $('.checkButton').unbind().click(function(event){
                    event.preventDefault();
                    location.href = Tecnotek.UI.urls["programQCheck"] + "/" + $(this).attr("rel");
                });

                $('.pdfButton').click(function(event){
                    event.preventDefault();
                    //Tecnotek.ProgramList.pdfcheckForm($(this).attr('rel'));
                    window.location = '/ucr/web/images/programas/'+$(this).attr("rel")+'.pdf';
                });

                $('.nopdfButton').click(function(event){
                    event.preventDefault();
                    alert("El programa aun no se ha finalizado");
                });

                $("#openProgramForm").fancybox({
                    'afterLoad' : function(){

                    }
                });

                $("#openProgramEdit").fancybox({
                    'afterLoad' : function(){

                    }
                });

                $('.editButton').unbind();
                $('.editButton').click(function(event){
                    event.preventDefault();

                    var programId = $(this).attr("rel");
                    Tecnotek.UI.vars["idProgram"]  = programId;
                    Tecnotek.ajaxCall(Tecnotek.UI.urls["getInfoProgramURL"],
                        {programId: $(this).attr("rel")},
                        function(data){
                            if(data.error === true) {
                                Tecnotek.showErrorMessage(data.message,true, "", false);
                            } else {
                                //Tecnotek.showInfoMessage(data.html, true, "", false);

                                $("#idProgram").val(data.id);
                                $("#detailProgram").val(data.detail);
                                $("#dateProgram").val(data.date);

                                //$("#teacherCourse").val(data.user);

                                $("#programFormContainer").html(data.html);
                                $("#openProgramEdit").trigger("click");
                                //alert("llega");
                            }
                        },
                        function(jqXHR, textStatus){
                            Tecnotek.showErrorMessage("Error in request: " + textStatus + ".",
                                true, "", false);
                        }, true);

                });*/




            },

            pdfcheckForm: function($id) {
                //var form = $("#" + $formName);
                //alert($("#programId").val());
                Tecnotek.ajaxCall(Tecnotek.UI.urls["pdfcheckProgramFormUrl"],
                    {programId: $id,
                        userId: $("#userId").val()},
                    function(data){
                        if(data.error === true) {
                            Tecnotek.showErrorMessage(data.message,true, "", false);
                        } else {
                            if(data)
                                alert("Se ha encontrado el archivo.");
                            else
                                alert("No se ha encontrado el archivo.");
                        }
                    },
                    function(jqXHR, textStatus){
                        Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                        $(this).val("");
                    }, true);
            },


            updateProgram: function() {
                Tecnotek.ajaxCall(Tecnotek.UI.urls["updateProgramURL"],
                    {   courseId: $('#idProgram').val(),
                        name: $('#detailProgram').val(),
                        code: $('#dateProgram').val()
                    },
                    function(data){
                        if(data.error === true) {
                            Tecnotek.showErrorMessage(data.message,true, "", false);
                        } else {
                            $.fancybox.close();
                            Tecnotek.Programs.searchPrograms();
                        }
                    },
                    function(jqXHR, textStatus){
                        Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                        $(this).val("");
                    }, true);
            },

            submit : function() {
            }
        },

		AdministratorShow : {
			init : function() {
				Tecnotek.AdministratorShow.initComponents();
				Tecnotek.AdministratorShow.initButtons();
			},
			initComponents : function() {
			},
			initButtons : function() {
				$('#btnEditar').click(function(event){
                    $("#firstname").val($("#labelFirstname").html());
                    $("#lastname").val($("#labelLastname").html());
                    $("#username").val($("#labelUsername").html());
                    $("#email").val($("#labelEmail").html());
                    if($("#labelActive").html() == "Activo") {
                        $("#active").attr('checked', true);
                    } else {
                        $("#active").attr('checked', false);
                    }
                    $("#showContainer").hide();
                    $("#editContainer").fadeIn('slow', function() {});
				});
                $('#btnCancelEdit').click(function(event){
                    $("#editContainer").hide();
                    $("#showContainer").fadeIn('slow', function() {});
                });
                $('#btnCambiarPass').click(function(event){
                    $("#buttonsContainer").hide();
                    $("#changePasswordContainer").fadeIn('slow', function() {});
				});
                $('#btnEliminar').click(function(event){
                    if (Tecnotek.showConfirmationQuestion(Tecnotek.UI.translates["confirmDelete"])){
                        location.href = Tecnotek.UI.urls["deleteAdminURL"];
                    }
				});
                $('#btnCancelarPassword').click(function(event){
                    $("#changePasswordContainer").hide();
                    $("#buttonsContainer").fadeIn('slow', function() {});
                });
                $('#btnActualizarPassword').click(function(event){
                    Tecnotek.ajaxCall(Tecnotek.UI.urls["changePasswordURL"],
                        {newPassword: $("#newPassword").val(), confirmPassword: $("#confirmPassword").val(),
                        userId: $("#userId").val()},
                        function(data){
                            if(data.error === true) {
                                Tecnotek.showErrorMessage(data.message,true, "", false);
                            } else {
                                $("#changePasswordContainer").hide();
                                $("#buttonsContainer").fadeIn('slow', function() {});
                                Tecnotek.showInfoMessage(data.message,true, "", false);
                            }
                        },
                        function(jqXHR, textStatus){
                            Tecnotek.showErrorMessage("Error updating password: " + textStatus + ".",
                                true, "", false);
                        }, true);
				});


			},
			submit : function() {
				//$("#frmCreateAccount").submit();
			}
		},
        EntityShow : {
            init : function() {
                Tecnotek.EntityShow.initComponents();
                Tecnotek.EntityShow.initButtons();
            },
            initComponents : function() {
            },
            initButtons : function() {
                $('#btnEliminar').click(function(event){
                    if (Tecnotek.showConfirmationQuestion(Tecnotek.UI.translates["confirmDelete"])){
                        location.href = Tecnotek.UI.urls["deleteURL"];
                    }
                });
            }
        },
        AbsencesByGroup : {
            init : function() {
                $( "#date" ).datepicker({
                    defaultDate: "0d",
                    changeMonth: true,
                    dateFormat: "yy-mm-dd",
                    showButtonPanel: true,
                    currentText: "Hoy",
                    numberOfMonths: 1,
                    onClose: function( selectedDate ) {
                        $("#absencesDate").val($("#date").val());
                    }
                });
                $("#date").datepicker('setDate', new Date()).keypress(function(event){event.preventDefault();});
                $("#absencesDate").val($("#date").val());
                $('#period').change(function(event){
                    event.preventDefault();
                    Tecnotek.AbsencesByGroup.loadGroupsOfPeriod();
                });
                $('#groups').change(function(event){
                    event.preventDefault();
                    Tecnotek.AbsencesByGroup.loadStudentsOfGroup();
                });

                Tecnotek.AbsencesByGroup.loadGroupsOfPeriod();

            },
            setRowsComponentsAction: function(){
                $(".cbRow").unbind().change(function(event){
                    event.preventDefault();
                    var $id = $(this).attr("rel");
                    var $str = $("#studentsIds").val();
                    if($(this).is(":checked") ) {
                        $("#justify_" + $id).removeAttr("disabled");
                        $("#number_" + $id).removeAttr("disabled");
                        $("#comments_" + $id).removeAttr("disabled");
                        $("#type_" + $id).focus().removeAttr("disabled");
                        $str += " " + $id; //Add the id
                    } else {
                        $("#type_" + $id).attr("disabled", true);
                        $("#justify_" + $id).attr("disabled", true);
                        $("#number_" + $id).attr("disabled", true);
                        $("#comments_" + $id).attr("disabled", true);
                        //studentsIds //Remove the id
                        var Re = new RegExp(" " + $id,"g");
                        $str = $str.replace(Re, "");
                    }
                    $("#studentsIds").val($str);
                });

                $(".commentsArea").unbind().focus(function(event){
                    $("#stdRow_" + $(this).attr("rel")).css("line-height", "60px").css("height", "60px");
                    $("#comments_" + $(this).attr("rel")).attr("rows", 3).animate({'height': '50px'}, 'slow' );
                }).blur(function(event){
                    $("#comments_" + $(this).attr("rel")).attr("rows", 1).animate({'height': '18px'}, 'slow', function() {
                        $("#stdRow_" + $(this).attr("rel")).css("line-height", "30px").css("height", "30px");
                    });

                });
            },
            loadGroupsOfPeriod : function(){
                $period = $("#period").val();
                if($period !== undefined && $period !== "undefined" && $period !== null) {
                    $('#groups').children().remove();
                    $('#studentsRows').empty();
                    Tecnotek.ajaxCall(Tecnotek.UI.urls["loadGroupsOfPeriodURL"],
                        {   periodId: $period },
                        function(data){
                            if(data.error === true) {
                                Tecnotek.showErrorMessage(data.message,true, "", false);
                            } else {
                                for(i=0; i<data.groups.length; i++) {
                                    $('#groups').append('<option value="' + data.groups[i].id + '">' + data.groups[i].name + ' - ' + data.groups[i].name_group +  '</option>');
                                }
                                Tecnotek.AbsencesByGroup.loadStudentsOfGroup();
                            }
                        },
                        function(jqXHR, textStatus){
                            Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                            $(this).val("");
                        }, true);
                }
            },
            loadStudentsOfGroup : function(){
                $group = $("#groups").val();
                if($group !== undefined && $group !== "undefined" && $group !== null) {
                    $('#studentsRows').empty();
                    Tecnotek.ajaxCall(Tecnotek.UI.urls["getStudentsURL"],
                        {   groupId: $group,
                            searchType: 1},
                        function(data){
                            if(data.error === true) {
                                Tecnotek.showErrorMessage(data.message,true, "", false);
                            } else {
                                var html = "";
                                var Re = new RegExp("STDYID","g");
                                for(i=0; i<data.students.length; i++) {
                                    html = $("#stdRow_STDYID").clone().attr("id","stdRow_" + data.students[i].id).attr("name","stdRow_" + data.students[i].id).css("display", "block").wrap('<p>').parent().html();
                                    html = html.replace(Re, data.students[i].id).replace("STDNAME", data.students[i].name);
                                    $('#studentsRows').append(html);
                                }
                                Tecnotek.AbsencesByGroup.setRowsComponentsAction();
                            }
                        },
                        function(jqXHR, textStatus){
                            Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                            $(this).val("");
                        }, true);
                }
            }
        },
        Penalties : {
            init : function() {
                $( "#from" ).datepicker({
                    defaultDate: "0d",
                    changeMonth: true,
                    dateFormat: "yy-mm-dd",
                    showButtonPanel: true,
                    currentText: "Hoy",
                    numberOfMonths: 1,
                    onClose: function( selectedDate ) {
                        $( "#to" ).datepicker( "option", "minDate", selectedDate );
                    }
                });
                $( "#to" ).datepicker({
                    defaultDate: "0d",
                    changeMonth: true,
                    dateFormat: "yy-mm-dd",
                    showButtonPanel: true,
                    currentText: "Hoy",
                    numberOfMonths: 1,
                    onClose: function( selectedDate ) {
                        $( "#from" ).datepicker( "option", "maxDate", selectedDate );
                    }
                });
                $( "#date" ).datepicker({
                    defaultDate: "0d",
                    changeMonth: true,
                    dateFormat: "yy-mm-dd",
                    showButtonPanel: true,
                    currentText: "Hoy",
                    numberOfMonths: 1
                });

                $("#date").keypress(function(event){event.preventDefault();});
                $("#from").keypress(function(event){event.preventDefault();});
                $("#to").keypress(function(event){event.preventDefault();});

                $("#searchByStudent").change(function(){
                    $this = $(this);
                    if($this.is(':checked')){
                        $("#" + $this.attr("rel")).removeAttr("disabled");
                    } else {

                        $("#" + $this.attr("rel")).val("").attr("disabled",true);
                    }
                });

                $('#createPenaltyForm').submit(function(event){
                    event.preventDefault();
                    Tecnotek.Penalties.save();
                });

                $('.cancelButton').click(function(event){
                    $.fancybox.close();
                });

                //Aca estaba eso
                Tecnotek.initRelativesSearch();

                //TODO Penalties
                $(".deleteButton").click(function(event){
                    event.preventDefault();
                    Tecnotek.Penalties.delete($(this).attr("rel"));
                });
            },
            delete : function(penaltyId){
                //TODO delete Penalty
                if (Tecnotek.showConfirmationQuestion(Tecnotek.UI.translates["confirmDelete"])){
                    location.href = Tecnotek.UI.urls["deleteURL"] + "/" + penaltyId;
                }
            },
            save : function(){
                if(Tecnotek.UI.vars["currentPeriod"] == 0){
                    Tecnotek.showErrorMessage("Es necesario definir un periodo como actual antes de guardar.",true, "", false);
                    return;
                }


                var $maxP = 0;
                var $minP = 0;
                var $pointsPenalty = 0;

                var $studentId = $("#studentId").val();
                var $date = $("#date").val();
                var $type = $("#penaltyType").val();
                var $comments = $("#comments").val();
                var $pointsPenalty = $("#pointsPenalty").val();
                $maxP = $('option:selected', '#penaltyType').attr('maxPenalty');
                $minP = $('option:selected', '#penaltyType').attr('minPenalty');

                //window.alert();
                if($comments === ""){
                    Tecnotek.showErrorMessage("Debe incluir un comentario.",
                        true, "", false);
                } else if(($pointsPenalty*1 < $minP*1) || ($pointsPenalty*1 > $maxP*1)){
                    Tecnotek.showErrorMessage("Los puntos para esta sancion esta fuera del rango.",
                        true, "", false);

                } else{
                    Tecnotek.ajaxCall(Tecnotek.UI.urls["savePenaltyURL"],
                        {studentId: $studentId,
                            date: $date,
                            type: $type,
                            comments: $comments,
                            pointsPenalty: $pointsPenalty,
                            periodId: Tecnotek.UI.vars["currentPeriod"]
                        },
                        function(data){
                            if(data.error === true) {
                                Tecnotek.showErrorMessage(data.message,true, "", false);
                            } else {
                                $.fancybox.close();
                                Tecnotek.showInfoMessage("La sancion se ha ingresado correctamente.", true, "", false)
                            }
                        },
                        function(jqXHR, textStatus){
                            Tecnotek.showErrorMessage("Error saving penalty: " + textStatus + ".",
                                true, "", false);
                        }, true);
                }

            }
        },
        Extrapoints : {
            init : function() {

                $("#period").change(function(event){
                    event.preventDefault();
                    Tecnotek.Extrapoints.loadExtrapoints($(this).val());
                });

                $("#searchByStudent").change(function(){
                    $this = $(this);
                    if($this.is(':checked')){
                        $("#" + $this.attr("rel")).removeAttr("disabled");
                    } else {

                        $("#" + $this.attr("rel")).val("").attr("disabled",true);
                    }
                });

                $('#createExtrapointForm').submit(function(event){
                    event.preventDefault();
                    Tecnotek.Extrapoints.save();
                });

                $('.cancelButton').click(function(event){
                    $.fancybox.close();
                });

                $("#newExtraPoint").fancybox({
                    'afterLoad' : function(){
                        Tecnotek.Extrapoints.loadCourses();
                    }
                });

                //Aca estaba eso
                Tecnotek.initStudentsSearch();

                //TODO Penalties
                $(".deleteButton").click(function(event){
                    event.preventDefault();
                    Tecnotek.Extrapoints.delete($(this).attr("rel"));
                });
            },
            delete : function(extrapId){
                //TODO delete Penalty
                if (Tecnotek.showConfirmationQuestion(Tecnotek.UI.translates["confirmDelete"])){
                    location.href = Tecnotek.UI.urls["deleteURL"] + "/" + extrapId;
                }
            },
            save : function(){
                if(Tecnotek.UI.vars["currentPeriod"] == 0){
                    Tecnotek.showErrorMessage("Es necesario definir un periodo como actual antes de guardar.",true, "", false);
                    return;
                }


                var $maxP = 100;
                var $minP = 1;
                var $pointsExtrap = 0;

                var $studentId = $("#studentId").val();
                var $course = $("#course").val();
                var $type = $("#extrapType").val();
                var $pointsExtrap = $("#pointsExtrap").val();
                //$maxP = $('option:selected', '#penaltyType').attr('maxPenalty');
                //$minP = $('option:selected', '#penaltyType').attr('minPenalty');

                //window.alert();
                if($pointsExtrap === ""){
                    Tecnotek.showErrorMessage("Debe incluir un puntaje.",
                        true, "", false);
                } else if(($pointsExtrap*1 < $minP*1) || ($pointsExtrap*1 > $maxP*1)){
                    Tecnotek.showErrorMessage("Los puntos estan fuera del rango.",
                        true, "", false);

                } else{
                    //alert($course);
                    Tecnotek.ajaxCall(Tecnotek.UI.urls["saveExtrapURL"],
                        {studentId: $studentId,
                            course: $course,
                            type: $type,
                            pointsExtrap: $pointsExtrap,
                            periodId: Tecnotek.UI.vars["currentPeriod"]
                        },
                        function(data){
                            if(data.error === true) {
                                Tecnotek.showErrorMessage(data.message,true, "", false);
                            } else {
                                $.fancybox.close();
                                Tecnotek.showInfoMessage("Los puntos se ha ingresado correctamente.", true, "", false)
                                Tecnotek.Extrapoints.loadExtrapoints();
                            }
                        },
                        function(jqXHR, textStatus){
                            Tecnotek.showErrorMessage("Error saving extra point: " + textStatus + ".",
                                true, "", false);
                        }, true);
                    //$.fancybox.close();
                    //Tecnotek.Extrapoints.loadExtrapoints();
                }

            },
            loadCourses: function() {
                $('#course').children().remove();
                //alert($('#groupToFormTeacher').val());
                Tecnotek.ajaxCall(Tecnotek.UI.urls["loadCoursesExtraPointsURL"],
                    {   periodId: Tecnotek.UI.vars["currentPeriod"]},
                    function(data){
                        if(data.error === true) {
                            Tecnotek.showErrorMessage(data.message,true, "", false);
                        } else {
                            $('#course').append('<option value="0">Todos</option>');
                            for(i=0; i<data.courses.length; i++) {
                                $('#course').append('<option value="' + data.courses[i].id + '">' + data.courses[i].name + '</option>');
                            }
                        }
                    },
                    function(jqXHR, textStatus){
                        Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                        $(this).val("");
                    }, true);
            },
            loadExtrapoints: function(period) {
                $("#extrap-container").html("");
                Tecnotek.uniqueAjaxCall(Tecnotek.UI.urls["searchExtraPoints"],
                    {
                        periodId: period
                    },
                    function(data){
                        if(data.error === true) {
                            //Tecnotek.hideWaiting();
                            Tecnotek.showErrorMessage(data.message,true, "", false);
                            //$("#new-relative-btn").hide();
                        } else {

                            var label = "";
                            var courselabel = "";

                            for(i=0; i<data.entity.length; i++) {
                                var row = '<div class="row userRow tableRowOdd">';
                                 row += '<div class="option_width" style="float: left; width: 350px;">' + data.entity[i].name + '</div>';
                                 row += '<div class="option_width" style="float: left; width: 150px;">' +  data.entity[i].points + '</div>';

                                 if(data.entity[i].typePoints == '1'){
                                    label = 'Puntos extras';
                                 }
                                 if(data.entity[i].typePoints == '2'){
                                     label = 'Puntos de traslado';
                                 }

                                if(data.entity[i].courseid == 'NULL'){
                                    courselabel = 'Todos';
                                }else{
                                    courselabel = data.entity[i].coursename;
                                }

                                 row += '<div class="option_width" style="float: left; width: 250px;">'+label+' (' + courselabel + ')</div>';

                                 row += '<div class="right imageButton editButton"  rel="' + data.entity[i].id + '"></div>';
                                 row += '<div class="right imageButton deleteButton" style="height: 16px;"  rel="' + data.entity[i].id + '"></div>';


                                $("#extrap-container").append(row);
                            }
                            Tecnotek.initStudentsSearch();
                            Tecnotek.AdministratorList.init();
                            Tecnotek.AdministratorList.initBtnSearch();
                            Tecnotek.Extrapoints.init();
                            //Tecnotek.hideWaiting();
                        }
                    },
                    function(jqXHR, textStatus){
                        if (textStatus != "abort") {
                            Tecnotek.hideWaiting();
                            console.debug("Error getting data: " + textStatus);
                        }
                    }, true, 'searchExtraPoints');
            }
        },
        RouteShow : {
            init : function() {
                $('#generalTab').click(function(event){
                    $('#studentsSection').hide();
                    $('#generalSection').show();
                    $('#generalTab').toggleClass("tab-current");
                    $('#studentsTab').toggleClass("tab-current");
                });
                $('#studentsTab').click(function(event){
                    $('#generalSection').hide();
                    $('#studentsSection').show();
                    $('#generalTab').toggleClass("tab-current");
                    $('#studentsTab').toggleClass("tab-current");
                });

                $("#entryFormCancel").click(function(event){
                    event.preventDefault();
                    $.fancybox.close();
                });

                $('#searchBox').keyup(function(event){
                    event.preventDefault();
                    if($(this).val().length == 0) {
                        $('#suggestions').fadeOut(); // Hide the suggestions box
                    } else {
                        Tecnotek.ajaxCall(Tecnotek.UI.urls["getStudentsURL"],
                            {text: $(this).val(), routeId: Tecnotek.UI.vars["routeId"]},
                            function(data){
                                if(data.error === true) {
                                    Tecnotek.showErrorMessage(data.message,true, "", false);
                                } else {
                                    $data = "";
                                    $data += '<p id="searchresults">';
                                    $data += '    <span class="category">Estudiantes</span>';
                                    for(i=0; i<data.students.length; i++) {
                                        $data += '    <a class="searchResult" rel="' + data.students[i].id + '" name="' +
                                            data.students[i].lastname + ' ' + data.students[i].firstname + '" route="' +
                                            data.students[i].in_route_id + ' type="' +
                                            data.students[i].routeType + '>';
                                        $data += '      <span class="searchheading">' + data.students[i].carne
                                            + ' ' + data.students[i].lastname
                                            + ' ' + data.students[i].firstname  + ' ' + data.students[i].groupyear
                                            + ' ' +  '</span>';
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
                                        Tecnotek.UI.vars["routeType"] = $(this).attr("type");
                                        Tecnotek.UI.vars["routeIn"] = $(this).attr("route");
                                        Tecnotek.ajaxCall(Tecnotek.UI.urls["associateStudentsURL"],
                                            {studentId: $(this).attr("rel"), routeId: Tecnotek.UI.vars["routeId"]},
                                            function(data){
                                                if(data.error === true) {
                                                    Tecnotek.showErrorMessage(data.message,true, "", false);
                                                } else {
                                                    console.debug("Add Student with Id: " + Tecnotek.UI.vars["studentId"]);
                                                    $html = '<div id="student_row_' + Tecnotek.UI.vars["studentId"] + '" class="row userRow" rel="' + Tecnotek.UI.vars["studentId"]
                                                        + '" typeroute="' + Tecnotek.UI.vars["routeType"] + '" routein="' + Tecnotek.UI.vars["routeIn"] +'">';
                                                    $html += '<div class="option_width" style="float: left; width: 300px;">' + Tecnotek.UI.vars["studentName"] + '</div>';
                                                    $html += '<div class="right imageButton deleteButton" style="height: 16px;"  title="delete???"  rel="' + Tecnotek.UI.vars["studentId"] + '"></div>';
                                                    $html += '<div class="clear"></div>';
                                                    $html += '</div>';
                                                    $("#studentsList").append($html);
                                                    Tecnotek.RouteShow.initDeleteButtons();
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

                $("#openStudentForm").fancybox({
                    'beforeLoad' : function(){

                    },
                    'modal': true,
                    'width': 650
                });

                $("#studentRouteIn, #studentRouteType").select2();

                $("#studentRouteForm").submit(function(e){
                    Tecnotek.ajaxCall(Tecnotek.UI.urls["changeStudentInInfoURL"],
                        {   studentId: Tecnotek.UI.vars["studentId"],
                            routeIn: $("#studentRouteIn").val(),
                            routeType: $("#studentRouteType").val()
                        },
                        function(data){
                            if(data.error === true) {
                                Tecnotek.showErrorMessage(data.message,true, "", false);
                            } else {
                                $("#btnEdit-" + Tecnotek.UI.vars["studentId"]).attr('routetype',$("#studentRouteType").val());
                                    //.val($("#studentRouteType").val());
                                $("#btnEdit-" + Tecnotek.UI.vars["studentId"]).attr('routein',$("#studentRouteIn").val());
                                $.fancybox.close();
                            }
                        },
                        function(jqXHR, textStatus){
                            Tecnotek.showErrorMessage("Error in request: " + textStatus + ".",
                                true, "", false);
                        }, true);
                    e.preventDefault();
                    return false;
                });

                Tecnotek.RouteShow.initDeleteButtons();
                Tecnotek.RouteShow.initializeRouteButtons();
            },
            initDeleteButtons : function() {
                $('.deleteButton').unbind();
                $('.deleteButton').click(function(event){
                    event.preventDefault();
                    if (Tecnotek.showConfirmationQuestion(Tecnotek.UI.translates["confirmRemoveStudent"])){
                        Tecnotek.UI.vars["studentId"] = $(this).attr("rel");
                        Tecnotek.ajaxCall(Tecnotek.UI.urls["removeStudentsFromRouteURL"],
                            {studentId: Tecnotek.UI.vars["studentId"],
                             routeId: Tecnotek.UI.vars["routeId"],
                             routeType: Tecnotek.UI.vars["routeType"]
                            },
                            function(data){
                                if(data.error === true) {
                                    Tecnotek.showErrorMessage(data.message,true, "", false);
                                } else {
                                    $("#student_row_" + Tecnotek.UI.vars["studentId"]).empty().remove();
                                }
                            },
                            function(jqXHR, textStatus){
                                Tecnotek.showErrorMessage("Error in request: " + textStatus + ".",
                                    true, "", false);
                            }, true);
                    }
                });
            },
            initializeRouteButtons: function(){
                $('.editStudent').unbind();
                $('.editStudent').click(function(event){
                    event.preventDefault();
                    var studentId = $(this).attr("rel");
                    var routeType = $(this).attr("routetype");
                    var routein = $(this).attr("routein");
                    Tecnotek.UI.vars["studentId"] = studentId;
                    $("#studentRouteType").val(routeType).trigger("change");
                    $("#studentRouteIn").val(routein).trigger("change");
                    $('#openStudentForm').trigger('click');
                });
            }
        },
        ClubShow : {
            init : function() {
                $('#generalTab').click(function(event){
                    $('#studentsSection').hide();
                    $('#generalSection').show();
                    $('#generalTab').toggleClass("tab-current");
                    $('#studentsTab').toggleClass("tab-current");
                });
                $('#studentsTab').click(function(event){
                    $('#generalSection').hide();
                    $('#studentsSection').show();
                    $('#generalTab').toggleClass("tab-current");
                    $('#studentsTab').toggleClass("tab-current");
                });

                $('#searchBox').keyup(function(event){
                    event.preventDefault();
                    if($(this).val().length == 0) {
                        $('#suggestions').fadeOut(); // Hide the suggestions box
                    } else {
                        Tecnotek.ajaxCall(Tecnotek.UI.urls["getStudentsURL"],
                            {text: $(this).val(), clubId: Tecnotek.UI.vars["clubId"]},
                            function(data){
                                if(data.error === true) {
                                    Tecnotek.showErrorMessage(data.message,true, "", false);
                                } else {
                                    $data = "";
                                    $data += '<p id="searchresults">';
                                    $data += '    <span class="category">Estudiantes</span>';
                                    for(i=0; i<data.students.length; i++) {
                                        console.debug();
                                        $data += '    <a class="searchResult" rel="' + data.students[i].id + '" name="' +
                                            data.students[i].lastname + ' ' + data.students[i].firstname + '">';
                                        $data += '      <span class="searchheading">' + data.students[i].lastname
                                            + ' ' + data.students[i].firstname + ' ' + data.students[i].groupyear +  '</span>';
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
                                        Tecnotek.ajaxCall(Tecnotek.UI.urls["associateStudentsURL"],
                                            {studentId: $(this).attr("rel"), clubId: Tecnotek.UI.vars["clubId"]},
                                            function(data){
                                                if(data.error === true) {
                                                    Tecnotek.showErrorMessage(data.message,true, "", false);
                                                } else {
                                                    console.debug("Add Student with Id: " + Tecnotek.UI.vars["studentId"]);
                                                    $html = '<div id="student_row_' + Tecnotek.UI.vars["studentId"] + '" class="row userRow" rel="' + Tecnotek.UI.vars["studentId"] + '">';
                                                    $html += '<div class="option_width" style="float: left; width: 300px;">' + Tecnotek.UI.vars["studentName"] + '</div>';
                                                    $html += '<div class="right imageButton deleteButton" style="height: 16px;"  title="delete???"  rel="' + Tecnotek.UI.vars["studentId"] + '"></div>';
                                                    $html += '<div class="clear"></div>';
                                                    $html += '</div>';
                                                    $("#studentsList").append($html);
                                                    Tecnotek.ClubShow.initDeleteButtons();
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

                Tecnotek.ClubShow.initDeleteButtons();
            },
            initDeleteButtons : function() {
                console.debug("entro a initDeleteButtons!!!");
                $('.deleteButton').unbind();
                $('.deleteButton').click(function(event){
                    event.preventDefault();
                    if (Tecnotek.showConfirmationQuestion(Tecnotek.UI.translates["confirmRemoveStudent"])){
                        Tecnotek.UI.vars["studentId"] = $(this).attr("rel");
                        console.debug("Delete student: " + Tecnotek.UI.vars["studentId"] + " :: " + Tecnotek.UI.vars["clubId"]);
                        Tecnotek.ajaxCall(Tecnotek.UI.urls["removeStudentsFromClubURL"],
                            {studentId: Tecnotek.UI.vars["studentId"], clubId: Tecnotek.UI.vars["clubId"]},
                            function(data){
                                if(data.error === true) {
                                    Tecnotek.showErrorMessage(data.message,true, "", false);
                                } else {
                                    $("#student_row_" + Tecnotek.UI.vars["studentId"]).empty().remove();
                                }
                            },
                            function(jqXHR, textStatus){
                                Tecnotek.showErrorMessage("Error in request: " + textStatus + ".",
                                    true, "", false);
                            }, true);
                    }
                });
            }
        },
        StudentShow : {
            translates : {},
            init : function() {
                console.debug("por aca vamos....");
                $('#generalTab').click(function(event){
                    event.preventDefault();
                    $('#relativesSection').hide();
                    $('#generalSection').show();
                    $('#generalTab').toggleClass("tab-current");
                    $('#relativesTab').toggleClass("tab-current");
                });
                $('#relativesTab').click(function(event){
                    event.preventDefault();
                    $('#generalSection').hide();
                    $('#relativesSection').show();
                    $('#generalTab').toggleClass("tab-current");
                    $('#relativesTab').toggleClass("tab-current");
                });
                $('#kinship').change(function(event){
                    event.preventDefault();
                    if($(this).val() == 99){
                        $('#otherDetail').show();
                    } else {
                        $('#otherDetail').hide();
                    }
                });

                $('.editButton').click(function(event){
                    event.preventDefault();
                    console.debug("AdministratorList :: initButtons :: editButton Event");
                    location.href = Tecnotek.UI.urls["edit"] + "/" + $(this).attr("rel");
                });

                $('#asociateButton').click(function(event){
                    event.preventDefault();
                    Tecnotek.createAndAssociateRelative(Tecnotek.afterCreateAndAssociateRelative);
                });


                $('#searchBox').keyup(function(event){
                    event.preventDefault();
                    if($(this).val().length == 0) {
                        $('#suggestions').fadeOut(); // Hide the suggestions box
                    } else {
                        Tecnotek.ajaxCall(Tecnotek.UI.urls["getContactsURL"],
                            {text: $(this).val(), studentId: Tecnotek.UI.vars["studentId"]},
                            function(data){
                                if(data.error === true) {
                                    Tecnotek.showErrorMessage(data.message,true, "", false);
                                } else {
                                    $data = "";
                                    $data += '<p id="searchresults">';
                                    $data += '    <span class="category">Contactos</span>';
                                    for(i=0; i<data.contacts.length; i++) {
                                        console.debug();
                                        $data += '    <a class="searchResult" rel="' + data.contacts[i].id + '" name="' +
                                            data.contacts[i].firstname + ' ' + data.contacts[i].lastname + '">';
                                        $data += '      <span class="searchheading">' + data.contacts[i].firstname
                                            + ' ' + data.contacts[i].lastname +  '</span>';
                                        $data += '      <span>Asociar este contacto.</span>';
                                        $data += '    </a>';
                                    }
                                    $data += '</p>';

                                    $('#suggestions').fadeIn(); // Show the suggestions box
                                    $('#suggestions').html($data); // Fill the suggestions box
                                    $('.searchResult').unbind();
                                    $('.searchResult').click(function(event){
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
                                                    $html = '<div id="relative_row_' + data.id + '" class="row" rel="' + data.id + '" style="padding: 0px; font-size: 10px;">';
                                                    $html += '    <div class="" style="float: left; width: 325px;">' + relativeName + '</div>';
                                                    $html += '    <div class="" style="float: left; width: 50px;">' + $detail + '</div>';

                                                    $html += '    <div class="right imageButton deleteButton" style="height: 16px;" title="Eliminar" rel="' + data.id + '"></div>';
                                                    $html += '    <div class="right imageButton viewButton" style="height: 16px;"  title="Ver"  rel="' + data.id + '"></div>';
                                                    $html += '<div class="right imageButton editButton" title="Editar"  rel="' + data.idc + '"></div>';                                                    
                                                    $html += '    <div class="clear"></div>';
                                                    $html += '</div>';

                                                    $("#relativesList").append($html);
                                                    $('#suggestions').fadeOut();
                                                    Tecnotek.StudentShow.initDeleteButtons();

                                                    $('.editButton').unbind().click(function(event){
                                                        event.preventDefault();
                                                        console.debug("AdministratorList :: initButtons :: editButton Event");
                                                        location.href = Tecnotek.UI.urls["edit"] + "/" + $(this).attr("rel");
                                                    });
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

                Tecnotek.StudentShow.initDeleteButtons();
            },
            initDeleteButtons : function() {
                $('.deleteButton').unbind();
                $('.deleteButton').click(function(event){
                    event.preventDefault();
                    if (Tecnotek.showConfirmationQuestion(Tecnotek.UI.translates["confirmRemoveRelative"])){
                        Tecnotek.UI.vars["relativeId"] = $(this).attr("rel");
                        Tecnotek.ajaxCall(Tecnotek.UI.urls["removeRelativeURL"],
                            {relativeId: Tecnotek.UI.vars["relativeId"]},
                            function(data){
                                if(data.error === true) {
                                    Tecnotek.showErrorMessage(data.message,true, "", false);
                                } else {
                                    $("#relative_row_" + Tecnotek.UI.vars["relativeId"]).empty().remove();
                                }
                            },
                            function(jqXHR, textStatus){
                                Tecnotek.showErrorMessage("Error in request: " + textStatus + ".",
                                    true, "", false);
                            }, true);
                    }
                });


                $('.viewButton').unbind();
                $('.viewButton').click(function(event){
                    console.debug("Click en view button: " + Tecnotek.UI.urls["getRelativeInfoURL"]);
                    event.preventDefault();
                    Tecnotek.UI.vars["relativeId"] = $(this).attr("rel");
                    Tecnotek.ajaxCall(Tecnotek.UI.urls["getRelativeInfoURL"],
                        {relativeId: Tecnotek.UI.vars["relativeId"]},
                        function(data){
                            if(data.error === true) {
                                Tecnotek.showErrorMessage(data.message,true, "", false);
                            } else {
                                //Tecnotek.showInfoMessage(data.html, true, "", false);
                                $("#contactContainer").html(data.html);
                                $("#showContactInfoLink").trigger("click");
                            }
                        },
                        function(jqXHR, textStatus){
                            Tecnotek.showErrorMessage("Error in request: " + textStatus + ".",
                                true, "", false);
                        }, true);
                });
            }
        },
        /*Tickets : {
            translates : {},
            init : function() {
                $('#generalTab').click(function(event){
                    event.preventDefault();
                    $('#relativesSection').hide();
                    $('#generalSection').show();
                    $('#generalTab').toggleClass("tab-current");
                    $('#relativesTab').toggleClass("tab-current");
                });

                $('#asociateButton').click(function(event){
                    event.preventDefault();
                    console.debug("asociateButton click!!");
                    $firstname = $("#firstname").val();
                    $lastname = $("#lastname").val();
                    $identification = $("#identification").val();
                    $detail = "";

                    switch($("#kinship").val()){
                        case "1": $detail = "Padre"; break;
                        case "2": $detail = "Madre"; break;
                        case "3": $detail = "Hermano"; break;
                        case "4": $detail = "Hermana"; break;
                        case "99": $detail = $('#description').val(); break;
                    }

                    if($firstname == "" || $lastname == "" || $identification == "" || $detail == ""){
                        Tecnotek.showErrorMessage(Tecnotek.StudentShow.translates["emptyFields"], true, "", false)
                    } else {
                        Tecnotek.ajaxCall(Tecnotek.UI.urls["saveNewContactURL"],
                            {studentId: Tecnotek.UI.vars["studentId"],
                                'tecnotek_expediente_contactformtype[firstname]': $firstname,
                                'tecnotek_expediente_contactformtype[lastname]': $lastname,
                                'tecnotek_expediente_contactformtype[identification]': $identification,
                                'kinship': $("#kinship").val(), 'detail': $detail},
                            function(data){
                                if(data.error === true) {
                                    Tecnotek.showErrorMessage(data.message,true, "", false);
                                } else {
                                    $html = '<div id="relative_row_' + data.id + '" class="row userRow" rel="' + data.id + '">';
                                    $html += '<div class="option_width" style="float: left; width: 200px;">' + $firstname + " " + $lastname + '</div>';
                                    $html += '<div class="option_width" style="float: left; width: 100px;">' + $detail + '</div>';
                                    $html += '<div class="right imageButton deleteButton" style="height: 16px;"  title="delete???"  rel="' + data.id + '"></div>';
                                    $html += '<div class="clear"></div>';
                                    $html += '</div>';
                                    $("#relativesList").append($html);
                                    //Clean fields
                                    $("#firstname").val("");
                                    $("#lastname").val("");
                                    $("#identification").val("");
                                    $('#description').val("");
                                    Tecnotek.StudentShow.initDeleteButtons();
                                    Tecnotek.showInfoMessage(Tecnotek.StudentShow.translates["confirmRelative"], true, "", false);
                                }
                            },
                            function(jqXHR, textStatus){
                                Tecnotek.showErrorMessage("Error saving data: " + textStatus + ".",
                                    true, "", false);
                            }, true);
                    }
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
                            console.debug("Add ticket: student-" + $id + " :: relative-" + $relative + " :: commments-" + $comments );
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
                                        window.location.reload(true);
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
                        Tecnotek.ajaxCall(Tecnotek.UI.urls["getStudentsURL"],
                            {text: $(this).val()},
                            function(data){
                                if(data.error === true) {
                                    Tecnotek.showErrorMessage(data.message,true, "", false);
                                } else {
                                    $data = "";
                                    $data += '<p id="searchresults">';
                                    $data += '    <span class="category">Estudiantes</span>';
                                    for(i=0; i<data.students.length; i++) {
                                        $data += '    <a class="searchResult" style="height: 20px; line-height: 20px;" rel="' + data.students[i].id + '" name="' +
                                            data.students[i].firstname + ' ' + data.students[i].lastname + '">';
                                        $data += '      <span class="searchheading">' + data.students[i].firstname
                                            + ' ' + data.students[i].lastname +  '</span>';
                                        $data += '    </a>';
                                    }
                                    $data += '</p>';

                                    $('#suggestions').fadeIn(); // Show the suggestions box
                                    $('#suggestions').html($data); // Fill the suggestions box
                                    $('.searchResult').unbind();
                                    $('.searchResult').click(function(event){
                                         event.preventDefault();
                                         Tecnotek.UI.vars["studentId"] = $(this).attr("rel");
                                         $('#student').attr("rel", Tecnotek.UI.vars["studentId"]);
                                         Tecnotek.UI.vars["studentName"] = $(this).attr("name");
                                         $('#student').val(Tecnotek.UI.vars["studentName"]);

                                         Tecnotek.ajaxCall(Tecnotek.UI.urls["loadStudentRelativesURL"],
                                             {studentId: Tecnotek.UI.vars["studentId"]},
                                             function(data){
                                                 if(data.error === true) {
                                                    Tecnotek.showErrorMessage(data.message,true, "", false);
                                                 } else {
                                                     if( data.relatives.length == 0){
                                                        Tecnotek.showInfoMessage(Tecnotek.StudentShow.translates["relative.not.exists"], true, "", false);
                                                         $('#student').attr("rel", 0);
                                                         $('#student').val("");
                                                     } else {
                                                         for(i=0; i<data.relatives.length; i++) {
                                                             $("#relative").append('<option value="' + data.relatives[i].id
                                                                 +'">' + data.relatives[i].contact + ' - ' + data.relatives[i].kinship + '</option>');
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

                $('#student').blur(function(event){
                    event.preventDefault();
                    $(this).val("");
                    $('#suggestions').fadeOut(); // Hide the suggestions box
                });

                $('.viewButton').click(function(event){
                    event.preventDefault();
                    var id = $(this).attr("rel");
                    //Lo que quiere que haga con el id
                    location.href = Tecnotek.UI.urls["show"] + "/" + $(this).attr("rel");
                    //console.debug("ver boleta con id: " + id);
                });
            }
        },*/
        PermisosUsuarios : {
            init : function() {
                Tecnotek.PermisosUsuarios.initComponents();
                Tecnotek.PermisosUsuarios.initButtons();
            },
            initComponents : function() {
                // TO CREATE AN INSTANCE
                // select the tree container using jQuery

                $("#demo1").jstree({
                        "plugins" : [ "themes", "html_data", "checkbox", "ui" ],
                        "core" : {  }
                    }).bind("loaded.jstree", function (event, data) {
                        // you get two params - event & data - check the core docs for a detailed description
                    });

                $("#btnSave").click(function(event){
                    event.preventDefault();

                    //$("#7").find('.jstree-checkbox').trigger("click");

                    if($("#users").val() == null || $("#users").val() === "null"){
                        Tecnotek.showErrorMessage("No se ha seleccionado un usuario.", true, "", false);
                    } else {
                        var checked_ids = [];
                        $('#demo1').jstree("get_checked",null,true).each(function(){
                            checked_ids.push(this.id);
                        });

                        var checked_institutions = [];
                        $(".insti-cb").each(function(){
                            if($(this).is(':checked')){
                                checked_institutions.push($(this).val());
                            }
                        });
                        //setting to hidden field
                        //console.debug($("#users").val() + " :: " + checked_ids.join(","));

                        Tecnotek.ajaxCall(Tecnotek.UI.urls["savePrivilegesURL"],
                            {   userId: $("#users").val(),
                                access: checked_ids.join(","),
                                institutions: checked_institutions.join(",")},
                            function(data){
                                if(data.error === true) {
                                    Tecnotek.showErrorMessage(data.message,true, "", false);
                                } else {
                                }
                            },
                            function(jqXHR, textStatus){
                                Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".",
                                    true, "", false);
                            }, true);
                    }

                });

                $("#users").change(function(event){
                    event.preventDefault();
                    $("#privilegesContainer").hide();
                    $("#institutions-container").hide();
                    $("#demo1").jstree("uncheck_all")
                    $("#demo1").jstree('close_all');
                    $(".insti-cb").each(function(){
                        $(this).removeAttr('checked');
                    });
                    if($("#users").val() == null || $("#users").val() === "null"){
                        return;
                    } else {
                        $( "#spinner-modal" ).dialog( "open" );
                        Tecnotek.ajaxCall(Tecnotek.UI.urls["getPrivilegesURL"],
                            {userId: $("#users").val()},
                            function(data){
                                if(data.error === true) {
                                    $( "#spinner-modal" ).dialog( "close" );
                                    Tecnotek.showErrorMessage(data.message,true, "", false);
                                } else {
                                    //console.debug("-> " + data.privileges);
                                    for(i=0; i<data.privileges.length; i++) {
                                        $("#" + data.privileges[i]).find('.jstree-checkbox').trigger("click");
                                    }
                                    for(i=0; i<data.institutions.length; i++) {
                                        $("#institution-" + data.institutions[i]).attr("checked", "checked");
                                    }
                                    $("#privilegesContainer").show();
                                    $("#institutions-container").show();
                                    $( "#spinner-modal" ).dialog( "close" );
                                }
                            },
                            function(jqXHR, textStatus){
                                $( "#spinner-modal" ).dialog( "close" );
                                Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".",
                                    true, "", false);
                            }, true);
                    }

                });

            },
            initButtons : function() {
            }
        },
        Qualifications : {
            translates : {},
            init : function() {
                $('#viewPrintable').click(function(event){
                    event.preventDefault();
                    console.debug("print!!!");

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
                    Tecnotek.Qualifications.loadQualificationsOfGroup($(this).val());
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
                                Tecnotek.Qualifications.loadCoursesOfGroupByTeacher($('#groups').val());
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
                    Tecnotek.Qualifications.loadQualificationsOfGroup(0);
                    Tecnotek.ajaxCall(Tecnotek.UI.urls["loadCoursesOfGroupByTeacherURL"],
                        {   groupId: $groupId },
                        function(data){
                            if(data.error === true) {
                                Tecnotek.showErrorMessage(data.message,true, "", false);
                            } else {
                                for(i=0; i<data.courses.length; i++) {
                                    $('#courses').append('<option value="' + data.courses[i].id + '">' + data.courses[i].name + '</option>');
                                }
                                Tecnotek.Qualifications.loadQualificationsOfGroup($('#courses').val());
                            }
                        },
                        function(jqXHR, textStatus){
                            Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                            $(this).val("");
                        }, false);
                }
            },
            loadQualificationsOfGroup: function(courseId) {
                $('.editEntry').unbind();
                $('#entriesRows').empty();
                $('#subentriesRows').empty();
                $('#subentryFormParent').empty();
                $('#contentBody').empty();
                $('#studentsHeader').empty();
                if(courseId == 0){//Clean page
                } else {
                    Tecnotek.ajaxCall(Tecnotek.UI.urls["loadQualificationsOfGroupURL"],
                        {   periodId: $("#period").val(),
                            courseId: courseId,
                            groupId: $("#groups").val()},
                        function(data){
                            if(data.error === true) {
                                Tecnotek.showErrorMessage(data.message,true, "", false);
                            } else {
                                console.debug("WIDHT----> " + data.codesCounter);

                                /*$("#tableContainer").width(data.codesCounter * 46 + 280);
                                if(data.codesCounter * 46 + 280 > 960) {
                                    $("#wrap").width(data.codesCounter * 46 + 280);
                                } else {
                                    $("#wrap").width(960);
                                }*/

                                $('#contentBody').html(data.html);
                                $('#tableContainer').show();

                                var height = data.studentsCounter * 26.66 + 300;
                                $("#studentsTableContainer").css("height", height + "px");



                                $(".textField").each(function(){
                                    if($(this).attr("val") !== "-1" && $(this).attr("val").indexOf("val") !== 0){
                                        $(this).val($(this).attr("val"));
                                    }
                                    $(this).trigger("blur");
                                });

                                Tecnotek.Qualifications.initializeTable();
                                Tecnotek.UI.vars["forzeBlur"] = true;
                                $(".textField").each(function(){
                                    $(this).trigger("focus");
                                    $(this).trigger("blur");
                                });
                                Tecnotek.UI.vars["forzeBlur"] = false;
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
                $('.editEntry').unbind();
                $('#entriesRows').empty();
                $('#subentriesRows').empty();
                $('#subentryFormParent').empty();

                $(".textField").focus(function(e){
                    Tecnotek.UI.vars["textFieldValue"] = $(this).val();
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
                    }
                    if(Tecnotek.UI.vars["forzeBlur"] == true){
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
                            $beforeText = ($totalTrim >= $notaMin)? "":"(*) ";
                            $("#total_trim_" + $stdId).html($beforeText + Tecnotek.roundTo($sum));
                        }
                    } else {
                        if(Tecnotek.UI.vars["textFieldValue"] === $nota) return;
                        Tecnotek.ajaxCall(Tecnotek.UI.urls["saveStudentQualificationURL"],
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
                            }, false);
                    }

                });
            }
        },
        afterCreateAndAssociateRelative: function(){
            $html = '<div id="relative_row_' + this.data.id + '" class="row" rel="' +  this.data.id + '" style="padding: 0px;">';
            $html += '<div class style="float: left; width: 325px;">' + $firstname + " " + $lastname + '</div>';
            $html += '<div class style="float: left; width: 50px;">' + $detail + '</div>';
            $html += '<div class="right imageButton deleteButton" style="height: 16px;"  title="Delete"  rel="' + data.id + '"></div>';
            $html += '<div class="right imageButton viewButton" style="height: 16px;"  title="Ver"  rel="' + data.id + '"></div>';
            $html += '<div class="right imageButton editButton" title="Editar"  rel="' + data.idc + '"></div>';
            $html += '<div class="clear"></div>';
            $html += '</div>';
            $("#relativesList").append($html);
            //Clean fields
            $("#firstname").val("gfdgfdgd");
            $("#lastname").val("");
            $("#identification").val("");
            $('#description').val("");
            Tecnotek.StudentShow.initDeleteButtons();
            Tecnotek.showInfoMessage(Tecnotek.StudentShow.translates["confirmRelative"], true, "", false);

            $('.editButton').unbind().click(function(event){
                event.preventDefault();
                console.debug("AdministratorList :: initButtons :: editButton Event");
                location.href = Tecnotek.UI.urls["edit"] + "/" + $(this).attr("rel");
            });
        },
        createAndAssociateRelative: function(whenSuccessFunction){
            console.debug("--> createAndAssociateRelative");
            $firstname = $("#firstname").val();
            $lastname = $("#lastname").val();
            $identification = $("#identification").val();
            $phonec = $("#phonec").val();
            $phonew = $("#phonew").val();
            $phoneh = $("#phoneh").val();
            $workplace = $("#workplace").val();
            $email = $("#email").val();
            $adress = $("#adress").val();
            $restriction = $("#restriction").val();
            $detail = "";

            switch($("#kinship").val()){
                case "1": $detail = "Padre"; break;
                case "2": $detail = "Madre"; break;
                case "3": $detail = "Hermano"; break;
                case "4": $detail = "Hermana"; break;
                case "99": $detail = $('#description').val(); break;
            }
            console.debug("FirstName: "+ $firstname + ", lastname: " + $lastname +
                ", id: " + $identification + ", val: " + $("#kinship").val() + ", detail: " + $detail);
            if($firstname == "" || $lastname == "" || $identification == "" || $detail == ""){
                Tecnotek.showErrorMessage(Tecnotek.StudentShow.translates["emptyFields"], true, "", false)
            } else {
                Tecnotek.ajaxCall(Tecnotek.UI.urls["saveNewContactURL"],
                    {studentId: Tecnotek.UI.vars["studentId"],
                        'tecnotek_expediente_contactformtype[firstname]': $firstname,
                        'tecnotek_expediente_contactformtype[lastname]': $lastname,
                        'tecnotek_expediente_contactformtype[identification]': $identification,
                        'tecnotek_expediente_contactformtype[phonec]': $phonec,
                        'tecnotek_expediente_contactformtype[phonew]': $phonew,
                        'tecnotek_expediente_contactformtype[phoneh]': $phoneh,
                        'tecnotek_expediente_contactformtype[workplace]': $workplace,
                        'tecnotek_expediente_contactformtype[email]': $email,
                        'tecnotek_expediente_contactformtype[adress]': $adress,
                        'tecnotek_expediente_contactformtype[restriction]': $restriction,
                        'tecnotek_expediente_contactformtype[degree]': 0,
                        'tecnotek_expediente_contactformtype[sector]': 0,
                        'kinship': $("#kinship").val(), 'detail': $detail},

                    function(data){
                        if(data.error === true) {
                            Tecnotek.showErrorMessage(data.message,true, "", false);
                        } else {

                            $html = '<div id="relative_row_' + this.data.id + '" class="row" rel="' +  this.data.id + '" style="padding: 0px;">';
                            $html += '<div class style="float: left; width: 325px;">' + $firstname + " " + $lastname + '</div>';
                            $html += '<div class style="float: left; width: 50px;">' + $detail + '</div>';
                            $html += '<div class="right imageButton deleteButton" style="height: 16px;"  title="Delete"  rel="' + data.id + '"></div>';
                            $html += '<div class="right imageButton viewButton" style="height: 16px;"  title="Ver"  rel="' + data.id + '"></div>';
                            $html += '<div class="right imageButton editButton" title="Editar"  rel="' + data.idc + '"></div>';
                            $html += '<div class="clear"></div>';
                            $html += '</div>';
                            $("#relativesList").append($html);
                            //Clean fields
                            $("#firstname").val("gfdgfdgd");
                            $("#lastname").val("");
                            $("#identification").val("");
                            $('#description').val("");
                            Tecnotek.StudentShow.initDeleteButtons();
                            Tecnotek.showInfoMessage(Tecnotek.StudentShow.translates["confirmRelative"], true, "", false);

                            $('.editButton').unbind().click(function(event){
                                event.preventDefault();
                                console.debug("AdministratorList :: initButtons :: editButton Event");
                                location.href = Tecnotek.UI.urls["edit"] + "/" + $(this).attr("rel");
                            });


                            whenSuccessFunction();
                        }
                    },
                    function(jqXHR, textStatus){
                        Tecnotek.showErrorMessage("Error saving data: " + textStatus + ".",
                            true, "", false);
                    }, true);

            }
        },
    initStudentsSearch: function(){
        $('#searchBox').focus(function(event){
            $("#studentId").val(0);
            $('#searchBox').val("");
        });

        $('#searchBox').keyup(function(event){
            event.preventDefault();
            if($(this).val().length == 0) {
                $('#suggestions').fadeOut(); // Hide the suggestions box
            } else {
                Tecnotek.ajaxCall(Tecnotek.UI.urls["getStudentsURL"],
                    {text: $(this).val()},
                    function(data){
                        if(data.error === true) {
                            Tecnotek.showErrorMessage(data.message,true, "", false);
                        } else {
                            $data = "";
                            $data += '<p id="searchresults">';
                            $data += '    <span class="category">Estudiantes</span>';
                            for(i=0; i<data.students.length; i++) {
                                $data += '    <a class="searchResult" rel="' + data.students[i].id + '" name="' +
                                    data.students[i].firstname + ' ' + data.students[i].lastname
                                    + '" inst="'+data.students[i].institution_id+'">';
                                $data += '      <span class="searchheading">' + data.students[i].carne
                                    + ' ' + data.students[i].firstname
                                    + ' ' + data.students[i].lastname + ' ' + data.students[i].groupyear
                                    + ' ' +  '</span>';
                                $data += '      <span>Incluir este estudiante.</span>';
                                $data += '    </a>';
                            }
                            $data += '</p>';

                            $('#suggestions').fadeIn(); // Show the suggestions box
                            $('#suggestions').html($data); // Fill the suggestions box
                            $('.searchResult').unbind();
                            $('.searchResult').click(function(event){
                                event.preventDefault();
                                $("#studentId").val($(this).attr("rel"));
                                //$("#institucionID").val($(this).attr("inst"));
                                $('#searchBox').val("");
                                $('#newExtraPoint').trigger('click');

                                //if(($(this).attr("inst")<19)||(($(this).attr("inst")>33)&&($(this).attr("inst")<52))){
                                if($(this).attr("inst")==2){
                                    $("select > option[insti*='3']").hide();
                                    $("select > option[insti*='3']").removeAttr("selected");
                                    $("select > option[insti*='2']").show();
                                    $("select > option[insti*='2']").attr('selected','selected');
                                }

                                //if((($(this).attr("inst")>18)&&($(this).attr("inst")<34))||($(this).attr("inst")>51)){
                                if($(this).attr("inst")==3){
                                    $("select > option[insti*='2']").hide();
                                    $("select > option[insti*='2']").removeAttr("selected");
                                    $("select > option[insti*='3']").show();
                                    $("select > option[insti*='3']").attr('selected','selected');
                                }



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
            //$("#institucionID").val("0");
        });
    },
    initRelativesSearch: function(){
            $('#searchBox').focus(function(event){
                $("#studentId").val(0);
                $('#searchBox').val("");
            });

            $('#searchBox').keyup(function(event){
                event.preventDefault();
                if($(this).val().length == 0) {
                    $('#suggestions').fadeOut(); // Hide the suggestions box
                } else {
                    Tecnotek.ajaxCall(Tecnotek.UI.urls["getStudentsURL"],
                        {text: $(this).val()},
                        function(data){
                            if(data.error === true) {
                                Tecnotek.showErrorMessage(data.message,true, "", false);
                            } else {
                                $data = "";
                                $data += '<p id="searchresults">';
                                $data += '    <span class="category">Estudiantes</span>';
                                for(i=0; i<data.students.length; i++) {
                                    $data += '    <a class="searchResult" rel="' + data.students[i].id + '" name="' +
                                        data.students[i].firstname + ' ' + data.students[i].lastname
                                        + '" inst="'+data.students[i].institution_id+'">';
                                    $data += '      <span class="searchheading">' + data.students[i].carne
                                        + ' ' + data.students[i].firstname
                                        + ' ' + data.students[i].lastname + ' ' + data.students[i].groupyear
                                        + ' ' +  '</span>';
                                    $data += '      <span>Incluir este estudiante.</span>';
                                    $data += '    </a>';
                                }
                                $data += '</p>';

                                $('#suggestions').fadeIn(); // Show the suggestions box
                                $('#suggestions').html($data); // Fill the suggestions box
                                $('.searchResult').unbind();
                                $('.searchResult').click(function(event){
                                    event.preventDefault();
                                    $("#studentId").val($(this).attr("rel"));
                                    //$("#institucionID").val($(this).attr("inst"));
                                    $('#searchBox').val("");
                                    $('#newAbsence').trigger('click');

                                    //if(($(this).attr("inst")<19)||(($(this).attr("inst")>33)&&($(this).attr("inst")<52))){
                                    if($(this).attr("inst")==2){
                                        $("select > option[insti*='3']").hide();
                                        $("select > option[insti*='3']").removeAttr("selected");
                                        $("select > option[insti*='2']").show();
                                        $("select > option[insti*='2']").attr('selected','selected');
                                    }

                                    //if((($(this).attr("inst")>18)&&($(this).attr("inst")<34))||($(this).attr("inst")>51)){
                                    if($(this).attr("inst")==3){
                                        $("select > option[insti*='2']").hide();
                                        $("select > option[insti*='2']").removeAttr("selected");
                                        $("select > option[insti*='3']").show();
                                        $("select > option[insti*='3']").attr('selected','selected');
                                    }



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
                //$("#institucionID").val("0");
            });
        }
	};

Tecnotek.PeriodGroupAbsences = {
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
            Tecnotek.PeriodGroupAbsences.loadPeriodLevels($(this).val());
        });

        $("#levels").change(function(event){
            event.preventDefault();
            //: function($periodId, $levelId)
            Tecnotek.PeriodGroupAbsences.loadGroupsOfPeriodAndLevel($('#period').val(), $(this).val());
        });

        $("#groups").change(function(event){
            event.preventDefault();
            Tecnotek.PeriodGroupAbsences.loadAbsencesOfGroup($('#period').val(), $('#group').val());
        });

        Tecnotek.PeriodGroupAbsences.loadPeriodLevels($('#period').val());
        Tecnotek.PeriodGroupAbsences.initButtons();
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
                        Tecnotek.PeriodGroupAbsences.loadGroupsOfPeriodAndLevel($('#period').val(), $('#levels').val());
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
    loadAbsencesOfGroup: function(periodId, groupId) {
        $('.editEntry').unbind();
        $('#contentBody').empty();
        $('#tableContainer').hide();
        if(periodId === null || groupId == 0){//Clean page
        } else {
            $('#fountainG').show();
            Tecnotek.ajaxCall(Tecnotek.UI.urls["loadAbsencesOfGroupURL"],
                {   periodId:   $('#period').val(),
                    levelId:    $('#levels').val(),
                    groupId:    $('#groups').val()},
                function(data){
                    console.debug("Load groups of period: " + data.periodId);
                    $('#fountainG').hide();
                    if(data.error === true) {
                        Tecnotek.showErrorMessage(data.message,true, "", false);
                    } else {
                        $temp = '<div class="center"><h3>Ausencias de la sección ' +data.entity[0].grade + '-' +data.entity[0].group + '</h3></div>';
                        $temp += "<table><tr>";
                        $temp += '<td style="height:50px;"><span style="font-family:arial;font-size:16px;">Carnet</span></td>';
                        $temp += '<td style="height:50px;"><span style="font-family:arial;font-size:16px;">Nombre</span></td>';
                        $temp += '<td style="height:50px;"><span style="font-family:arial;font-size:16px;">Fecha</span></td>';
                        $temp += '<td style="height:50px;"><span style="font-family:arial;font-size:16px;">Ausencia</span></td>';
                        $temp += '<td style="height:50px;"><span style="font-family:arial;font-size:16px;">Comentarios</span></td>';
                        $temp += '<td style="height:50px;"><span style="font-family:arial;font-size:16px;">Estado</span></td>';
                        $temp += "</tr>";
                        for(i=0; i<data.entity.length; i++) {
                            //Tecnotek.PeriodGroupAverages.completeText += '<div>' +data.entity[i].name + '-' + data.entity[i].average +'</div>';
                            $temp += "<tr>";
                            $temp += '<td style="height:20px;"><span style="font-family:arial;font-size:16px;">'+data.entity[i].carne  + '</span></td>';
                            $temp += '<td style="height:20px;"><span style="font-family:arial;font-size:16px;">'+data.entity[i].name  + '</span></td>';
                            $temp += '<td style="height:20px;"><span style="font-family:arial;font-size:16px;">'+data.entity[i].date  + '</span></td>';
                            $temp += '<td style="height:20px;"><span style="font-family:arial;font-size:16px;">'+data.entity[i].absence  + '</span></td>';
                            $temp += '<td style="height:20px;"><span style="font-family:arial;font-size:16px;">'+data.entity[i].comments  + '</span></td>';
                            if(data.entity[i].name == '0'){
                                $temp += '<td align="center"><span style="font-family:arial;font-size:16px;">Sin Justificar</span></td>';
                            }else {
                                $temp += '<td align="center"><span style="font-family:arial;font-size:16px;">Justificada</span></td>';
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

String.prototype.replaceAll = function(search, replacement) {
    var target = this;
    return target.replace(new RegExp(search, 'g'), replacement);
};
