var Tecnotek = Tecnotek || {};

Tecnotek.PrintQualifications = {
    translates : {},
    init : function() {
        $('#btnPrint').click(function(event){
            console.debug("print!!!");
            $("#tablaCalificacion").printElement({printMode:'iframe', pageTitle:$(this).attr('rel')});
        });

        Tecnotek.Qualifications.initializeTable();
        $(".noPrint").hide();
        Tecnotek.UI.vars["forzeBlur"] = true;
        $(".textField").each(function(){
            var value = $(this).attr("val");
            if(value.indexOf("val") > -1 ){
                $(this).val("");
            } else {
                $(this).val(value);
            }
            $(this).trigger("focus");
            $(this).trigger("blur");
        });
        Tecnotek.UI.vars["forzeBlur"] = false;
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
                    $("#total_trim_" + $stdId).html("" + Tecnotek.roundTo($sum));
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
};