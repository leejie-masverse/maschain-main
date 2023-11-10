import form from './utils/form.js'
import theme from './utils/theme.js'
// import orderForm from './components/orderForm.js'
// import quickSidebar from './components/quickSidebar.js'
// import ingredientQuantityTable from './components/ingredientQuantityTable.js'
// import enquiryForm from './components/enquiryForm.js'
import dateRange from './components/dateRange.js'
// import dishOptions from './components/dishOptions.js'
import productForm from './components/productForm.js'
import orderForm from './components/orderForm.js'


$(document).ready(() => {
    theme.init();
    form.init();

    // orderForm.init()
    // quickSidebar.init()
    // enquiryForm.init()
    // dateRange.init()
    // dishOptions.init()
    // ingredientQuantityTable.init()

    //vendor
    dateRange.init();
    initQuickSidebar();
    initSelect2Dropdown();
    initCKEditor();
    initBootstrapSwitch();
    initDropZone();

    //components
    productForm.init();
    orderForm.init();

    initBookingSummaryCalendar();

    checkAdminForm();

    $('input.form_admin_role').on('click', function (e) {
        var $role = $(e.target);
        var role = $role.val();

        if (role === "system administrator") {
            $("#form_admin_abilities_section").show();
        } else {
            $("#form_admin_abilities_section").hide();
        }
    });

    $('#select_by_category').on('change', function (e) {
        var $checkbox = $(e.target);
        if ($checkbox.is(":checked")) {
            $("#product_category_section").show();
        } else {
            $("#product_category_section").hide();
        }
    });

    $('select[name=category_product]').on('change', function (e) {
        $("#reward_product option:selected").prop("selected", false).change();
        var selectedCategoryProduct = $(e.target).children("option:selected").data('product');
        selectedCategoryProduct.forEach(element => {
                $('#reward_product option[value=' + element + ']').prop('selected', 'selected').change();
            }
        );
    });

    $('select[name=venue_id]').on('change', function (e) {

        $('#session_date_div').show();
    });

    $('input[name=session_date]').on('change', function (e) {
        var selectedVenueId = $("select[name=venue_id]").children("option:selected").val();
        $('#venues-' + selectedVenueId).show();
    });

    $('input[name=specificDate]').on('click', function () {
        if ($(this).data('target') === 'date_range') {
            $('#dateRange').prop('hidden', false);
        } else {
            $('#dateRange').prop('hidden', true);
            $('#dateFrom').val('');
            $('#dateBefore').val('');
        }
        if ($(this).hasClass('radioChecked')) {
            $(this).prop('checked', false);
            $(this).removeClass('radioChecked');
            $('#dateRange').prop('hidden', true);
            $('#dateFrom').val('');
            $('#dateBefore').val('');
        } else {
            $('input[name=specificDate]').removeClass('radioChecked');
            $(this).addClass('radioChecked');
        }
    })

    $('input[name=export_date]').on('change', function (e) {
        $('#export_booking_button').show();
        var selectedDate = $(e.target).val();
        $('#booking_export_date').val(selectedDate);

    })
    // $('input[name=specificDate]').on('click', function() {
    //     if ($(this).data('target')==='date_range'){
    //         $('#dateRange').prop('hidden',false);
    //     }else{
    //         $('#dateRange').prop('hidden',true);
    //         $('#dateFrom').val('');
    //         $('#dateBefore').val('');
    //     }
    //     if(  $(this).hasClass('radioChecked')){
    //         $(this).prop('checked',false);
    //         $(this).removeClass('radioChecked');
    //         $('#dateRange').prop('hidden',true);
    //         $('#dateFrom').val('');
    //         $('#dateBefore').val('');
    //     }else{
    //         $('input[name=specificDate]').removeClass('radioChecked');
    //         $(this).addClass('radioChecked');
    //     }
    // })
});

function initBookingSummaryCalendar() {
    // var calendarEl = document.getElementById('booking_summary_calendar');

}

function checkAdminForm() {
    var $role = $("input.form_admin_role:checked");
    console.log($role)
    if ($role !== null && $role !== undefined) {
        var role = $role.val();
        console.log(role)
        if (role === "system administrator") {
            $("#form_admin_abilities_section").show();
        } else {
            $("#form_admin_abilities_section").hide();
        }
    }
}

function initQuickSidebar() {
    const $quickSidebarToggler = $('[data-quick-sidebar]');
    if (!$quickSidebarToggler.length) {
        return
    }

    const quickSidebarId = $quickSidebarToggler.data('quick-sidebar')

    new mOffcanvas(quickSidebarId, {
        overlay: true,
        baseClass: 'm-quick-sidebar',
        closeBy: `${quickSidebarId}__close`,
        toggleBy: `${quickSidebarId}-toggler`
    });
}

function initSelect2Dropdown() {
    $(".select2").each(function () {
        if (!$(this).hasClass("select2-hidden-accessible") && $(this).data('prevent-default-init') !== 'true') {
            console.log('default');
            $(this).select2({
                width: '100%',
                placeholder: {
                    id: '',
                    text: '--------'
                },
                allowClear: true,
                theme: "bootstrap",
                closeOnSelect: false,
            });
        }
    })
}

function initCKEditor() {

    $(".ckeditor4").each(function () {
        // NOTES: Can be configured through here https://ckeditor.com/latest/samples/toolbarconfigurator/index.html#basic
        // CKEDITOR.editorConfig = function( config ) {
        //     config.toolbarGroups = [
        //         { name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
        //         { name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
        //         { name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
        //         { name: 'forms', groups: [ 'forms' ] },
        //         { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
        //         { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
        //         '/',
        //         { name: 'links', groups: [ 'links' ] },
        //         { name: 'styles', groups: [ 'styles' ] },
        //         { name: 'insert', groups: [ 'insert' ] },
        //         { name: 'colors', groups: [ 'colors' ] },
        //         { name: 'about', groups: [ 'about' ] },
        //         '/',
        //         { name: 'tools', groups: [ 'tools' ] },
        //         { name: 'others', groups: [ 'others' ] }
        //     ];
        //
        //     config.removeButtons = 'Source,Save,Templates,Cut,Undo,Find,SelectAll,Scayt,Form,Unlink,Anchor,Image,Flash,Table,Smiley,HorizontalRule,SpecialChar,Iframe,PageBreak,Blockquote,CreateDiv,Outdent,Indent,JustifyLeft,JustifyCenter,JustifyRight,JustifyBlock,Language,BidiRtl,BidiLtr,Font,Format,Maximize,ShowBlocks,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField';
        // };

        CKEDITOR.replace(this, {
            toolbarGroups: [
                {name: 'document', groups: ['mode', 'document', 'doctools']},
                {name: 'clipboard', groups: ['clipboard', 'undo']},
                {name: 'editing', groups: ['find', 'selection', 'spellchecker', 'editing']},
                {name: 'forms', groups: ['forms']},
                {name: 'basicstyles', groups: ['basicstyles', 'cleanup']},
                {name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi', 'paragraph']},
                {name: 'links', groups: ['links']},
                {name: 'styles', groups: ['styles']},
                {name: 'insert', groups: ['insert']},
                {name: 'colors', groups: ['colors']},
                {name: 'about', groups: ['about']},
                {name: 'tools', groups: ['tools']},
                {name: 'others', groups: ['others']}
            ],
            removeButtons: 'Source,Save,Templates,Cut,Undo,Find,SelectAll,Scayt,Form,Unlink,Anchor,Image,Flash,Table,Smiley,HorizontalRule,SpecialChar,Iframe,PageBreak,Blockquote,CreateDiv,Outdent,Indent,JustifyLeft,JustifyCenter,JustifyRight,JustifyBlock,Language,BidiRtl,BidiLtr,Font,Format,Maximize,ShowBlocks,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField',
        });
    })
}

function initBootstrapSwitch() {
    $(".bt-bootstrap-switch").each(function () {
        $(this).bootstrapSwitch();
    })
}

function initDropZone() {
    Dropzone.autoDiscover = false;

    $(".dropzone_area").each(function () {
        var dropZoneInstance = $(this).dropzone({
            dictDefaultMessage: 'Drag in to upload.',
            previewsContainer: '#dropzone-preview-container',
            addRemoveLinks: true,
            success: function (file, request) {
                location.reload();
            },
            removedfile: function (file) {
                console.log(file);

                if (file.upload == undefined) {
                    var name = file.name;
                } else {
                    var name = file.upload.filename;
                }

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': csrf
                    },
                    type: 'DELETE',
                    url: URL_DELETE_IMAGE,
                    data: {filename: name},
                    success: function (data) {
                        // alert("File deleted successfully!!");
                    },
                    error: function (e) {
                        console.log(e);
                    }
                });
                var fileRef;
                return (fileRef = file.previewElement) != null ?
                    fileRef.parentNode.removeChild(file.previewElement) : void 0;
            },
            init: function () {
                var this_ = this;
                console.log(URL_GET_IMAGES)
                /**
                 * Retrieve uploaded images
                 */
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': csrf
                    },
                    type: 'GET',
                    url: URL_GET_IMAGES,
                    success: function (data) {

                        console.log(data.data)

                        if (data.data.count == 1) {
                            var image = data.data;
                            var mockFile = {name: image.id, size: 400, type: 'image/jpeg', url: image.url};
                            this_.options.addedfile.call(this_, mockFile);
                            this_.options.thumbnail.call(this_, mockFile, image.url);
                        } else {
                            data.data.forEach(function (image) {
                                var mockFile = {name: image.id, size: 400, type: 'image/jpeg', url: image.url};
                                this_.options.addedfile.call(this_, mockFile);
                                this_.options.thumbnail.call(this_, mockFile, image.url);
                            })
                        }

                    },
                    error: function (e) {
                        console.log(e);
                    }
                });

            }
        });


    })
}
