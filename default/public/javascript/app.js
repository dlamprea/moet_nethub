$(document).ready(function(){
    $('#toast').on('click', function(){
        $('#basic-modal-content-toast').modal();
        return false;
    });

    $('#toast-big').on('click', function(){
        stream_toast();
    });

    stream_toast = function(){
        $.ajax({
            url: PUBLIC_PATH + 'usopen/share',
            data: {service: 'facebook', target: 'toast'},
            type: 'get'
        }).done(function(data){
            FB.ui({
                method: 'feed',
                name: "Celebrate the US Open with Moët & Chandon",
                caption: 'Moët USA',
                source: 'https://moetusa-apps.com/usopen/img/toast.jpg',
                description: 'Join Moët & Chandon for a toast to success and glamour in celebration of the US Open',
                link: 'http://www.facebook.com/moetUSA/app_291716654269966',
            },function(response) {
                if (response && response.post_id) {
                    $.ajax({
                        url: PUBLIC_PATH + 'usopen/share',
                        data: {service: 'facebook', target: 'toast_shared'},
                        type: 'get'
                    }).done(function(data){
                        window.top.location.href = "https://www.facebook.com/photo.php?fbid=451836191515895&set=a.451836168182564.113065.296313307068185&type=3&theater";
                    });
                }
            });
        });
    };
    $('#find').click(function (e) {
        $('#basic-modal-content').modal();
        $.ajax({
            url: PUBLIC_PATH + 'usopen/share',
            data: {service: 'find', target: 'moet'},
            type: 'get'
        });
        return false;
    });
    $('#img-back').on('click', function(){
        window.location.href=parent.window.location.href
    });
    $('#btn1').on('click', function(){
        permisions();
    });
});

var urlLinkApp = "https://www.facebook.com/moetUSA/app_291716654269966";

function sendTwitter(){
    $.ajax({
        url: PUBLIC_PATH + 'usopen/share',
        data: {service: 'twitter', target: 'game'},
        type: 'get'
    }).done(function(data){
        var width  = 575,
        height = 400,
        left   = ($(window).width()  - width)  / 2,
        top    = ($(window).height() - height) / 2,
        url    = 'http://twitter.com/share?url=&text=' + encodeURIComponent("Play @MoetUSA's 'Perfect Serve' for the chance to win tennis lessons with a pro: " + urlLinkApp),
        opts   = 'status=0' +
        ',width='  + width  +
        ',height=' + height +
        ',top='    + top    +
        ',left='   + left;
        window.open(url, 'twitter', opts);
    });
    return false;
};

function sendFacebook(){
    $.ajax({
        url: PUBLIC_PATH + 'usopen/share',
        data: {service: 'facebook', target: 'game'},
        type: 'get'
    }).done(function(data){
        FB.ui({
            method: 'feed',
            name: 'Celebrate the US Open with Moët & Chandon',
            source: 'https://moetusa-apps.com/usopen/img/cup.jpg',
            link: 'http://www.facebook.com/moetUSA/app_291716654269966',
            description: "Play Moët & Chandon's Perfect Serve for a chance to win tennis lessons with a pro. " + urlLinkApp
        },function(response) {
            if (response && response.post_id) {
                $.ajax({
                    url: PUBLIC_PATH + 'usopen/share',
                    data: {service: 'facebook', target: 'game_shared'},
                    type: 'get'
                });
            }
        });
    });
};

function sendMenu(){
    window.location.href = urlMenu;
};

function permisions(){
    FB.login(function(response){
        if (response.authResponse) {
            FB.api('/me', function(response) {
                user({user:response});
            });
        }
    },{scope: 'user_birthday,user_location,email'});
};

function user(data){
    $.ajax({
        url: PUBLIC_PATH + 'usopen/user',
        data: data,
        type: 'post',
        dataType: 'json'
    }).done(function(data){
        if(data.success == true){
            if(data.create == true){
                 window.location.href = PUBLIC_PATH + 'usopen/game';
            }
        }
    });
};