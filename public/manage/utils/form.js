import swal from '../plugins/sweetalert2.js'

function init () {
    initConfirmSubmit()
    initFileInputPreview()
}

function initConfirmSubmit () {
    const $form = $('.m-form[data-confirm]')
    if (!$form.length) {
        return
    }

    let confirm = swal.confirm
    switch ($form.data('confirm-type')) {
        case 'warning':
            confirm = swal.confirmWarning
            break

        case 'danger':
            confirm = swal.confirmDanger
            break

        case 'delete':
            confirm = swal.confirmDelete
            break
    }

    $form.on('submit', async (event) => {
        event.preventDefault()

        const $form = $(event.target)

        const result = await confirm({
            title: $form.data('confirm-title'),
            text: $form.data('confirm-text')
        })
        if (result.value) {
            event.currentTarget.submit()
        }
    })
}

function initFileInputPreview () {
    const $input = $(':file[data-file-preview]')
    if (!$input.length) {
        return
    }

    const $preview = $($input.data('file-preview') || $input.prev('.file-input-preview'))
    $preview.on('click', (event) => $input.click())

    $input.on('change', (event) => {
        const files = event.target.files
        if (!files.length) {
            $preview.attr('src', $preview.data('placeholder'))
            return
        }

        const reader = new FileReader()
        reader.onload = (event) => $preview.find('img').attr('src', event.target.result)
        reader.readAsDataURL(files[0])
    });
}

export default {
    init
}
