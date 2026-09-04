swal({
    title: "{{ trans('messages.Data saved!') }}",
    text: "{{ trans('messages.The data you sent was correctly saved!') }}",
    timer: 3000,
    showConfirmButton: true,
    confirmButtonColor: '#2196f3',
    type: 'success',
    html: true,
    closeOnConfirm: false
});
