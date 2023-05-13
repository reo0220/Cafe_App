//ファイル１
document.getElementById("preview").style.display ="none";
function previewImage(obj){
    var fileReader = new FileReader();
    fileReader.onload = (function() {
        document.getElementById("preview").style.visibility = "visible";
        document.getElementById('preview').src = fileReader.result;
    });
    fileReader.readAsDataURL(obj.files[0]);
    //imageの非表示処理
    const image = document.getElementById("image");
    image.style.display ="none";
}
function deselect1_1(){
    document.getElementById("image").style.visibility ="hidden";
    document.getElementById("preview").style.visibility = "hidden";
    document.getElementById("file1").value = "";
    //ファイルが選択されていないとき、０をvalueに渡す
    document.getElementById("deselect1_2").value = "0";

} 
const fileInput = document.getElementById("file1");
//ファイルが選択されているとき、１をvalueに渡す
const handleFileSelect = () => {
    const files = fileInput.files;
    if(files.length === 1){
        document.getElementById("deselect1_2").value = "1";
        document.getElementById("preview").style.display ="block";
    }
}
fileInput.addEventListener('change', handleFileSelect);

//ファイル2
document.getElementById("preview2").style.display ="none";
function previewImage2(obj){
    var fileReader2 = new FileReader();
    fileReader2.onload = (function() {
        document.getElementById("preview2").style.visibility = "visible";
        document.getElementById('preview2').src = fileReader2.result;
    });
    fileReader2.readAsDataURL(obj.files[0]);
    const image2 = document.getElementById("image2");
    image2.style.display ="none";
}
function deselect2_1(){
    document.getElementById("image2").style.visibility = "hidden";
    document.getElementById("preview2").style.visibility = "hidden";
    document.getElementById("file2").value = "";
    document.getElementById("deselect2_2").value = "0";
} 
const fileInput2 = document.getElementById("file2");
const handleFileSelect2 = () => {
    const files2 = fileInput2.files;
    if(files2.length === 1){
        document.getElementById("preview2").style.display ="block";
        document.getElementById("deselect2_2").value = "1";
    }
}
fileInput2.addEventListener('change', handleFileSelect2);

//ファイル3
document.getElementById("preview3").style.display ="none";
function previewImage3(obj){
    var fileReader3 = new FileReader();
    fileReader3.onload = (function() {
        document.getElementById("preview3").style.visibility = "visible";
        document.getElementById('preview3').src = fileReader3.result;
    });
    fileReader3.readAsDataURL(obj.files[0]);
    const image3 = document.getElementById("image3");
    image3.style.display ="none";
}
function deselect3_1(){
    document.getElementById("image3").style.visibility = "hidden";
    document.getElementById("preview3").style.visibility = "hidden";
    document.getElementById("file3").value = "";
    document.getElementById("deselect3_2").value = "0";
}
const fileInput3 = document.getElementById("file3");
const handleFileSelect3 = () => {
    const files3 = fileInput3.files;
    if(files3.length === 1){
        document.getElementById("preview3").style.display ="block";
        document.getElementById("deselect3_2").value = "1";
    }
}
fileInput3.addEventListener('change', handleFileSelect3);

//ファイル4
document.getElementById("preview4").style.display ="none";
function previewImage4(obj){
    var fileReader4 = new FileReader();
    fileReader4.onload = (function() {
        document.getElementById("preview4").style.visibility = "visible";
        document.getElementById('preview4').src = fileReader4.result;
    });
    fileReader4.readAsDataURL(obj.files[0]);
    const image4 = document.getElementById("image4");
    image4.style.display ="none";
}
function deselect4_1(){
    document.getElementById("image4").style.visibility ="hidden";
    document.getElementById("preview4").style.visibility = "hidden";
    document.getElementById("file4").value = "";
    document.getElementById("deselect4_2").value = "0";
}
const fileInput4 = document.getElementById("file4");
const handleFileSelect4 = () => {
    const files4 = fileInput4.files;
    if(files4.length === 1){
        document.getElementById("preview4").style.display ="block";
        document.getElementById("deselect4_2").value = "1";
    }
}
fileInput4.addEventListener('change', handleFileSelect4);