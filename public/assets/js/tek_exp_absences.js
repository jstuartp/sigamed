var Tecnotek = Tecnotek || {};


Tecnotek.Absences = {
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

        $("#from").datepicker('setDate', new Date()).keypress(function(event){event.preventDefault();});
        $("#to").datepicker('setDate', new Date()).keypress(function(event){event.preventDefault();});

        $("#searchByStudent").change(function(){
            $this = $(this);
            if($this.is(':checked')){
                $("#" + $this.attr("rel")).removeAttr("disabled");
            } else {

                $("#" + $this.attr("rel")).val("").attr("disabled",true);
            }
        });

        $('#createAbsenceForm').submit(function(event){
            event.preventDefault();
            Tecnotek.Absences.save();
        });

        $('.cancelButton').click(function(event){
            $.fancybox.close();
        });
        $('#searchBox').focus(function(event){
            $("#tecnotek_expediente_absenceformtype_student").val(0);
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
                                    data.students[i].firstname + ' ' + data.students[i].lastname + '">';
                                $data += '      <span class="searchheading">' + data.students[i].firstname
                                    + ' ' + data.students[i].lastname +  '</span>';
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
                                $('#searchBox').val("");
                                $('#newAbsence').trigger('click');

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

        $(".deleteButton").click(function(event){
            event.preventDefault();
            Tecnotek.Absences.delete($(this).attr("rel"));
        });

        $('#btnPrint').click(function(event){
            $("#report").printElement({printMode:'popup', pageTitle:$(this).attr('rel')});
        });
    },
    delete : function(absenceId){
        //TODO delete Absence
        if (Tecnotek.showConfirmationQuestion(Tecnotek.UI.translates["confirmDelete"])){
            location.href = Tecnotek.UI.urls["deleteURL"] + "/" + absenceId;
        }
    },
    save : function(){
        if(Tecnotek.UI.vars["currentPeriod"] == 0){
            Tecnotek.showErrorMessage("Es necesario definir un periodo como actual antes de guardar.",true, "", false);
            return;
        }
        var $studentId = $("#studentId").val();
        var $date = $("#date").val();
        var $type = $("#typeId").val();
        var $justify = $("#justify").is(':checked');
        var $comments = $("#comments").val();

        if($comments === ""){
            Tecnotek.showErrorMessage("Debe incluir un comentario.",
                true, "", false);
        } else {
            Tecnotek.ajaxCall(Tecnotek.UI.urls["saveAbsenceURL"],
                {studentId: $studentId,
                    date: $date,
                    type: $type,
                    justify: $justify,
                    comments: $comments,
                    periodId: Tecnotek.UI.vars["currentPeriod"]
                },
                function(data){
                    if(data.error === true) {
                        Tecnotek.showErrorMessage(data.message,true, "", false);
                    } else {
                        $.fancybox.close();
                        Tecnotek.showInfoMessage("La ausencia se ha ingresado correctamente.", true, "", false)
                    }
                },
                function(jqXHR, textStatus){
                    Tecnotek.showErrorMessage("Error saving absence: " + textStatus + ".",
                        true, "", false);
                }, true);
        }

    }
};

Tecnotek.AbsencesTypes = {
    init : function() {
        $('#createForm').submit(function(event){

            var error = "";

            console.debug($("#name").val() + " --- " + $.trim($("#name").val()) + " --- " + ($.trim($("#name").val()) === "") );
            if($.trim($("#name").val()) === ""){
                error="El nombre no puede estar vacio.<br/>";
            }

            var errorIns = "";
            $(".institution").each(function(){
                if($(this).val() === ""){
                    errorIns = "Es necesario definir los puntos para todas las instituciones.";
                }
            });

            error += errorIns;
            if(error !== ""){
                event.preventDefault();
                Tecnotek.showErrorMessage(error,true, "", false);
            }
        });
    }
};