
//setInterval(isPhoneRing, 1000);

isPhoneRing();

function isPhoneRing() {
    alert();
    $.ajax({
       url: l_url ,
       data: { phone_num: l_phone_num },
       type: 'post',
       success: function(data) {
           //$('#cc-container').html(data.isRing);
           $('#cc-container').html('<iframe src="http://ds24.ru" style="overflow:hidden;height:100%;width:100%" height="100%" width="100%">\n\
                                        Ваш браузер не поддерживает плавающие фреймы!\n\
                                    </iframe>');
       }
    });    
}
