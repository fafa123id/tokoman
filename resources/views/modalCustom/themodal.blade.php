<?php
$theVal = $theVal ?? '';
?>
<div id="modalYakin{{$theVal}}" class="hidden">
    @include('layouts.modalYakin', ['message' => $message, 'form' => $form, 'parrent' => 'modalYakin'.$theVal])
</div>
<div id="modalAlert{{$theVal}}" class="hidden">
    @include('layouts.modalAlert', ['message' => 'Data Tidak Valid', 'parrent' => 'modalAlert'.$theVal])
</div>

<script>
    function validasiForm{{$theVal}}() {
        if (document.querySelector('#{{$form}}').checkValidity()) {
            document.getElementById('modalYakin{{$theVal}}').classList.remove('hidden');
        } else {
            document.getElementById('massage').innerHTML = getFirstValidationError(document.querySelector('#{{$form}}'));
            document.getElementById('modalAlert{{$theVal}}').classList.remove('hidden');
        }

    }
    function getFirstValidationError(form) {
        for (let element of form.elements) {
            if (element.willValidate && !element.checkValidity()) {
                return 'Field ' + element.name + ': ' + element.validationMessage;
            }
        }
        return null;
    }
</script>