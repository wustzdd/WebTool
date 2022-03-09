//排序
function sort(type)
{
    types = ['fans','days'];

    if($.inArray(type,types) == -1){
        layer.msg('不支持的排序类型',{time:10000});
        return false;
    }

    value = setUrlSort('sort');
    if(!value){ //不存在,加入
        url = setUrlParam(type);
    }else{ //判断当前值是否与当前相同
        if(value == type){ //判断排序方式(asc,desc)
            sort = setUrlSort('field');
            if(!sort){ //初始排序
                url = setUrlField('desc');
            }else{
                field = sort == 'desc' ? 'asc' : 'desc';
                url = setUrlField(field,true);
            }
        }else{
            // 重新设置排序字段
            url = setUrlParam(type,true)
        }
    }

    location.href = url;
}


// 获取指定URL参数
function getUrlParam(name)
{
    var url = document.location.toString();
    var urlArr = url.split('?');
    if(urlArr.length > 1){
        var arr;
        var params = urlArr[1].split('&');

        for(var i=0; i< params.length; i++){
            arr = params[i].split('=');

            if(arr != null && arr[0] == name){
                return arr[1];
            }
        }

    }else{
        return '';
    }
}

// 设置排序字段
function setUrlSort(type,is_null=false)
{   
    var url = document.location.toString();
    if(!is_null){
        if(url.search("\\?") == -1){ //不存在
            newUrl = url + '?sort='+type+'&field=desc';
        }else{
            newUrl = url + '&sort='+type+'&field=desc';
        }
    }else{
        urlArr = url.split('?');
        params = urlArr[1].split('&');

        params = params.filter(function(s){
            return s && s.trim();
        });
        urlParams = '';

        for(var j=0; j<params.length; j++){
            value = params[j].split('=');
            if(value[0] == 'sort'){
                urlParams += '&'+value[0]+'='+type;
            }else{
                urlParams += '&'+value[0]+'='+value[1]
            }
        }
        newUrl = urlArr[0]+'?'+urlParams.substr(1);
    }
    return newUrl;
}

// 设置排序方式
function setUrlField(type,is_null=false)
{   
    var url = document.location.toString();
    if(!is_null){
        if(url.search("\\?") == -1){ 
            newUrl = url + '?field='+type;
        }else{
            newUrl = url + '&field='+type;
        }
    }else{
        urlArr = url.split('?');
        params = urlArr[1].split('&');

        //删除空值
        params = params.filter(function(s){
            return s && s.trim();
        });
        urlParams = '';
        for(var j=0; j<params.length; j++){
            value = params[j].split('=');

            if(value[0] == 'field'){
                urlParams += '&'+value[0]+'='+type;
            }else{
                urlParams += '&'+value[0]+'='+value[1]
            }
        }

        newUrl = urlArr[0]+'?'+urlParams.substr(1);
    }
    return newUrl;
}