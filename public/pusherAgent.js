var pusher2 = new Pusher('c7361a97e7eadb2f7fe4', {
    cluster: 'ap1'
});
let url = window.location.href;
let parts = url.split('/');
let id = parts[4];
let givepush = true;
fetch('/theAPI', {
    method: 'GET',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    },
})
    .then(response => response.json())
    .then(data => {
        var userReceives = data;

        var channelLogout = pusher2.subscribe('agent-channel');
        channelLogout.bind('my-event', function (data) {
            if (userReceives != data.user) {
                if (window.location.pathname.split('/').pop() == 'edit') {
                    givepush = false;
                    if (id == data.id) {
                        givepush = true
                    }
                }
                if (givepush) {
                    createModal(data.massage);
                }
            }
        });
    });
