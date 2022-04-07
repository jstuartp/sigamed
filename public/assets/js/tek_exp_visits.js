var Tecnotek = Tecnotek || {};

Tecnotek.Visits = {
    visitId: 0,
    currentList: null,
    init : function() {
        $( "#date" ).datepicker({
            defaultDate: "0d",
            changeMonth: true,
            dateFormat: "dd/mm/yy",
            showButtonPanel: true,
            currentText: "Hoy",
            numberOfMonths: 1,
            onClose: function( selectedDate ) {
                //$( "#to" ).datepicker( "option", "minDate", selectedDate );
            }
        });
        $('.cancelButton').click(function(event){
            $.fancybox.close();
        });
        $("#newVisit").click(function(e){
            if (!Tecnotek.UI.vars['opening-edit']) {
                Tecnotek.Visits.visitId = 0;
                $("#date").val("");
                $("#comments").val("");
                $("#people").val("");
                $("#observations").val("");
                $("#modalTitle").html(Tecnotek.UI.translates['creating-visit']);
                Tecnotek.Visits.enableForm();
            }
            Tecnotek.UI.vars['opening-edit'] = false;
        });
        $('#createVisitForm').submit(function(event){
            event.preventDefault();
            Tecnotek.Visits.save();
        });
        $('#searchText').keyup(function(event){
            Tecnotek.UI.vars["page"] = 1;
            Tecnotek.Students.searchVisits();
        });
        $('#btnSearch').unbind().click(function(event){
            Tecnotek.Students.searchVisits();
        });
        $(".sort_header").click(function() {
            Tecnotek.UI.vars["sortBy"] = $(this).attr("field-name");
            Tecnotek.UI.vars["order"] = $(this).attr("order");
            console.debug("Order by " + Tecnotek.UI.vars["sortBy"] + " " + Tecnotek.UI.vars["order"]);
            $(this).attr("order", Tecnotek.UI.vars["order"] == "asc"? "desc":"asc");
            $(".header-title").removeClass("asc").removeClass("desc").addClass("sortable");
            $(this).children().addClass(Tecnotek.UI.vars["order"]);
            Tecnotek.Visits.searchVisits();
        });
        Tecnotek.UI.vars["order"] = "asc";
        Tecnotek.UI.vars["sortBy"] = "v.date";
        Tecnotek.UI.vars["page"] = 1;
        Tecnotek.Visits.searchVisits();
    },
    searchVisits: function() {
        $("#visits-container").html("");
        $("#pagination-container").html("");
        Tecnotek.showWaiting();
        Tecnotek.uniqueAjaxCall(Tecnotek.UI.urls["search"],
            {
                text: ""/*$("#searchText").val()*/,
                sortBy: Tecnotek.UI.vars["sortBy"],
                order: Tecnotek.UI.vars["order"],
                page: Tecnotek.UI.vars["page"],
                studentId: Tecnotek.UI.vars["student-id"]
            },
            function(data){
                if(data.error === true) {
                    Tecnotek.hideWaiting();
                    Tecnotek.showErrorMessage(data.message,true, "", false);
                } else {
                    var baseHtml = $("#visitRowTemplate").html();
                    var editButtonHtml = $("#editButtonTemplate").html();
                    var visits = new Array();
                    for (i=0; i<data.visits.length; i++) {
                        visits[data.visits[i].id] = data.visits[i];
                        var row = '<div class="row userRow ROW_CLASS" rel="VISIT_ID">' + baseHtml;
                        var creatorId = data.visits[i]['user_id'];
                        if (Tecnotek.UI.vars["user-id"] == creatorId) {
                            row += editButtonHtml;
                        }
                        row += '</div>';
                        row = row.replaceAll('ROW_CLASS', (i % 2 == 0? 'tableRowOdd':'tableRow'));
                        row = row.replaceAll('VISIT_ID', data.visits[i].id);
                        row = row.replaceAll('VISIT_DATE', data.visits[i].date);
                        row = row.replaceAll('VISIT_CREATOR', data.visits[i].creator);
                        row = row.replaceAll('VISIT_PEOPLE', data.visits[i].people);
                        row = row.replaceAll('VISIT_COMMENTS', data.visits[i].comments);
                        $("#visits-container").append(row);
                    }
                    Tecnotek.Visits.currentList = visits;
                    Tecnotek.Visits.initButtons();
                    Tecnotek.UI.printPagination(data.total, data.filtered, Tecnotek.UI.vars["page"], 10, "pagination-container");
                    $(".paginationButton").unbind().click(function() {
                        Tecnotek.UI.vars["page"] = $(this).attr("page");
                        Tecnotek.Visits.searchVisits();
                    });
                    Tecnotek.hideWaiting();
                }
            },
            function(jqXHR, textStatus){
                if (textStatus != "abort") {
                    Tecnotek.hideWaiting();
                    console.debug("Error getting data: " + textStatus);
                }
            }, true, 'searchStudents');
    },
    initButtons: function () {
        $(".viewButton").unbind().click(function (e) {
            Tecnotek.UI.vars['opening-edit'] = true;
            var visitId = $(this).attr("rel");
            Tecnotek.Visits.putValuesInForm(visitId);
            Tecnotek.Visits.disableForm();
            $("#modalTitle").html(Tecnotek.UI.translates['viewing-visit']);
            $("#newVisit").click();
        });
        $(".editButton").unbind().click(function (e) {
            Tecnotek.UI.vars['opening-edit'] = true;
            var visitId = $(this).attr("rel");
            Tecnotek.Visits.putValuesInForm(visitId);
            Tecnotek.Visits.enableForm();
            $("#modalTitle").html(Tecnotek.UI.translates['editing-visit']);
            $("#newVisit").click();
        });
    },
    putValuesInForm: function(visitId) {
        var visit = Tecnotek.Visits.currentList[visitId];
        Tecnotek.Visits.visitId = visitId;
        $("#date").val(visit.date);
        $("#comments").val(visit.comments);
        $("#people").val(visit.people);
        $("#observations").val(visit.observations);
    },
    enableForm: function() {
        $("#date").removeAttr("disabled");
        $("#comments").removeAttr("disabled");
        $("#people").removeAttr("disabled");
        $("#observations").removeAttr("disabled");
        $("#saveVisitBtn").show();
        $("#cancelBtn").show();
        $("#closeBtn").hide();
    },
    disableForm: function(){
        $("#date").attr("disabled", "disabled");
        $("#comments").attr("disabled", "disabled");
        $("#people").attr("disabled", "disabled");
        $("#observations").attr("disabled", "disabled");
        $("#saveVisitBtn").hide();
        $("#cancelBtn").hide();
        $("#closeBtn").show();
    },
    save : function(){
        /*if(Tecnotek.UI.vars["currentPeriod"] == 0){
            Tecnotek.showErrorMessage("Es necesario definir un periodo como actual antes de guardar.",true, "", false);
            return;
        }
        var $studentId = $("#studentId").val();

        var $type = $("#typeId").val();*/
        var $date = $("#date").val();
        var $comments = $("#comments").val();
        var $people = $("#people").val();
        var $observations = $("#observations").val();
        if ($comments === "") {
            Tecnotek.showErrorMessage("Debe incluir un comentario.", true, "", false);
        } else if ($people === ""){
            Tecnotek.showErrorMessage("Debe incluir las personas presentes.", true, "", false);
        } else if ($date === ""){
            Tecnotek.showErrorMessage("Debe incluir la fecha.", true, "", false);
        } else {
            Tecnotek.ajaxCall(Tecnotek.UI.urls["saveVisit"],
                {   studentId: Tecnotek.UI.vars["student-id"],
                    date: $date,
                    visitId: Tecnotek.Visits.visitId,
                    comments: $comments,
                    people: $people,
                    observations: $observations
                },
                function(data){
                    if(data.error === true) {
                        Tecnotek.showErrorMessage(data.message,true, "", false);
                    } else {
                        $.fancybox.close();
                        Tecnotek.showInfoMessage("La visita se ha ingresado correctamente.", true, "", false);
                        Tecnotek.Visits.searchVisits();
                    }
                },
                function(jqXHR, textStatus){
                    Tecnotek.showErrorMessage("Error saving visita: " + textStatus + ".",
                        true, "", false);
                }, true);
        }
    }
};