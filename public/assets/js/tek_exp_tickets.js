var Tecnotek = Tecnotek || {};

Tecnotek.TicketsSearch = {
    translates : {},
    init : function() {
        $("#searchByStudent").change(function(){
            $this = $(this);
            if($this.is(':checked')){
                $("#" + $this.attr("rel")).removeAttr("disabled");
            } else {

                $("#" + $this.attr("rel")).val("").attr("disabled",true);
            }
        });
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
                            $("#new-relative-btn").hide();
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

                                Tecnotek.Tickets.loadStudentRelatives();
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
