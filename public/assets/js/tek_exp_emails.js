var Tecnotek = Tecnotek || {};

Tecnotek.Emails = {
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
            Tecnotek.Emails.loadPeriodLevels($(this).val());
        });

        $("#levels").change(function(event){
            event.preventDefault();
            //: function($periodId, $levelId)
            Tecnotek.Emails.loadGroupsOfPeriodAndLevel($('#period').val(), $(this).val());
        });

        $("#groups").change(function(event){
            event.preventDefault();
            $("#emails-ta").val("");
        });


        $("#attachmentFile").change(function(event){
            Tecnotek.UI.vars["attachment"] = this.files[0];
            console.debug("File updated: " + Tecnotek.UI.vars["attachment"]);
        });
/*
        $("#copyBtn").click(function(e){
            var ta =
            Copied = $("#emails-ta".val()).createTextRange();
            Copied.execCommand("Copy");
        });*/

        $("#emailForm").submit(function(e) {
            e.preventDefault();
            e.stopPropagation();
            //Tecnotek.Emails.sendEmail();
            Tecnotek.Emails.uploadFile();
            return false;
        });
        Tecnotek.Emails.loadPeriodLevels($('#period').val());
        Tecnotek.Emails.initButtons();
    },
    initButtons : function() {
        $('#btnLoad').click(function(event){
            Tecnotek.Emails.loadEmails();
        });
    },
    loadPeriodLevels: function($periodId){
        if(($periodId!==null)){
            $('#levels').children().remove();
            $('#groups').children().remove();
            $("#emails-ta").val("");
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
                        Tecnotek.Emails.loadGroupsOfPeriodAndLevel($('#period').val(), $('#levels').val());
                    }
                },
                function(jqXHR, textStatus){
                    Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                    $(this).val("");
                }, true);
        }
    },
    loadGroupsOfPeriodAndLevel: function($periodId, $levelId) {
        if(($periodId!==null)){
            $('#groups').children().remove();
            $("#emails-ta").val("");
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
    loadEmails: function() {
        $("#emails-ta").val("");
        Tecnotek.ajaxCall(Tecnotek.UI.urls["loadEmailsURL"],
            {   periodId:   $('#period').val(),
                levelId:    $('#levels').val(),
                groupId:    $('#groups').val()},
            function(data){
                if(data.error === true) {
                    Tecnotek.showErrorMessage(data.message,true, "", false);
                } else {
                    /*for(i=0; i<data.emails.length; i++) {
                        $("#emails-ta").val(data.emails[i].emails);
                    }*/
                    $("#emails-ta").val(data.emails);
                }
            },
            function(jqXHR, textStatus){
                Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                $(this).val("");
            }, true);
    },
    sendEmail: function() {
        //Tecnotek.UI.urls["sendEmailsURL"]
        var $subject = $("#subject").val().trim();
        var $content = tinymce.get('mail-content').getContent();
        var $emails = $("#emails-ta").val().trim();
        if ($emails == "") {
            Tecnotek.showErrorMessage("Antes de enviar un correo debe obtener una lista de correos en la parte superior", true, '', false);
            return;
        }
        if ($subject == "" || $content == "") {
            Tecnotek.showErrorMessage("Por favor defina el asunto y el contenido", true, '', false);
            return;
        }
        Tecnotek.ajaxCall(Tecnotek.UI.urls["sendEmailsURL"],
            {   emails:     $emails,
                subject:    $subject,
                body:       $content},
            function(data){
                if(data.error === true) {
                    Tecnotek.showErrorMessage(data.message,true, "", false);
                } else {
                    Tecnotek.showInfoMessage("El correo electr�nico se ha enviado a la lista seleccionada correctamente",
                        true, '', false);
                }
            },
            function(jqXHR, textStatus){
                Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                $(this).val("");
            }, true);

    },
    uploadFile: function() {
        // + '?name=' + Tecnotek.UI.vars["attachment"].name
        var $form    = $("#emailForm"),
            formData = new FormData(),
            params   = $form.serializeArray(),
            files    = $form.find('[name="attachmentFile"]')[0].files;

        $.each(files, function(i, file) {
            // Prefix the name of uploaded files with "uploadedFiles-"
            // Of course, you can change it to any string
            formData.append('attachment-' + i, file);
        });

        var $subject = $("#subject").val().trim();
        var $content = tinymce.get('mail-content').getContent();
        var $emails = $("#emails-ta").val().trim();
        var $extraEmails = $("#extraEmails").val().trim();
        if ($emails == "" && $extraEmails == "") {
            Tecnotek.showErrorMessage("Antes de enviar un correo debe obtener una lista de correos en la parte superior" +
                " o definir uno o varios correos adicionales a los que enviar el mensaje", true, '', false);
            return;
        }
        if ($subject == "" || $content == "") {
            Tecnotek.showErrorMessage("Por favor defina el asunto y el contenido", true, '', false);
            return;
        }
        formData.append('extraEmails', $extraEmails);
        formData.append('emails', $emails);
        formData.append('subject', $subject);
        formData.append('body', $content);
        /*$.each(params, function(i, val) {
            formData.append(val.name, val.value);
        });*/
        Tecnotek.showWaiting();
        $.ajax({
            type: 'POST',
            url: Tecnotek.UI.urls["sendEmailsURL"],
            data: formData,
            cache: false,
            success: function (data) {
                data= jQuery.parseJSON( data );
                Tecnotek.hideWaiting();
                if(data.error === true) {
                    Tecnotek.showErrorMessage(data.message,true, "", false);
                } else {
                    Tecnotek.showInfoMessage(data.message, true, '', false);
                }
            },
            xhrFields: {
                // add listener to XMLHTTPRequest object directly for progress (jquery doesn't have this yet)
                onprogress: function (progress) {
                    // calculate upload progress
                    var percentage = Math.floor((progress.total / progress.totalSize) * 100);
                    // log upload progress to console
                    //console.log('progress', percentage);
                    if (percentage === 100) {
                        //console.log('DONE!');
                    }
                }
            },
            processData: false,
            contentType: false
            /*contentType: Tecnotek.UI.vars["attachment"].type*/
        });
    }
};