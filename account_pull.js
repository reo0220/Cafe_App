function screenChange(){
    pullSellect = document.pullForm.pullMenu.selectedIndex ;
    location.href = document.pullForm.pullMenu.options[pullSellect].value ;
}