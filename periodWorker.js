function sleep(millis){
    var date = new Date();
    var curDate=null;
    do{
        curDate=new Date();
    }while(curDate-date<millis);
}
let id;
onmessage = function(ev){

    while(true){
        postMessage(ev.data);
        sleep(ev.data);
    }
}

