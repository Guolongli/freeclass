/**
 * Created by 龙鲤 on 2016/12/22.
 */
var xmlhttp;
function deleteCourse(value){
    if(window.XMLHttpRequest){
        xmlhttp = new XMLHttpRequest();
    }
    if(window.ActiveXObject){
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    if(xmlhttp!=null){
        xmlhttp.open('POST',"/freeclass2.0/index.php/Home/Course/DeleteCourse/action/deleteCourse.html",true);
        xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        xmlhttp.send('course='+value);
        xmlhttp.onreadystatechange = function(){
            if(xmlhttp.readyState==4&&xmlhttp.status==200){
                if(xmlhttp.responseText == -1){
                    alert('对不起，你无权操作次课程');
                }else if(xmlhttp.responseText == 1){
                    alert('删除成功');
                    location.reload(true);
                }
            }
        }
    }
}