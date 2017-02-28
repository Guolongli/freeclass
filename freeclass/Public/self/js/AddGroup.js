/**
 * Created by 龙鲤 on 2016/12/5.
 */
function CheckGroupName(url){
    var groupname = document.getElementById('groupname').value;
    var word= document.getElementById('word');
    var check = document.getElementById('checkgroupname');
    if(groupname.length!=0){
        if(window.XMLHttpRequest){
            xmlhttp = new XMLHttpRequest();
        }
        if(window.ActiveXObject){
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        if(xmlhttp!=null){
            xmlhttp.open('POST',url,true);
            xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
            xmlhttp.send('groupname='+groupname);
            xmlhttp.onreadystatechange = function(){
                if(xmlhttp.readyState==4&&xmlhttp.status==200){
                    check.style.display = 'block';
                    if(xmlhttp.responseText==0){
                        check.innerText = "该群组名已经存在";
                        check.style.color='red';
                        word.readOnly = 'true';
                    }else{
                        check.innerText = "该群组名可以使用";
                        check.style.color = 'green';
                    }
                }
            }
        }
    }else{
        check.style.display = 'none';
    }
}

function CheckSubmit(){
    var groupname = document.getElementById('groupname').value;
    var word= document.getElementById('word').value;
    if(groupname.length == 0){
        alert('群组名不能为空');
        return false;
    }
    if(word.length == 0){
        alert('加群口令不能为空');
        return false;
    }
    return true;
}