function send_post(url, data, success) {
    $.ajax({
        url, type: "POST", data, success, error: function (err) {
            layer.msg("服务器异常：" + err.responseText)
        }
    })
}

function send_get(url, param, success) {
    $.ajax({
        url, param, type: "GET", success, error: function (err) {
            layer.msg("服务器异常：" + err.responseText)
        }
    })
}

function ajax_post(url, data, success) {
    send_post("/index.php" + url, data, function (response) {
        if (response.code === 200) {
            success(response.data, response.msg);
        } else {
            layer.msg(response.msg)
        }
    })
}

function ajax_get(url, param, success) {
    send_get("/index.php" + url, param, function (response) {
        if (response.code === 200) {
            success(response.data, response.msg);
        } else {
            layer.msg(response.msg)
        }
    })
}

function html_get(url, success) {
    send_get("/index.php" + url, {}, function (response) {
        success(response)
    })
}

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
    return "";
}


function getQueryVariable(name) {
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