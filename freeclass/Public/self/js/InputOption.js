/**
 * Created by 龙鲤 on 2016/12/5.
 */
var Week = document.getElementById('week');
var DropWeek = document.getElementById('dropweek');
var fanWei1;
var fanWei2;

function GetFAnWei(){
    var week1 = document.getElementById('week1');
    fanWei1 = week1.selectedIndex;
    var week2 = document.getElementById('week2');
    fanWei2 = week2.selectedIndex;
    var wb = document.getElementById('wb');
    while(wb.hasChildNodes()) //当div下还存在子节点时 循环继续
    {
        wb.removeChild(wb.firstChild);
    }
    for(var i = fanWei1+1;i<=fanWei2+1;i++){
        var span = document.createElement('span');
        span.className = "week";
        span.setAttribute("onclick","CheckWeek(this.innerText)");
        span.innerText = i;
        wb.appendChild(span);
    }
}

function ShowWeek(){
    GetFAnWei();
    Week.disabled = 'true';
    DropWeek.style.display='block';
}

function CheckWeek(value){
    var WeekValue = document.getElementsByClassName('week')[value-1];
    if(WeekValue.style.background==''){
        WeekValue.style.background = "rgb(233,242,246)";
        Week.value = Week.value+value+"周";
    }else if(WeekValue.style.background == "rgb(233, 242, 246)"){
        WeekValue.style.background = '';
        var TempWeek = Week.value.split('周');
        for(i=0;i<TempWeek.length;i++){
            if(TempWeek[i] == value){
                TempWeek.splice(i,1);
                break;
            }
        }
        TempWeek.splice(TempWeek.length,1);
        Week.value = TempWeek.join('周');
    }

}

function Choose(option){
    var button = document.getElementsByClassName('weekbtn');
    var WeekValue = document.getElementsByClassName('week');
    switch (option){
        case 'single':
            button[0].style.background = "rgb(233, 242, 246)";
            button[1].style.background = "";
            button[2].style.background = "";
            Week.value = '';
            var j=0;
            for(var i=fanWei1+1;i<=fanWei2+1;i++){
                if(i%2 == 1){
                    WeekValue[j].style.background ="rgb(233, 242, 246)";
                    Week.value = Week.value+i+"周";
                }else{
                    WeekValue[j].style.background ="";
                }
                j++;
            }
            button[2].innerText = '全选';
            break;
        case 'double':
            button[1].style.background = "rgb(233, 242, 246)";
            button[0].style.background = "";
            button[2].style.background = "";
            Week.value = '';
            j=0;
            for(i=fanWei1+1;i<=fanWei2+1;i++){
                if(i%2 == 0){
                    WeekValue[j].style.background ="rgb(233, 242, 246)";
                    Week.value = Week.value+i+"周";
                }else{
                    WeekValue[j].style.background ="";
                }
                j++;
            }
            button[2].innerText = '全选';
            break;
        case 'all':
            if(button[2].style.background == "rgb(233, 242, 246)"){
                button[2].style.background = "";
                button[0].style.background = "";
                button[1].style.background = "";
                Week.value = '';
                for(i=fanWei1+1,j=0;i<=fanWei2+1;i++,j++){
                    WeekValue[j].style.background ="";
                }
                button[2].innerText = '全选';
            }else{
                button[2].style.background = "rgb(233, 242, 246)";
                button[0].style.background = "";
                button[1].style.background = "";
                Week.value = '';
                for(i=fanWei1+1,j=0;i<=fanWei2+1;i++,j++){
                    WeekValue[j].style.background ="rgb(233, 242, 246)";
                    Week.value = Week.value+i+"周";
                }
                button[2].innerText = '取消全选';
            }
            break;
    }
}

function CheckSubmit(){
    if(Week.value==''){
        alert('周次不能为空！');
        return false;
    }else{
        Week.disabled = '';
        return true;
    }
}