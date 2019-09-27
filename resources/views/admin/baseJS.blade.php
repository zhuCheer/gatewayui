<script src="{{$siteStatic}}/layer/layer.js"></script>
<script>
    if(typeof QI == "undefined")
        QI={};
    $.extend(QI, {
        uploadUrl : '/',
        ajaxForm:function(url,param, callback){
            layer.load(2);
            var that = this;
            $.ajax({
                type: "POST",
                url: url,
                data: param,
                success: function(json){
                    layer.closeAll('loading');
                    var alert = '系统出小差';
                    if(json.info){
                        alert = json.info;
                    }

                    if(typeof callback == 'function'){
                        return callback(json);
                    }

                    if(json.status != 0){
                        that.errorAlert(alert);
                        return false;
                    }

                    layer.closeAll();
                    that.successAlert(json.info);
                    if(json.data.url){
                        that.delayJump(json.data.url);
                    }else if(json.data.topurl){
                        that.delayTopJump(json.data.topurl);
                    }else{
                        that.delayJump('');
                    }

                },
                error:function(e){
                    layer.closeAll('loading');
                    that.errorAlert('系统出小差了（'+e.statusText+'）');
                }
            });
        },
        confirmDo:function(title,url,params){
            var that = this;
            layer.confirm(title, {
                icon: 3, title:'提示',
                btn: ['确认','取消'] //按钮
            },function(){
                layer.closeAll('dialog');
                that.ajaxForm(url, params);
            });
        },
        delayJump:function(url){
            setTimeout(function(){
                if(url){
                    location.href = url;
                }else{
                    location.reload();
                }
            },800);
        },
        delayTopJump:function(url){
            setTimeout(function(){
                if(url){
                    top.location.href = url;
                }else{
                    location.reload();
                }
            },500);
        },
        successAlert:function(msg){
            layer.msg(msg,{icon: 6,time:1000});
        },
        errorAlert:function(msg){
            layer.msg(msg,{icon: 5,time:2000,anim:6});
        }
    });
</script>