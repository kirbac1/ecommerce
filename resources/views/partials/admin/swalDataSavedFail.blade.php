swal({
    title: "{{ trans('messages.Data NOT saved!') }}",
    text: "{{ trans('messages.There was a problem saving the data! The data you sent was NOT SAVED!') }}",
    timer: 10000,
    showConfirmButton: true,
    type: 'error',
    html: true,
    closeOnConfirm: false
});
