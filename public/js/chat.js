var Chat = (function () {
    var form = document.getElementById('form');
    var message_area = document.getElementById('message-area');
    var messages_ul;
    var t = 0;

	function createXHR() {
        var client;
        if (window.ActiveXObject) {
            try {
                client = new ActiveXObject("Microsoft.XMLHTTP");
            } catch(e) {
                client = null;
            }
        } else {
            client = new XMLHttpRequest();
        }

        return client;
    }

    function getMessages(t) {
        var xhr = createXHR();
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                updateMessages(JSON.parse(xhr.responseText));
                message_area.scrollTop = message_area.scrollHeight;
                t = parseInt(Date.now() / 1000, 10);
                clearTimeout(timeout);
                getMessages(t);

                return true;
            }
        };

        xhr.open('GET', '/messages?time=' + t, true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.send();
        var timeout = setTimeout(function() {
            xhr.abort();
            t = parseInt(Date.now() / 1000, 10);
            getMessages(t);
        }, 30000);
    }

    function addMessage(username, message) {
        var xhr = createXHR();
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                return xhr.responseText;
            }
        };
        xhr.open('POST', '/messages/add-message', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.send('username=' + username + '&message=' + message);
    }

    function updateMessages(messages) {
        var ul = document.getElementById('messages');
        for (var i in messages) {
            var li = document.createElement('li');
            var time = document.createElement('span');
            var username = document.createElement('span');
            var message = document.createElement('span');

            var date = new Date(messages[i]['timestamp'] * 1000);
            var time_text = '';
            time_text += (date.getHours() < 10 ? '0' : '') + date.getHours() + ':';
            time_text += (date.getMinutes() < 10 ? '0' : '') + date.getMinutes() + ':';
            time_text += (date.getSeconds() < 10 ? '0' : '') + date.getSeconds();
            time.innerHTML = time_text;
            time.className = 'time';

            username.innerHTML = messages[i]['username'];
            username.className = 'username';

            message.innerHTML = messages[i]['message'];
            message.className = 'message';

            li.appendChild(time);
            li.appendChild(username);
            li.appendChild(message);

            messages_ul.appendChild(li);
        }
    }

    function init() {
        form.addEventListener("submit", function(e) {
            e.preventDefault();
            var username = document.getElementById('username');
            var message = document.getElementById('message');

            if (username.value === '' || message.value === '') {
                alert('Please enter a username and message');
                return false;
            }
            addMessage(username.value, message.value);

            username.disabled = 'disabled';
            message.value = '';
        });

        messages_ul = document.createElement('ul');
        messages_ul.id = 'messages';
        message_area.appendChild(messages_ul);
        getMessages(t);
    }

    return init();
}());