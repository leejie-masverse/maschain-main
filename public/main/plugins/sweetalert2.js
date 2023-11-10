const styledSwal = swal.mixin({
  width: 400,
  padding: '2.5rem',
  buttonsStyling: false,
  confirmButtonClass: 'btn btn-success m-btn m-btn--custom',
  confirmButtonColor: null,
  cancelButtonClass: 'btn btn-secondary m-btn m-btn--custom',
  cancelButtonColor: null
})

const confirmSwal = styledSwal.mixin({
  type: 'warning',
  confirmButtonText: 'Confirm',
  confirmButtonClass: 'btn btn-brand m-btn m-btn--custom',
  showCancelButton: true,
  cancelButtonText: 'Close',
  animation: false,
  customClass: 'animated animated--fast zoomIn',
  allowOutsideClick: () => !swal.isLoading()
})

const confirmWarningSwal = confirmSwal.mixin({
    confirmButtonClass: 'btn btn-warning m-btn m-btn--custom'
})

const confirmDangerSwal = confirmSwal.mixin({
    confirmButtonClass: 'btn btn-danger m-btn m-btn--custom'
})

const confirmDeleteSwal = confirmDangerSwal.mixin({
  confirmButtonText: 'Delete',
})

export default {
    confirm: confirmSwal,
    confirmWarning: confirmWarningSwal,
    confirmDanger: confirmDangerSwal,
    confirmDelete: confirmDeleteSwal
}
