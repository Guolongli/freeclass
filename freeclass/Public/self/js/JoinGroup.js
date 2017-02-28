/**
 * Created by 龙鲤 on 2016/12/5.
 */
var GroupName = document.getElementById('groupname');
var txt = document.getElementById('txt');
function Search(url){
    GroupName.autocomplete = 'off';
    var GroupNameValue = GroupName.value;
    var GroupNameWidth = GroupName.style.width;
    if(GroupNameValue.length!=0){
        if(window.XMLHttpRequest){
            xmlhttp = new XMLHttpRequest();
        }
        if(window.ActiveXObject){
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        if(xmlhttp!=null){
            xmlhttp.open('POST',url,true);
            xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
            xmlhttp.send('groupname='+GroupNameValue);
            xmlhttp.onreadystatechange = function(){
                if(xmlhttp.readyState==4&&xmlhttp.status==200){
                    var result = xmlhttp.responseText;
                    var Show = eval('(' + result + ')');
                    txt.innerHTML = '';
                    for(val in Show){
                        txt.innerHTML=txt.innerHTML+"<p class='p' onclick='Select(this.innerText);'>"+Show[val]['name']+"</p>";
                    }
                    txt.style.display = 'block';
                    txt.style.width = GroupNameWidth;
                }
            }
        }
    }else{
        txt.style.display = 'none';
    }
}
function Select(PValue){
    GroupName.value=PValue;
    txt.style.display = "none";

}
function CheckSubmit(){
    var word = document.getElementById('word').value;
    if(GroupName.value.length == 0){
        alert('群组名不能为空');
        return false;
    }
    if(word.length == 0){
        alert('口令不能为空');
        return false;
    }
    return true;
}

document.onclick = function (e) {
    e = e || event;
    var btn = document.getElementById("groupname");
    var msg = document.getElementById("txt");
    var target = e.target || e.srcElement;
    if (target !== btn && target !== msg) {
        msg.style.display = "none";
    }
}
