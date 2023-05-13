document.getElementById("preview2").style.display = "none";
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
    document.getElementById("preview2").style.visibility = "hidden";
    document.getElementById("file1").value = "";
    //ファイルが選択されていないとき、０をvalueに渡す
    document.getElementById("deselect1_2").value = "2";
} 
const fileInput = document.getElementById("file1");
//ファイルが選択されているとき、１をvalueに渡す
const handleFileSelect = () => {
    const files = fileInput.files;
    if(files.length === 1){
        document.getElementById("deselect1_2").value = "1";
        document.getElementById("preview2").style.display = "block";
        document.getElementById("preview").style.visibility = "visible";
        
    }
}
fileInput.addEventListener('change', handleFileSelect);