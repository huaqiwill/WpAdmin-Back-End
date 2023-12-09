function setCookie(cookieName, cookieValue, expirationDays) {
    const d = new Date();
    d.setTime(d.getTime() + (expirationDays * 24 * 60 * 60 * 1000));
    const expires = "expires=" + d.toUTCString();
    document.cookie = cookieName + "=" + cookieValue + ";" + expires + ";path=/";
}

function getCookie(cookieName) {
    const name = cookieName + "=";
    const decodedCookie = decodeURIComponent(document.cookie);
    const cookieArray = decodedCookie.split(';');

    for (let i = 0; i < cookieArray.length; i++) {
        let cookie = cookieArray[i];
        while (cookie.charAt(0) === ' ') {
            cookie = cookie.substring(1);
        }
        if (cookie.indexOf(name) === 0) {
            return cookie.substring(name.length, cookie.length);
        }
    }
    return undefined;
}

function getQuery(name) {
    let query = window.location.search.substring(1);
    let vars = query.split("&");
    for (let i = 0; i < vars.length; i++) {
        let pair = vars[i].split("=");
        if (pair[0] === name) {
            return pair[1];
        }
    }
    return undefined;
}

//发送GET、POST请求，发送getHtml请求
const ajax = function () {
    return {
        post(url, data, success) {
            $.ajax({
                url,
                type: "POST",
                data,
                success: (res) => {
                    if (res.code === 200) {
                        success(res.data, res.msg)
                    } else {
                        layer.msg(res.msg, { icon: 2 })
                    }
                },
                error: (err) => {
                    layer.msg(err.responseText, { icon: 2 })
                }
            })
        },
        get(url, param, success) {
            $.ajax({
                url,
                type: "GET",
                param,
                success: (res) => {
                    if (res.code === 200) {
                        success(res.data, res.msg)
                    } else {
                        layer.msg(res.msg)
                    }
                },
                error: (err) => {
                    layer.msg(err.responseText)
                }
            })
        },
        getHtml(url, success) {
            $.ajax({
                url,
                type: "GET",
                success,
                error: (err) => {
                    layer.msg(err.responseText)
                    return undefined;
                }
            })
        }
    }
}();
