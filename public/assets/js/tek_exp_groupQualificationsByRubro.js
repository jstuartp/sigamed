var Tecnotek = Tecnotek || {};

Tecnotek.GroupQualificationsByRubro = {
    translates : {},
    init : function() {

        $("#period").change(function(event){
            event.preventDefault();
            $('#subentryFormParent').empty();
            Tecnotek.GroupQualificationsByRubro.loadGroupsOfPeriod($(this).val());
        });

        Tecnotek.GroupQualificationsByRubro.loadGroupsOfPeriod($('#period').val());
        Tecnotek.GroupQualificationsByRubro.initButtons();
    },
    initButtons : function() {
        $('#btnPrint').click(function(event){
            $("#tablaCalificacion").printElement({printMode:'popup', pageTitle:$(this).attr('rel')});
        });

        $('#btnGenerate').click(function(event){
            Tecnotek.GroupQualificationsByRubro.generateReport();
        });
    },
    loadGroupsOfPeriod: function($periodId) {
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
                    }
                },
                function(jqXHR, textStatus){
                    Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                    $(this).val("");
                }, true);
        }
    },
    generateReport: function() {
        var groupId = $('#groups').val();
        if( groupId === null ){
            Tecnotek.showErrorMessage("No es posible generar el reporte sin un grupo seleccionado.", true, "", false);
        } else {
            var rubro = $("#entry").val();
            if( rubro.trim() === ""){
                Tecnotek.showErrorMessage("No es posible generar el reporte sin definir un rubro.", true, "", false);
            } else {
                $('#tableContainer').hide();
                $('#fountainG').show();
                Tecnotek.ajaxCall(Tecnotek.UI.urls["loadQualificationsOfGroupURL"],
                    {   periodId: $("#period").val(),
                        code: rubro,
                        groupId: $("#groups").val()},
                    function(data){
                        $('#fountainG').hide();
                        if(data.error === true) {
                            Tecnotek.showErrorMessage(data.message,true, "", false);
                        } else {
                            if(data.html === ""){
                                Tecnotek.showInfoMessage("No se encontro informacion del rubro: " + rubro, true);
                            } else {
                                var tableHeader = "";

                                tableHeader += '<div class="reportContentHeader">';
                                tableHeader += '<div class="left reportContentLabel">Periodo:</div><div class="left reportContentText">' + $("#period").find(":selected").text() + '</div><div class="clear"></div>';
                                tableHeader += '<div class="left reportContentLabel">Grado y Grupo:</div><div class="left reportContentText">' + $("#groups").find(":selected").text() + '</div><div class="clear"></div>';
                                //tableHeader += '<div class="left reportContentLabel">Estudiante:</div><div class="left reportContentText">' + $("#students").find(":selected").text() + '</div><div class="clear"></div>';
                                tableHeader += "</div>";
                                //
                                $('#contentHeader').html(tableHeader);
                                $('#contentBody').html(data.html);
                                $('#tableContainer').show();
                            }
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
};
