import $ from 'jquery';

class Form {
    constructor($form, successFunc) {
        this.$form = $form;
        this.$form.submit(this.onSubmit.bind(this));
        this.successFunc = successFunc ? successFunc : (data) => {};
    }

    onSubmit() {
        const $form = this.$form, successFunc = this.successFunc;
        const action  = $form.attr('action');
        const formData = $form.serialize();
        $form.find('.is-invalid').removeClass('is-invalid');
        $form.find('.form-control').attr('disabled', 'disabled');
        $form.find('.btn').attr('disabled', 'disabled');
        $form.find('.invalid-feedback').remove();
        $.post(action, formData, function(data) {
            if (!('success' in data)) {
                console.log('Invalid response', data);
                return;
            }
            if (data.success) {
                console.log(successFunc);
                successFunc(data);
                if ('message' in data) {
                    alert(data.message);
                }
                if ('location' in data) {
                    document.location = data.location;
                }
            } else {
                if ('errors' in data && data.errors.length > 0) {
                    for (const error of data.errors) {
                        const $field = $form.find('#' + error['field']);
                        $field.addClass('is-invalid');
                        $field.after('<div class="invalid-feedback">'+error['error']+'</div>');
                    }
                }
            }

        })
        .fail(function() {
            alert('Invalid response');
        })
        .always(function() {
            $form.find('.form-control').removeAttr('disabled');
            $form.find('.btn').removeAttr('disabled');
        });
        return false;
    }
}

export default function ($form, successFunc) {
    return new Form($form, successFunc);
}
