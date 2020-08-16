function readURL(input, preview = '#imageResult', parentInp = false) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $(preview).attr('src', e.target.result);
            if (parentInp) {
                $(input).parent().children(':first').val(e.target.result);
            }
        };
        let val = reader.readAsDataURL(input.files[0]);
    }
}


/*  ==========================================
SHOW UPLOADED IMAGE NAME
* ========================================== */
var input = document.getElementById( 'upload' );


$(function () {
    $('#upload').on('change', function () {
        readURL(input);
    });
});

// var infoArea = document.getElementById( 'upload-label' );
//
// input.addEventListener( 'change', showFileName );
// function showFileName( event ) {
//     var input = event.srcElement;
//     var fileName = input.files[0].name;
//     infoArea.textContent = 'File name: ' + fileName;
// }
