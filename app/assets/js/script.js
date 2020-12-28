$('input[type="file"]').change(function(e){
    //get the file name
    var fileName = e.target.files[0].name;
    //replace the "Choose a file" label
    $('.custom-file-label').html(fileName);
});