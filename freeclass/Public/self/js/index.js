/**
 * Created by 龙鲤 on 2016/12/8.
 */
//查看群成员
function Look(url){
    if(window.XMLHttpRequest){
        xmlhttp = new XMLHttpRequest();
    }
    if(window.ActiveXObject){
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    if(xmlhttp!=null){
        xmlhttp.open('POST',url,true);
        xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        xmlhttp.send();
        xmlhttp.onreadystatechange = function(){
            if(xmlhttp.readyState==4&&xmlhttp.status==200){
                var result= xmlhttp.responseText;
                var ret = eval('(' + result + ')');
                var body1 = document.getElementById('is');
                var body2 = document.getElementById('no');
                body1.innerHTML = "";
                body2.innerHTML = "";
                for(val in ret[0]){
                    body1.innerHTML = body1.innerHTML+ret[0][val]+"<br>";
                }
                for(val in ret[1]){
                    body2.innerHTML = body2.innerHTML+ret[1][val]+"<br>";
                }
            }
        }
    }
}
