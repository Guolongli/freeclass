/**
 * Created by 龙鲤 on 2016/12/6.
 */
function PostAjax(url){
    if(window.XMLHttpRequest){
        xmlhttp = new XMLHttpRequest();
    }
    if(window.ActiveXObject){
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    var ret;
    if(xmlhttp!=null){
        xmlhttp.open('POST',url,true);
        xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        xmlhttp.send();
        xmlhttp.onreadystatechange = function(){
            if(xmlhttp.readyState==4&&xmlhttp.status==200){
                var result= xmlhttp.responseText;
                ret = eval('(' + result + ')');
                return ret;
            }
        }
    }
}
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
                ret = eval('(' + result + ')');
                var body = document.getElementById('body1');
                body.innerHTML = '';
                for(val in ret){
                    body.innerHTML = body.innerHTML+"<span>"+ret[val]['name']+"</span>&nbsp;";
                }
            }
        }
    }
}
//退出群组
function Quit(){

}
//删除群成员
function DeleteMember(url,act,url2){
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
                ret = eval('(' + result + ')');
                $('#myModalLabe2').html(act);
                $('#form').attr("action",url2);
                var body = document.getElementById('body2');
                body.innerHTML = '';
                for(val in ret){
                    body.innerHTML = body.innerHTML+"<input name='user[]' type='checkbox' value='"+ret[val]['id']+"' />"+ret[val]['name']+"&nbsp;";
                }
            }
        }
    }
}
//添加管理员
function AddAdmin(url,act,url2){
    DeleteMember(url,act,url2);
}
//删除管理员
function DeleteAdmin(url,act,url2){
    DeleteMember(url,act,url2);
}
//解散群组
function DeleteGroup(){

}

function CloseCont(){
    var cont = document.getElementById('cont');
    cont.style.display = 'none';
}

//发送邮件
function sentMessage(groupid){
    var groupId = document.getElementById('messageGroup');
    groupId.value = groupid;
}