var Tecnotek = Tecnotek || {};

Tecnotek.Questionnaires = {
    translates : {},
    completeText: "",
    studentsIndex: 0,
    periodId: 0,
    groupId: 0,
    studentsLength: 0,
    init : function() {
        console.debug("Init Questionnaires Page");
        $(".q-group").change(function(event){
            event.preventDefault();
            $groupId = $(this).val();
            $q = $(this).attr('rel');
            Tecnotek.Questionnaires.saveQuestionnaireConfig('group', $q, $groupId);
        });

        $(".q-teacher").change(function(event){
            event.preventDefault();
            $val = $(this).val();
            $q = $(this).attr('rel');
            Tecnotek.Questionnaires.saveQuestionnaireConfig('teacher', $q, $val);
        });

        $(".q-inst").change(function(event){
            event.preventDefault();
            $q = $(this).attr('rel');
            $inst = $(this).attr('inst');
            if($(this).is(':checked')){
                $val = $inst + "-" + 1;
            } else {
                $val = $inst + "-" + 0;
            }
            Tecnotek.Questionnaires.saveQuestionnaireConfig('institution', $q, $val);
        });
    },
    saveQuestionnaireConfig: function($field, $q, $val) {
        console.debug("Save config of questionnaire: " + $q + " for field: " +
            $field + " with value: " + $val);

        Tecnotek.ajaxCall(Tecnotek.UI.urls["save"],
            {   field:  $field,
                q:      $q,
                val:    $val},
            function(data){
                if(data.error === true) {
                    Tecnotek.showErrorMessage(data.message,true, "", false);
                } else {
                }
            },
            function(jqXHR, textStatus){
                Tecnotek.showErrorMessage("Error getting data: " + textStatus + ".", true, "", false);
                $(this).val("");
            }, true);
    }
};