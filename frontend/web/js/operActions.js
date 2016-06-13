function itwww(_container, _statusString, _phpid, _actionUrl, _logoutUrl) {
    
    var self = this;
    
    self.frame = '<iframe src="%goal%" style="border:0px;overflow:hidden;height:100%;width:100%;" height="100%" width="100%"></iframe>';
    
    self.waitingText = 'Ожидание звонка ';
    self.incomingText = 'Входящий звонок ';
    self.conErrorText = 'Ошибка соединения с сервером';
    self.sipErrorText = 'Ваш телефон не подключен к АТС';
    
    // Div elements for injections
    self.container = _container;
    self.statusString = _statusString;
    
    // ID operator
    self.phpid = _phpid;
    
    // Controller Actions
    self.actionUrl = _actionUrl;
    self.logoutUrl = _logoutUrl;
    
    self.timerID;
    
    self.prevCardCid = '';
    self.isCardOpen = false;
    //self.isCardFreez = false;
    
    
    // Start for monitoring online
    self.start = function() {
        
        $('.close-card > a').css('display','none');
        this.timerID = setInterval(self.isRingCheck, 1500);
        
    }
    
    self.stop = function() {
        
        clearInterval(self.timerID);

    }
    
    self.isRingCheck = function() {
        
        $.ajax({
            url: self.actionUrl ,
            data: { phpid: self.phpid , action : 'check-card' },
            type: "post",
            success: function(data) { 
                        self.checkProcessing(data); 
                     }
            });

    }
    
    self.closeCard = function() {
        
        self.clearCard();
        
        $.ajax({
            url: self.actionUrl ,
            data: { phpid: self.phpid , action : 'close-card' },
            type: "post",
            success: function(data) { 
                        self.checkPhpId(data);
                     }
            });
            
    }
    
    self.openCard = function($data) {
        
        var url = $data.qU;
        
        $(self.container).html(self.frame.replace('%goal%',url));
    }
    
    self.clearCard = function() {
        $(self.container).html('');
    }
    
    self.checkPhpId = function(ajaxData) {
        if ( !ajaxData.isL ) { window.location.href = self.logoutUrl; };
    }
    
    self.checkProcessing = function(ajaxData) {

        // check php session
        self.checkPhpId(ajaxData);
        
        if ( ajaxData.isA == 1) {

            self.stop();
            $('.close-card > a').css('display','block');
            console.log('freez');
        }
        
        // if phone is ringing
        if (ajaxData.isR == 1) {
            
            if ( self.isCardOpen ) {
                if ( self.prevCardCid != ajaxData.cN ) {
                    self.prevCardCid = ajaxData.cN;
                    $(self.statusString).html(self.incomingText + ajaxData.cN);
                    self.openCard(ajaxData);
                    console.log('newCard ' + self.prevCardCid + ' newCid:' + ajaxData.cN);
                }// else { }
            } else {
                // if Card not opened yet
                self.isCardOpen = true;
                self.prevCardCid = ajaxData.cN;
                $(self.statusString).html(self.incomingText + ajaxData.cN);
                self.openCard(ajaxData);
                console.log('FirstCard ' + self.prevCardCid + ' newCid:' + ajaxData.cN);
            }
            
            
        } else {
            self.prevCardCid = '';
            self.isCardOpen = false;
            $(self.statusString).html(self.waitingText + ajaxData.sT);
            self.clearCard();
            console.log('waitCall');
        }
        
    }
}
