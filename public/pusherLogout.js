var pusher = new Pusher('c7361a97e7eadb2f7fe4', {
    cluster: 'ap1'
});
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
        var channelLogout = pusher.subscribe(userReceives.replace(' ', ''));
        channelLogout.bind('my-event', function (data) {
            createModal(data.massage);
        });
    });
