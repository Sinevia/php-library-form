namespace Sinevia;

class Form {

    public static function formBuild($fields, $options = []) {
        $submitText = isset($options['submit.text']) ? $options['submit.text'] : 'Submit';
        $formContainer = (new \Sinevia\Html\Div)
                ->setClass('row');

        foreach ($fields as $field) {
            $type = trim($field['type'] ?? null);
            $name = trim($field['name'] ?? null);
            $value = $field['value'] ?? req($name, old($name));
            $options = $field['options'] ?? [];
            $disabled = $field['disabled'] ?? false;
            $readonly = $field['readonly'] ?? false;
            $label = $field['label'] ?? $name;
            $width = $field['width'] ?? 12;
            $html = trim($field['html'] ?? null); // for "html" fields only

            if ($type == 'html') {
                $formContainer->addChild($html);
                continue;
            }

            if ($name == "") {
                continue;
            }

            if ($type == "") {
                continue;
            }

            $value = req($name, old($name, $value));

            $formGroup = (new \Sinevia\Html\Div)->setClass('form-group float-left col-sm-' . $width);

            $label = (new \Sinevia\Html\Label)->addChild($label);

            $input = 'n/a';
            $hiddenInput = null; // For readonly selects only

            if ($type == 'password') {
                $input = (new \Sinevia\Html\Input)
                        ->setClass('form-control')
                        ->setName($name)
                        ->setValue($value)
                        ->setType('password');
            }

            if ($type == 'select') {
                $input = (new \Sinevia\Html\Select)
                        ->setClass('form-control')
                        ->setName($name);
                //->setValue($value);
                foreach ($options as $optionKey => $optionValue) {
                    $selected = $optionKey == $value ? true : false;
                    $input->item($optionKey, $optionValue, $selected);
                }
            }

            if ($type == 'text') {
                $input = (new \Sinevia\Html\Input)
                        ->setClass('form-control')
                        ->setName($name)
                        ->setValue($value);
            }

            if ($type == 'textarea') {
                $input = (new \Sinevia\Html\Textarea)
                        ->setClass('form-control')
                        ->setName($name)
                        ->setValue($value);
            }

            if (is_object($input) AND $disabled == true) {
                $input->setAttribute('disabled', 'disabled');
            }

            if (is_object($input) AND $readonly == true) {
                // Selects are different. Readonly for selects does not work.
                // Disable and create a hidden field
                if ($type == "select") {
                    $input->setAttribute('disabled', 'disabled');
                    $input->setName($name . '_Readonly');
                    $hiddenInput = (new \Sinevia\Html\Input())
                            ->setClass('form-control')
                            ->setName($name)
                            ->setValue($value)
                            ->setType('hidden');
                } else {
                    $input->setAttribute('readonly', 'readonly');
                }
            }

            $formGroup->addChild($label);
            $formGroup->addChild($input);
            if (is_null($hiddenInput) == false) {
                $formGroup->addChild($hiddenInput);
            }

            $formContainer->addChild($formGroup);
        }

        $buttonSave = (new \Sinevia\Html\Button())
                ->setClass('btn btn-success')
                ->setType('submit')
                ->setText($submitText);

        $formGroup = (new \Sinevia\Html\Div)->setClass('form-group float-left col-sm-12');
        $formGroup->addChild($buttonSave);
        $formContainer->addChild($formGroup);

//        $csrfField = (new \Sinevia\Html\Input)
//                ->setName('_token')
//                ->setValue(csrf_token())
//                ->setType(\Sinevia\Html\Input::TYPE_HIDDEN);
//        $form->addChild($csrfField);
        $form = (new \Sinevia\Html\Form)->setMethod('POST');
        $form->addChild($formContainer);
        return $form;
    }

}
