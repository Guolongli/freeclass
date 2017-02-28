/**
 * Created by 龙鲤 on 2016/12/10.
 */
function ShowMember(StuNo,StuName){
    var Body = document.getElementById('body1');
    Body.innerHTML = "<span><li>学号：</li>"+StuNo+"</span><span><li>姓名：</li>"+StuName+"</span>";
}
function DownLoadWord(url){
    window.open(url,'newwindow');
}
function DownLoadExcel(url){
    window.open(url,'newwindow');
}