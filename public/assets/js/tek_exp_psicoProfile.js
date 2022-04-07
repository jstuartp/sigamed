var Tecnotek = Tecnotek || {};

Tecnotek.psicoProfile = {
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
        Tecnotek.psicoProfile.initButtons();
    },
    initButtons : function() {
        $('.btnSubmitForm').click(function(event){
            event.preventDefault();
            Tecnotek.psicoProfile.submitForm($(this).attr('rel'));
            //alert("Submit Form: " + $(this).attr('rel'));
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
    },
    submitForm: function($formName) {
        var form = $("#" + $formName);
        Tecnotek.ajaxCall(Tecnotek.UI.urls["savePsicoFormUrl"],
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
