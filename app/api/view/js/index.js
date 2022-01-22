function validate(str){
    //No.1
    //开始写代码
    var reg_phone=/^1[34578]\d{9}$/;
    var reg_mail=/^(\w-*\.*)+@(\w-?)+(\.\w{2,})+$/;

    return reg_phone.test(str)|reg_mail.test(str);



    //end_code
}

function check(){
    var user = document.getElementById("user").value.trim();
    //No.2
    //开始写代码
    var el_in=document.querySelector("#user");
    var el_hint=document.querySelector(".hint");
    if (  user ) {
        
    }else{

        el_in.style.borderColor='red';
        el_hint.style.display='block';
        el_hint.innerHTML='内容不能为空！';



    }
    //end_code
}