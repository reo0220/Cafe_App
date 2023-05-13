 //プレビューの表示
 function previewImage(obj){
    var fileReader = new FileReader();
    fileReader.onload = (function() {
        document.getElementById("preview").style.visibility = "visible";
        document.getElementById('preview').src = fileReader.result;
    });
    fileReader.readAsDataURL(obj.files[0]);
}
function previewImage2(obj){
    var fileReader2 = new FileReader();
    fileReader2.onload = (function() {
        document.getElementById("preview2").style.visibility = "visible";
        document.getElementById('preview2').src = fileReader2.result;
    });
    fileReader2.readAsDataURL(obj.files[0]);
}
function previewImage3(obj){
    var fileReader3 = new FileReader();
    fileReader3.onload = (function() {
        document.getElementById("preview3").style.visibility = "visible";
        document.getElementById('preview3').src = fileReader3.result;
    });
    fileReader3.readAsDataURL(obj.files[0]);
}
function previewImage4(obj){
    var fileReader4 = new FileReader();
    fileReader4.onload = (function() {
        document.getElementById("preview4").style.visibility = "visible";
        document.getElementById('preview4').src = fileReader4.result;
    });
    fileReader4.readAsDataURL(obj.files[0]);
}

//選択解除をクリックした時
function deselect1(){
    document.getElementById("preview").style.visibility = "hidden";
    document.getElementById("file1").value = "";
}
function deselect2_1(){
    document.getElementById("preview2").style.visibility = "hidden";
    document.getElementById("file2").value = "";
}
function deselect3_1(){
    document.getElementById("preview3").style.visibility = "hidden";
    document.getElementById("file3").value = "";
}
function deselect4_1(){
    document.getElementById("preview4").style.visibility = "hidden";
    document.getElementById("file4").value = "";
}

//ファイルを選択した時に表示
const fileInput = document.getElementById("file1");
const handleFileSelect = () => {
    const files = fileInput.files;
    if(files.length === 1){
        document.getElementById("file2").style.visibility = "visible";
        document.getElementById("deselect").style.visibility = "visible";
    }
    fileInput.addEventListener('change', handleFileSelect);
}
const fileInput2 = document.getElementById("file2");
const handleFileSelect2 = () => {
    const files2 = fileInput2.files;
    if(files2.length === 1){
        document.getElementById("file3").style.visibility = "visible";
        document.getElementById("deselect2").style.visibility = "visible";
    }
    fileInput2.addEventListener('change', handleFileSelect2);
}
const fileInput3 = document.getElementById("file3");
const handleFileSelect3 = () => {
    const files3 = fileInput3.files;
    if(files3.length === 1){
        document.getElementById("file4").style.visibility = "visible";
        document.getElementById("deselect3").style.visibility = "visible";
    }
    fileInput3.addEventListener('change', handleFileSelect3);
}
const fileInput4 = document.getElementById("file4");
const handleFileSelect4 = () => {
    const files4 = fileInput4.files;
    if(files4.length === 1){
        document.getElementById("deselect4").style.visibility = "visible";
    }
    fileInput4.addEventListener('change', handleFileSelect4);
}